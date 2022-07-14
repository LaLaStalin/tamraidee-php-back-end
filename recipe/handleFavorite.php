<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connect.php';

$request_data = file_get_contents("php://input");
$data = json_decode($request_data, true);

$idUser = ($data['id_user']);
$idRecipe = ($data['id_recipe']);
$stmtFavorite = ($data['check_favorited']);

if ($stmtFavorite) {
    $stmtDel = $conn->prepare("DELETE FROM favorite WHERE user_id = $idUser AND recipe_id = $idRecipe");
    $stmtDel->execute();
} else {
    $stmtInsert = $conn->prepare("INSERT INTO favorite(user_id, recipe_id) VALUES ($idUser, $idRecipe)");
    $stmtInsert->execute();
}
echo json_encode(["success" => true]);
