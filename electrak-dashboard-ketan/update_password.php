<?php
include "server.php";

$connection = OpenCon();
function sanitize_input($input)
{
    global $connection;
    $input = trim($input);
    $input = mysqli_real_escape_string($connection, $input);
    return $input;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $Email = sanitize_input($_POST["email"]);
    $UserId = sanitize_input($_POST["username"]);
    $Password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    if ($Password !== $confirmPassword) {
        $response = array("message" => "Error: Passwords do not match.");
        echo json_encode($response);
        exit;
    }

    $query = "SELECT * FROM fw_users WHERE Email = '$Email' AND UserId = '$UserId'";
    $result = $connection->query($query);

    if ($result->num_rows === 1) {
        $updateQuery = "UPDATE fw_users SET Password = '$Password' WHERE Email = '$Email' AND UserId = '$UserId'";
        if ($connection->query($updateQuery) === TRUE) {

            header("Location: forgotPassword.php?status=1");
        } else {
            header("Location: forgotPassword.php?status=0");
        }
    } else {
        $response = array("message" => "Error: Incorrect Email or UserId.");
        echo json_encode($response);
    }

    $connection->close();
}
