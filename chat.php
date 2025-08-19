<?php
require 'functions.php';
redirectIfNotLoggedIn(); // Only allow logged-in users

$user_id = $_SESSION['user_id'];
$history = loadUserHistory($user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_msg = trim($_POST['message']);
    if ($user_msg) {
        $timestamp = date('Y-m-d H:i:s');

        // Save user's message with timestamp and type
        $history[] = [
            'sender' => 'User',
            'message' => $user_msg,
            'type' => 'text',
            'timestamp' => $timestamp
        ];

        // Load AI responses
        $ai_json = file_get_contents('ai_responses.json');
        $ai_data = json_decode($ai_json, true);

        $msg_lower = strtolower($user_msg);

        // Keyword-based category detection
        if (strpos($msg_lower,'hi') !== false || strpos($msg_lower,'hello') !== false) {
            $ai_msg = $ai_data['greetings'][array_rand($ai_data['greetings'])];
        } elseif (strpos($msg_lower,'bye') !== false) {
            $ai_msg = $ai_data['farewells'][array_rand($ai_data['farewells'])];
        } elseif (strpos($msg_lower,'order') !== false || strpos($msg_lower,'refund') !== false || strpos($msg_lower,'support') !== false) {
            $ai_msg = $ai_data['customer_service'][array_rand($ai_data['customer_service'])];
        } elseif (strpos($msg_lower,'device') !== false || strpos($msg_lower,'error') !== false || strpos($msg_lower,'issue') !== false) {
            $ai_msg = $ai_data['tech_support'][array_rand($ai_data['tech_support'])];
        } elseif (strpos($msg_lower,'invoice') !== false || strpos($msg_lower,'billing') !== false || strpos($msg_lower,'payment') !== false) {
            $ai_msg = $ai_data['billing'][array_rand($ai_data['billing'])];
        } elseif (strpos($msg_lower,'product') !== false || strpos($msg_lower,'feature') !== false || strpos($msg_lower,'spec') !== false) {
            $ai_msg = $ai_data['product_info'][array_rand($ai_data['product_info'])];
        } elseif (strpos($msg_lower,'joke') !== false || strpos($msg_lower,'fun') !== false) {
            $ai_msg = $ai_data['fun_talk'][array_rand($ai_data['fun_talk'])];
        } else {
            $ai_msg = $ai_data['default'][array_rand($ai_data['default'])];
        }

        // Save AI response with timestamp and type
        $history[] = [
            'sender' => 'AI',
            'message' => $ai_msg,
            'type' => 'text',
            'timestamp' => date('Y-m-d H:i:s')
        ];

        // Save updated history to user's JSON file
        saveUserHistory($user_id, $history);
    }
}

// Return chat history as HTML including timestamps
foreach ($history as $msg) {
    $class = ($msg['sender'] === 'User') ? 'user' : 'ai';
    echo "<p class='{$class}'><b>{$msg['sender']}:</b> {$msg['message']} <span style='font-size:0.8em;color:gray;'>({$msg['timestamp']})</span></p>";
}
?>
