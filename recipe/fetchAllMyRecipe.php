<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connect.php';

$request_data = file_get_contents("php://input");
$data = json_decode($request_data, true);

$id = ($data['id']);

$stmt = $conn->prepare("SELECT * FROM recipe WHERE user_id=$id");
$stmt->execute();
$row_recipe = $stmt->fetchAll(PDO::FETCH_ASSOC);




if (!$row_recipe) {
    echo json_encode(["success" => false, "warning" => "No reciepe found"]);
}
echo json_encode(["success" => true,  "dataRecipe" => $row_recipe]);
