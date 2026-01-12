<?php
// This script takes text from JS and returns an MP3 audio stream.

// Set the header to tell the browser we are sending audio
header('Content-Type: audio/mpeg');

//Loads OPENAI_API_KEY from a config file
$configPath = __DIR__ . '/../../config.php';
if (file_exists($configPath)) {
    require_once $configPath;
}

// GET text from 'ai_escort.js' file
$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: [];
$text = trim($data['text'] ?? '');

// If text is empty, return an error
if ($text === '') {
  http_response_code(400);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['error' => 'no_text'], JSON_UNESCAPED_UNICODE);
  exit;
}

// Check API key
$apiKey = OPENAI_API_KEY ?? '';
if (!$apiKey) {
  http_response_code(500);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['error' => 'no_api_key'], JSON_UNESCAPED_UNICODE);
  exit;
}

// Prepare data for OpenAI
$payload = [
  'model' => 'gpt-4o-mini-tts',
  'voice' => 'alloy', // Voice style
  'format' => 'mp3',
  'input' => $text,
];

// Send request to OpenAI using CURL
$ch = curl_init("https://api.openai.com/v1/audio/speech");
curl_setopt_array($ch, [
  CURLOPT_POST => true,
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer " . $apiKey,
    "Content-Type: application/json"
  ],
  CURLOPT_RETURNTRANSFER => true, // Save results in a variable
  CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE)
]);

$audio = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Play the audio
if ($code === 200 &&& $audio !== false) {

    // If everything is OK, send the audio back to JS
    echo $audio;
  } else {
      http_response_code($code ?: 500);
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode(['error' => 'tts_failed', 'http_code' => $code], JSON_UNESCAPED_UNICODE);
}
