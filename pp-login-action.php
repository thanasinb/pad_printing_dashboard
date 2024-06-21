<?php
session_start();

// เชื่อมต่อกับไฟล์เชื่อมต่อฐานข้อมูล
require 'update/establish.php';

// ตรวจสอบว่ามีการส่งข้อมูล username และ password มาหรือไม่
if (isset($_POST['username']) && isset($_POST['password'])) {
    // รับค่า username และ password จากฟอร์ม
    $username = $_POST['username'];
    $password = $_POST['password'];

    // คำสั่ง SQL สำหรับเลือกข้อมูลผู้ใช้จากฐานข้อมูล
    $sql = "SELECT login.*, role.*, login.password AS hashed_password
            FROM login
            INNER JOIN staff ON login.id_staff = staff.id_staff
            INNER JOIN role ON staff.id_role = role.id_role
            WHERE login.username = ? AND (role.role_group = 2 OR role.role_group = 3)";

    // เตรียมและดำเนินการ SQL statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // ตรวจสอบว่ามีข้อมูลผู้ใช้ในฐานข้อมูลหรือไม่
        if ($result->num_rows > 0) {
            // รับข้อมูลผู้ใช้
            $row = $result->fetch_assoc();
            $hashed_password = $row['hashed_password'];

            // ตรวจสอบรหัสผ่าน
            if (password_verify($password, $hashed_password)) {
                // รหัสผ่านถูกต้อง
                // เก็บข้อมูล username ใน Session
                $_SESSION['username'] = $username;

                // เพิ่มรายการประวัติการเข้าสู่ระบบลงในฐานข้อมูล history
                $login_user = $_SESSION['username'];
                $history_sql = "INSERT INTO history (username, action, date_time)
                                VALUES (?, 'Login', NOW())";
                if ($history_stmt = $conn->prepare($history_sql)) {
                    $history_stmt->bind_param("s", $login_user);
                    $history_stmt->execute();
                    $history_stmt->close();
                } else {
                    echo "History insert prepare failed: " . $conn->error;
                }

                // ส่งผู้ใช้ไปยังหน้า pp-machine-3.php
                header("Location: pp-machine-3.php");
                exit();
            } else {
                // รหัสผ่านไม่ถูกต้อง
                header("Location: pp-login.php?error=password_incorrect");
                exit();
            }
        } else {
            // ไม่พบข้อมูลผู้ใช้
            header("Location: pp-login.php?error=user_not_found");
            exit();
        }
        $stmt->close();
    } else {
        echo "Prepare failed: " . $conn->error;
    }
} else {
    echo "Username and password are required.";
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
require 'update/terminate.php';
?>
