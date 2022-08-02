<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connect.php';

$request_data = file_get_contents("php://input");
$data = json_decode($request_data, true);


if (
    isset($data['user_id'])
    && isset($data['name']) && isset($data['description'])
    && isset($data['listIngredient']) && isset($data['listCooking'])
    && isset($data['minute']) && isset($data['listTag'])
) {


    $img_user = $data['exist_img'];
    if (isset($data['uploadImg'])) {
        $DIR = "../imgs/recipe/";
        $image = $data['uploadImg'];
        $file_chunks = explode(";base64,", $image);
        $fileType = explode("image/", $file_chunks[0]);
        $image_type = $fileType[1];
        $base64Img = base64_decode($file_chunks[1]);

        $file = $DIR . uniqid() . '.' . $image_type;
        $extract_file = explode("/", $file)[3];
        file_put_contents($file, $base64Img);
        if (isset($data['deleteOldImg'])) {
            $filename = $data['deleteOldImg'];
            $path = "../imgs/recipe/";
            unlink($path . $filename);
        }
    }
    if (isset($img_user)) {
        $extract_file = $img_user;
    }


    $id = ($data['user_id']);
    $del_recipe_by_id = ($data['recipe_id']);
    $name = ($data['name']);
    $description = ($data['description']);
    $listIngredient = ($data['listIngredient']);
    $listCooking = ($data['listCooking']);
    $hour = ($data['hour']);
    $minute = ($data['minute']);
    $amount = ($data['amount']);
    $listTag = ($data['listTag']);
    $row_like = null;


    if ($del_recipe_by_id) {
        $checkLike = $conn->prepare("SELECT * FROM likes WHERE recipe_id = $del_recipe_by_id");
        $checkLike->execute();
        $row_like = $checkLike->fetchAll(PDO::FETCH_ASSOC);

        $del = $conn->prepare("DELETE FROM recipe WHERE recipe_id = $del_recipe_by_id");
        $del->execute();
    }


    $stmt = $conn->prepare("INSERT INTO recipe(
        recipe_img, recipe_name, recipe_description, recipe_duration_hr, recipe_duration_m, recipe_amount, user_id) 
    VALUES('$extract_file', '$name', '$description', '$hour', '$minute', '$amount', $id)");
    $stmt->execute();

    $recipeID = $conn->lastInsertId();

    foreach ($listIngredient as $valueIngre) {
        $nameIngre = $valueIngre['name'];
        $volumnIngre = $valueIngre['volume'];
        $stmt = $conn->prepare("INSERT INTO ingredient(ingredient_name, ingredient_volume, recipe_id) 
        VALUES('$nameIngre', '$volumnIngre', $recipeID)");
        $stmt->execute();
    }

    foreach ($listCooking as $valueCooking) {
        $stmt = $conn->prepare("INSERT INTO cooking(recipe_id, cooking_step) 
        VALUES('$recipeID', '$valueCooking')");
        $stmt->execute();
    }

    foreach ($listTag as $valueTag) {
        $stmt = $conn->prepare("INSERT INTO recipe_tag(recipe_id, tag_id) 
        VALUES('$recipeID', '$valueTag')");
        $stmt->execute();
    }


    if ($row_like) {
        foreach ($row_like as $like) {
            $userLike = $like['user_id'];
            $insert_like = $conn->prepare("INSERT INTO likes(user_id, recipe_id) 
            VALUES($userLike,  $recipeID)");
            $insert_like->execute();
        }
    }

    echo json_encode(["success" => true, "hour" =>  $hour]);

    try {
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
