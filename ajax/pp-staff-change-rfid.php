<?php
require '../update/establish.php';
require '../update/lib_get_staff_by_rfid.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(array("statusCode" => 403, "message" => "Unauthorized access."));
    exit();
}

$username = $_SESSION['username'];

if (isset($_GET['id_staff'], $_GET['id_rfid'], $_GET['name_first'], $_GET['name_last'], $_GET['prefix'], $_GET['id_role'], $_GET['id_shif'], $_GET['site'])) {
    $id_staff = $conn->real_escape_string($_GET['id_staff']);
    $id_rfid = $conn->real_escape_string($_GET['id_rfid']);
    $name_first = $conn->real_escape_string($_GET['name_first']);
    $name_last = $conn->real_escape_string($_GET['name_last']);
    $prefix = $conn->real_escape_string($_GET['prefix']);
    $id_role = $conn->real_escape_string($_GET['id_role']);
    $id_shif = $conn->real_escape_string($_GET['id_shif']);
    $site = $conn->real_escape_string($_GET['site']);

    // ดึงข้อมูลเก่าก่อนอัปเดต
    $sql_old = "SELECT id_rfid, name_first, name_last, prefix, id_role, id_shif, site FROM staff WHERE id_staff=?";
    $stmt_old = $conn->prepare($sql_old);
    $stmt_old->bind_param("s", $id_staff);
    $stmt_old->execute();
    $result_old = $stmt_old->get_result();
    $old_data = $result_old->fetch_assoc();
    $stmt_old->close();

    $data_staff_rfid = get_staff_by_rfid($conn, $id_rfid);

    if (empty($data_staff_rfid) || $data_staff_rfid['id_staff'] == $id_staff) {
        $sql = "UPDATE staff SET 
                    id_rfid=?, 
                    name_first=?, 
                    name_last=?, 
                    prefix=?, 
                    id_role=?, 
                    id_shif=?, 
                    site=? 
                WHERE id_staff=?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss",
            $id_rfid,
            $name_first,
            $name_last,
            $prefix,
            $id_role,
            $id_shif,
            $site,
            $id_staff
        );

        if ($stmt->execute()) {
            $status_code = 200;

            // ตรวจสอบการเปลี่ยนแปลงและบันทึกลงประวัติ
            $prefixText = ($prefix == 1) ? "นาย" : (($prefix == 2) ? "นาง" : "นางสาว");
            $action = "แก้ไขข้อมูลพนักงาน: $prefixText $name_first $name_last";
            $changes = [];

            if ($old_data['id_rfid'] != $id_rfid) {
                $changes[] = "แก้ไข RFID: {$old_data['id_rfid']} → $id_rfid";
            }
            if ($old_data['name_first'] != $name_first) {
                $changes[] = "แก้ไข First name: {$old_data['name_first']} → $name_first";
            }
            if ($old_data['name_last'] != $name_last) {
                $changes[] = "แก้ไข Last name: {$old_data['name_last']} → $name_last";
            }
            if ($old_data['prefix'] != $prefix) {
                $old_prefix = ($old_data['prefix'] == 1) ? "นาย" : (($old_data['prefix'] == 2) ? "นาง" : "นางสาว");
                $changes[] = "แก้ไข Prefix: $old_prefix → $prefixText";
            }
            // ดึงข้อมูล Role เก่าก่อนอัปเดต
            $sql_role_old = "SELECT role FROM role WHERE id_role = ?";
            $stmt_role_old = $conn->prepare($sql_role_old);
            $stmt_role_old->bind_param("s", $old_data['id_role']);
            $stmt_role_old->execute();
            $result_role_old = $stmt_role_old->get_result();
            $old_role = $result_role_old->fetch_assoc()['role'] ?? $old_data['id_role']; // ถ้าไม่มีชื่อ role ให้ใช้ id_role
            $stmt_role_old->close();

// ดึงข้อมูล Role ใหม่
            $sql_role_new = "SELECT role FROM role WHERE id_role = ?";
            $stmt_role_new = $conn->prepare($sql_role_new);
            $stmt_role_new->bind_param("s", $id_role);
            $stmt_role_new->execute();
            $result_role_new = $stmt_role_new->get_result();
            $new_role = $result_role_new->fetch_assoc()['role'] ?? $id_role; // ถ้าไม่มีชื่อ role ให้ใช้ id_role
            $stmt_role_new->close();

// บันทึกการเปลี่ยนแปลง Role
            if ($old_data['id_role'] != $id_role) {
                $changes[] = "แก้ไข Role: $old_role → $new_role";
            }
            if ($old_data['id_shif'] != $id_shif) {
                $changes[] = "แก้ไข Shift: {$old_data['id_shif']} → $id_shif";
            }
            if ($old_data['site'] != $site) {
                $changes[] = "แก้ไข Site: {$old_data['site']} → $site";
            }

            if (!empty($changes)) {
                $changeDetails = json_encode($changes, JSON_UNESCAPED_UNICODE);
                $final_action = $action ;

                // ใช้ฟิลด์ 'details' ที่มีอยู่เพื่อเก็บ JSON
                $sql_log = "INSERT INTO history (username, action, details, date_time) VALUES (?, ?, ?, NOW())";
                $stmt_log = $conn->prepare($sql_log);
                $stmt_log->bind_param("sss", $username, $final_action, $changeDetails);
                $stmt_log->execute();
                $stmt_log->close();
            }

        } else {
            $status_code = 500; // Error code for update failure
        }
        $stmt->close();
    } else {
        $status_code = 30; // RFID already exists
    }
} else {
    $status_code = 400; // Bad request, missing parameters
}

require '../update/terminate.php';

echo json_encode(array("statusCode" => $status_code), JSON_UNESCAPED_UNICODE);
?>