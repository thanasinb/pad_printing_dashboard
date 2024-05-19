<?php
require 'update/establish.php'; // Ensure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debugging line
    echo "POST request received";

    // รับค่าจากฟอร์ม
    $id_staff = $_POST['new_id_staff'];
    $username = $_POST['new_username'];
    $password = $_POST['new_password']; // Hash the password for security

    // Validate input
    if (!empty($id_staff) && !empty($username) && !empty($password)) {
        // Debugging line
        echo "All fields are set";

        // เตรียมคำสั่ง SQL และ bind parameters
        $stmt = $conn->prepare("INSERT INTO login (id_staff, username, password) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $id_staff, $username, $password);
            if ($stmt->execute()) {
                echo "New user added successfully";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Prepare failed: " . $conn->error;
        }
    } else {
        echo "All fields are required";
    }

    $conn->close();
} else {
    echo "Invalid request method";
}
?>
