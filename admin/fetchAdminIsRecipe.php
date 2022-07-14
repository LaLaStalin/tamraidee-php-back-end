<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connect.php';


$stmt = $conn->prepare("SELECT * FROM user INNER JOIN recipe ON user.user_id = recipe.user_id WHERE user_urole = 'A'");
$stmt->execute();
$row = $stmt->fetchAll();


if (!$row) {
    echo json_encode(["success" => false, "warning" => "No Admin's recipe found",  "dataUser" => $row]);
}
echo json_encode(["success" => true,  "dataUser" => $row]);
