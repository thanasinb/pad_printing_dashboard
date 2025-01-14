<?php
require 'update/establish.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

if (isset($_POST['query'])) {
    $query = $_POST['query'] . '%'; // เพิ่ม wildcard % สำหรับการค้นหา

    $stmt = $conn->prepare("SELECT id_staff FROM staff WHERE id_staff LIKE ? LIMIT 10");
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<a href="#" class="list-group-item list-group-item-action suggestion-item">'
                . htmlspecialchars($row['id_staff']) . '</a>';
        }
    } else {
        echo '<div class="list-group-item">No matching IDs found</div>';
    }

    $stmt->close();
    $conn->close();
}
?>