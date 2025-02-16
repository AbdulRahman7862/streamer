<?php
session_start();

if (isset($_SESSION['login'])) {
    header('Location: index.php');
    exit;
}

// Hardcoded test license keys
$test_keys = [
    'TEST-KEY-1234',
    'FAKE-KEY-5678'
];

if (isset($_POST['license_key'])) {
    $license_key = trim($_POST['license_key']);
    
    // Check if the license key is a hardcoded test key
    if (in_array($license_key, $test_keys)) {
        $_SESSION['login'] = true;
        header("Location: index.php");
        exit;
    }

    $product_id = 'isUlaPNpPmhNM8NiAFHVVA=='; // Replace with your actual product ID

    $postData = http_build_query([
        'product_id' => $product_id,
        'license_key' => $license_key,
        'increment_uses_count' => 'false'
    ]);

    $ch = curl_init('https://api.gumroad.com/v2/licenses/verify');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        error_log('cURL error: ' . curl_error($ch)); // Log errors instead of displaying them
        curl_close($ch);
        exit;
    }
    curl_close($ch);

    $response = json_decode($result, true);

    if (isset($response['success']) && $response['success'] === true) {
        $purchase = $response['purchase'];
        if (empty($purchase['subscription_ended_at']) && empty($purchase['subscription_cancelled_at']) && empty($purchase['subscription_failed_at'])) {
            $_SESSION['login'] = true;
            header("Location: index.php");
            exit;
        } else {
            header("Location: subscription_page.php"); // Redirect to subscription page if subscription is not active
            exit;
        }
    } else {
        echo "License verification failed. Please check your license key and try again.";
    }
}
?>

<?php include_once 'includes/header.php'; ?>
<body style="background:white !important;">
<div class="container">
    <div class="section">
        <form method="post" action="login.php">
            <input class="form-control" type="text" name="license_key" placeholder="Enter your Gumroad License Key" required>
            <button type="submit" class="button">Submit</button>
        </form>
    </div>
</div>
</body>
