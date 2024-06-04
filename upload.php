<?php
require 'establish.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png'];
    $filename = $_FILES['imageFile']['name'];
    $filetype = $_FILES['imageFile']['type'];
    $filesize = $_FILES['imageFile']['size'];

    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!in_array($ext, $allowed)) {
        echo "ERROR: Please select a valid file format.";
        exit;
    }

    if ($filesize > 5242880) { // 5MB
        echo "ERROR: File size is larger than the allowed limit.";
        exit;
    }

    $new_filename = uniqid() . "." . $ext;
    $upload_dir = 'uploads/';
    $upload_file = $upload_dir . $new_filename;

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (move_uploaded_file($_FILES['imageFile']['tmp_name'], $upload_file)) {
        // Replace with actual user id from session or other source
        $user_id = 1;

        $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
        $stmt->bind_param('si', $new_filename, $user_id);

        if ($stmt->execute()) {
            echo $upload_file;
        } else {
            echo "ERROR: Could not update profile image in database.";
        }
    } else {
        echo "ERROR: Could not move uploaded file.";
    }
} else {
    echo "ERROR: Invalid file or no file uploaded.";
}
?>
