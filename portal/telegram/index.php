<?php
// Replace with your bot's token
$botToken = "7716130453:AAE9IfH1hMZ0SYAQeT4X9TCIs3bQi91sKMw";
$apiURL = "https://api.telegram.org/bot" . $botToken;

// Fetch updates from Telegram
$update = file_get_contents("php://input");
$updateData = json_decode($update, true);

if (isset($updateData["message"])) {
    $chatID = $updateData["message"]["chat"]["id"];
    $message = $updateData["message"]["text"];

    // Handle messages
    if ($message == "/start") {
        sendMessage($chatID, "Welcome to my bot!");
    } else {
        sendMessage($chatID, "You said: $message");
    }
}

// Function to send a message
function sendMessage($chatID, $message) {
    global $apiURL;
    $url = $apiURL . "/sendMessage?chat_id=" . $chatID . "&text=" . urlencode($message);
    file_get_contents($url);
}
?>
