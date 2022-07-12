<?php
require 'vendor/autoload.php';

use App\Connection as Connection;
use App\mProdi;

session_start();
if (!empty($_SESSION['KODEPRODI'])) {
    $kodeProdi = $_SESSION['KODEPRODI'];
    $stepAksi = $_SESSION['STEPAKSI'];
    //     echo '<pre>';
    // print_r($stepAksi);
    // echo '</pre>';
    // die();
} else {
    header('Location: step_one.php');
}

try {
    // connect to the PostgreSQL database
    $pdo = Connection::get()->connect();
    $tProdi = new mProdi($pdo);
    $a_prodi = $tProdi->getByKodeProdi($kodeProdi);

    $a_mhs = $tProdi->getMahasiswaReservasi($kodeProdi, $stepAksi);
} catch (\PDOException $e) {
    echo $e->getMessage();
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
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />

    <title>PIN (Penomoran Ijzah Nasional)</title>
</head>
<body>

<div class="container-fluid">
    <div class="col-md-10 col-sm-12 container mt-3 mb-5">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h2 class="h5 mb-0 text-gray-800">Periksa Daftar Lulusan <?= $a_prodi['prodi'] ?></h2>
            <a href="./step_one.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i>Kembali</a>
        </div>

        <div style="margin-top: 50px;">
            <p>DAFTAR MAHASISWA ELIGIBLE</p>
            <hr>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="datatablesSimple">
                    <thead>
                        <th width="4%">No</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>SKS</th>
                        <th>IPK</th>
                        <th>Alasan</th>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($a_mhs['eligible'] as $key => $data) { ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data['nama'] ?></td>
                                <td><?= $data['nim'] ?></td>
                                <td><?= $data['total_sks'] ?></td>
                                <td><?= $data['ipk'] ?></td>
                                <td>OK</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top: 50px;">
            <p>DAFTAR MAHASISWA TIDAK ELIGIBLE</p>
            <hr>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="datatablesSimple2">
                    <thead>
                        <th width="4%">No</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>SKS</th>
                        <th>IPK</th>
                        <th>Alasan</th>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($a_mhs['tidak_eligible'] as $key => $data) { ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data['nama'] ?></td>
                                <td><?= $data['nim'] ?></td>
                                <td><?= $data['total_sks'] ?></td>
                                <td><?= $data['ipk'] ?></td>
                                <td><?= implode(', ', $data['alasan']) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>

<script>
    window.addEventListener('DOMContentLoaded', event => {
        const datatablesSimple = document.getElementById('datatablesSimple');
        const datatablesSimple2 = document.getElementById('datatablesSimple2');
        if (datatablesSimple || datatablesSimple2) {
            new simpleDatatables.DataTable(datatablesSimple);
            new simpleDatatables.DataTable(datatablesSimple2);
        }
    });
</script>
</body>
</html>