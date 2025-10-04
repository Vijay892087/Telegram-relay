<?php
if(!isset($_GET['msg'])) {
    die("No message provided!");
}

$message = $_GET['msg'];

$bot_token = "6391372827:AAHY-gfeyHZvtaGKIr4TLyga17lr73lj86o";
$chat_id   = "@earning_adda982";

$url = "https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=".urlencode($message);
file_get_contents($url);

echo "✅ Message sent successfully to Telegram!";
?>
