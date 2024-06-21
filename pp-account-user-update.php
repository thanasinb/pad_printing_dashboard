<?php
require 'update/establish.php';
session_start(); // เรียกใช้ session

// ตรวจสอบว่ามีการส่งข้อมูลแบบ POST มาหรือไม่ เพื่ออัปเดตข้อมูล
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idStaff = $_POST['id_staff'];
    $newUsername = $_POST['username'];
    $newPassword = $_POST['password'];

    // ตั้งค่าตัวแปรสำหรับเก็บข้อความในประวัติ
    $action = "แก้ไขข้อมูลของ: ";

    // ดึงข้อมูลปัจจุบันที่มี id_staff ที่ถูกส่งมา พร้อมชื่อจริงและนามสกุล
    $sqlGetUser = "SELECT login.username, login.password, staff.name_first, staff.name_last 
                   FROM login 
                   LEFT JOIN staff ON login.id_staff = staff.id_staff 
                   WHERE login.id_staff='$idStaff'";
    $resultGetUser = $conn->query($sqlGetUser);

    if ($resultGetUser->num_rows > 0) {
        // ดึงข้อมูลผู้ใช้
        $row = $resultGetUser->fetch_assoc();
        $currentUsername = $row['username'];
        $currentPassword = $row['password'];
        $nameFirst = $row['name_first'];
        $nameLast = $row['name_last'];
        $action .= "$nameFirst $nameLast";

        // อัปเดตข้อมูลผู้ใช้ในตาราง login
        $sql = "UPDATE login SET ";
        $fields = [];
        $details = [];

        // ตรวจสอบการเปลี่ยนแปลงของ username
        if (!empty($newUsername) && $newUsername !== $currentUsername) {
            $fields[] = "username='$newUsername'";
            $details[] = "แก้ไข username";
        }

        // ตรวจสอบการเปลี่ยนแปลงของ password
        if (!empty($newPassword) && $newPassword !== $currentPassword) {
            $fields[] = "password='$newPassword'";
            $details[] = "แก้ไข password";
        }

        if (!empty($fields)) { // มีการเปลี่ยนแปลงข้อมูลจริง ๆ
            $sql .= implode(", ", $fields);
            $sql .= " WHERE id_staff='$idStaff'";
            $result = $conn->query($sql);

            if ($result === TRUE) {
                // อัปเดตข้อมูลในเซสชันถ้าเป็นผู้ใช้คนเดียวกัน
                if ($_SESSION['id_staff'] == $idStaff) {
                    if (!empty($newUsername)) {
                        $_SESSION['username'] = $newUsername;
                    }
                    if (!empty($newPassword)) {
                        $_SESSION['password'] = $newPassword;
                    }

                    // อัปเดตข้อมูล Role Group และ Role ใหม่ในเซสชัน
                    $sqlRole = "SELECT role.role_group, role_group.role_group_name, role.role_name
                                FROM staff
                                INNER JOIN role ON staff.id_role = role.id_role
                                INNER JOIN role_group ON role.role_group = role_group.id_role_group
                                WHERE staff.id_staff='$idStaff'";
                    $resultRole = $conn->query($sqlRole);
                    if ($resultRole->num_rows > 0) {
                        $rowRole = $resultRole->fetch_assoc();
                        $_SESSION['role_group'] = $rowRole['role_group'];
                        $_SESSION['role_group_name'] = $rowRole['role_group_name'];
                        $_SESSION['role'] = $rowRole['role_name'];
                    }

                    // Generate a new secure session token
                    $session_token = bin2hex(random_bytes(32));
                    $_SESSION['session_token'] = $session_token;

                    // Set secure cookie with the new session token
                    setcookie("session_token", $session_token, time() + (30 * 24 * 60 * 60), "/", "", true, true);
                }

                // บันทึกลงในประวัติ
                $date_time = date("Y-m-d H:i:s");
                $usernameUpdater = $_SESSION['username']; // ดึง username ของผู้ใช้ที่ทำการแก้ไข

                // เพิ่มรายละเอียดการแก้ไข
                if (!empty($details)) {
                    $action .= " " . implode(" และ ", $details);
                }

                // SQL query เพื่อเพิ่มข้อมูลการแก้ไขลงในประวัติ
                $history_query = "INSERT INTO history (username, action, date_time) 
                                VALUES ('$usernameUpdater', '$action', '$date_time')";
                $history_result = $conn->query($history_query);

                if ($history_result === TRUE) {
                    echo json_encode(array("statusCode" => 200, "message" => "User updated successfully."), JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(array("statusCode" => 500, "message" => "Error updating user: " . $conn->error . ". Failed to update history."), JSON_UNESCAPED_UNICODE);
                }
            } else {
                echo json_encode(array("statusCode" => 500, "message" => "Error updating user: " . $conn->error), JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(array("statusCode" => 400, "message" => "No changes detected."), JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode(array("statusCode" => 400, "message" => "User not found."), JSON_UNESCAPED_UNICODE);
    }
}

require 'update/terminate.php';
?>
