<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller; // not strictly needed if AppController exists
use Cake\Event\EventInterface;

class DashboardController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        // Authentication component
        $this->loadComponent('Authentication.Authentication');

        // Load your tables via TableRegistry instead of loadModel()
        $this->Conversations = $this->getTableLocator()->get('Conversations');
        $this->Messages = $this->getTableLocator()->get('Messages');
    }

    public function index()
    {
        $user = $this->request->getAttribute('identity');

        $conversations = $this->Conversations
            ->find()
            ->where(['user_id' => $user->id])
            ->order(['created' => 'DESC'])
            ->all();

        $this->set(compact('conversations'));
    }

   public function getMessages($conversationId)
{
    $this->request->allowMethod(['get']);

    $messages = $this->Messages
    ->find()
    ->where(['conversation_id' => $conversationId])
    ->order(['created' => 'ASC'])
    ->all()
    ->toArray();

    $formatted = array_map(function($msg) {
    return [
        'id'      => $msg->id,
        'role'    => $msg->sender,   // sender not role
        'content' => $msg->message,  // message not content
        'created' => $msg->created ? $msg->created->toDateTimeString() : null
    ];
}, $messages);

    return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
            'success'  => true,
            'messages' => $formatted
        ]));
}

    public function sendMessage()
{
    $this->request->allowMethod(['post']);

    $user = $this->request->getAttribute('identity');
    $data = $this->request->getData();

    $conversationId = $data['conversation_id'] ?? null;
    $messageText = $data['message'] ?? '';

    if (!$messageText) {
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['success' => false, 'reply' => 'No message provided.']));
    }

    if (!$conversationId) {
        $conversation = $this->Conversations->newEmptyEntity();
        $this->Conversations->patchEntity($conversation, [
            'user_id' => $user->id,
            'title'   => substr($messageText, 0, 30)
        ]);
        $this->Conversations->save($conversation);
        $conversationId = $conversation->id;
    }

    // Save user message — using correct column names
    $userMessage = $this->Messages->newEmptyEntity();
    $this->Messages->patchEntity($userMessage, [
        'conversation_id' => $conversationId,
        'sender'          => 'user',
        'message'         => $messageText
    ]);
    $this->Messages->save($userMessage);

    // Get AI reply
    $aiReply = $this->generateAIPrediction($messageText);

    // Save bot message — using correct column names
    $botMessage = $this->Messages->newEmptyEntity();
    $this->Messages->patchEntity($botMessage, [
        'conversation_id' => $conversationId,
        'sender'          => 'bot',
        'message'         => $aiReply
    ]);
    $this->Messages->save($botMessage);

    return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode([
            'success'         => true,
            'reply'           => $aiReply,
            'conversation_id' => $conversationId
        ]));
}

 protected function generateAIPrediction($text)
{
    $apiKey = getenv('GROQ_API_KEY');
    if (!$apiKey) {
        \Cake\Log\Log::error('GROQ: API key missing');
        return "GROQ API key is not set.";
    }

    $url = "https://api.groq.com/openai/v1/chat/completions";
    $data = [
        "model" => "llama-3.1-8b-instant",
        "messages" => [
            ["role" => "system", "content" => "You are a helpful healthcare assistant."],
            ["role" => "user", "content" => $text]
        ],
        "temperature" => 0.7,
        "max_tokens" => 300
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        \Cake\Log\Log::error('GROQ CURL ERROR: ' . curl_error($ch));
        curl_close($ch);
        return "Error communicating with AI service.";
    }

    curl_close($ch);

    \Cake\Log\Log::debug('GROQ RAW RESPONSE: ' . $response);

    $result = json_decode($response, true);

    if (isset($result['error'])) {
        \Cake\Log\Log::error('GROQ API ERROR: ' . json_encode($result['error']));
        return "AI error: " . $result['error']['message'];
    }

    $reply = $result['choices'][0]['message']['content'] ?? null;

    if (!$reply) {
        \Cake\Log\Log::error('GROQ: No reply in response: ' . json_encode($result));
        return "Sorry, I couldn't generate a response.";
    }

    return trim($reply);
}

    public function deleteConversation($id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $conversation = $this->Conversations->get($id);

        if ($this->Conversations->delete($conversation)) {
            $this->Flash->success('Conversation deleted');
        }

        return $this->redirect(['action' => 'index']);
    }
}