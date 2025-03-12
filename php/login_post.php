<?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);

    if (empty($username) || empty($password)) {
        echo "Please fill in all fields.";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    session_start();
    $_SESSION['username'] = $username;

    echo "<h2>LOGIN Successful</h2>";
    echo "<p>Username: $username</p>";
    echo "<p>Password (hashed): $hashed_password</p>";
    echo "You are now logged in!";
    
} else {
    echo "Invalid.";
}

?>
