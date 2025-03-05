<?php
require 'update/establish.php'; // เชื่อมต่อฐานข้อมูล

// ดึงข้อมูลพนักงาน พร้อมจำนวนงานที่ทำ และเปรียบเทียบ On Target / Dis Target
$sql = "
    SELECT 
        a.id_staff,
        s.name_first, 
        s.name_last, 
        COUNT(a.id_activity) AS total_jobs, 
        SUM(a.no_pulse1 + a.no_pulse2 + a.no_pulse3) AS total_trays,
        SUM(CASE 
            WHEN (TIME_TO_SEC(a.total_work) / 3600) <= (p.run_time_std * ( + a.no_pulse2 + a.no_pulse3) / p.qty_per_pulse2)
            THEN 1 ELSE 0 END) AS on_target_count,
        SUM(CASE 
            WHEN (TIME_TO_SEC(a.total_work) / 3600) > (p.run_time_std * ( + a.no_pulse2 + a.no_pulse3) / p.qty_per_pulse2)
            THEN 1 ELSE 0 END) AS dis_target_count
    FROM activity a
    JOIN staff s ON a.id_staff = s.id_staff
    JOIN planning p ON a.id_task = p.id_task
    WHERE s.active = 1
    GROUP BY a.id_staff
    ORDER BY dis_target_count DESC, on_target_count DESC, s.id_staff ASC
";

$result_staff = $conn->query($sql);
$index = 1; // เริ่มต้นตัวเลขลำดับ

if ($result_staff->num_rows > 0) {
    while ($data_staff = $result_staff->fetch_assoc()) {
        echo "<tr class='text-black fw-bold row_staff'>";

        // ลำดับที่ (Index)
        echo "<td class='text-center'>" . $index . "</td>";

        // ID พนักงาน
        echo "<td class='text-center'>" . htmlspecialchars($data_staff['id_staff']) . "</td>";

        // จำนวนงานที่ทำทั้งหมด (แสดงที่ id_task)
        echo "<td class='text-center'>" . htmlspecialchars($data_staff['total_jobs']) . "</td>";

        // ชื่อพนักงาน (ชื่อจริง + นามสกุล)
        echo "<td class='text-center'>" . htmlspecialchars($data_staff['name_first'] . " " . $data_staff['name_last']) . "</td>";

        // จำนวนถาดที่ทำทั้งหมด
        echo "<td class='text-center'>" . htmlspecialchars($data_staff['total_trays']) . "</td>";

        // On Target (งานที่ทำได้ตรงเวลา)
        echo "<td class='text-center'>" . htmlspecialchars($data_staff['on_target_count']) . "</td>";

        // Dis Target (งานที่ใช้เวลามากกว่าที่คำนวณ)
        echo "<td class='text-center'>" . htmlspecialchars($data_staff['dis_target_count']) . "</td>";

        echo "</tr>";
        $index++; // เพิ่มลำดับ
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>ไม่พบข้อมูลพนักงาน</td></tr>";
}

$conn->close();
?>