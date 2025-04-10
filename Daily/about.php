<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About - Daily</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo"><a href="index.php">Daily</a></div>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <?php if (isset($_SESSION['email'])): ?>
          <li><a href="todo.php">To-Do</a></li>
          <li><a href="rss.php">RSS Feed</a></li>
          <li><a href="tracker.html">Tracker</a></li>
          <li><a href="notes.php">Notes</a></li>
        <?php endif; ?>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <?php if (!isset($_SESSION['email'])): ?>
          <li><a href="login.php" class="btn login-btn">Login</a></li>
          <li><a href="signup.php" class="btn signup-btn">Sign Up</a></li>
        <?php else: ?>
          <li><a href="logout.php" class="btn logout-btn">Sign Out</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>

  <!-- Main Content -->
  <main class="about-container">
    <!-- Hero Section for About -->
    <section class="about-hero">
      <h1>About Us</h1>
      <p>Meet our team</p>
    </section>

    <!-- Team Section -->
    <section class="team">
      <h2>Our Team</h2>
      <div class="team-container">
        <div class="card">
          <h3>Anthony Jennis Nadar</h3>
          <p>SY A 16010123051</p>
          <p>anthony.jn@somaiya.edu</p>
        </div>
        <div class="card">
          <h3>Anuj Madke</h3>
          <p>SY A 16010123052</p>
          <p>anuj.madke@somaiya.edu</p>
        </div>
        <div class="card">
          <h3>Anurag Singh</h3>
          <p>SY A 16010123053</p>
          <p>singh.an@somaiya.edu</p>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Daily. All rights reserved.</p>
  </footer>
</body>
</html>
