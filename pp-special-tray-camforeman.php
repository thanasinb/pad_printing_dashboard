<?php
require 'update/establish.php';

$sql = "
    SELECT * 
    FROM special_tray_data 
    WHERE id_cam = 'CamForeman' 
      AND is_display_camforeman = 1 
    ORDER BY timestamp DESC
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr id='row_" . htmlspecialchars($row['id']) . "'>";
        echo "<td class='text-center'>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['tray_id']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['id_cam']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['qty_per_pulse2']) . "</td>";
        echo "<td class='text-center'>" . htmlspecialchars($row['timestamp']) . "</td>";
        echo "<td class='text-center'>
                <button type='button' class='btn btn-warning btn-sm edit_qty' 
                        data-id='" . htmlspecialchars($row['id']) . "' 
                        data-qty='" . htmlspecialchars($row['qty_per_pulse2']) . "' 
                        data-bs-toggle='modal' 
                        data-bs-target='#editQtyModal'>
                    Edit
                </button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>No entries found</td></tr>";
}

$conn->close();
?>