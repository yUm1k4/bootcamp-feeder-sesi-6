<?php
require 'vendor/autoload.php';

use App\Connection as Connection;
use App\mProdi;

try {
    // connect to the PostgreSQL database
    $pdo = Connection::get()->connect();
    $tProdi = new mProdi($pdo);
    $a_data = $tProdi->getAll();
    // echo '<pre>';
    // print_r($a_data);
    // echo '</pre>';
    // die();
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
    <div class="col-md-8 col-sm-12 container mt-3 mb-5">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0 text-gray-800">Pilih Tahun Ijazah & Program Studi</h1>
            <a href="./" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i>Kembali</a>
        </div>

        <div class="form-group">
            <form action="./reservasi_hasil.php" method="post">
                <label for="tahunIjazah">Pilih Tahun Ijazah</label>
                <input type="number" name="tahunIjazah" id="tahunIjazah" min="<?= date('Y') - 1 ?>" value="2022">
            </form>
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
                                <button type="submit" type="button" class="btn btn-primary btn-block btn-sm" onclick="goToProccess(<?= $data['kode_prodi'] ?>)">Pilih</button>
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

    // form action submit then redirect to page
    function goToProccess(kodeProdi) {
        // get tahunIjazah then post tahunIjazah using post method
        let tahunIjazah = document.getElementById('tahunIjazah').value;

        // post javascript native
        let form = document.createElement('form');
        form.method = 'post';
        form.action = './reservasi_hasil.php';
        let inputTahunIjazah = document.createElement('input');
        inputTahunIjazah.type = 'hidden';
        inputTahunIjazah.name = 'tahunIjazah';
        inputTahunIjazah.value = tahunIjazah;
        form.appendChild(inputTahunIjazah);
        let inputKodeProdi = document.createElement('input');
        inputKodeProdi.type = 'hidden';
        inputKodeProdi.name = 'kodeProdi';
        inputKodeProdi.value = kodeProdi;
        form.appendChild(inputKodeProdi);

        // submit form
        document.body.appendChild(form);
        form.submit();
    }
</script>
</body>
</html>