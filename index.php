<?php
include "config.php";

// Auto-create CSV if not exists
if(!file_exists($csv_file)){
    $f = fopen($csv_file,'w');
    fputcsv($f, ['UPI ID','Claim','Time']);
    fclose($f);
}

// Calculate total claims
$total_claims = 0;
if(file_exists($csv_file)){
    $data = array_map('str_getcsv', file($csv_file));
    if(!empty($data) && $data[0][0] === 'UPI ID') $total_claims = count($data)-1;
    else $total_claims = count($data);
}

$claim_available = $total_claims < $total_claim_limit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Free ₹1 Per UPI</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
body{margin:0;font-family:'Inter',sans-serif;background:linear-gradient(135deg,#0f1722,#0b3b2e);color:#fff;display:flex;justify-content:center;align-items:center;height:100vh;}
.container{background:rgba(0,0,0,0.8);padding:30px;border-radius:15px;width:90%;max-width:400px;text-align:center;box-shadow:0 8px 30px rgba(0,0,0,0.5);}
h1{color:#2ecc71;margin-bottom:10px;}
p{margin-bottom:20px;}
input{width:100%;padding:12px;margin-bottom:16px;border-radius:10px;border:1px solid #ccc;font-size:16px;}
button{width:100%;padding:14px;background:#27ae60;border:none;color:#fff;font-size:18px;font-weight:700;border-radius:10px;cursor:pointer;transition:0.3s;}
button:hover{background:#2ecc71;}
button:disabled{background:#7f8c8c;cursor:not-allowed;}
</style>
</head>
<body>
<div class="container">
<h1>🆓 Free ₹1 per UPI ID</h1>
<p>1 user 1 UPI linked with bank</p>

<?php if($claim_available): ?>
<form action="submit.php" method="post">
<input type="text" name="upi_id" placeholder="Enter UPI ID for getting payment" required>
<button type="submit">Get Money</button>
</form>
<p>Total Claims So Far: <?php echo $total_claims; ?> / <?php echo $total_claim_limit; ?></p>
<?php else: ?>
<h2>⚠ Claim Limit Reached!</h2>
<p>Total Claims: <?php echo $total_claims; ?> / <?php echo $total_claim_limit; ?></p>
<?php endif; ?>
</div>
</body>
</html>