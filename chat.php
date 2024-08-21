<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['reply' => 'Invalid request method.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';

if (empty($message)) {
    echo json_encode(['reply' => 'No message provided.']);
    exit;
}

$apiKey = 'AIzaSyBgO8noquDwoAZwLqDFQ_HbMmGi1Ovk1HM';
$apiUrl = "https://generativelanguage.googleapis.com/v1beta2/models/chat-bison-001:generateMessage?key=$apiKey";

$data = [
    'prompt' => [
        'messages' => [
            ['author' => 'user', 'content' => $message]
        ]
    ]
];

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
    ],
];

$context  = stream_context_create($options);
$response = file_get_contents($apiUrl, false, $context);

if ($response === FALSE) {
    echo json_encode(['reply' => 'Something went wrong.']);
    exit;
}

$responseData = json_decode($response, true);
$reply = $responseData['candidates'][0]['content'] ?? 'Sorry, I didn\'t understand that.';

echo json_encode(['reply' => $reply]);
?>