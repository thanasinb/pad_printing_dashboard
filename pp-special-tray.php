<?php
require 'pp-session-start.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $qty_per_pulse2 = floatval($_POST['qty_per_pulse2']);

    $sql = "UPDATE special_tray_data SET qty_per_pulse2 = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $qty_per_pulse2, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "id" => $id]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }

    $stmt->close();
    $conn->close();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Job overview by Tray </title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/machine-learning.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <link rel="stylesheet" href="css/reorder-columns/dragtable.css">
    <link rel="stylesheet" href="css/reorder-columns/bootstrap-table.min.css">
    <link rel="stylesheet" href="css/majorette.css">
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- css style -->
    <link href="css/pp-account-user.css" rel="stylesheet" />
    <style>
        .overtime {
            color: red;
            font-weight: bold;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            50% { opacity: 0; }
        }
    </style>
</head>
<body class="nav-fixed">
<?php require 'pp-setting-sidenavAccordion.php'; ?>
<div id="layoutSidenav">
    <?php require 'pp-layoutSidenav_nav.php'; ?>

    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-dark pb-5">
                <div class="container-xl px-4">
                    <div class="page-header-content pt-4">
                    </div>
                </div>
            </header>
            <div class="container-fluid px-4 mt-n10">
                <!-- Example DataTable for Dashboard Demo-->
                <div class="card mb-4 w-100" id="table-machine">
                    <div class="card-header bg-red fw-bold text-white fs-4 d-flex justify-content-between">
                        <div>Tray List</div>
                    </div>



                    <div class="card-body">
                        <table id="datatablesSimple" class="table table-striped" style="width: 100%; white-space: nowrap">
                            <thead class="text-black" style="background-color: #ffea07">
                            <?php
                            // เรียกใช้ไฟล์ pp-setting-qr-table-head.php เพื่อแสดงหัวตาราง
                            require_once 'pp-special-tray-table-head.php';
                            ?>
                            </thead>
                            <tbody id="table_body">

                            <?php
                            require 'pp-special-script-tray.php';
                            ?>
                            </tbody>

                        </table>
                        <div class="card mb-4 w-100 " id="table-camforeman">
                            <div class="card-header bg-primary fw-bold  bg-red text-white fs-4 d-flex justify-content-between">
                                <div>CamForeman</div>
                            </div>
                            <div class="card-body">
                                <table id="datatableCamForeman" class="table table-striped" style="width: 100%; white-space: nowrap">
                                    <thead class="text-black" style="background-color: #17a2b8">
                                    <tr>
                                        <th class='text-center'>ID</th>
                                        <th class='text-center'>Tray ID</th>
                                        <th class='text-center'>Camera ID</th>
                                        <th class='text-center'>Qty per Pulse</th>
                                        <th class='text-center'>Timestamp</th>
                                        <th class='text-center'>Edit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php require 'pp-special-tray-camforeman.php'; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function () {
                                $('#datatableCamForeman').DataTable({
                                    "paging": true,
                                    "searching": true,
                                    "ordering": true,
                                    "info": true,
                                    "autoWidth": false
                                });
                            });
                        </script>
                        <!-- Modal สำหรับแก้ไขค่า qty_per_pulse2 -->
                        <div class="modal fade" id="editQtyModal" tabindex="-1" aria-labelledby="editQtyLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editQtyLabel">Edit Qty per Pulse</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editQtyForm">
                                            <input type="hidden" id="edit_id" name="id">
                                            <div class="mb-3">
                                                <label for="edit_qty" class="form-label">Qty per Pulse</label>
                                                <input type="number" class="form-control" id="edit_qty" name="qty_per_pulse2" step="1">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            $(document).ready(function () {
                                // เมื่อกดปุ่ม Edit
                                $(".edit_qty").click(function () {
                                    let id = $(this).data("id");
                                    let qty = $(this).data("qty");

                                    $("#edit_id").val(id);
                                    $("#edit_qty").val(qty);
                                });

                                // เมื่อกดบันทึก
                                $("#editQtyForm").submit(function (e) {
                                    e.preventDefault();

                                    let id = $("#edit_id").val();
                                    let qty_per_pulse2 = $("#edit_qty").val();

                                    $.ajax({
                                        url: "pp-update-qty.php",
                                        type: "POST",
                                        data: { id: id, qty_per_pulse2: qty_per_pulse2 },
                                        dataType: "json",
                                        success: function (response) {
                                            if (response.status === "success") {
                                                // ลบแถวออกจากตาราง (DOM) ทันที
                                                $("#row_" + response.id).fadeOut(500, function () {
                                                    $(this).remove();
                                                });
                                                $("#editQtyModal").modal("hide");
                                            } else {
                                                alert("Error: " + response.message);
                                            }
                                        },
                                        error: function () {
                                            alert("Failed to update data.");
                                        }
                                    });
                                });
                            });
                        </script>
                        <script>
                            function refreshTable() {
                                $.ajax({
                                    url: "pp-special-script-tray.php", // ดึงข้อมูลใหม่
                                    type: "GET",
                                    success: function(data) {
                                        $("#table_body").html(data); // อัปเดตเฉพาะตาราง
                                    }
                                });
                            }

                            // รีเฟรชตารางทุก 5 วินาที
                            setInterval(refreshTable, 5000);
                        </script>







                        <script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
                        <script src="js/scripts.js"></script>
                        <script src="js/simple-datatables@latest" type="text/javascript"></script>
                        <script src="js/datatables/datatables-staff.js"></script>
                        <script src="js/litepicker/dist/bundle.js"></script>
                        <script src="js/litepicker.js"></script>
                        <!--                        <script  src="js/majorette/pp-time-stamp.js"></script>-->
                        <script type="text/javascript" src="js/majorette/pp-session.js"></script>

</body>
</html>