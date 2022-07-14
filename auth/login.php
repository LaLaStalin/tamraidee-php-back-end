<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connect.php';

$request_data = file_get_contents("php://input");
$data = json_decode($request_data, true);

if (isset($data['email']) && isset($data['password'])) {
    try {
        $email = trim($data['email']);
        $check_data = $conn->prepare("SELECT * FROM `user` where user_email='$email'");
        $check_data->execute();
        $row = $check_data->fetch(PDO::FETCH_ASSOC);

        if ($check_data->rowCount() > 0) {
            if (password_verify($data['password'], $row['user_password'])) {
                echo json_encode(["success" => true, "msg" => "Welcome to Tam Rai Dee", "dataUser" => $row]);
            } else {
                echo "Your email or password is wrong.";
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
