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
$idWritter = $data['id_writter'];
$userWriiter = $conn->prepare("SELECT * FROM user WHERE user_id=$idWritter");
$userWriiter->execute();
$rowUser = $userWriiter->fetch(PDO::FETCH_ASSOC);

$ingre = $conn->prepare("SELECT * FROM ingredient WHERE recipe_id=$idRecipe");
$ingre->execute();
$rowIngredient = $ingre->fetchAll(PDO::FETCH_ASSOC);

$cooking = $conn->prepare("SELECT * FROM cooking WHERE recipe_id=$idRecipe");
$cooking->execute();
$rowCooking = $cooking->fetchAll(PDO::FETCH_ASSOC);

$tag = $conn->prepare("SELECT * FROM tag INNER JOIN recipe_tag ON tag.tag_id = recipe_tag.tag_id WHERE recipe_id = $idRecipe;");
$tag->execute();
$rowTag = $tag->fetchAll(PDO::FETCH_ASSOC);

$liked = false;
$favorited = false;

if (isset($idUser)) {
    $checkLike = $conn->prepare("SELECT * FROM likes WHERE user_id = $idUser AND recipe_id =$idRecipe");
    $checkLike->execute();
    $rowLike = $checkLike->fetch(PDO::FETCH_ASSOC);
    if ($rowLike) {
        $liked = true;
    } else {
        $liked = false;
    }

    $checkFavorite = $conn->prepare("SELECT * FROM favorite WHERE user_id = $idUser AND recipe_id =$idRecipe");
    $checkFavorite->execute();
    $rowFavorite = $checkFavorite->fetch(PDO::FETCH_ASSOC);
    if ($rowFavorite) {
        $favorited = true;
    } else {
        $favorited = false;
    }
}


echo json_encode([
    "success" => true,
    "dataUser" => $rowUser,
    "dataIngre" => $rowIngredient,
    "dataCooking" => $rowCooking,
    "dataTag" => $rowTag,
    "checkLiked" => $liked,
    "checkFavorited" => $favorited
]);
