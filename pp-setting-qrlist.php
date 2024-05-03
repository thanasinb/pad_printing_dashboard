<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Add QR CODE</title>
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
    <script type="text/javascript" src="js/majorette/pp-machine-refresh-3.js"></script>
    <script type="text/javascript" src="js/majorette/pp-machine-clock.js"></script>


    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        
            background-size: cover;
            background-repeat: no-repeat;
        }

        #qrcodeContainer {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-top: 40px;
            padding: 40px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 80%;
            max-width: 400px;
        }

        h1 {
            color: #333;
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
        }

        #downloadBtn{

            font-size: 14px;
            cursor: pointer;
            border: none;
            margin-top: 10px;
            transition: background-color 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            white-space: nowrap;
        }
        #printBtn{
            font-size: 14px;
            cursor: pointer;
            border: none;
            margin-top: 10px;
            transition: background-color 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            white-space: nowrap;
        }

        button:hover {
            background-color: #0070c9;
            color: #fff;
        }

        #qrcode {
            margin-top: 20px;
        }

        #qrValue {
            margin-top: 20px;
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body class="nav-fixed">
<?php require 'pp-setting-sidenavAccordion.php'; ?>
<div id="layoutSidenav">
    <?php require 'pp-layoutSidenav_nav.php'; ?>
    <div id="layoutSidenav_content">
    </div>

    <main>


    </main>
    <!-- Include QR Code library -->
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/simple-datatables@latest" type="text/javascript"></script>
    <script src="js/datatables/datatables-simple-demo.js"></script>
    <script src="js/litepicker/dist/bundle.js"></script>
    <script src="js/litepicker.js"></script>
</body>
</html>
