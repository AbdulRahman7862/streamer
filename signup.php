<?php
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "your_database_name"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_name = trim($_POST['username']);
    $email = trim($_POST['email']);
    $license_key = trim($_POST['license_key']);

    // Validate inputs
    if (empty($user_name) || empty($email) || empty($license_key)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Validate license key with Gumroad
    $gumroad_api_url = "https://api.gumroad.com/v2/licenses/verify";
    $gumroad_product_id = "isUlaPNpPmhNM8NiAFHVVA=="; // Replace with your Gumroad product ID
    $gumroad_access_token = "PxQfs9603rcqvSxBntjvnJYSeMSUxZ7SXp_vXmZB5M8"; // Replace with your Gumroad access token

    $postData = [
        'product_id' => $gumroad_product_id,
        'license_key' => $license_key,
        'access_token' => $gumroad_access_token
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $gumroad_api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $gumroad_response = json_decode($response, true);

    if (!$gumroad_response['success']) {
        die("Invalid license key.");
    }

    // Insert user data into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, license_key) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user_name, $email, $license_key);

    if ($stmt->execute()) {
        echo "Signup successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
