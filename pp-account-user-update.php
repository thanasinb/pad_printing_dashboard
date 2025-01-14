<?php
require 'update/establish.php';
session_start(); // เรียกใช้ session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idStaff = $_POST['id_staff'];
    $newUsername = trim($_POST['username']);
    $newPassword = trim($_POST['password']);

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
        $action = "แก้ไขข้อมูลของ: $nameFirst $nameLast";

        $fields = [];
        $details = [];

        if (!empty($newUsername) && $newUsername !== $currentUsername) {
            $fields[] = "username=?";
            $details[] = "แก้ไข username";
        }

        if (!empty($newPassword)) {
            if (!password_verify($newPassword, $currentPassword)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $fields[] = "password=?";
                $details[] = "แก้ไข password";
            }
        }

        if (!empty($fields)) {
            $sqlUpdate = "UPDATE login SET " . implode(", ", $fields) . " WHERE id_staff=?";
            $stmtUpdate = $conn->prepare($sqlUpdate);

            $params = [];
            foreach ($fields as $field) {
                if ($field === "username=?") $params[] = $newUsername;
                if ($field === "password=?") $params[] = $hashedPassword;
            }
            $params[] = $idStaff;
            $stmtUpdate->bind_param(str_repeat("s", count($params)), ...$params);

            if ($stmtUpdate->execute()) {
                $action .= " " . implode(" และ ", $details);
                $stmtHistory = $conn->prepare("INSERT INTO history (username, action, date_time) VALUES (?, ?, CURRENT_TIMESTAMP)");
                $usernameUpdater = $_SESSION['username'];
                $stmtHistory->bind_param("ss", $usernameUpdater, $action);
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