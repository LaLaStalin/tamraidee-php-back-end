<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connect.php';

$request_data = file_get_contents("php://input");
$data = json_decode($request_data, true);


if (isset($data['id_recipe']) && isset($data['deleteImg'])) {
    try {
        $id = ($data['id_recipe']);
        $deletestmt = $conn->prepare("DELETE FROM recipe WHERE recipe_id = $id");
        $deletestmt->execute();

        $filename = $data['deleteImg'];
        $path = "../imgs/recipe/";
        unlink($path . $filename);
        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
