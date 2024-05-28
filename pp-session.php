<?php
require 'update/establish.php';

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Get username from session

    // SQL query to retrieve user's name from database
    $sql = "SELECT staff.name_first, staff.name_last ,role.role, role_group.role_group_name
        FROM login
        INNER JOIN staff ON login.id_staff = staff.id_staff
        INNER JOIN role ON staff.id_role = role.id_role
        INNER JOIN role_group ON role.role_group = role_group.id_role_group
        WHERE login.username = '$username'";

    $result = $conn->query($sql);

    // Check if query returned any results
    if ($result->num_rows > 0) {
        // Display user's name and surname
        while($row = $result->fetch_assoc()) {
            $name = $row["name_first"];
            $surname = $row["name_last"];
            $role = $row["role"];
            $role_group = $row["role_group_name"];
            echo '<div class="dropdown-user-details-name">' . $name . ' ' . $surname . '</div>';
        }
    } else {
        $name = "Welcome"; // If no results found, set name to "Welcome"
    }

}
?>