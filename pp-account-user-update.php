<?php
require 'update/establish.php';
session_start(); // ใช้ session เพื่อตรวจสอบ user ที่แก้ไข

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idStaff = $_POST['id_staff'];
    $newUsername = trim($_POST['username']);
    $newPassword = trim($_POST['password']);

    // ดึงข้อมูลปัจจุบัน
    $sqlGetUser = "SELECT login.username, login.password, staff.name_first, staff.name_last 
                   FROM login 
                   LEFT JOIN staff ON login.id_staff = staff.id_staff 
                   WHERE login.id_staff=?";
    $stmtGetUser = $conn->prepare($sqlGetUser);
    $stmtGetUser->bind_param("s", $idStaff);
    $stmtGetUser->execute();
    $resultGetUser = $stmtGetUser->get_result();

    if ($resultGetUser->num_rows > 0) {
        $row = $resultGetUser->fetch_assoc();
        $currentUsername = $row['username'];
        $currentPassword = $row['password'];
        $nameFirst = $row['name_first'];
        $nameLast = $row['name_last'];

        $action = "แก้ไขบัญชีของ: $nameFirst $nameLast";
        $changes = [];

        // ถ้า `username` เปลี่ยน ให้เก็บรายละเอียด
        if (!empty($newUsername) && $newUsername !== $currentUsername) {
            $changes[] = "แก้ไข Username: $currentUsername → $newUsername";
        }

        // ถ้า `password` เปลี่ยน ให้บันทึกเป็น `แก้ไข Password`
        if (!empty($newPassword)) {
            if (empty($currentPassword) || !password_verify($newPassword, $currentPassword)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $changes[] = "แก้ไข Password";
            }
        }

        // ถ้ามีการเปลี่ยนแปลง
        if (!empty($changes)) {
            $fields = [];
            $params = [];
            $types = "";

            if (!empty($newUsername) && $newUsername !== $currentUsername) {
                $fields[] = "username=?";
                $params[] = $newUsername;
                $types .= "s";
            }

            if (!empty($newPassword)) {
                if (empty($currentPassword) || !password_verify($newPassword, $currentPassword)) {
                    $fields[] = "password=?";
                    $params[] = $hashedPassword;
                    $types .= "s";
                }
            }

            $params[] = $idStaff;
            $types .= "s";

            $sqlUpdate = "UPDATE login SET " . implode(", ", $fields) . " WHERE id_staff=?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param($types, ...$params);
            $final_action = $action ;


            if ($stmtUpdate->execute()) {
                // บันทึก `history` เป็น JSON
                $historyDetails = json_encode($changes, JSON_UNESCAPED_UNICODE);
                $stmtHistory = $conn->prepare("INSERT INTO history (username, action, details, date_time) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
                $usernameUpdater = $_SESSION['username'];
                $stmtHistory->bind_param("sss", $usernameUpdater, $final_action, $historyDetails);
                $stmtHistory->execute();

                echo json_encode(["statusCode" => 200, "message" => "User updated successfully."]);
            } else {
                echo json_encode(["statusCode" => 500, "message" => "Error updating user: " . $stmtUpdate->error]);
            }
        } else {
            echo json_encode(["statusCode" => 400, "message" => "No changes detected."]);
        }
    } else {
        echo json_encode(["statusCode" => 400, "message" => "User not found."]);
    }
}

$conn->close();
?>