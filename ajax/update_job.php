<?php
require '../update/establish.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(["statusCode" => 403, "message" => "Unauthorized access."], JSON_UNESCAPED_UNICODE);
    exit();
}

$username = $_SESSION['username'];

// ✅ ตรวจสอบค่าที่ส่งมา
if (isset($_POST['id_job'], $_POST['operation'], $_POST['machine'], $_POST['work_order'], $_POST['item_no'], $_POST['op_color'], $_POST['op_side'], $_POST['date_due'])) {
    $id_job = $conn->real_escape_string($_POST['id_job']);
    $operation = $conn->real_escape_string($_POST['operation']);
    $machine = $conn->real_escape_string($_POST['machine']);
    $work_order = $conn->real_escape_string($_POST['work_order']);
    $item_no = $conn->real_escape_string($_POST['item_no']);
    $op_color = $conn->real_escape_string($_POST['op_color']);
    $op_side = $conn->real_escape_string($_POST['op_side']);
    $date_due = $conn->real_escape_string($_POST['date_due']);

    // 🔹 ดึงข้อมูลก่อนอัปเดต
    $sql_old = "SELECT operation, machine, work_order, item_no, op_color, op_side, date_due FROM planning WHERE id_job = ?";
    $stmt_old = $conn->prepare($sql_old);
    $stmt_old->bind_param("s", $id_job);
    $stmt_old->execute();
    $result_old = $stmt_old->get_result();
    $old_data = $result_old->fetch_assoc();
    $stmt_old->close();

    if (!$old_data) {
        echo json_encode(["statusCode" => 404, "message" => "❌ Job ID not found."], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // 🔹 ตรวจสอบค่าที่เปลี่ยนแปลงและสร้าง SQL UPDATE
    $changes = [];
    $update_fields = [];
    $update_values = [];
    $update_types = "";

    if ($old_data['operation'] !== $operation) {
        $changes[] = "แก้ไข Operation: {$old_data['operation']} → {$operation}";
        $update_fields[] = "operation = ?";
        $update_types .= "s";
        $update_values[] = $operation;
    }
    if ($old_data['machine'] !== $machine) {
        $changes[] = "แก้ไข Machine: {$old_data['machine']} → {$machine}";
        $update_fields[] = "machine = ?";
        $update_types .= "s";
        $update_values[] = $machine;
    }
    if ($old_data['work_order'] !== $work_order) {
        $changes[] = "แก้ไข Work Order: {$old_data['work_order']} → {$work_order}";
        $update_fields[] = "work_order = ?";
        $update_types .= "s";
        $update_values[] = $work_order;
    }
    if ($old_data['item_no'] !== $item_no) {
        $changes[] = "แก้ไข Item No: {$old_data['item_no']} → {$item_no}";
        $update_fields[] = "item_no = ?";
        $update_types .= "s";
        $update_values[] = $item_no;
    }
    if ($old_data['op_color'] !== $op_color) {
        $changes[] = "แก้ไข Color: {$old_data['op_color']} → {$op_color}";
        $update_fields[] = "op_color = ?";
        $update_types .= "s";
        $update_values[] = $op_color;
    }
    if ($old_data['op_side'] !== $op_side) {
        $changes[] = "แก้ไข Side: {$old_data['op_side']} → {$op_side}";
        $update_fields[] = "op_side = ?";
        $update_types .= "s";
        $update_values[] = $op_side;
    }
    if ($old_data['date_due'] !== $date_due) {
        $changes[] = "แก้ไข Due Date: {$old_data['date_due']} → {$date_due}";
        $update_fields[] = "date_due = ?";
        $update_types .= "s";
        $update_values[] = $date_due;
    }

    // ✅ ถ้าไม่มีการเปลี่ยนแปลง ให้หยุดการทำงาน
    if (empty($changes)) {
        echo json_encode(["statusCode" => 204, "message" => "✅ No changes made."], JSON_UNESCAPED_UNICODE);
        exit();
    }

    // 🔹 อัปเดตเฉพาะค่าที่เปลี่ยนแปลง
    $sql_update = "UPDATE planning SET " . implode(", ", $update_fields) . " WHERE id_job = ?";
    $update_types .= "s";
    $update_values[] = $id_job;

    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param($update_types, ...$update_values);

    if ($stmt_update->execute()) {
        // 🔹 เพิ่มข้อมูลใน `history`
        $changeDetails = json_encode($changes, JSON_UNESCAPED_UNICODE);
        $action = "แก้ไขข้อมูล Job ID: $id_job ";

        $sql_log = "INSERT INTO history (username, action, details, date_time) VALUES (?, ?, ?, NOW())";
        $stmt_log = $conn->prepare($sql_log);
        $stmt_log->bind_param("sss", $username, $action, $changeDetails);
        $stmt_log->execute();
        $stmt_log->close();


        echo json_encode(["statusCode" => 200, "message" => "✅ Job updated successfully."], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["statusCode" => 500, "message" => "❌ Error updating job."], JSON_UNESCAPED_UNICODE);
    }
    $stmt_update->close();
} else {
    echo json_encode(["statusCode" => 400, "message" => "❌ Invalid request. Missing parameters."], JSON_UNESCAPED_UNICODE);
}

require '../update/terminate.php';
?>