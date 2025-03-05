<?php
require 'update/establish.php';

if (isset($_POST['query'])) {
    $query = $_POST['query'];

    // Query ค้นหา Staff ID ที่ role_group เป็น 2 หรือ 3 เท่านั้น
    $sql = "SELECT id_staff FROM staff 
            WHERE id_staff LIKE ? 
            AND id_role_group IN (2, 3) 
            ORDER BY id_staff ASC LIMIT 10";

    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $query . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $output = "";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $output .= '<div class="suggestion-item" data-id="' . $row['id_staff'] . '">' . $row['id_staff'] . '</div>';
        }
    } else {
        $output = '<div class="suggestion-item">No results found</div>';
    }

    echo $output;
    $stmt->close();
    $conn->close();
}
?>