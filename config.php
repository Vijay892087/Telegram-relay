<?php
// ---------------- TIMEZONE ---------------- //
date_default_timezone_set('Asia/Kolkata');

// ---------------- CLAIM SETTINGS ---------------- //
$claim_amount = 1; // 1rs per UPI
$total_claim_limit = 500;
$csv_file = __DIR__ . "/free.csv"; // CSV storage

// ---------------- TELEGRAM BOT CONFIG ---------------- //
$telegram_bot_token = "6391372827:AAHY-gfeyHZvtaGKIr4TLyga17lr73lj86o";
$chat_id = "969062037"; // Admin bot full data
$channel_chat_id = -1003073944495; // Masked data for channel

// ---------------- DEBUG / LOGGING ---------------- //
$debug_mode = true;
if($debug_mode){
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);
}
?>