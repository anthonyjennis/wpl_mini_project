<?php
session_start();

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$dbname = "Daily";

// Enable mysqli error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    echo "<script>alert('Database connection failed: " . mysqli_connect_error() . "');</script>";
    die();
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle note submission (Insert/Update)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_note'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $note_id = $_POST['note_id'] ?? null;

    if (!empty($title) && !empty($content)) {
        try {
            if ($note_id) {
                // Update existing note with prepared statement
                $stmt = $conn->prepare("UPDATE notes SET title=?, text=? WHERE id=? AND user_id=?");
                $stmt->bind_param("ssii", $title, $content, $note_id, $user_id);
            } else {
                // Insert new note with prepared statement
                $stmt = $conn->prepare("INSERT INTO notes (user_id, title, text) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $user_id, $title, $content);
            }
            $stmt->execute();
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { // Duplicate entry error code
                echo "<script>alert('A note with this title already exists. Please choose a different title.');</script>";
            } else {
                echo "<script>alert('Error saving note: " . $e->getMessage() . "');</script>";
            }
        }
    }
}

// Handle note deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_note'])) {
    $note_id = $_POST['note_id'];
    
    if (!empty($note_id)) {
        try {
            // Delete note with prepared statement
            $stmt = $conn->prepare("DELETE FROM notes WHERE id=? AND user_id=?");
            $stmt->bind_param("ii", $note_id, $user_id);
            $stmt->execute();
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            echo "<script>alert('Error deleting note: " . $e->getMessage() . "');</script>";
        }
    }
}

// Fetch all notes for the logged-in user
try {
    $stmt = $conn->prepare("SELECT id, title, text FROM notes WHERE user_id=? ORDER BY id DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $notesResult = $stmt->get_result();
    $notes = [];
    while ($row = $notesResult->fetch_assoc()) {
        $notes[] = $row;
    }
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    echo "<script>alert('Error fetching notes: " . $e->getMessage() . "');</script>";
    $notes = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Notes - Daily</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
    :root {
      --primary-color: #5271ff;
      --secondary-color: #f8f9fa;
      --accent-color: #e4ebff;
      --text-color: #333;
      --sidebar-width: 250px;
      --border-radius: 8px;
      --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      --transition: all 0.3s ease;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--text-color);
      background-color: #f5f7fa;
      margin: 0;
      line-height: 1.6;
    }
    
    .notes-page {
      display: flex;
      height: calc(100vh - 70px);
      margin-top: 10px;
    }
    
    .notes-sidebar {
      width: var(--sidebar-width);
      background-color: var(--secondary-color);
      border-right: 1px solid #e0e0e0;
      padding: 20px;
      overflow-y: auto;
      box-shadow: var(--box-shadow);
      border-radius: var(--border-radius);
      margin-left: 15px;
    }
    
    .notes-sidebar h2 {
      color: var(--primary-color);
      margin-bottom: 20px;
      font-size: 1.5rem;
      border-bottom: 2px solid var(--primary-color);
      padding-bottom: 10px;
    }
    
    .notes-tree {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    
    .note-item {
      padding: 12px 15px;
      margin-bottom: 8px;
      display: block;
      background-color: white;
      border-radius: var(--border-radius);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      cursor: pointer;
      transition: var(--transition);
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    
    .note-item:hover {
      background-color: var(--accent-color);
      transform: translateY(-2px);
    }
    
    .note-item.active {
      background-color: var(--primary-color);
      color: white;
      font-weight: 500;
    }
    
    .notes-editor {
      flex: 1;
      padding: 20px;
      display: flex;
      flex-direction: column;
    }
    
    .notes-editor form {
      display: flex;
      flex-direction: column;
      height: 100%;
      background-color: white;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      padding: 20px;
    }
    
    .editor-title {
      font-size: 1.5rem;
      padding: 12px 15px;
      margin-bottom: 15px;
      border: 1px solid #e0e0e0;
      border-radius: var(--border-radius);
      outline: none;
      transition: var(--transition);
    }
    
    .editor-title:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(82, 113, 255, 0.2);
    }
    
    .editor-content {
      flex: 1;
      padding: 15px;
      margin-bottom: 15px;
      border: 1px solid #e0e0e0;
      border-radius: var(--border-radius);
      resize: none;
      font-family: inherit;
      font-size: 1rem;
      line-height: 1.6;
      outline: none;
      transition: var(--transition);
    }
    
    .editor-content:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(82, 113, 255, 0.2);
    }
    
    .btn {
      border: 1px solid #007bff;
      border-radius: 4px;
      padding: 5px 12px;
      font-weight: bold;
    }
    
    .primary-btn {
      background-color: var(--primary-color);
      color: white;
    }
    
    .primary-btn:hover {
      background-color: #3f5cdb;
      transform: translateY(-2px);
    }
    
    .delete-btn {
      background-color: #ff5252;
      color: white;
      margin-top: 10px;
    }
    
    .delete-btn:hover {
      background-color: #e04747;
      transform: translateY(-2px);
    }
    
    .button-group {
      display: flex;
      gap: 10px;
    }
    
    @media (max-width: 768px) {
      .notes-page {
        flex-direction: column;
        height: auto;
      }
      
      .notes-sidebar {
        width: 100%;
        margin-bottom: 20px;
        margin-left: 0;
      }
      
      .editor-title, .editor-content {
        margin-bottom: 10px;
      }
      
      .notes-editor {
        padding: 10px;
      }
    }
  </style>
  <script>
    function loadNote(id, title, text) {
      // Set form values
      document.querySelector("input[name='note_id']").value = id;
      document.querySelector(".editor-title").value = title;
      document.querySelector(".editor-content").value = text;
      
      // Highlight selected note
      document.querySelectorAll('.note-item').forEach(item => {
        item.classList.remove('active');
      });
      event.currentTarget.classList.add('active');
    }
    
    function confirmDelete() {
      const noteId = document.querySelector("input[name='note_id']").value;
      if (!noteId) {
        alert("Please select a note to delete.");
        return false;
      }
      return confirm("Are you sure you want to delete this note?");
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
        <?php if (count($notes) > 0): ?>
          <?php foreach ($notes as $note): ?>
            <li>
              <span class="note-item" onclick="loadNote('<?php echo $note['id']; ?>', '<?php echo addslashes(htmlspecialchars($note['title'])); ?>', '<?php echo addslashes(htmlspecialchars($note['text'])); ?>')">
                <?php echo htmlspecialchars($note['title']); ?>
              </span>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li><p>No notes yet. Create your first note!</p></li>
        <?php endif; ?>
      </ul>
    </aside>

    <section class="notes-editor">
      <form method="POST" action="notes.php">
        <input type="hidden" name="note_id" />
        <input type="text" class="editor-title" name="title" placeholder="Enter note title..." required />
        <textarea class="editor-content" name="content" placeholder="Start writing your note here..." required></textarea>
        <div class="button-group">
          <button type="submit" name="save_note" class="btn primary-btn">Save Note</button>
          <button type="submit" name="delete_note" class="btn delete-btn" onclick="return confirmDelete()">Delete Note</button>
        </div>
      </form>
    </section>
  </main>
</body>
</html>

<?php mysqli_close($conn); ?>
