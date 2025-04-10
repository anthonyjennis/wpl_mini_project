<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$dbname = "Daily";

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    if (empty($name) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required.');</script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO `sign-up` (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>alert('Account created successfully! Please log in.'); window.location.href = 'login.html';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
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
      <div class="logo"><a href="index.html">Daily</a></div>
      <ul class="nav-links">
        <li><a href="index.html">Home</a></li>
        <li><a href="todo.php">To-Do</a></li>
        <li><a href="rss.php">RSS Feed</a></li>
        <li><a href="tracker.html">Tracker</a></li>
        <li><a href="notes.php">Notes</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="login.php" class="btn login-btn">Login</a></li>
      </ul>
    </nav>
  </header>

  <main class="auth-container">
    <h2>Create an Account</h2>
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
