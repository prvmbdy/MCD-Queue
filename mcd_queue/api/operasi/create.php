<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/Antrian.php';

$database = new Database();
$db = $database->getConnection();
$item = new Antrian($db);
$data = json_decode(file_get_contents("php://input"));

$latestArrivalTime = $item->getLatestArrivalTime();

$item->waktudatang = $data->waktudatang;
$item->awalpelayanan = $data->awalpelayanan;
$item->selesai = $data->selesai;
$item->selisihkedatangan = calculateTimeDifference($latestArrivalTime['waktudatang'], $data->waktudatang);
$item->selisihpelayanankasir = calculateTimeDifference($latestArrivalTime['awalpelayanan'], $data->awalpelayanan);
$item->selisihkeluarantrian = calculateTimeDifference($latestArrivalTime['selesai'], $data->selesai);

if ($item->createAntrian()) {
    echo json_encode(['message' => 'Data Customer Berhasil Ditambahkan.']);
} else {
    echo json_encode(['message' => 'Data Customer Gagal Ditambahkan.']);
}
function calculateTimeDifference($time1, $time2)
{
    $datetime1 = new DateTime($time1);
    $datetime2 = new DateTime($time2);
    $interval = $datetime1->diff($datetime2);

    return $interval->i + $interval->h * 60;
}
