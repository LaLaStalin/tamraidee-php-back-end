<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connect.php';

$request_data = file_get_contents("php://input");
$data = json_decode($request_data, true);

if (isset($data['user_id'])  && isset($data['oldPassword'])  && isset($data['newPassword'])) {
    try {
        $id = ($data['user_id']);
        $oldPassword = trim($data['oldPassword']);
        $newPassword = trim($data['newPassword']);
        $check_email = $conn->prepare("SELECT user_password FROM user WHERE user_id = $id");
        $check_email->execute();
        $row = $check_email->fetch(PDO::FETCH_ASSOC);


        if (!password_verify($oldPassword, $row['user_password'])) {
            echo json_encode(["warning" => "รหัสผ่านเก่าไม่ถูกต้อง", "exist" => true]);
        } else {
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = $conn->prepare("UPDATE user SET user_password = '$passwordHash' WHERE user_id = $id");
            $sql->execute();

            $check_data = $conn->prepare("SELECT * FROM user where user_id = $id");
            $check_data->execute();
            $row = $check_data->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                echo json_encode(["success" => "อัพเดทข้อมูลเรียบร้อยแล้ว", "dataUser" => $row]);
            } else {
                echo json_encode(["error" => "ข้อมูลไม่ได้รับการอัพเดต"]);
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
