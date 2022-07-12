<?php
require 'vendor/autoload.php';

use App\Connection as Connection;
use App\mProdi;

try {
    // connect to the PostgreSQL database
    $pdo = Connection::get()->connect();
} catch (\PDOException $e) {
    echo $e->getMessage();
}

// jika ada action submit
if (!empty($_POST['act']) && $_POST['act'] == 'submit') {
    session_start();
    $_SESSION['STEPAKSI'] = $_POST['key'];

    header('Location: step_one.php');
}

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>PIN (Penomoran Ijzah Nasional)</title>
</head>
<body>

<div class="container-fluid">
    <div class="col-md-8 col-sm-12 container mt-3 mb-5">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Sistem Penomoran Ijazah Nasional</h1>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <form method="post">
                    <button type="submit" class="jumbotron btn btn-lg btn-block btn-primary" style="border-color: #1ab394; background-color: #1ab394;font-size:130%">
                        <i class="fa fa-plus"></i> <strong> Reservasi Nomor Ijazah </strong>
                    </button>
                    <input type="hidden" name="act" value="submit">
                    <input type="hidden" name="key" value="<?= mProdi::RESERVASI ?>">
                </form>
            </div>
            <div class="col-lg-6">
                <form method="post">
                    <button type="submit" class="jumbotron btn btn-lg btn-block btn-info" style="border-color: #1ab394; background-color: #23c6c8;font-size:130%">
                        <i class="fa fa-pencil"></i> <strong> Nomor Ijazah</strong>
                    </button>
                    <input type="hidden" name="act" value="submit">
                    <input type="hidden" name="key" value="<?= mProdi::PEMASANGAN ?>">
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>