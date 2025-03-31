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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Validate the form data
    if (empty($name) || empty($email) || empty($message)) {
        echo "<script>alert('All fields are required.'); window.location.href = 'contact.html';</script>";
    } else {
        // Prepare SQL query
        $sql = "INSERT INTO contact (name, email, message) VALUES ('$name', '$email', '$message')";
        
        // Execute query
        $result = mysqli_query($conn, $sql);
        
        if ($result) {
            echo "<script>alert('Thank you for contacting us, $name. We will get back to you soon.'); window.location.href = 'contact.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href = 'contact.php';</script>";
        }
    }
}


// Close the database connection
mysqli_close($conn);
?>








<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact - Daily</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo"><a href="index.html">Daily</a></div>
      <ul class="nav-links">
        <li><a href="index.html">Home</a></li>
        <li><a href="todo.html">To-Do</a></li>
        <li><a href="rss.html">RSS Feed</a></li>
        <li><a href="tracker.html">Tracker</a></li>
        <li><a href="notes.php">Notes</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </nav>
  </header>

  <main class="contact-container">
    <h2>Contact Us</h2>
    <p>Have questions or feedback? Reach out to us!</p>
    <form action="" method="POST" class="contact-form">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      <textarea name="message" placeholder="Your Message" required></textarea>
      <button type="submit" class="btn primary-btn">Send Message</button>
    </form>
  </main>
</body>
</html>
