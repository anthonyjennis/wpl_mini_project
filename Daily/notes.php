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

// Handle note submission (Insert/Update)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (!empty($title) && !empty($content)) {
        // Insert or update note
        $sql = "INSERT INTO notes (title, text) VALUES ('$title', '$content') ON DUPLICATE KEY UPDATE text='$content'";
        mysqli_query($conn, $sql);
    }
}

// Fetch all notes
$notesQuery = "SELECT id, title, text FROM notes";
$notesResult = mysqli_query($conn, $notesQuery);
$notes = [];
while ($row = mysqli_fetch_assoc($notesResult)) {
    $notes[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notes - Daily</title>
  <link rel="stylesheet" href="styles.css">
  <script>
    function loadNote(title, text) {
        document.querySelector('.editor-title').value = title;
        document.querySelector('.editor-content').value = text;
    }
  </script>
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
        <li><a href="login.html" class="btn login-btn">Login</a></li>
        <li><a href="signup.html" class="btn signup-btn">Sign Up</a></li>
      </ul>
    </nav>
  </header>

  <main class="notes-page">
    <aside class="notes-sidebar">
      <h2>My Notes</h2>
      <ul class="notes-tree">
        <?php foreach ($notes as $note) { ?>
            <li><span class="note-item" onclick="loadNote('<?php echo addslashes($note['title']); ?>', '<?php echo addslashes($note['text']); ?>')"><?php echo $note['title']; ?></span></li>
        <?php } ?>
      </ul>
    </aside>

    <section class="notes-editor">
      <form method="POST" action="notes.php">
        <input type="text" class="editor-title" name="title" placeholder="Enter note title..." required>
        <textarea class="editor-content" name="content" placeholder="Start writing your note here..." required></textarea>
        <button type="submit">Save Note</button>
      </form>
    </section>
  </main>
</body>
</html>

<?php mysqli_close($conn); ?>
