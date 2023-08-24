<?php
include 'server.php';
function validateLogin($username, $password)
{
    $conn = OpenCon();
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    $sql = "SELECT * FROM fw_users WHERE UserId='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if ($password == $user['Password']) {
            CloseCon($conn);
            return true;
        }
    }
    CloseCon($conn);
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (validateLogin($username, $password)) {
        session_start();
        $_SESSION['username'] = $username;
        

        if (isset($_SESSION['username'])) {
            header("Location: dashboard.php");
        }
        exit();
    } else {
    
        header("Location: loginPage.php?error=1");
        exit();
    }
}
