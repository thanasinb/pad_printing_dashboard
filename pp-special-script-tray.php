<?php
require 'update/establish.php';

// รับค่าจากการสแกน QR Code (ถ้ามีการส่งมาด้วย method POST)
$tray_id    = isset($_POST['tray_id']) ? $_POST['tray_id'] : null;  // รหัสถาดที่สแกน
$id_cam     = isset($_POST['id_cam']) ? $_POST['id_cam'] : null;    // รหัสกล้องที่ใช้สแกน
$id_mc      = isset($_POST['id_mc']) ? $_POST['id_mc'] : null;      // รหัสเครื่องจักร
$description= isset($_POST['description']) ? $_POST['description'] : null; // สถานะ "detected" หรือ "removed"

// กรณีมีข้อมูล tray_id, id_cam, id_mc, description ส่งมาทาง POST
if ($tray_id && $id_cam && $id_mc && $description) {
    // ตรวจสอบว่า Tray ID นี้มีอยู่ในฐานข้อมูลหรือไม่
    $check_sql = "SELECT id, id_cam, id_mc FROM qr_data WHERE qr_code_data = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("s", $tray_id);
    $stmt_check->execute();
    $stmt_check->store_result();
    $stmt_check->bind_result($existing_id, $existing_cam, $existing_mc);
    $stmt_check->fetch();
    $exists = $stmt_check->num_rows > 0;
    $stmt_check->close();

    if ($exists) {
        // หาก Tray มีอยู่แล้ว ให้ตรวจสอบว่ามีการเปลี่ยนแปลง id_cam หรือ id_mc หรือไม่
        if ($existing_cam !== $id_cam || $existing_mc !== $id_mc) {
            // ----------------------------------------------------------
            // (1) บันทึก Log ลง work_time_log ก่อนทำการ update ใน qr_data
            // ----------------------------------------------------------
            // เราจะ query แบบ aggregated สำหรับ Tray นี้เพื่อดึงข้อมูลบางส่วน
            $aggSql = "
                SELECT 
                    DATE(timestamp) as date,
                    MIN(timestamp) as time_in,
                    MAX(timestamp) as time_out,
                    TIMEDIFF(MAX(timestamp), MIN(timestamp)) as total_time
                FROM qr_data
                WHERE qr_code_data = ?
                GROUP BY qr_code_data
            ";
            $stmtAgg = $conn->prepare($aggSql);
            $stmtAgg->bind_param("s", $tray_id);
            $stmtAgg->execute();
            $resultAgg = $stmtAgg->get_result();
            if ($rowAgg = $resultAgg->fetch_assoc()) {
                $date_field   = $rowAgg['date'];
                $time_in      = $rowAgg['time_in'];
                $time_out     = $rowAgg['time_out'];
                $total_time   = $rowAgg['total_time'];
            } else {
                // หากไม่พบข้อมูล aggregated ให้ใช้ค่า default
                $date_field   = date('Y-m-d');
                $time_in      = date('Y-m-d H:i:s');
                $time_out     = date('Y-m-d H:i:s');
                $total_time   = '00:00:00';
            }
            $stmtAgg->close();

            // กำหนดค่า default สำหรับฟิลด์ที่อาจไม่มีข้อมูล ณ จุดนี้
            $run_time_std      = 0;
            $total_time_min    = 0;
            $total_qty_pulse2  = 0;
            $id_staff_db       = '';
            $id_task_db        = '';
            // สมมติว่าเมื่อมีการเปลี่ยนแปลง เรากำหนด status เป็น 'normal' (หรือปรับตามความเหมาะสม)
            $status_new = 'normal';
            $timestamp  = date('Y-m-d H:i:s');

            // Insert log record ลง work_time_log
            $insertLogSql = "
                INSERT INTO work_time_log
                (
                    tray_id, date_field, start_time, end_time, total_time, 
                    run_time_std, total_time_min, id_cam, id_mc, total_qty_per_pulse2, 
                    id_staff, id_task, status, created_at
                )
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
            $stmtLog = $conn->prepare($insertLogSql);
            $stmtLog->bind_param(
                "sssssdsssssss",
                $tray_id,
                $date_field,
                $time_in,
                $time_out,
                $total_time,
                $run_time_std,
                $total_time_min,
                $id_cam,      // ใช้ค่าที่ส่งเข้ามา (ใหม่)
                $id_mc,       // ใช้ค่าที่ส่งเข้ามา (ใหม่)
                $total_qty_pulse2,
                $id_staff_db,
                $id_task_db,
                $status_new,
                $timestamp
            );
            $stmtLog->execute();
            $stmtLog->close();

            // ----------------------------------------------------------
            // (2) นำค่าปัจจุบันใน special_tray_data ไปบวกใน planning ก่อนรีเซ็ต
            // ----------------------------------------------------------
            // 2.1) ดึงค่าเก่าจาก special_tray_data
            $sqlQty = "SELECT qty_per_pulse2 
                       FROM special_tray_data 
                       WHERE tray_id = ? 
                       LIMIT 1";
            $stmtQty = $conn->prepare($sqlQty);
            $stmtQty->bind_param("s", $tray_id);
            $stmtQty->execute();
            $resultQty = $stmtQty->get_result();
            $oldQty = 0;
            if ($rowQty = $resultQty->fetch_assoc()) {
                $oldQty = (float)$rowQty['qty_per_pulse2'];
            }
            $stmtQty->close();

            // 2.2) หา id_task จาก machine + activity โดยใช้ existing_cam, existing_mc (ค่าเดิม)
            $sqlTask = "
                SELECT a.id_task
                FROM machine m
                JOIN activity a ON m.id_mc = a.id_machine
                WHERE m.id_cam = ?
                  AND m.id_mc = ?
                ORDER BY a.id DESC
                LIMIT 1
            ";
            $stmtTask = $conn->prepare($sqlTask);
            $stmtTask->bind_param("ss", $existing_cam, $existing_mc);
            $stmtTask->execute();
            $resultTask = $stmtTask->get_result();
            $old_id_task = 0;
            if ($rowTask = $resultTask->fetch_assoc()) {
                $old_id_task = $rowTask['id_task'];
            }
            $stmtTask->close();

            // 2.3) ถ้ามี id_task และ oldQty > 0 ให้บวกเข้ากับ planning.qty_order
            if ($old_id_task > 0 && $oldQty > 0) {
                $updatePlanning = "
                    UPDATE planning
                    SET qty_order = qty_order + ?
                    WHERE id_task = ?
                ";
                $stmtPlan = $conn->prepare($updatePlanning);
                $stmtPlan->bind_param("di", $oldQty, $old_id_task);
                $stmtPlan->execute();
                $stmtPlan->close();
            }

            // ----------------------------------------------------------
            // (3) รีเซ็ตค่าใน special_tray_data
            // ----------------------------------------------------------
            $reset_sql = "
                UPDATE special_tray_data 
                SET qty_per_pulse2 = 0, 
                    is_visible = 0,
                    is_display_camforeman = 0
                WHERE tray_id = ?
            ";
            $stmt_reset = $conn->prepare($reset_sql);
            $stmt_reset->bind_param("s", $tray_id);
            $stmt_reset->execute();
            $stmt_reset->close();
        }

        // อัปเดต qr_data ด้วยข้อมูลใหม่ (ไม่ว่าจะแก้ไขหรือไม่ก็ตาม)
        $update_sql = "
            UPDATE qr_data 
            SET id_cam = ?, id_mc = ?, timestamp = NOW(), description = ?
            WHERE qr_code_data = ?
        ";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("ssss", $id_cam, $id_mc, $description, $tray_id);
        $stmt_update->execute();
        $stmt_update->close();

    } else {
        // ถ้ายังไม่มี Tray นี้ใน qr_data => Insert ใหม่
        $insert_sql = "
            INSERT INTO qr_data (id_cam, id_mc, qr_code_data, description, timestamp) 
            VALUES (?, ?, ?, ?, NOW())
        ";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("ssss", $id_cam, $id_mc, $tray_id, $description);
        $stmt_insert->execute();
        $stmt_insert->close();
    }
}

// -------------------------------------------------------------
// ส่วนแสดงข้อมูลรวมของถาด (Tray) และคำนวณเวลารวม
// -------------------------------------------------------------
$sql = "
   SELECT 
    q.qr_code_data, 
    DATE(q.timestamp) as date, 
    MIN(q.timestamp) as time_in, 
    MAX(q.timestamp) as time_out, 
    TIMEDIFF(MAX(q.timestamp), MIN(q.timestamp)) as total_time, 
    q.id_cam, 
    q.id_mc, 
    a.id_task,
    a.id_staff,
    a.total_work,
    CASE
      WHEN s.qty_per_pulse2 IS NOT NULL 
           AND s.qty_per_pulse2 <> 0
      THEN s.qty_per_pulse2
      ELSE COALESCE(p.qty_per_pulse2, 0)
    END AS total_qty_per_pulse2,
       
    COALESCE(p.run_time_std, 0) as run_time_std,
    ROUND(
        (COALESCE(p.run_time_std, 0) * 
         CASE
             WHEN s.qty_per_pulse2 IS NOT NULL AND s.qty_per_pulse2 <> 0
             THEN s.qty_per_pulse2
             ELSE COALESCE(p.qty_per_pulse2, 0)
         END
        ) * 60, 2
    ) AS total_time_min,
    p.qty_order,       -- เพิ่ม qty_order
    p.qty_comp,        -- เพิ่ม qty_comp
    CASE 
      WHEN TIMEDIFF(MAX(q.timestamp), MIN(q.timestamp)) > 
           SEC_TO_TIME(
               ROUND(
                   (COALESCE(p.run_time_std, 0)
                    * CASE
                        WHEN s.qty_per_pulse2 IS NOT NULL AND s.qty_per_pulse2 <> 0
                        THEN s.qty_per_pulse2
                        ELSE COALESCE(p.qty_per_pulse2, 0)
                      END
                   ) * 60, 2
               ) * 60
           )
      THEN 'Over Time'
      ELSE ''
    END AS over_time_status

FROM qr_data q
LEFT JOIN machine m ON q.id_cam = m.id_cam
LEFT JOIN activity a ON m.id_mc = a.id_machine
LEFT JOIN planning p ON a.id_task = p.id_task  -- ดึง qty_order, qty_comp จาก planning
LEFT JOIN special_tray_data s ON q.qr_code_data = s.tray_id
GROUP BY q.qr_code_data
ORDER BY time_out DESC;
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        // -----------------------------
        //  แสดงผลในตาราง (HTML)
        // -----------------------------
        $total_time_display = htmlspecialchars($row['total_time']);
        if ($row['over_time_status'] == 'Over Time') {
            $total_time_display = "<span class='overtime'>" . $total_time_display . "</span>";
        }

        echo "<tr>";
        echo "<td class='text-center'>" . htmlspecialchars($row['qr_code_data']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['id_task']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['date']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['time_in']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['time_out']) . "</td>";
        echo "<td class='text-center overtime-cell'>" . $total_time_display . "</td>";
        echo "<td class='text-center'>" . round($row['run_time_std'] * 3600, 2) . " sec</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['total_time_min']) . " min</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['id_cam']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['id_mc']) . "</td>";

        echo "<td class='text-center'>" . htmlspecialchars($row['total_qty_per_pulse2']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['qty_order']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['qty_comp']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['id_staff']) . "</td>";
        echo "</tr>";

        // -------------------------------------------------------
        //  บันทึก Log ลงตาราง work_time_log เมื่อสถานะ Normal/Over Time เปลี่ยน
        // -------------------------------------------------------
        $currentStatus = ($row['over_time_status'] === 'Over Time') ? 'over_time' : 'normal';

        // เตรียมค่าต่างๆ สำหรับ work_time_log
        $tray_id_db        = $row['qr_code_data'];
        $date_field        = $row['date'];         // date (YYYY-mm-dd)
        $start_time        = $row['time_in'];      // datetime
        $end_time          = $row['time_out'];     // datetime
        $total_time        = $row['total_time'];   // time (HH:MM:SS)
        $run_time_std      = (float) $row['run_time_std'];
        $total_time_min    = (float) $row['total_time_min'];
        $id_cam_db         = $row['id_cam'];
        $id_mc_db          = $row['id_mc'];
        $total_qty_pulse2  = (float) $row['total_qty_per_pulse2'];
        $id_staff_db       = $row['id_staff'];
        $id_task_db        = $row['id_task'];

        // เช็กสถานะล่าสุดใน work_time_log สำหรับ tray_id นี้
        $sqlCheck = "
            SELECT status 
            FROM work_time_log
            WHERE tray_id = ?
            ORDER BY id DESC
            LIMIT 1
        ";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $tray_id_db);
        $stmtCheck->execute();
        $stmtCheck->store_result();
        $stmtCheck->bind_result($previousStatus);
        $stmtCheck->fetch();

        // ถ้ายังไม่เคย insert มาก่อน หรือมีการเปลี่ยนแปลงสถานะ => insert ลง log
        if ($stmtCheck->num_rows == 0 || $previousStatus !== $currentStatus) {
            $insertLogSql = "
                INSERT INTO work_time_log
                (
                    tray_id, date_field, start_time, end_time, total_time, 
                    run_time_std, total_time_min, id_cam, id_mc, total_qty_per_pulse2, 
                    id_staff, id_task, status, created_at
                )
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ";
            $stmtInsert = $conn->prepare($insertLogSql);
            $stmtInsert->bind_param(
                "sssssdsssssss",
                $tray_id_db,
                $date_field,
                $start_time,
                $end_time,
                $total_time,
                $run_time_std,
                $total_time_min,
                $id_cam_db,
                $id_mc_db,
                $total_qty_pulse2,
                $id_staff_db,
                $id_task_db,
                $currentStatus
            );
            $stmtInsert->execute();
            $stmtInsert->close();
        }
        $stmtCheck->close();
    }
} else {
    echo "<tr><td colspan='14' class='text-center'>No entries found</td></tr>";
}

$conn->close();
?>
