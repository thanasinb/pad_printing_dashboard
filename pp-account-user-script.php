<?php
// เชื่อมต่อกับฐานข้อมูล
require 'update/establish.php';

$sql = "SELECT *, staff.name_first, staff.name_last, prefix.prefix, role.role, role_group.role_group_name
    FROM login
    LEFT JOIN staff ON login.id_staff = staff.id_staff
    LEFT JOIN prefix ON staff.prefix = prefix.id_prefix
    LEFT JOIN role ON staff.id_role = role.id_role
    LEFT JOIN role_group ON login.id_role_group = role_group.id_role_group";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr class='text-black fw-bold row_staff'>";
        echo '<td class="text-center id_staff">' . $row['id_staff'] . '</td>';
        echo '<td class="text-center name_first">' . $row['name_first'] . '</td>';
        echo '<td class="text-center name_last">' . $row['name_last'] . '</td>';
        echo '<td class="text-center username">' . $row['username'] . '</td>';
        echo '<td class="text-center password">' . $row['password'] . '</td>'; // Display password for editing
        echo '<td class="text-center">' . $row['role'] . '</td>';
        echo '<td class="text-center">' . $row['role_group_name'] . '</td>';
        echo '<td class="text-center">
                <button type="button" 
                        class="btn btn-datatable btn-icon text-black me-2 user_edit" 
                        data-id_staff="' . $row['id_staff'] . '" 
                        data-bs-toggle="modal" 
                        data-bs-target="#setting_dt_modal">
                    <i class="far fa-edit fs-6"></i>
                </button>
                <button type="button" 
                        class="btn btn-datatable btn-icon text-black me-2 user_delete" 
                        data-id_staff="' . $row['id_staff'] . '" 
                        data-bs-toggle="modal" 
                        data-bs-target="#delete_user_modal">
                    <i class="fas fa-trash"></i>
                </button>
              </td>';
        echo '</tr>';
    }
} else {
    echo "No results found.";
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$conn->close();
?>
