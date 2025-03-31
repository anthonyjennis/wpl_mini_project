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
            echo "<script>alert('Thank you for contacting us, $name. We will get back to you soon.'); window.location.href = 'contact.html';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href = 'contact.html';</script>";
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>
