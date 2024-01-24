<?php
class Antrian
{
    // Connection
    private $conn;
    // Table
    private $db_table = "antrian";
    // Columns
    public $id;
    public $waktudatang;
    public $selisihkedatangan;
    public $awalpelayanan;
    public $selisihpelayanankasir;
    public $selesai;
    public $selisihkeluarantrian;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function getAntrians()
    {
        $sqlQuery = "SELECT id, waktudatang, selisihkedatangan, awalpelayanan, selisihpelayanankasir, selesai, selisihkeluarantrian FROM " . $this->db_table;
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function createAntrian()
    {
        $sqlQuery = "INSERT INTO " . $this->db_table . "
            SET
            waktudatang = :waktudatang,
            selisihkedatangan = :selisihkedatangan,
            awalpelayanan = :awalpelayanan,
            selisihpelayanankasir = :selisihpelayanankasir,
            selesai = :selesai,
            selisihkeluarantrian = :selisihkeluarantrian";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        // (Tetapkan sanitize sesuai kebutuhan)

        // bind data
        $stmt->bindParam(":waktudatang", $this->waktudatang);
        $stmt->bindParam(":selisihkedatangan", $this->selisihkedatangan);
        $stmt->bindParam(":awalpelayanan", $this->awalpelayanan);
        $stmt->bindParam(":selisihpelayanankasir", $this->selisihpelayanankasir);
        $stmt->bindParam(":selesai", $this->selesai);
        $stmt->bindParam(":selisihkeluarantrian", $this->selisihkeluarantrian);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // SingleData
    public function getSingleData()
    {
        $sqlQuery = "SELECT
        id, 
        waktudatang, 
        selisihkedatangan, 
        awalpelayanan, 
        selisihpelayanankasir, 
        selesai, 
        selisihkeluarantrian
        FROM
        " . $this->db_table . "
        WHERE
        id = ?
        LIMIT 0,1";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->waktudatang = $dataRow['waktudatang'];
        $this->selisihkedatangan = $dataRow['selisihkedatangan'];
        $this->awalpelayanan = $dataRow['awalpelayanan'];
        $this->selesai = $dataRow['selesai'];
        $this->selisihpelayanankasir = $dataRow['selisihpelayanankasir'];
        $this->selisihkeluarantrian = $dataRow['selisihkeluarantrian'];
    }

    // UPDATE
    public function updateAntrian()
    {
        $sqlQuery = "UPDATE " . $this->db_table . "
            SET
            waktudatang = :waktudatang,
            selisihkedatangan = :selisihkedatangan,
            awalpelayanan = :awalpelayanan,
            selisihpelayanankasir = :selisihpelayanankasir,
            selesai = :selesai,
            selisihkeluarantrian = :selisihkeluarantrian
            WHERE
            id = :id";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        // (Tetapkan sanitize sesuai kebutuhan)

        // bind data
        $stmt->bindParam(":waktudatang", $this->waktudatang);
        $stmt->bindParam(":selisihkedatangan", $this->selisihkedatangan);
        $stmt->bindParam(":awalpelayanan", $this->awalpelayanan);
        $stmt->bindParam(":selisihpelayanankasir", $this->selisihpelayanankasir);
        $stmt->bindParam(":selesai", $this->selesai);
        $stmt->bindParam(":selisihkeluarantrian", $this->selisihkeluarantrian);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    public function deleteAntrian()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function generateByAVG()
    {
        $sqlQuery = "SELECT 
    MIN(waktudatang) AS r_waktu_kedatangan,
    MAX(waktudatang) AS r_max_waktu_kedatangan,
    AVG(selisihkedatangan) AS r_selisihkedatangan, 
    AVG(selisihpelayanankasir) AS r_selisihpelayanankasir, 
    AVG(selisihkeluarantrian) AS r_selisihkeluarantrian,
    MIN(selisihkeluarantrian) AS r_min_selisihkeluarantrian,
    MAX(selisihkeluarantrian) AS r_max_selisihkeluarantrian
    
    FROM " . $this->db_table;

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $dataRow = $stmt->fetch();

        $this->waktudatang = $dataRow['r_waktu_kedatangan'];
        $this->max_waktudatang = $dataRow['r_max_waktu_kedatangan'];
        $this->selisihkedatangan = $dataRow['r_selisihkedatangan'];
        $this->selisihpelayanankasir = $dataRow['r_selisihpelayanankasir'];
        $this->selisihkeluarantrian = $dataRow['r_selisihkeluarantrian'];
        $this->min_selisihkeluarantrian = $dataRow['r_min_selisihkeluarantrian'];
        $this->max_selisihkeluarantrian = $dataRow['r_max_selisihkeluarantrian'];
    }

    public function getLatestArrivalTime()
    {
        $query = "SELECT waktudatang, awalpelayanan, selesai FROM antrian ORDER BY waktudatang DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // $waktudatang = $row['waktudatang'];
        return $row;
    }
}
