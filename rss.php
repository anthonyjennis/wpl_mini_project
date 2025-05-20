<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>RSS Feed - Daily</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <!-- Navigation -->
  <header>
    <nav class="navbar">
      <div class="logo"><a href="index.php">Daily</a></div>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>

        <?php if (isset($_SESSION['email'])): ?>
          <li><a href="todo.php">To-Do</a></li>
          <li><a href="rss.php">RSS Feed</a></li>
          <li><a href="tracker.php">Tracker</a></li>
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

  <main>
    <section class="feeds-hero">
      <h1>My Feeds</h1>
    </section>

    <section class="feeds-scroll-container">
      <div class="feed-box">Feed One</div>
      <div class="feed-box">Feed Two</div>
      <div class="feed-box">Random Feed</div>
      <div class="feed-box">Lorem Ipsum</div>
    </section>

    <section class="feed-articles">
      <article class="article-card">
        <h2>Lorem Ipsum Dolor</h2>
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
          Praesent convallis augue in neque fermentum, sed tristique metus imperdiet.
        </p>
      </article>
      <article class="article-card">
        <h2>Aliquam Ultrices</h2>
        <p>
          Aliquam ultrices felis a suscipit auctor. Morbi mollis est 
          eget augue tempus, eget suscipit nisl gravida.
        </p>
      </article>
      <article class="article-card">
        <h2>Curabitur In Luctus</h2>
        <p>
          Curabitur in luctus nibh, sed consequat metus. Nunc sed tincidunt diam. 
          Fusce vulputate laoreet consequat.
        </p>
      </article>
    </section>

    <button class="fab-add-feed" title="Add new feed">+</button>

    <div class="modal" id="addFeedModal">
      <div class="modal-content">
        <h3>Add New Feed</h3>
        <input type="text" placeholder="Paste RSS Link Here" />
        <div class="modal-buttons">
          <button class="btn primary-btn">Add Feed</button>
          <button class="btn btn-close">Cancel</button>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 Daily. All rights reserved.</p>
  </footer>
</body>
</html>
