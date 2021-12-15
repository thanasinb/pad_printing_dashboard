<?php
require 'update/establish.php';
$sql = "SELECT * FROM job WHERE id_job=" . $_GET["id_job"];
$result = $conn->query($sql);
require 'update/terminate.php';
$label_open = "<label class=\"list-group-item list-group-item-action small\"><table><tr><td width='120px'>";
$label_close = "</td></tr></table></label>";
$cell_open = "<td>";
$cell_close = "</td>";
$row = $result->fetch_assoc();
echo "<div class=\"col-lg-3\"><div class=\"card mb-4\">";
echo "<div class=\"card-header\">Route card (Job ID: " . $_GET["id_job"] . ")</div>";
echo "<div class=\"list-group list-group-flush small\">";
echo $label_open . "Job ID: " . $cell_close;
echo $cell_open . $row["id_job"] . $label_close;
echo $label_open . "Work Order: " . $cell_close;
echo $cell_open . $row["work_order"] . $label_close;
echo $label_open . "Item Number: " . $cell_close;
echo $cell_open . $row["item_no"] . $label_close;
echo $label_open . "Item Description: " . $cell_close;
echo $cell_open . $row["item_des"] . $label_close;
echo "</div></div></div>";

echo "<div class=\"col-lg-3\">";
//echo "<div class=\"card bg-primary border-0 mb-4\">";
//echo "<div class=\"card-body\">";
//echo "<h5 class=\"text-white-50\">Budget Overview</h5>";
//echo "<div class=\"mb-4\">";
//echo "<span class=\"display-4 text-white\">$48k</span>";
//echo "<span class=\"text-white-50\">per year</span>";
//echo "</div>";
//echo "<div class=\"progress bg-white-25 rounded-pill\" ";
//echo "style=\"height: 0.5rem\"><div class=\"progress-bar bg-white w-75 rounded-pill\" ";
//echo "role=\"progressbar\" aria-valuenow=\"75\" aria-valuemin=\"0\" aria-valuemax=\"100\">";
//echo "</div></div></div></div>";

echo "<div class=\"card mb-4\">";
echo "<div class=\"card-header\">Qty</div>";
echo "<div class=\"list-group list-group-flush small\">";
echo $label_open . "Qty Ordered" . $cell_close;
echo $cell_open . number_format($row["qty_order"]) . $label_close;
echo $label_open . "Qty Completed" . $cell_close;
echo $cell_open . number_format($row["qty_completed"]) . $label_close;
echo $label_open . "Qty Rejected" . $cell_close;
echo $cell_open . number_format($row["qty_rejected"]) . $label_close;
echo "</div></div></div>";

echo "<div class=\"col-lg-3\"><div class=\"card mb-4\">";
echo "<div class=\"card-header\">Date</div>";
echo "<div class=\"list-group list-group-flush small\">";
echo $label_open . "Order Date" . $cell_close;
echo $cell_open . $row["date_order_job"] . $label_close;
echo $label_open . "Release Date" . $cell_close;
echo $cell_open . $row["date_release_job"] . $label_close;
echo $label_open . "Due Date" . $cell_close;
echo $cell_open . $row["date_due_job"] . $label_close;
echo "</div></div></div>";

echo "<div class=\"col-lg-3\"><div class=\"card mb-4\">";
echo "<div class=\"card-header\">Misc.</div>";
echo "<div class=\"list-group list-group-flush small\">";
echo $label_open . "Sales/Job: " . $cell_close;
echo $cell_open . $row["sales_job"] . $label_close;
echo $label_open . "Production Line: " . $cell_close;
echo $cell_open . $row["prod_line"] . $label_close;
echo $label_open . "Production Rate: " . $cell_close;
echo $cell_open . $row["prod_rate"] . $label_close;
echo "</div></div></div>";

?>