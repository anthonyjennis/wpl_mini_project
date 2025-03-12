<?php
echo "<pre>";
print_r($_POST);
echo "</pre>";
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = test_input($_POST['username']);
    $gender = test_input($_POST['gender']);
    $email = test_input($_POST['email']);
    $password = test_input($_POST['password']);

    if (empty($username) || empty($gender) || empty($email) || empty($password)) {
        echo "Please fill in all fields.";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    session_start();
    $_SESSION['username'] = $username;

    echo "<h2>Registration Successful</h2>";
    echo "<p>Username: $username</p>";
    echo "<p>Gender: $gender</p>";
    echo "<p>Email: $email</p>";
    echo "<p>Password (hashed): $hashed_password</p>";
    echo "You are now signed up";
    
} else {
    echo "Invalid.";
}

?>
