<?php

namespace App;

class mPesertaDidik {

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

    public function all() {
        $schemaTable = $this->getSchemaTable();
        $primaryKeys = $this->getPrimaryKeys();
        $columns = $this->getColumns();
        $dataToBeSend = $this->getDataToBeSent();

        $a_data = [];
        foreach ($schemaTable as $key => $value)
            $a_data[$key] = $value;
        $a_data['primary_keys'] = $primaryKeys;
        $a_data['struktur'] = $columns;
        $a_data['rows'] = $dataToBeSend;

        // echo '<pre>';
        // print_r($a_data);
        // echo '</pre>';
        // die();

        return $a_data;
    }

    public function getSchemaTable()
    {
        $sql = "select * from information_schema.columns where table_schema='public' and table_name='peserta_didik'";

        $stmt = $this->pdo->query($sql);
        $a_data = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $a_data['schema'] = $row['table_schema'];
            $a_data['table'] = $row['table_name'];
        }

        return $a_data;
    }

    public function getPrimaryKeys()
    {
        $sql = "SELECT a.attname, format_type(a.atttypid, a.atttypmod) AS data_type
            FROM pg_index i
            JOIN pg_attribute a ON a.attrelid = i.indrelid AND a.attnum = ANY(i.indkey)
            WHERE i.indrelid = 'peserta_didik'::regclass AND i.indisprimary;";

        $stmt = $this->pdo->query($sql);
        $a_data = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $a_data[] = $row['attname'];
        }

        return $a_data;
    }

    public function getColumns()
    {
        $sql = "select * from information_schema.columns where table_schema='public' and table_name='peserta_didik'";

        $stmt = $this->pdo->query($sql);
        $a_data = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $a_data[] = $row['column_name'];
        }

        return $a_data;
    }

    public function getDataToBeSent()
    {
        $sql = "select * from public.peserta_didik where last_update > last_sync";

        $stmt = $this->pdo->query($sql);
        $a_data = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $a_data[] = array_values($row);
        }

        return $a_data;
    }
}