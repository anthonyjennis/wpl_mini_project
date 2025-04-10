<?php
session_start();

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

// Handle note submission (Insert/Update)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_note'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (!empty($title) && !empty($content)) {
        $sql = "INSERT INTO notes (title, text) 
                VALUES ('$title', '$content') 
                ON DUPLICATE KEY UPDATE text='$content'";
        mysqli_query($conn, $sql);
    }
}

// Handle note deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_note'])) {
    $note_id = $_POST['note_id'];
    $deleteQuery = "DELETE FROM notes WHERE id='$note_id'";
    mysqli_query($conn, $deleteQuery);
}

// Fetch all notes
$notesQuery = "SELECT id, title, text FROM notes ORDER BY id DESC";
$notesResult = mysqli_query($conn, $notesQuery);
$notes = [];
while ($row = mysqli_fetch_assoc($notesResult)) {
    $notes[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Notes - Daily</title>
  <link rel="stylesheet" href="styles.css" />
  <script>
    function loadNote(id, title, text) {
      document.querySelector("input[name='note_id']").value = id;
      document.querySelector(".editor-title").value = title;
      document.querySelector(".editor-content").value = text;
    }
  </script>
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

  <main class="notes-page">
    <aside class="notes-sidebar">
      <h2>My Notes</h2>
      <ul class="notes-tree">
        <?php foreach ($notes as $note) { ?>
          <li>
            <span class="note-item" onclick="loadNote('<?php echo $note['id']; ?>', '<?php echo addslashes($note['title']); ?>', '<?php echo addslashes($note['text']); ?>')">
              <?php echo htmlspecialchars($note['title']); ?>
            </span>
          </li>
        <?php } ?>
      </ul>
    </aside>

    <section class="notes-editor">
      <form method="POST" action="notes.php">
        <input type="hidden" name="note_id" />
        <input type="text" class="editor-title" name="title" placeholder="Enter note title..." required />
        <textarea class="editor-content" name="content" placeholder="Start writing your note here..." required></textarea>
        <button type="submit" name="save_note" class="btn primary-btn">Save Note</button>
        <button type="submit" name="delete_note" class="btn delete-btn">Delete Note</button>
      </form>
    </section>
  </main>
</body>
</html>

<?php mysqli_close($conn); ?>
