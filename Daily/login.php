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

$loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Query user by email
    $sql = "SELECT * FROM `sign-up` WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $email;
            $_SESSION['created_at'] = time();
            echo "<script>alert('Login successful'); window.location.href='index.php';</script>";
            exit();
        } else {
            $loginError = "Invalid password.";
        }
    } else {
        $loginError = "User not found.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Daily</title>
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
        <li><a href="signup.php" class="btn signup-btn">Sign Up</a></li>
      </ul>
    </nav>
  </header>

  <main class="auth-container">
    <h2>Login to Daily</h2>

    <?php if ($loginError): ?>
      <p style="color:red; font-weight:bold;">
        <?= $loginError ?>
      </p>
    <?php endif; ?>

    <form action="login.php" method="POST" class="auth-form">
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit" class="btn primary-btn">Login</button>
    </form>
    <p class="auth-link">New user? <a href="signup.php">Sign up</a></p>
  </main>
</body>
</html>