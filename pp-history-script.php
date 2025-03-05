<?php
require 'update/establish.php';

// รับค่าจากฟอร์ม (หลาย Action => OR)
$filterActions = [];
if (isset($_GET['filter_action'])) {
    if (is_array($_GET['filter_action'])) {
        foreach ($_GET['filter_action'] as $fa) {
            $fa = trim($fa);
            if ($fa !== "") {
                $filterActions[] = $fa;
            }
        }
    } else {
        $fa = trim($_GET['filter_action']);
        if ($fa !== "") {
            $filterActions[] = $fa;
        }
    }
}

// รับ Start/End Date
$startDate = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$endDate   = isset($_GET['end_date'])   ? trim($_GET['end_date'])   : '';

// สร้างเงื่อนไข SQL
$conditions = [];
$params = [];
$types = '';

// (OR) Action
if (!empty($filterActions)) {
    $actionClauses = [];
    foreach ($filterActions as $action) {
        $actionClauses[] = "history.action LIKE ?";
        $params[] = '%'.$action.'%';
        $types   .= 's';
    }
    $conditions[] = '(' . implode(' OR ', $actionClauses) . ')';
}

// (AND) Date
if (!empty($startDate) && !empty($endDate)) {
    $conditions[] = "history.date_time BETWEEN ? AND ?";
    $params[] = $startDate . ' 00:00:00';
    $params[] = $endDate   . ' 23:59:59';
    $types   .= 'ss';
}

// สร้าง SQL Query
$history_query = "
    SELECT 
        history.id_history, 
        COALESCE(prefix.prefix, 'N/A') AS prefix, 
        COALESCE(staff.name_first, 'Unknown') AS name_first, 
        COALESCE(staff.name_last, 'Unknown') AS name_last, 
        history.action, 
        history.details, 
        history.date_time
    FROM history
    LEFT JOIN login ON login.username = history.username
    LEFT JOIN staff ON login.id_staff = staff.id_staff
    LEFT JOIN prefix ON staff.prefix = prefix.id_prefix
";

if (!empty($conditions)) {
    $history_query .= " WHERE " . implode(" AND ", $conditions);
}

$history_query .= " ORDER BY history.date_time DESC";

$stmt = $conn->prepare($history_query);
if (!empty($conditions)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// จากนั้นใน pp-history.php ก็จะทำการ loop $result ออกมาเป็นตาราง


// ฟังก์ชันสำหรับกำหนด Icon
function getActionIcon($action) {
    if ($action == 'Logout (Log out yourself)') {
        return '<i class="me-2 text-red" data-feather="log-out"></i>';
    } elseif ($action == 'Logout (session expired)') {
        return '<i class="me-2 text-gray" data-feather="log-out"></i>';
    } elseif ($action == 'Login') {
        return '<i class="me-2 text-green" data-feather="log-in"></i>';
    } elseif (strpos($action, 'Download QR Code:') !== false) {
        return '<i class="me-2 text-green" data-feather="download"></i>';
    } elseif (strpos($action, 'แก้ไขบัญชีของ:') !== false) {
        return '<i class="me-2 text-blue" data-feather="edit"></i>';
    } elseif (strpos($action, 'บันทึก QR Code:') !== false) {
        return '<i class="me-2 text-green" data-feather="save"></i>';
    } elseif (strpos($action, 'แก้ไข Description Thai:') !== false) {
        return '<i class="me-2 text-blue" data-feather="edit"></i>';
    } elseif (strpos($action, 'แก้ไข Description Eng:') !== false) {
        return '<i class="me-2 text-blue" data-feather="edit"></i>';
    }elseif (strpos($action, 'แก้ไขข้อมูล Machine ID:') !== false) {
        return '<i class="me-2 text-blue" data-feather="edit"></i>';
    }elseif (strpos($action, 'สร้าง Machine ID:') !== false) {
        return '<i class="me-2 text-green" data-feather="plus-circle"></i>';
    }elseif (strpos($action, 'เพิ่ม job ID') !== false) {
        return '<i class="me-2 text-green" data-feather="plus-circle"></i>';
    }elseif (strpos($action, 'เอา job ID') !== false) {
        return '<i class="me-2 text-red" data-feather="minus-circle"></i>';
    }elseif (strpos($action, 'แก้ไข job ID') !== false) {
        return '<i class="me-2 text-blue" data-feather="edit"></i>';
    }elseif (strpos($action, 'สร้างพนักงาน: ') !== false) {
        return '<i class="me-2 text-green" data-feather="user-plus"></i>';
    }elseif (strpos($action, 'แก้ไขข้อมูลพนักงาน: ') !== false) {
        return '<i class="me-2 text-blue" data-feather="edit"></i>';
    }elseif (strpos($action, 'ลบพนักงาน:') !== false) {
        return '<i class="me-2 text-red" data-feather="minus-circle"></i>';
    }elseif (strpos($action, 'แก้ไข Downtime Box Code:') !== false) {
        return '<i class="me-2 text-blue" data-feather="edit"></i>';
    } elseif (strpos($action, 'สร้าง job ด้วย Job ID:') !== false) {
        return '<i class="me-2 text-green" data-feather="plus-circle"></i>';
    }elseif (strpos($action, 'แก้ไขข้อมูล Job ID:') !== false) {
        return '<i class="me-2 text-blue" data-feather="edit"></i>';
    }elseif (strpos($action, 'ลบ Machine ที่มี ID') !== false) {
        return '<i class="me-2 text-red" data-feather="minus-circle"></i>';
    }  elseif (strpos($action, 'เพิ่ม Downtime Code:') !== false) {
        return '<i class="me-2 text-green" data-feather="plus-circle"></i>';
    } elseif (strpos($action, 'ลบ downtime:') !== false) {
        return '<i class="me-2 text-red" data-feather="minus-circle"></i>';
    }  elseif (strpos($action, 'ลบงาน: Work Order') !== false) {
        return '<i class="me-2 text-red" data-feather="minus-circle"></i>';
    }elseif (strpos($action, 'ลบบัญชีผู้ใช้:') !== false) {
        return '<i class="me-2 text-red" data-feather="minus-circle"></i>';
    } elseif (strpos($action, 'เพิ่มผู้ใช้:') !== false) {
        return '<i class="me-2 text-green" data-feather="plus-circle"></i>';
    }

    return '';
}

// แสดงผลข้อมูล
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $icon = getActionIcon($row['action']);
        $changeDetails = json_decode($row['details'], true) ?? [];

        // 🔹 ตรวจสอบว่าการกระทำเกี่ยวข้องกับ QR Code หรือไม่
        if (strpos($row['action'], 'บันทึก QR Code:') !== false) {
            // ดึง id_qr จาก action
            preg_match('/บันทึก QR Code: (.+)/', $row['action'], $matches);
            $id_qr = isset($matches[1]) ? trim($matches[1]) : '';

            // ดึง Path ของ QR Code จากฐานข้อมูล
            $sql_qr = "SELECT qr_code_image_path FROM qrcodes WHERE id_qr = ?";
            $stmt_qr = $conn->prepare($sql_qr);
            $stmt_qr->bind_param("s", $id_qr);
            $stmt_qr->execute();
            $result_qr = $stmt_qr->get_result();
            $qr_data = $result_qr->fetch_assoc();

            // ถ้ามีภาพ QR Code ให้เพิ่มลงใน details
            if ($qr_data) {
                $changeDetails['QR Code Image'] = $qr_data['qr_code_image_path'];
            }
        }

        // แปลงกลับเป็น JSON
        $encodedDetails = htmlspecialchars(json_encode($changeDetails, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');

        echo "<tr class='text-black fw-bold row_staff' 
                data-bs-toggle='modal' 
                data-bs-target='#historyModal' 
                data-name='" . htmlspecialchars($row['name_first'] . ' ' . $row['name_last'], ENT_QUOTES, 'UTF-8') . "'
                data-action='" . htmlspecialchars($row['action'], ENT_QUOTES, 'UTF-8') . "'
                data-details='{$encodedDetails}'>";

        echo "<td class='text-center'>{$row['id_history']}</td>";
        echo "<td class='text-center'>{$row['prefix']}</td>";
        echo "<td class='text-center'>{$row['name_first']}</td>";
        echo "<td class='text-center'>{$row['name_last']}</td>";
        echo "<td class='text-center'>{$icon} {$row['action']}</td>";
        echo "<td class='text-center'>{$row['date_time']}</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center text-danger'>ไม่มีข้อมูล</td></tr>";
}

$stmt->close();
$conn->close();
?>