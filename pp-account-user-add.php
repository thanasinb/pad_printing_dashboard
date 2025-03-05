<?php
require 'update/establish.php'; // เชื่อมต่อฐานข้อมูล
session_start(); // ใช้เพื่อดึง username ผู้ทำการเพิ่มผู้ใช้

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_staff = trim($_POST['new_id_staff']);
    $username = trim($_POST['new_username']);
    $password = trim($_POST['new_password']);

    // ✅ ตรวจสอบว่าข้อมูลไม่ว่าง
    if (!empty($id_staff) && !empty($username) && !empty($password)) {
        // ✅ ตรวจสอบว่า id_staff ซ้ำหรือไม่
        $stmt_check = $conn->prepare("SELECT COUNT(*) FROM login WHERE id_staff = ?");
        $stmt_check->bind_param("s", $id_staff);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($count > 0) {
            // ❌ แจ้งว่า ID ซ้ำ
            echo json_encode(["statusCode" => 409, "message" => "มี Staff ID นี้ในระบบอยู่แล้ว"]);
            exit;
        }

        // ✅ เข้ารหัสรหัสผ่าน
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // ✅ เพิ่มข้อมูลในตาราง login
        $stmt = $conn->prepare("INSERT INTO login (id_staff, username, password) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $id_staff, $username, $hashed_password);
            if ($stmt->execute()) {
                // ✅ ดึงข้อมูลชื่อและนามสกุลจากตาราง staff
                $stmt_staff = $conn->prepare("SELECT name_first, name_last FROM staff WHERE id_staff = ?");
                $stmt_staff->bind_param("s", $id_staff);
                $stmt_staff->execute();
                $result = $stmt_staff->get_result();
                $row = $result->fetch_assoc();
                $fullName = $row ? $row['name_first'] . " " . $row['name_last'] : "ไม่ทราบชื่อ";

                // ✅ บันทึกลง history พร้อมรายละเอียด
                $adminUsername = $_SESSION['username'] ?? 'system'; // ผู้ทำการเพิ่ม
//                $action = "เพิ่มผู้ใช้: " . $username . " ของพนักงาน " . $fullName ;
                $action = "เพิ่มผู้ใช้: " . $username . " ของพนักงาน " . $fullName ;


                // 🔹 รายละเอียดข้อมูลที่เพิ่มเข้าไป
                $userDetails = json_encode([
                    "Staff ID: " . $id_staff,
                    "Username: " . $username,
                    "Full Name: " . $fullName
                ], JSON_UNESCAPED_UNICODE);

                // 🔹 Insert ลง `history`
                $stmt_history = $conn->prepare("INSERT INTO history (username, action, details, date_time) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
                $stmt_history->bind_param("sss", $adminUsername, $action, $userDetails);
                $stmt_history->execute();
                $stmt_history->close();

                // ✅ ส่งสถานะสำเร็จ
                echo json_encode(["statusCode" => 200, "message" => "User added successfully."]);
            } else {
                // ❌ เกิดข้อผิดพลาด
                echo json_encode(["statusCode" => 500, "message" => "Failed to add user: " . $stmt->error]);
            }
            $stmt->close();
        } else {
            // ❌ เกิดข้อผิดพลาดในคำสั่ง SQL
            echo json_encode(["statusCode" => 500, "message" => "Prepare failed: " . $conn->error]);
        }
    } else {
        // ❌ ข้อมูลไม่ครบ
        echo json_encode(["statusCode" => 400, "message" => "All fields are required."]);
    }

    $conn->close();
} else {
    // ❌ ไม่ใช่คำขอแบบ POST
    echo json_encode(["statusCode" => 405, "message" => "Invalid request method."]);
}
?>