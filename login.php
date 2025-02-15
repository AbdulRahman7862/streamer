

<?php
session_start();

if (isset($_SESSION['login'])) {
    header('Location: index.php');
    exit;
}

if (isset($_POST['license_key'])) {
    // The license key submitted by the user
    $license_key = trim($_POST['license_key']);
    // Replace with your Gumroad product's permalink (the unique identifier, e.g., "streamwithus")
    $product_permalink = 'streamwithus';

    // Prepare the POST data for Gumroad API
    $postData = http_build_query([
        'product_permalink' => $product_permalink,
        'license_key'       => $license_key,
    ]);

    // Initialize cURL
    $ch = curl_init('https://api.gumroad.com/v2/licenses/verify');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    // Execute the API request
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        // Handle cURL error
        echo 'Error: ' . curl_error($ch);
        curl_close($ch);
        exit;
    }
    curl_close($ch);

    // Decode the JSON response
    $response = json_decode($result, true);

    // Check if Gumroad verified the license key successfully
    if (isset($response['success']) && $response['success'] === true) {
        // License verified!
        // You can extract additional info if needed (like purchaser's email, etc.)
        // $purchase = $response['purchase'];

        // Set up the session as verified
        $_SESSION['login'] = true;

        // Optionally, you can generate a token and store it in your database (see your original code)
        // For example:
        // $token = generateToken();
        // $expiresAt = date('Y-m-d H:i:s', time() + 86400);
        // ... (insert token into database)

        header("Location: index.php");
        exit;
    } else {
        // Verification failed – you can provide a more detailed error message if desired
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

