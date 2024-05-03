<?php
require 'update/establish.php';

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Get username from session

    // SQL query to retrieve user's name from database
    $sql = "SELECT staff.name_first
            FROM login
            INNER JOIN staff ON login.id_staff = staff.id_staff
            WHERE login.username = '$username'";
    $result = $conn->query($sql);

    // Check if query returned any results
    if ($result->num_rows > 0) {
        // Display user's name
        while($row = $result->fetch_assoc()) {
            $name = $row["name_first"];
            echo '<div class="dropdown-user-details-name">' . $name . '</div>';
        }
    } else {
        $name = "Welcome"; // If no results found, set name to "Welcome"
    }
}
?>