<?php
echo "<pre>";
print_r($_POST);
echo "</pre>";
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fname = test_input($_POST['firstname']);
    $lname = test_input($_POST['lastname']);
    $email = test_input($_POST['email']);
    $subject = $_POST['subject'];

    if (empty($fname) || empty($email)) {
        echo "Please enter your email";
        exit();
    }

    session_start();
    $_SESSION['fname'] = $fname;

    if(isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['file'];
        echo "<pre>";
        print_r($_FILES);
        echo "</pre>";

        $file_name = $_FILES['file']['name'];
        $tmpname = $_FILES['file']['tmp_name'];

        if (move_uploaded_file($tmpname, "IMG/".$file_name)) {
            echo "Successfully uploaded";
        } else {
            echo "Could not upload";
        }

    } else {
        echo "No file uploaded or there was an error uploading the file.";
    }

    echo "<h2>Successful</h2>";
    echo "<p>Email: $email</p>";
    
} else {
    echo "Invalid.";
}

?>
