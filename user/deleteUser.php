<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connect.php';

$request_data = file_get_contents("php://input");
$data = json_decode($request_data, true);


if (isset($data['user_id'])) {
    try {
        $id = ($data['user_id']);
        $deletestmt = $conn->prepare("DELETE FROM user WHERE user_id = $id");
        $deletestmt->execute();

        $stmt = $conn->prepare("SELECT * FROM user");
        $stmt->execute();
        $row = $stmt->fetchAll();

        echo json_encode(["success" => true, "msg" => "Data has been deleted successuly", "dataUser" => $row]);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
