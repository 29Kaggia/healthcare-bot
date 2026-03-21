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
    $messageText    = $data['message'] ?? '';

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

    $userMessage = $this->Messages->newEmptyEntity();
    $this->Messages->patchEntity($userMessage, [
        'conversation_id' => $conversationId,
        'sender'          => 'user',
        'message'         => $messageText
    ]);
    $this->Messages->save($userMessage);

    // Pass user profile to AI
    $profile = [
        'age'        => $user->age,
        'gender'     => $user->gender,
        'conditions' => $user->conditions,
        'allergies'  => $user->allergies,
    ];

    $aiReply = $this->generateAIPrediction($messageText, $profile);

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

protected function generateAIPrediction(string $text, array $profile = []): string
{
    $apiKey = getenv('GROQ_API_KEY');
    if (!$apiKey) return "GROQ API key is not set.";

    // Build personalized system prompt
    $systemPrompt = "You are a helpful healthcare assistant. Answer clearly and politely.";

    if (!empty($profile)) {
        $systemPrompt .= "\n\nPatient Profile:";
        if (!empty($profile['age']))        $systemPrompt .= "\n- Age: {$profile['age']}";
        if (!empty($profile['gender']))     $systemPrompt .= "\n- Gender: {$profile['gender']}";
        if (!empty($profile['conditions'])) $systemPrompt .= "\n- Known conditions: {$profile['conditions']}";
        if (!empty($profile['allergies']))  $systemPrompt .= "\n- Allergies: {$profile['allergies']}";
        $systemPrompt .= "\n\nUse this profile to give personalized responses.";
    }

    $url  = "https://api.groq.com/openai/v1/chat/completions";
    $data = [
        "model"    => "llama-3.1-8b-instant",
        "messages" => [
            ["role" => "system", "content" => $systemPrompt],
            ["role" => "user",   "content" => $text]
        ],
        "temperature" => 0.7,
        "max_tokens"  => 300
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
        $error = curl_error($ch);
        curl_close($ch);
        return "Error communicating with AI service: " . $error;
    }

    curl_close($ch);
    $result = json_decode($response, true);

    if (isset($result['error'])) {
        return "AI error: " . $result['error']['message'];
    }

    return trim($result['choices'][0]['message']['content'] ?? "Sorry, I couldn't generate a response.");
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