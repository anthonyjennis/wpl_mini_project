<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$dbname = "Daily";

// Connect to DB
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$signupError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    if (empty($name) || empty($email) || empty($password)) {
        $signupError = "All fields are required.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO `sign-up` (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>alert('Account created successfully! Please log in.'); window.location.href = 'login.php';</script>";
            exit();
        } else {
            $signupError = "Error: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up - Daily</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo"><a href="index.php">Daily</a></div>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="login.php" class="btn login-btn">Login</a></li>
      </ul>
    </nav>
  </header>

  <main class="auth-container">
    <h2>Create an Account</h2>

    <?php if (!empty($signupError)): ?>
      <p style="color:red; font-weight:bold;">
        <?= $signupError ?>
      </p>
    <?php endif; ?>

    <form method="POST" class="auth-form">
      <input type="text" name="name" placeholder="Full Name" required />
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit" class="btn primary-btn">Sign Up</button>
    </form>
    <p class="auth-link">Already have an account? <a href="login.php">Login</a></p>
  </main>
</body>
</html>
