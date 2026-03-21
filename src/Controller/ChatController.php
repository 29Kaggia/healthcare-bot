<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;

class ChatController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Users');
        $this->loadModel('Conversations');
        $this->loadModel('Messages');
        $this->loadModel('Symptoms');
        $this->loadModel('Predictions');
        $this->loadModel('Reports');

        $this->request->allowMethod(['get', 'post']);
    }

    /**
     * Start a new conversation
     */
    public function startConversation($userId = null)
    {
        if (!$userId) {
            throw new NotFoundException('User not specified');
        }

        $conversation = $this->Conversations->newEmptyEntity();
        $conversation->user_id = $userId;
        $conversation->title = "Conversation with User {$userId}";
        $this->Conversations->save($conversation);

        $this->set([
            'conversation' => $conversation,
            '_serialize' => ['conversation']
        ]);
    }

    /**
     * Send a message
     */
    public function sendMessage()
    {
        $data = $this->request->getData();

        if (!isset($data['conversation_id'], $data['user_id'], $data['message'])) {
            throw new NotFoundException('Required data missing');
        }

        $message = $this->Messages->newEmptyEntity();
        $message->conversation_id = $data['conversation_id'];
        $message->user_id = $data['user_id'];
        $message->message = $data['message'];
        $this->Messages->save($message);

        // AI prediction placeholder
        $predictionText = $this->generateAIPrediction($data['message']);

        $prediction = $this->Predictions->newEmptyEntity();
        $prediction->conversation_id = $data['conversation_id'];
        $prediction->prediction = $predictionText;
        $this->Predictions->save($prediction);

        $this->set([
            'message' => $message,
            'prediction' => $prediction,
            '_serialize' => ['message', 'prediction']
        ]);
    }

    /**
     * Get conversation messages
     */
    public function getConversation($conversationId = null)
    {
        if (!$conversationId) {
            throw new NotFoundException('Conversation not specified');
        }

        $conversation = $this->Conversations->get($conversationId, [
            'contain' => ['Messages', 'Predictions', 'Symptoms']
        ]);

        $this->set([
            'conversation' => $conversation,
            '_serialize' => ['conversation']
        ]);
    }

    /**
     * Placeholder AI Prediction function
     */
   private function generateAIPrediction($text)
{
    // Load API key from environment
    $apiKey = getenv('OPENAI_API_KEY');
    if (!$apiKey) {
        return "OpenAI API key is not set. Please check your .env file.";
    }

    // OpenAI Chat Completion endpoint
    $url = "https://api.openai.com/v1/chat/completions";

    // Request payload
    $data = [
        "model" => "gpt-3.5-turbo", // or "gpt-4" if you have access
        "messages" => [
            ["role" => "system", "content" => "You are a helpful healthcare assistant. Answer the user's questions clearly and politely."],
            ["role" => "user", "content" => $text]
        ],
        "temperature" => 0.7,
        "max_tokens" => 300
    ];

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute request
    $response = curl_exec($ch);

    // Handle cURL errors
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return "Error communicating with AI service: " . $error_msg;
    }

    curl_close($ch);

    // Decode JSON response
    $result = json_decode($response, true);

    // Extract AI's reply
    if (isset($result['choices'][0]['message']['content'])) {
        return trim($result['choices'][0]['message']['content']);
    }

    return "Sorry, I couldn't generate a response at this time.";
}
}