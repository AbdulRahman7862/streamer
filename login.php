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

$test_keys = [
    'TEST-KEY-1234', 'FAKE-KEY-1238',
    'TEST-KEY-5678', 'FAKE-KEY-9012',
    'TEST-KEY-3456', 'FAKE-KEY-7890',
    'TEST-KEY-2345', 'FAKE-KEY-6789',
    'TEST-KEY-4567', 'FAKE-KEY-8901',
    'TEST-KEY-6789', 'FAKE-KEY-0123',
    'TEST-KEY-8901', 'FAKE-KEY-2345',
    'TEST-KEY-0123', 'FAKE-KEY-4567',
    'TEST-KEY-2345', 'FAKE-KEY-6789',
    'TEST-KEY-4567', 'FAKE-KEY-8901'
];

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
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#dc2626">

    <style>
    /* Global Styles & Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html,
    body {
        height: 100%;
        font-family: "poppins", sans-serif;
    }

    .container {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* Mobile Layout: Left & Right with specific heights */
    .left-side,
    .right-side {
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

        .left-side,
        .right-side {
            width: 50%;
            height: auto;
            /* Let content decide height on desktop */
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
        background: url("https://images.unsplash.com/photo-1615986201152-7686a4867f30?q=80&w=3125&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D") center/cover no-repeat;
    }

    .red-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(220, 38, 38, 0.8);
        /* red color with 80% opacity */
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

    /* PWA Install Modal Styles */
    .pwa-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .pwa-modal-content {
        background-color: #fff;
        padding: 2rem;
        border-radius: 10px;
        max-width: 90%;
        width: 400px;
        text-align: center;
        position: relative;
        animation: modalSlideIn 0.3s ease-out;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .pwa-modal h2 {
        color: #dc2626;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        font-weight: bold;
    }

    .pwa-modal p {
        color: #333;
        margin-bottom: 1.5rem;
        line-height: 1.5;
        font-size: 1.1rem;
    }

    .pwa-modal-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .pwa-modal-btn {
        padding: 0.8rem 1.5rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .pwa-install-btn {
        background-color: #dc2626;
        color: #fff;
        flex: 2;
    }

    .pwa-install-btn:hover {
        background-color: #b91c1c;
        transform: scale(1.05);
    }

    .pwa-close-btn {
        background-color: #f3f4f6;
        color: #333;
        flex: 1;
    }

    .pwa-close-btn:hover {
        background-color: #e5e7eb;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    </style>

    <script>
    let deferredPrompt;
    let isInstallable = false;
    let pwaModal = null;

    // Check if the app is already installed
    function isPWAInstalled() {
        return window.matchMedia('(display-mode: standalone)').matches || 
               window.navigator.standalone || 
               document.referrer.includes('android-app://');
    }

    // Get browser-specific installation instructions
    function getInstallInstructions() {
        const ua = navigator.userAgent;
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(ua);
        
        if (ua.includes('iPhone') || ua.includes('iPad') || ua.includes('iPod')) {
            return {
                title: 'Install on iOS',
                steps: [
                    '1. Tap the share button (□↑) in the bottom bar',
                    '2. Scroll down and tap "Add to Home Screen"',
                    '3. Tap "Add" to confirm'
                ]
            };
        } else if (ua.includes('Android')) {
            if (ua.includes('Chrome')) {
                return {
                    title: 'Install on Android Chrome',
                    steps: [
                        '1. Tap the three dots menu (⋮) in the top-right corner',
                        '2. Tap "Add to Home screen"',
                        '3. Tap "Add" to confirm'
                    ]
                };
            } else if (ua.includes('Firefox')) {
                return {
                    title: 'Install on Android Firefox',
                    steps: [
                        '1. Tap the menu button (☰) in the top-right corner',
                        '2. Tap "Add to Home screen"',
                        '3. Tap "Add" to confirm'
                    ]
                };
            } else if (ua.includes('SamsungBrowser')) {
                return {
                    title: 'Install on Samsung Browser',
                    steps: [
                        '1. Tap the menu button (⋮) in the top-right corner',
                        '2. Tap "Add to Home screen"',
                        '3. Tap "Add" to confirm'
                    ]
                };
            }
        } else if (ua.includes('Chrome')) {
            return {
                title: 'Install on Chrome',
                steps: [
                    '1. Click the three dots menu (⋮) in the top-right corner',
                    '2. Look for "Install Couch Potato" or "Add to Home Screen"',
                    '3. Click "Install" in the popup that appears'
                ]
            };
        } else if (ua.includes('Firefox')) {
            return {
                title: 'Install on Firefox',
                steps: [
                    '1. Click the menu button (☰) in the top-right corner',
                    '2. Look for "Install Couch Potato" or "Add to Home Screen"',
                    '3. Click "Install" in the popup that appears'
                ]
            };
        } else if (ua.includes('Edge')) {
            return {
                title: 'Install on Edge',
                steps: [
                    '1. Click the three dots menu (⋮) in the top-right corner',
                    '2. Look for "Install Couch Potato" or "Add to Home Screen"',
                    '3. Click "Install" in the popup that appears'
                ]
            };
        } else if (ua.includes('Safari')) {
            return {
                title: 'Install on Safari',
                steps: [
                    '1. Click the share button (□↑) in the top bar',
                    '2. Scroll down and click "Add to Home Screen"',
                    '3. Click "Add" to confirm'
                ]
            };
        }

        // Default instructions for unsupported browsers
        return {
            title: 'Install Couch Potato',
            steps: [
                'Please use Chrome, Edge, Firefox, or Safari to install the app',
                'Mobile users: Please use Chrome, Firefox, or Safari on your device'
            ]
        };
    }

    // Create and show the PWA modal
    function createPWAModal() {
        if (pwaModal) return;

        const instructions = getInstallInstructions();
        pwaModal = document.createElement('div');
        pwaModal.className = 'pwa-modal';
        pwaModal.innerHTML = `
            <div class="pwa-modal-content">
                <h2>Install Couch Potato App</h2>
                <p>To use this website, you must install our app. This will give you the best experience with offline support and quick access.</p>
                <div class="pwa-modal-buttons">
                    <button class="pwa-modal-btn pwa-install-btn">Install App</button>
                    <button class="pwa-modal-btn pwa-close-btn">Close</button>
                </div>
                <div class="install-instructions">
                    <h3>${instructions.title}</h3>
                    <ul>
                        ${instructions.steps.map(step => `<li>${step}</li>`).join('')}
                    </ul>
                </div>
            </div>
        `;
        document.body.appendChild(pwaModal);

        // Handle install button click
        pwaModal.querySelector('.pwa-install-btn').addEventListener('click', async () => {
            if (!deferredPrompt) {
                // If deferredPrompt is not available, try to trigger installation directly
                const ua = navigator.userAgent;
                if (ua.includes('Chrome') || ua.includes('Edge')) {
                    // For Chrome/Edge, try to trigger the native install prompt
                    if ('getInstalledRelatedApps' in navigator) {
                        try {
                            const relatedApps = await navigator.getInstalledRelatedApps();
                            if (!relatedApps.length) {
                                // If app is not installed, try to trigger installation
                                if ('standalone' in navigator) {
                                    navigator.standalone.request();
                                } else {
                                    // Try to trigger the native install prompt
                                    const manifestLink = document.querySelector('link[rel="manifest"]');
                                    if (manifestLink) {
                                        const manifestUrl = manifestLink.href;
                                        // Create a temporary link to trigger installation
                                        const link = document.createElement('a');
                                        link.href = manifestUrl;
                                        link.rel = 'manifest';
                                        document.head.appendChild(link);
                                        link.click();
                                        document.head.removeChild(link);
                                    }
                                }
                                return;
                            }
                        } catch (error) {
                            console.error('Error checking installed apps:', error);
                        }
                    }
                } else if (ua.includes('Firefox')) {
                    // For Firefox, try to trigger the native install prompt
                    if ('mozApps' in navigator) {
                        try {
                            const request = navigator.mozApps.getSelf();
                            request.onsuccess = function() {
                                if (!request.result) {
                                    // Try to trigger installation
                                    if ('standalone' in navigator) {
                                        navigator.standalone.request();
                                    }
                                }
                            };
                        } catch (error) {
                            console.error('Error checking Firefox app:', error);
                        }
                    }
                }
                return;
            }
            
            try {
                // Show the install prompt
                deferredPrompt.prompt();
                // Wait for the user to respond to the prompt
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    console.log('User accepted the install prompt');
                    pwaModal.style.display = 'none';
                } else {
                    // If user declines installation, show message and redirect
                    alert('Please close this tab and use the installed app instead.');
                    window.location.href = '/';
                }
            } catch (error) {
                console.error('Error during installation:', error);
                alert('Please close this tab and use the installed app instead.');
                window.location.href = '/';
            }
        });

        // Handle close button click
        pwaModal.querySelector('.pwa-close-btn').addEventListener('click', () => {
            alert('Please close this tab and use the installed app instead.');
            window.location.href = '/';
        });

        // Prevent closing the modal by clicking outside
        pwaModal.addEventListener('click', (e) => {
            if (e.target === pwaModal) {
                e.preventDefault();
            }
        });
    }

    // Add styles for installation instructions
    const style = document.createElement('style');
    style.textContent = `
        .install-instructions {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: left;
        }
        .install-instructions h3 {
            color: #dc2626;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        .install-instructions ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .install-instructions li {
            color: #666;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }
    `;
    document.head.appendChild(style);

    // Initialize PWA functionality when DOM is loaded
    document.addEventListener('DOMContentLoaded', () => {
        // Register service worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then((registration) => {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch((error) => {
                    console.log('Service Worker registration failed:', error);
                });
        }

        // Create and show modal if needed
        if (!isPWAInstalled() && isInstallableBrowser()) {
            setTimeout(() => {
                createPWAModal();
                pwaModal.style.display = 'flex';
            }, 1000);
        }
    });

    // Handle the beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent Chrome 67 and earlier from automatically showing the prompt
        e.preventDefault();
        // Stash the event so it can be triggered later
        deferredPrompt = e;
        isInstallable = true;
        console.log('Install prompt is ready');
    });

    // Handle successful installation
    window.addEventListener('appinstalled', () => {
        if (pwaModal) {
            pwaModal.style.display = 'none';
        }
        console.log('PWA was installed');
        // Redirect to the installed app
        window.location.href = '/';
    });

    // Prevent default browser back button
    window.history.pushState(null, null, window.location.href);
    window.onpopstate = function () {
        window.history.pushState(null, null, window.location.href);
    };
    </script>
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
                            <input id="password" type="password" name="license_key" placeholder="••••••••" required />
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