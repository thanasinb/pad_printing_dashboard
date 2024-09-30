<?php
session_start();
require 'update/establish.php'; // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // ดึงข้อมูลภาพโปรไฟล์จากฐานข้อมูล
    $sql = "SELECT profile_image FROM staff WHERE id_staff = (SELECT id_staff FROM login WHERE username = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // ตรวจสอบว่ามีภาพโปรไฟล์หรือไม่
    if ($row && !empty($row['profile_image'])) {
        $profileImagePath = "uploads/" . $row['profile_image'];
    } else {
        // หากไม่มีภาพโปรไฟล์ ให้แสดงภาพเริ่มต้น
        $profileImagePath = "assets/img/illustrations/profiles/profile-1.png";
    }
} else {
    // หากผู้ใช้ไม่ได้ล็อกอิน ให้กลับไปที่หน้าเข้าสู่ระบบ
    header("Location: login.php");
    exit();
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
    <title>Account</title>
    <link href="css/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="js/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="js/feather-icons/4.28.0/feather.min.js"></script>
    <link rel="stylesheet" href="css/majorette.css">
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/majorette/pp-setting-dt.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>

    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet" />
    <!-- Cropper.js JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

</head>
<body class="nav-fixed">
<?php require 'pp-setting-sidenavAccordion.php'; ?>
<div id="layoutSidenav">
    <?php require 'pp-layoutSidenav_nav.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
                <div class="container-xl px-4">
                    <div class="page-header-content">
                        <div class="row align-items-center justify-content-between pt-3">
                            <div class="col-auto mb-3">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="user"></i></div>
                                    Account Settings - Profile
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main page content-->
            <div class="container-xl px-4 mt-4">
                <!-- Account page navigation-->
                <nav class="nav nav-borders">
                    <a class="nav-link active ms-0" href="/projects/mjrqr/pp-account.php">Profile</a>
                    <a class="nav-link" href="/projects/mjrqr/master/account-billing.html">Billing</a>
                    <a class="nav-link" href="/projects/mjrqr/master/account-security.html">Security</a>
                    <a class="nav-link" href="/projects/mjrqr/master/account-notifications.html">Notifications</a>
                </nav>
                <hr class="mt-0 mb-4" />
                <div class="container-xl px-4 mt-4">
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">Profile Picture</div>
                        <div class="card-body text-center">
                            <!-- Profile picture image -->
                            <img id="previewImage" class="img-account-profile mb-2" src="/projects/mjrqr/uploads/<?php echo $profile_image; ?>" alt="" />

                            <!-- Profile picture help block -->
                            <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>

                            <!-- Profile picture upload button -->
                            <form id="uploadImageForm" method="post" enctype="multipart/form-data">
                                <div class="preview-container">
                                    <img id="preview" class="img-account-profile" src="<?php echo $profileImagePath; ?>" alt="Profile Image" />
                                </div>
                                <div class="button-container">
                                    <label for="imageFile" class="btn-custom">
                                        Upload new image
                                        <input type="file" id="imageFile" name="imageFile" style="display: none;" accept="image/png, image/jpeg" onchange="startCrop()">
                                    </label>
                                </div>

                                    <!-- พื้นที่แสดงการครอบภาพ -->
                                    <div id="cropContainer" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.8); z-index: 1000; justify-content: center; align-items: center;">
                                        <div style="max-width: 90%; max-height: 90%; display: flex; justify-content: center; align-items: center;">
                                            <img id="imageToCrop" style="max-width: 100%; max-height: 100%;" />
                                        </div>
                                        <div class="button-container" style="position: fixed; bottom: 20px; width: 100%; display: flex; justify-content: center;">
                                            <button type="button" class="btn-custom" id="cropButton" style="margin: 10px;">Crop Image</button>
                                            <button type="button" class="btn-custom" id="cancelCropButton" style="margin: 10px;" onclick="cancelCrop()">Cancel</button>
                                        </div>
                                    </div>

                                    <!-- ปุ่มสำหรับอัปโหลด -->
                                    <div class="button-container">
                                        <input type="submit" value="Upload Image" class="btn-custom" id="uploadButton" style="display: none;">
                                    </div>
                                </form>
                                <style>
                                    .preview-container {
                                        display: flex;
                                        justify-content: center; /* จัดให้อยู่ตรงกลางแนวนอน */
                                        align-items: center;     /* จัดให้อยู่ตรงกลางแนวตั้ง */
                                        margin-bottom: 20px;     /* ระยะห่างจากปุ่ม */
                                        min-height: 150px;       /* ความสูงขั้นต่ำสำหรับพื้นที่แสดงภาพ */
                                        position: relative;
                                    }

                                    .button-container {
                                        display: flex;
                                        justify-content: center; /* จัดปุ่มให้อยู่ตรงกลาง */
                                        gap: 10px; /* ระยะห่างระหว่างปุ่ม */
                                    }

                                    .btn-custom {
                                        background-color: #4CAF50; /* สีพื้นหลัง */
                                        color: white; /* สีตัวอักษร */
                                        padding: 10px 20px; /* ระยะห่างภายใน */
                                        text-align: center; /* จัดกึ่งกลางข้อความ */
                                        text-decoration: none; /* ไม่ต้องมีเส้นใต้ */
                                        display: inline-block; /* จัดเป็นบล็อกอินไลน์ */
                                        font-size: 16px; /* ขนาดตัวอักษร */
                                        font-weight: bold; /* ตัวหนา */
                                        border-radius: 10px; /* มุมโค้งมน */
                                        border: none; /* ไม่มีขอบ */
                                        cursor: pointer; /* รูปเคอร์เซอร์เป็นมือตอนชี้ */
                                        transition: background-color 0.3s, transform 0.3s; /* การเปลี่ยนแปลงเมื่อ Hover */
                                        width: 150px; /* กำหนดความกว้างเท่ากัน */
                                    }

                                    .btn-custom:hover {
                                        background-color: #45a049; /* สีพื้นหลังเมื่อ hover */
                                        transform: scale(1.05); /* ขยายเล็กน้อยเมื่อ hover */
                                    }

                                    /* กำหนดขนาดปุ่มสำหรับหน้าจอเล็ก */
                                    @media (max-width: 768px) {
                                        .btn-custom {
                                            width: 100%; /* ปรับขนาดปุ่มให้เต็มความกว้างในหน้าจอเล็ก */
                                        }
                                    }

                                    /* ทำให้ภาพเป็นวงกลม */
                                    #preview {
                                        width: 150px;          /* กำหนดความกว้าง */
                                        height: 150px;         /* กำหนดความสูง */
                                        border-radius: 50%;    /* ทำให้เป็นวงกลม */
                                        object-fit: cover;     /* ปรับภาพให้เต็มพื้นที่ */
                                        margin-bottom: 20px;
                                    }
                                    .cropper-crop-box {
                                        border-radius: 50% !important; /* ทำให้ crop box เป็นวงกลม */
                                        border: 2px solid white !important; /* เพิ่มขอบขาวรอบๆ */
                                    }

                                </style>


                            </div>
                        </div>

                    </div>

        </main>
        <footer class="footer-admin mt-auto footer-light">
            <div class="container-xl px-4">
                <div class="row">
                    <div class="col-md-6 small">Copyright &copy; Your Website 2021</div>
                    <div class="col-md-6 text-md-end small">
                        <a href="#!">Privacy Policy</a>
                        &middot;
                        <a href="#!">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

<script>
    function previewNewImage() {
        const file = document.getElementById('imageFile').files[0];
        const reader = new FileReader();

        reader.onload = function (e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('uploadButton').style.display = 'inline-block'; // แสดงปุ่ม upload
            document.getElementById('cancelButton').style.display = 'inline-block'; // แสดงปุ่ม cancel
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    $('#uploadImageForm').on('submit', function(e) {
        e.preventDefault(); // ป้องกันการส่ง form แบบปกติ
        var formData = new FormData(this);

        $.ajax({
            url: 'upload.php',
            type: 'POST',
            data: formData,
            success: function(data) {
                if (data.startsWith("ERROR")) {
                    alert(data);
                } else {
                    // อัปเดตภาพใหม่ในหน้าโดยไม่ต้องรีเฟรช
                    $('#preview').attr('src', data); // อัปเดตเส้นทางของภาพใหม่
                    $('#previewImage').attr('src', data); // อัปเดตในส่วนอื่นหากจำเป็น
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    function cancelImage() {
        // รีเซ็ตภาพกลับไปที่ภาพเดิม
        document.getElementById('preview').src = "<?php echo $profileImagePath; ?>";
        document.getElementById('uploadButton').style.display = 'none'; // ซ่อนปุ่ม upload
        document.getElementById('cancelButton').style.display = 'none'; // ซ่อนปุ่ม cancel
        document.getElementById('imageFile').value = ''; // ล้างค่าไฟล์ที่ถูกเลือก
    }
    let cropper;

    function startCrop() {
        const imageFile = document.getElementById('imageFile').files[0];
        const reader = new FileReader();

        reader.onload = function (event) {
            const image = document.getElementById('imageToCrop');
            image.src = event.target.result;
            document.getElementById('cropContainer').style.display = 'flex'; // แสดงพื้นที่ครอบภาพ

            if (cropper) {
                cropper.destroy();  // ทำลาย cropper เก่าถ้ามี
            }

            cropper = new Cropper(image, {
                aspectRatio: 1, // กำหนดสัดส่วนเป็น 1:1
                viewMode: 1,
                background: false,
                zoomable: true,
                movable: true,
                scalable: true,
                ready: function () {
                    // ทำให้ crop box เป็นวงกลมโดยใช้ CSS
                    const cropBox = document.querySelector('.cropper-crop-box');
                    cropBox.style.borderRadius = '50%'; // เพิ่มการทำให้เป็นวงกลม
                    cropBox.style.border = '2px solid white'; // เพิ่มขอบสีขาว
                }
            });
        };

        if (imageFile) {
            reader.readAsDataURL(imageFile);
        }
    }

    document.getElementById('cropButton').addEventListener('click', function () {
        // ครอบภาพและแสดงใน preview
        const croppedCanvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
        });

        // สร้างภาพเป็นวงกลมใน canvas
        const circleCanvas = document.createElement('canvas');
        circleCanvas.width = 300;
        circleCanvas.height = 300;
        const ctx = circleCanvas.getContext('2d');

        // วาดภาพครอบเป็นวงกลม
        ctx.beginPath();
        ctx.arc(150, 150, 150, 0, Math.PI * 2); // วาดวงกลมกลาง
        ctx.closePath();
        ctx.clip(); // ตัดภาพให้เป็นวงกลม

        ctx.drawImage(croppedCanvas, 0, 0, 300, 300);

        // แปลง canvas เป็น Data URL สำหรับแสดงผลและอัปโหลด
        const croppedImageDataUrl = circleCanvas.toDataURL('image/png');

        // แสดงภาพครอบใน preview ด้านบน
        document.getElementById('preview').src = croppedImageDataUrl;
        document.getElementById('cropContainer').style.display = 'none'; // ซ่อนพื้นที่ครอบ
        document.getElementById('uploadButton').style.display = 'block'; // แสดงปุ่มอัปโหลด

        // ส่งภาพที่ครอบไปยังเซิร์ฟเวอร์เมื่อกดปุ่มอัปโหลด
        document.getElementById('uploadImageForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append('croppedImage', croppedImageDataUrl);

            $.ajax({
                url: 'upload.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.startsWith("ERROR")) {
                        alert(response);
                    } else {
                        document.getElementById('preview').src = response; // เปลี่ยนภาพโปรไฟล์เป็นภาพใหม่
                    }
                },
                error: function () {
                    alert('Error uploading image');
                }
            });
        });
    });

    function cancelCrop() {
        document.getElementById('cropContainer').style.display = 'none'; // ซ่อนพื้นที่ครอบ
        document.getElementById('imageFile').value = ''; // ล้างค่าไฟล์ที่ถูกเลือก
    }
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="/projects/mjrqr/js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
<script src="/projects/mjrqr/assets/demo/chart-area-demo.js"></script>
<script src="/projects/mjrqr/assets/demo/chart-pie-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script src="/projects/mjrqr/js/datatables/datatables-simple-demo.js"></script>
<script type="text/javascript" src="js/majorette/pp-session.js"></script>



</body>
</html>
