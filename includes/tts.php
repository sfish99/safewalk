<?php

/*
  Receives JSON: { "text": "..." }
  Returns: mp3 audio (OpenAI TTS)
*/

header('Content-Type: audio/mpeg');

require_once __DIR__ . '/../../config.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: [];
$text = trim($data['text'] ?? '');

if ($text === '') {
  http_response_code(400);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['error' => 'no_text'], JSON_UNESCAPED_UNICODE);
  exit;
}

$apiKey = OPENAI_API_KEY ?? '';
if (!$apiKey) {
  http_response_code(500);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['error' => 'no_api_key'], JSON_UNESCAPED_UNICODE);
  exit;
}

$payload = [
  'model' => 'gpt-4o-mini-tts',
  'voice' => 'alloy',
  'format' => 'mp3',
  'input' => $text,
];

$ch = curl_init("https://api.openai.com/v1/audio/speech");
curl_setopt_array($ch, [
  CURLOPT_POST => true,
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer " . $apiKey,
    "Content-Type: application/json"
  ],
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE)
]);

$audio = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($code !== 200 || $audio === false) {
  http_response_code($code ?: 500);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['error' => 'tts_failed', 'http_code' => $code], JSON_UNESCAPED_UNICODE);
  exit;
}

echo $audio;
