<?php
session_start();

if (isset($_SESSION['login'])) {
    header('Location: index.php');
    exit;
}

// Database Connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=movie_db;charset=utf8", "root", "TyTy123!!");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$test_keys = ['TEST-KEY-1234', 'FAKE-KEY-1238'];

if (isset($_POST['license_key'])) {
    $license_key = trim($_POST['license_key']);
    $user_ip = $_SERVER['REMOTE_ADDR']; 

    if ($user_ip === '::1') {
        $user_ip = '127.0.0.1'; // Convert IPv6 loopback to IPv4
    }

    $is_test_key = in_array($license_key, $test_keys);

    if (!$is_test_key) {
        $product_id = 'isUlaPNpPmhNM8NiAFHVVA==';

        $postData = http_build_query([
            'product_id' => $product_id,
            'license_key' => $license_key,
        ]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.gumroad.com/v2/licenses/verify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
        ]);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            error_log('cURL error: ' . curl_error($ch));
            curl_close($ch);
            exit('An error occurred.');
        }
        curl_close($ch);

        $response = json_decode($result, true);

        if (!($response['success'] ?? false) || !empty($response['subscription_cancelled_at']) || !empty($response['subscription_failed_at'])) {
            header("Location: https://cozycouchpotato.gumroad.com/l/streamwithus");
            exit;
        }
    }

    // Check if the license key exists in the database
    $stmt = $pdo->prepare("SELECT ip_address FROM license_logins WHERE license_key = ?");
    $stmt->execute([$license_key]);
    $stored_ip = $stmt->fetchColumn();

    if ($stored_ip) {
        if ($stored_ip !== $user_ip) {
            exit('License key already in use from a different IP address.');
        }

        $updateStmt = $pdo->prepare("UPDATE license_logins SET last_login = NOW() WHERE license_key = ?");
        $updateStmt->execute([$license_key]);
    } else {
        $insertStmt = $pdo->prepare("INSERT INTO license_logins (license_key, ip_address) VALUES (?, ?)");
        $insertStmt->execute([$license_key, $user_ip]);
    }

    $_SESSION['login'] = true;
    header("Location: index.php");
    exit;
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login Form</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    /* Global Styles & Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    html, body {
      height: 100%;
      font-family: "poppins", sans-serif;
    }
    .container {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    /* Mobile Layout: Left & Right with specific heights */
    .left-side, .right-side {
      width: 100%;
    }
    .left-side {
      height: 45vh;
    }
    .right-side {
      height: 55vh;
    }
    /* For screens wider than 768px, display side-by-side */
    @media (min-width: 768px) {
      .container {
        flex-direction: row;
      }
      .left-side, .right-side {
        width: 50%;
        height: auto; /* Let content decide height on desktop */
      }
    }
    /* Left Side Styles */
    .left-side {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }
    .background-image {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url("https://images.unsplash.com/photo-1615986201152-7686a4867f30?q=80&w=3125&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D")
        center/cover no-repeat;
    }
    .red-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(220, 38, 38, 0.8); /* red color with 80% opacity */
    }
    .left-content {
      position: relative;
      z-index: 1;
      padding: 20px;
      max-width: 320px;
      text-align: center;
      color: #fff;
    }
    .left-content h1 {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 10px;
      opacity: 0;
      animation: fadeInUp 1s ease forwards;
    }
    .left-content p {
      font-size: 1.125rem;
      margin-bottom: 10px;
      opacity: 0;
      animation: fadeInUp 1s ease forwards;
      animation-delay: 0.5s;
    }
    /* Right Side Styles */
    .right-side {
      background-color: #000;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .form-container {
      width: 100%;
      max-width: 400px;
      opacity: 0;
      animation: fadeInUp 1s ease forwards;
      animation-delay: 0.2s ease;
    }
    .form-container h2 {
      font-size: 1.875rem;
      font-weight: bold;
      color: #fff;
      margin-bottom: 20px;
    }
    .form-group {
      margin-bottom: 16px;
    }
    .form-group label {
      display: block;
      margin-bottom: 4px;
      color: #ccc;
    }
    .password-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }
    .form-group input {
      width: 100%;
      padding: 16px;
      font-size: 1.125rem;
      border: 1px solid #444;
      border-radius: 4px;
      background-color: #333;
      color: #fff;
    }
    .form-group input:focus {
      outline: none;
      border-color: #dc2626;
      box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.5);
    }
    .toggle-btn {
      position: absolute;
      right: 8px;
      background: none;
      border: none;
      color: #ccc;
      font-size: 0.9rem;
      cursor: pointer;
    }
    .btn-submit {
      width: 100%;
      padding: 16px;
      background-color: #dc2626;
      color: #fff;
      font-weight: 600;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin-top: 16px;
      font-size: 1.125rem;
    }
    .btn-submit:hover {
      background-color: #b91c1c;
    }
    .register-link {
      margin-top: 16px;
      text-align: center;
      color: #aaa;
    }
    .register-link a {
      color: #dc2626;
      text-decoration: none;
    }
    .register-link a:hover {
      text-decoration: underline;
    }
    /* Animation Keyframes */
    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Left Side: Image with Red Overlay -->
    <div class="left-side">
      <div class="background-image"></div>
      <div class="red-overlay"></div>
      <div class="left-content">
        <h1>Welcome Back</h1>
        <p>Enjoy Your Favourite Movies</p>
      </div>
    </div>
    
    <!-- Right Side: Login Form -->
    <div class="right-side">
      <div class="form-container">
        <h2>Login</h2>
        <form action="login.php" method="post">
          <div class="form-group">
            <label for="password">Enter your Gumroad License Key:</label>
            <div class="password-wrapper">
              <input
                id="password"
                type="password"
                name="license_key"
                placeholder="••••••••"
                required
              />
              <button type="button" class="toggle-btn" id="togglePassword">Show</button>
            </div>
          </div>
          <button type="submit" class="btn-submit">Login</button>
        </form>
      </div>
    </div>
  </div>
  <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.textContent = type === 'password' ? 'Show' : 'Hide';
    });
  </script>
</body>
</html>
