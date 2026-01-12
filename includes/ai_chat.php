<?php
header('Content-Type: application/json; charset=utf-8');

//Loads OPENAI_API_KEY from a config file
$configPath = __DIR__ . '/../../config.php';
if (file_exists($configPath)) {
    require_once $configPath;
}

// Check if we have the API key. If not, show a message to the user about a connection error.
$apiKey = defined('OPENAI_API_KEY') ? OPENAI_API_KEY : '';
if (!$apiKey) {
    echo json_encode(['reply' => "אני איתך, פשוט יש לי תקלה קטנה בחיבור כרגע. אל תדאגי."], JSON_UNESCAPED_UNICODE);
    exit;
}

// Get the JSON data sent from the 'ai_escort.js' file
$inputJSON = file_get_contents('php://input');
$inputData = json_decode($inputJSON, true);

$userMsg = $inputData['message'] ?? '';
$chatHistory = $inputData['history'] ?? [];

// Create an array to hold the messages for the OpenAI
$messages = [];

// Prompt for defining AI assistant behavior
$messages[] = [
    'role' => 'system',
    'content' =>
    "את מלווה לילה לנשים ההולכות לבד בסביבה לא בטוחה.
    דברי בעברית, בתור אישה, בטון רגוע, קצר ואמפתי.
    אל תתני עצות מסוכנות. אם נראה שיש מצוקה – המליצי לפנות לעזרה אנושית (משפחה/חברה/מוקד חירום).
    המטרה שלך היא לחזק, להרגיע ולהיות נוכחת, לא לתת ייעוץ רפואי או משפטי."
    ];

// Add previous history to add context to the AI assistant
foreach ($chatHistory as $msg) {
    $messages[] = [
        'role' => ($msg['role'] === 'user' ? 'user' : 'assistant'),
        'content' => $msg['text']
    ];
}

// Add the current messafe from the user
$messages[] = ['role' => 'user', 'content' => $userMsg];

// Preparing the data to be sent to OpenAI in the following payload structure
$payload = [
    'model' => 'gpt-4o-mini',
    'messages' => $messages,
    'temperature' => 0.7
];

// Start CURL request to the OpenAI API
$ch = curl_init("https://api.openai.com/v1/chat/completions");

// Set CURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

// Add headers for security and content type
$headers = [
    "Content-Type: application/json",
    "Authorization: Bearer " . $apiKey
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Ingestion of the data to the payload
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

// Send the request and save the response
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Process the outcome from OpenAI
$result = json_decode($response, true);

// Get the AI's reply text or show an error message if something failed
$aiReply = $result['choices'][0]['message']['content'] ?? 'סליחה, לא הבנתי. תוכלי לחזור על זה?';

// Send the reply back to the 'ai_escort.js' file
echo json_encode(['reply' => $aiReply], JSON_UNESCAPED_UNICODE);