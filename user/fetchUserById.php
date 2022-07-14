<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connect.php';

$request_data = file_get_contents("php://input");
$data = json_decode($request_data, true);

if (isset($data['id'])) {
    try {
        $id = ($data['id']);
        $check_data = $conn->prepare("SELECT * FROM `user` where user_id=$id");
        $check_data->execute();
        $row = $check_data->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo json_encode(["success" => "alreay login", "dataUser" => $row]);
        } else {
            echo json_encode(["error" => "ข้อมูลไม่ได้รับการอัพเดต", "exist" => true]);
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
