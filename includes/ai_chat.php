<?php
// SafeWalk - AI chat
// Handles the communication with OpenAI API.

header('Content-Type: application/json; charset=utf-8');

//Loads OPENAI_API_KEY from a config file
$configPath = __DIR__ . '/../../config.php';
if (file_exists($configPath)) {
    require_once $configPath;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get the raw JSON data from the request body
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

// Set default values to prevent "undefined index" errors
$userMessage = isset($data['message']) ? trim($data['message']) : '';
$history = isset($data['history']) ? $data['history'] : [];
$isEmergency = isset($data['meta']['simulatedEmergency']) ? $data['meta']['simulatedEmergency'] : false;

if (empty($userMessage)) {
    echo json_encode(['error' => 'No message provided']);
    exit;
}

//if (!is_array($data)) $data = [];

//$message = trim($data['message'] ?? '');
//if ($message === '') {
//    echo json_encode(['error' => 'no_message']);
//    exit;
//}

// if API key is missing, return a fallback so the app still works
$apiKey = defined('OPENAI_API_KEY') ? OPENAI_API_KEY : '';

//if (!$apiKey) {
//    $fallback = "אני מלווה אותך כאן, גם בלי חיבור מלא 😊 אם את מרגישה חוסר ביטחון, נשמי עמוק, הסתכלי סביבך, ואם צריך – תפני למישהי קרובה או למוקד חירום.";
//    echo json_encode(['reply' => $fallback], JSON_UNESCAPED_UNICODE);
//    exit;
//}

// Build chat messages for OpenAI
$messages = [];

// System prompt: defines assistant behavior, it is important that the AI escort will talk as a women.
$messages[] = [
    'role' => 'system',
    'content' =>
"את מלווה לילה לנשים ההולכות לבד בסביבה לא בטוחה.
דברי בעברית, בתור אישה, בטון רגוע, קצר ואמפתי.
אל תתני עצות מסוכנות. אם נראה שיש מצוקה – המליצי לפנות לעזרה אנושית (משפחה/חברה/מוקד חירום).
המטרה שלך היא לחזק, להרגיע ולהיות נוכחת, לא לתת ייעוץ רפואי או משפטי."
];

// Add conversation history so the AI "remembers" the context
foreach ($history as $turn) {
    $messages[] = [
        'role' => ($turn['role'] === 'user' ? 'user' : 'assistant'),
        'content' => $turn['text']
    ];
}

// Add the current user message
$messages[] = [
    'role' => 'user',
    'content' => $userMessage
];

// If the SOS logic was triggered, simulate “distress detected”
if (!empty($meta['simulatedEmergency'])) {
    $messages[] = [
        'role' => 'user',
        'content' => "המשתמשת נשמעת במצוקה או ביקשה עזרה ('עזרה', 'מפחיד', 'תתקשרו למישהו')."
    ];
}

// 5. Prepare and execute the API call using CURL
$payload = [
    'model' => 'gpt-4o-mini',
    'messages' => $messages,
    'temperature' => 0.7
];

// Call OpenAI
$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Return the response back to the JS

if ($httpStatus === 200) {
    $resData = json_decode($response, true);
    $reply = $resData['choices'][0]['message']['content'] ?? '';
    echo json_encode(['reply' => $reply], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['reply' => "אני פה איתך, גם אם כרגע יש בעיה בחיבור ל-AI.
        . תזכרי שאת לא לבד, ואם את מרגישה לא בטוח – אפשר לפנות לחברה קרובה או למוקד חירום."]);
}
