<?php  
// Load domain knowledge - changed to domain_knowledgeEmp.php
$domainKnowledgeFile = 'domain_knowledgeEmp.php';
if (!file_exists($domainKnowledgeFile)) {
    echo json_encode(['error' => 'Domain knowledge file not found']);
    exit;
}

$domainKnowledge = include($domainKnowledgeFile);
if (!is_array($domainKnowledge)) {
    echo json_encode(['error' => 'Invalid domain knowledge format']);
    exit;
}

$api_key = "GEMINI_API_KEY"; // Your Gemini key

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Get input safely
$input = json_decode(file_get_contents("php://input"), true);
if (!$input || !isset($input['message'])) {
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$user_message = trim($input['message']);
if (empty($user_message)) {
    echo json_encode(['error' => 'Empty message']);
    exit;
}

// 1. Check if this is a request for domain questions
if ($user_message === "domain_questions") {
    $followUpQuestions = array_map(function($item) {
        return $item['question'] ?? '';
    }, $domainKnowledge);
    
    echo json_encode([
        'response' => 'Please select a question:',
        'source' => 'domain',
        'follow_up' => array_values(array_filter($followUpQuestions))
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 2. Check domain questions first
foreach ($domainKnowledge as $qa) {
    if (!isset($qa['question']) || !isset($qa['answer'])) {
        continue;
    }
    
    if (stripos($user_message, $qa['question']) !== false) {
        $followUpQuestions = array_map(function($item) {
            return $item['question'] ?? '';
        }, $domainKnowledge);
        
        echo json_encode([
            'response' => $qa['answer'], 
            'source' => 'domain',
            'follow_up' => array_values(array_filter($followUpQuestions))
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 3. Fallback to Gemini
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$api_key";
$data = ["contents" => [["parts" => [["text" => $user_message]]]]];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Accept: application/json"
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$error = curl_error($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($error) {
    echo json_encode(['error' => "CURL error: $error"]);
    exit;
}

if ($http_code !== 200) {
    echo json_encode(['error' => "API error (HTTP $http_code)", 'response' => $response]);
    exit;
}

$response_data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'Invalid JSON response', 'raw' => $response]);
    exit;
}

if (!isset($response_data['candidates'][0]['content']['parts'][0]['text'])) {
    echo json_encode(['error' => 'Unexpected response format', 'data' => $response_data]);
    exit;
}

$followUpQuestions = array_map(function($item) {
    return $item['question'] ?? '';
}, $domainKnowledge);

echo json_encode([
    'response' => trim($response_data['candidates'][0]['content']['parts'][0]['text']),
    'source' => 'gemini',
    'follow_up' => array_values(array_filter($followUpQuestions))
], JSON_UNESCAPED_UNICODE);
?>