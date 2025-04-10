<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Daily</title>
  <link rel="stylesheet" href="styles.css">
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
        <li><a href="signup.php" class="btn signup-btn">Sign Up</a></li>
      </ul>
    </nav>
  </header>

  <main class="auth-container">
    <h2>Login to Daily</h2>
    <form action="process_login.php" method="POST" class="auth-form">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="btn primary-btn">Login</button>
    </form>
    <p class="auth-link">New user? <a href="signup.php">Sign up</a></p>
  </main>
</body>
</html>
