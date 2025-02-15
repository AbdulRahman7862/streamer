<?php
session_start();

if (isset($_POST['new_password'])) {
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $database = 'movie';
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $database);
    if ($conn) {
        $new_password = base64_encode($_POST['new_password']);
        $sql = "UPDATE login SET password = '$new_password'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo 'Password changed successfully';
        } else {
            echo 'Error in password update';
        }
        mysqli_close($conn);

    }

}
?>
<?php include_once 'includes/header.php'; ?>

<body style="background:white !important;">
    <div class="container">
        <div class="section">
            <form method="post" action="reset_password.php">
                <input class="form-control" type="password" name="new_password" placeholder="New Password" required>
                <button type="submit" class="button">Submit</button>
            </form>
        </div>
    </div>
</body>