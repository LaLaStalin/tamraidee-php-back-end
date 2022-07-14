<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connect.php';
class LIKE
{
    public $recipe_id_by_like;
    public $count_like;
}

$stmt = $conn->prepare("SELECT * FROM user");
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

$all_recipe = $conn->prepare("SELECT * FROM recipe 
                            INNER JOIN user
                            ON recipe.user_id = user.user_id
                            ORDER BY RAND ( );");
$all_recipe->execute();
$all_row_recipe = $all_recipe->fetchAll(PDO::FETCH_ASSOC);

$tags = $conn->prepare("SELECT * FROM recipe_tag");
$tags->execute();
$tags_row = $tags->fetchAll(PDO::FETCH_ASSOC);

$list_like = array();
foreach ($all_row_recipe as $recipe) {
    $id = $recipe['recipe_id'];
    $check_data = $conn->prepare("SELECT COUNT(*) FROM likes WHERE recipe_id = $id ");
    $check_data->execute();
    $likes = $check_data->fetch(PDO::FETCH_ASSOC);

    $object_like = new LIKE();
    $object_like->recipe_id_by_like = $id;
    $object_like->count_like = $likes['COUNT(*)'];

    array_push($list_like, $object_like);
}




if (!$row) {
    echo json_encode(["success" => false, "warning" => "No users found",  "dataUser" => $row]);
}
echo json_encode(["success" => true,  "dataUser" => $row,  "dataRecipe" => $all_row_recipe, "dataTags" => $tags_row, "like" => $list_like]);
