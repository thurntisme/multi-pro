<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $commonController->getSiteName() ?> <?= !empty($pageTitle) ? " - " . $pageTitle : ""  ?></title>

    <!-- Custom fonts for this template-->
    <link href="<?= home_url("assets/fontawesome-free/css/all.min.css") ?>" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= home_url("css/sb-admin-2.min.css") ?>" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <?php echo $pageContent ?? "Page Content" ?>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= home_url("assets/jquery/jquery.min.js") ?>"></script>
    <script src="<?= home_url("assets/bootstrap/js/bootstrap.bundle.min.js") ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= home_url("assets/jquery-easing/jquery.easing.min.js") ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= home_url("js/sb-admin-2.min.js") ?>"></script>

    <script src="<?= home_url("js/main.js") ?>"></script>

    <?php
    if (!empty($additionJs)) {
        echo $additionJs;
    }
    ?>

</body>

</html>