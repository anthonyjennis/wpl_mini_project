<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>To-Do - Daily</title>
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
        <li><a href="login.php" class="btn login-btn">Login</a></li>
        <li><a href="signup.php" class="btn signup-btn">Sign Up</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <!-- 1. My Lists Section -->
    <section class="my-lists-section">
      <h1>My Lists</h1>
      <div class="lists-container">
        <!-- Default "All Tasks" -->
        <div class="list-card">
          <h2>All Tasks</h2>
          <span class="task-count">13</span>
        </div>
        <!-- Example categories -->
        <div class="list-card">
          <h2>Personal</h2>
          <span class="task-count">1</span>
        </div>
        <div class="list-card">
          <h2>Grocery List</h2>
          <span class="task-count">2</span>
        </div>
        <div class="list-card">
          <h2>College</h2>
          <span class="task-count">4</span>
        </div>
        <div class="list-card">
          <h2>later</h2>
          <span class="task-count">6</span>
        </div>
      </div>
      <!-- Floating button for adding new lists -->
      <button class="fab fab-lists" title="Add a new list">+</button>
    </section>

    <!-- 2. Category Tasks Section -->
    <!-- In a real app, this section would be shown after the user clicks a list card -->
    <section class="category-section">
      <h1>Category: Grocery List</h1>
      <ul class="task-list">
        <li>
          <input type="checkbox" id="task-1">
          <label for="task-1">Buy Milk</label>
        </li>
        <li>
          <input type="checkbox" id="task-2">
          <label for="task-2">Buy Eggs</label>
        </li>
        <li>
          <input type="checkbox" id="task-3">
          <label for="task-3">Pick up fruits</label>
        </li>
      </ul>
      <!-- Floating button for adding new tasks -->
      <button class="fab fab-tasks" title="Add a new task">+</button>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 Daily. All rights reserved.</p>
  </footer>
</body>
</html>
