<?php
header('Content-Type: application/json; charset=utf-8');

//Loads OPENAI_API_KEY from a config file
$configPath = __DIR__ . '/../../config.php';
if (file_exists($configPath)) {
    require_once $configPath;
}
//בדיקה, למחוק אחר כך
if (!defined('OPENAI_API_KEY')) {
    echo json_encode(['error' => 'OPENAI_API_KEY not loaded']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method !== 'POST') {
    echo json_encode(['error' => 'invalid_method'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Read JSON body from the browser
$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: [];

$userMessage = $data['message'] ?? '';
$history     = $data['history'] ?? [];
$meta        = $data['meta'] ?? [];

if (!$userMessage) {
    echo json_encode(['error' => 'no_message'], JSON_UNESCAPED_UNICODE);
    exit;
}

// if API key is missing, return a fallback so the app still works
$apiKey = defined('OPENAI_API_KEY') ? OPENAI_API_KEY : '';

if (!$apiKey) {
    $fallback = "אני מלווה אותך כאן, גם בלי חיבור מלא 😊 אם את מרגישה חוסר ביטחון, נשמי עמוק, הסתכלי סביבך, ואם צריך – תפני למישהי קרובה או למוקד חירום.";
    echo json_encode(['reply' => $fallback], JSON_UNESCAPED_UNICODE);
    exit;
}

// Build chat messages for OpenAI
$messages = [];

// System prompt: defines assistant behavior
$messages[] = [
    'role' => 'system',
    'content' =>
"את מלווה לילה לנשים ההולכות לבד בסביבה לא בטוחה.
דברי בעברית, בתור אישה, בטון רגוע, קצר ואמפתי.
אל תתני עצות מסוכנות. אם נראה שיש מצוקה – המליצי לפנות לעזרה אנושית (משפחה/חברה/מוקד חירום).
המטרה שלך היא לחזק, להרגיע ולהיות נוכחת, לא לתת ייעוץ רפואי או משפטי."
];

// Add previous history
foreach ($history as $h) {
    if (!isset($h['role'], $h['text'])) continue;
    $role = $h['role'] === 'user' ? 'user' : 'assistant';
    $messages[] = [
        'role' => $role,
        'content' => $h['text']
    ];
}

// Current user message
$messages[] = [
    'role' => 'user',
    'content' => $userMessage
];

// Simulate “distress detected” mode
if (!empty($meta['simulatedEmergency'])) {
    $messages[] = [
        'role' => 'user',
        'content' => "המשתמשת נשמעת במצוקה או ביקשה עזרה ('עזרה', 'מפחיד', 'תתקשרו למישהו')."
    ];
}

$payload = [
    'model' => 'gpt-4o-mini',
    'messages' => $messages,
    'temperature' => 0.6,
    'max_tokens' => 120
];

// Call OpenAI Chat Completions
$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . $apiKey,
        "Content-Type: application/json"
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE)
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

if ($httpCode !== 200 || $response === false) {
    $fallbackReply = 'אני פה איתך, גם אם כרגע יש בעיה בחיבור ל-AI. '
        . 'תזכרי שאת לא לבד, ואם את מרגישה לא בטוח – אפשר לפנות לחברה קרובה או למוקד חירום.';
    echo json_encode([
        'error'      => 'api_error',
        'http_code'  => $httpCode,
        'curl_error' => $curlError,
        'reply'      => $fallbackReply
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$respData = json_decode($response, true);
$reply = $respData['choices'][0]['message']['content'] ?? '';

echo json_encode(['reply' => $reply], JSON_UNESCAPED_UNICODE);