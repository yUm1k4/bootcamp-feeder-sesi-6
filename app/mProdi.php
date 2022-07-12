<?php

namespace App;

class mProdi {

    const IDUNIV = '8e5d195a-0035-41aa-afef-db715a37b8da';
    const RESERVASI = 'R';
    const PEMASANGAN = 'P';

    /**
     * PDO object
     * @var \PDO
     */
    private $pdo;

    /**
     * init the object with a \PDO object
     * @param type $pdo
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "select sms.kode_prodi, jp.nm_jenj_didik||' '||sms.nm_lemb as prodi
            from public.sms sms
            join ref.jenis_sms jsms using(id_jns_sms)
            join public.satuan_pendidikan sp using (id_sp)
            join ref.jenjang_pendidikan jp using(id_jenj_didik)
            where 1=1
                and sp.id_sp = '" . self::IDUNIV . "'
                and jsms.id_jns_sms = '3'";

        $stmt = $this->pdo->query($sql);
        $a_data = [];
        // fetch all stmt
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $a_data[] = $row;
        }

        return $a_data;
    }

    public function getByKodeProdi($kodeProdi)
    {
        $sql = "select sms.kode_prodi, jp.nm_jenj_didik||' '||sms.nm_lemb as prodi
            from public.sms sms
            join ref.jenis_sms jsms using(id_jns_sms)
            join public.satuan_pendidikan sp using (id_sp)
            join ref.jenjang_pendidikan jp using(id_jenj_didik)
            where 1=1
                and sp.id_sp = '" . self::IDUNIV . "'
                and jsms.id_jns_sms = '3'
                and sms.kode_prodi = '" . $kodeProdi . "'";

        //         echo '<pre>';
        // print_r($sql);
        // echo '</pre>';
        // die();
        $stmt = $this->pdo->query($sql);
        $a_data = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $a_data;
    }

    public function getMahasiswa($kodeProdi, $status)
    {
        if (empty($kodeProdi) or empty($status))
            return false;

        $sql = "select rpd.nipd, pd.nm_pd, kmhs.id_smt, kmhs.ips, kmhs.sks_smt, kmhs.ipk, kmhs.sks_total, jp.nm_jenj_didik, sem.smt
            from public.reg_pd rpd
            join public.peserta_didik pd using (id_pd)
            join public.sms sms using(id_sms)
            join public.satuan_pendidikan sp on sp.id_sp = rpd.id_sp and sp.id_sp = sms.id_sp
            join ref.jenjang_pendidikan jp using(id_jenj_didik)
            left join public.kuliah_mhs kmhs on kmhs.id_reg_pd = rpd.id_reg_pd
            left join ref.semester sem on sem.id_smt = kmhs.id_smt
            where 1=1
                and sp.id_sp = '" . self::IDUNIV . "'
                and sms.kode_prodi = '" . $kodeProdi . "'
            order by rpd.nipd, kmhs.id_smt asc";

        $stmt = $this->pdo->query($sql);
        $a_data = [];

        // fetch all stmt
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $nim = trim($row['nipd']);
            $a_data[$nim]['nim'] = $nim;
            $a_data[$nim]['nama'] = $row['nm_pd'];
            $a_data[$nim]['total_sks'] = $row['sks_total'];
            $a_data[$nim]['ipk'] = $row['ipk'];
            $a_data[$nim]['jenjang'] = $row['nm_jenj_didik'];
            $a_data[$nim]['sks_smt'] = $row['sks_smt'];
            $a_data[$nim]['semesterke'] = $row['smt'];

            // ? Cek jumlah sks semester
            // jika nm_jenj_didik D1 atau D2 ata D3 atau D4 atau S1
            if (in_array($row['nm_jenj_didik'], ['D1', 'D2', 'D3', 'D4', 'S1'])) {
                if ($row['sks_smt'] >= '25') {
                    $a_data[$nim]['tidak_eligible'] = 1;
                    $a_data[$nim]['alasan'][1] = 'Jumlah SKS semester melebihi ketentuan';
                }
            }

            // ? Cek jumlah sks semester pendek
            if ($row['smt'] == '3' && $row['sks_smt'] >= '9') {
                $a_data[$nim]['alasan'][2] = 'Jumlah SKS semester pendek melebihi ketentuan';
            }
        }

        foreach ($a_data as $key => $value) {
            // ? Cek minimal IPK
            if ((in_array($value['jenjang'], ['D1', 'D2', 'D3', 'D4', 'S1']))) { // diploma / sarjana
                if ($value['ipk'] < '2.00') {
                    $a_data[$key]['tidak_eligible'] = 1;
                    $a_data[$key]['alasan'][3] = 'IPK kurang dari 3.00';
                }
            } else if (in_array($value['jenjang'], ['S2', 'S3'])) {
                if ($value['ipk'] < '3.00') {
                    $a_data[$key]['tidak_eligible'] = 1;
                    $a_data[$key]['alasan'][3] = 'IPK kurang dari 3.00';
                }
            }

            // ? Cek total sks yang ditempuh
            if ($status == self::RESERVASI) {
                if ((($value['jenjang'] == 'D1' || $value['jenjang'] == 'S2') && $value['total_sks'] < '12') ||
                    (($value['jenjang'] == 'D2') && $value['total_sks'] < '48') ||
                    (($value['jenjang'] == 'D3') && $value['total_sks'] < '84') ||
                    (($value['jenjang'] == 'D4' || $value['jenjang'] == 'S1') && $value['total_sks'] < '120') ||
                    (($value['jenjang'] == 'S3') && $value['total_sks'] < '18')) {
                        $a_data[$key]['tidak_eligible'] = 1;
                        $a_data[$key]['alasan'][4] = 'Total SKS yang ditempuh kurang dari syarat SKS';
                }
            } else if ($status == self::PEMASANGAN) {
                if ((($value['jenjang'] == 'D1' || $value['jenjang'] == 'S2') && $value['total_sks'] < '36') ||
                    (($value['jenjang'] == 'D2') && $value['total_sks'] < '72') ||
                    (($value['jenjang'] == 'D3') && $value['total_sks'] < '108') ||
                    (($value['jenjang'] == 'D4' || $value['jenjang'] == 'S1') && $value['total_sks'] < '144') ||
                    (($value['jenjang'] == 'S3') && $value['total_sks'] < '42')) {
                        $a_data[$key]['tidak_eligible'] = 1;
                        $a_data[$key]['alasan'][4] = 'Total SKS yang ditempuh kurang dari syarat SKS';
                }
            }
        }

        // group by eligible and tidak_eligible
        $a_eligible = [];
        $a_tidak_eligible = [];
        foreach ($a_data as $key => $value) {
            if (!empty($value['tidak_eligible'])) {
                $a_tidak_eligible[] = $value;
            } else {
                $a_eligible[] = $value;
            }
        }
        // echo '<pre>';
        // print_r($a_tidak_eligible);
        // echo '</pre>';
        // die();

        return [
            'eligible' => $a_eligible,
            'tidak_eligible' => $a_tidak_eligible
        ];
    }
}