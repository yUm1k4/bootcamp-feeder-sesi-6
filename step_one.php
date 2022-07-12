<?php
require 'vendor/autoload.php';

use App\Connection as Connection;
use App\mProdi;

try {
    session_start();

    $pdo = Connection::get()->connect();
    $tProdi = new mProdi($pdo);
    $a_data = $tProdi->getAll();
} catch (\PDOException $e) {
    echo $e->getMessage();
}

// jika ada action submit
if (!empty($_POST['act']) && $_POST['act'] == 'submit') {
    session_start();
    $_SESSION['KODEPRODI'] = $_POST['key'];

    header('Location: step_two.php');
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

    <title><?= $_SESSION['STEPAKSI'] == mProdi::RESERVASI ? 'Reservasi' : 'Pemasangan' ?> - PIN (Penomoran Ijzah Nasional)</title>
</head>
<body>

<div class="container-fluid">
    <div class="col-md-8 col-sm-12 container mt-3 mb-5">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0 text-gray-800">Pilih Salah Satu Program Studi</h1>
            <a href="./" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i>Kembali</a>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-hover" id="datatablesSimple">
                <thead>
                    <th width="7%">No</th>
                    <th width="10%">Kode</th>
                    <th>Nama</th>
                    <th width="12%">Operasi</th>
                </thead>
                <tbody>
                    <?php foreach ($a_data as $key => $data) { ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $data['kode_prodi'] ?></td>
                            <td><?= $data['prodi'] ?></td>
                            <td>
                                <form method="post">
                                    <button type="submit" type="button" class="btn btn-primary btn-block btn-sm">Pilih</button>
                                    <input type="hidden" name="act" value="submit">
                                    <input type="hidden" name="key" value="<?= $data['kode_prodi'] ?>">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
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
        if (datatablesSimple) {
            new simpleDatatables.DataTable(datatablesSimple);
        }
    });
</script>
</body>
</html>