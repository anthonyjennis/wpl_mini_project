<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daily - Productivity App</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- Navigation -->
  <header>
    <nav class="navbar">
      <div class="logo">
        <a href="index.php">Daily</a>
      </div>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="todo.php">To-Do</a></li>
          <li><a href="rss.php">RSS Feed</a></li>
          <li><a href="tracker.php">Tracker</a></li>
          <li><a href="notes.php">Notes</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="logout.php" class="btn logout-btn">Sign Out</a></li>
        <?php else: ?>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="login.php" class="btn login-btn">Login</a></li>
          <li><a href="signup.php" class="btn signup-btn">Sign Up</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>

  <!-- Main Content -->
  <main>
    <!-- Hero Section -->
    <section class="hero">
      <h1>Welcome to Daily</h1>
      <p>Your ultimate productivity companion</p>
    </section>

    <!-- Tech Stack Section -->
    <section class="tech-stack">
      <h2>Tech Stack</h2>
      <ul>
        <li><strong>Design:</strong> Figma</li>
        <li><strong>Frontend:</strong> HTML5, CSS3, JavaScript</li>
        <li><strong>Backend:</strong> PHP</li>
        <li><strong>Database:</strong> MySQL</li>
        <li><strong>Web Server:</strong> Apache</li>
        <li><strong>Version Control:</strong> Git &amp; GitHub</li>
      </ul>
    </section>

    <!-- Features Section -->
    <section class="features">
      <h2>Features</h2>
      <div class="features-scroll">
        <div class="feature-card">Static Pages: Home, About, Contact Us</div>
        <div class="feature-card">User Authentication: Login &amp; Sign Up</div>
        <div class="feature-card">To-Do List with full CRUD functionality</div>
        <div class="feature-card">RSS Feed Aggregator for unified blog feeds</div>
        <div class="feature-card">Activity Tracker for goal tracking</div>
        <div class="feature-card">Note Taking with nested pages &amp; basic formatting</div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Daily. All rights reserved.</p>
  </footer>
</body>
</html>
