<?php
// ดึงจำนวนพนักงานทั้งหมด
$sql_total_staff = "SELECT COUNT(*) AS total_staff FROM staff WHERE active = 1";
$total_staff_result = $conn->query($sql_total_staff);
$total_staff = ($total_staff_result->fetch_assoc())['total_staff'];

// ดึงจำนวนพนักงานแยกตาม role
$sql_role_counts = "
SELECT role.role, COUNT(staff.id_staff) AS staff_count
FROM staff
JOIN role ON staff.id_role = role.id_role
WHERE staff.active = 1
GROUP BY role.role
ORDER BY staff_count DESC";
$role_counts_result = $conn->query($sql_role_counts);

$role_counts = [];
while ($row = $role_counts_result->fetch_assoc()) {
$role_counts[] = $row;
}
?>