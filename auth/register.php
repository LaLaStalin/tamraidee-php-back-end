<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connect.php';

$request_data = file_get_contents("php://input");
$data = json_decode($request_data, true);

if (isset($data['firstname']) && isset($data['lastname']) && isset($data['email']) && isset($data['password'])) {
    try {
        $firstname = trim($data['firstname']);
        $lastname = trim($data['lastname']);
        $email = trim($data['email']);
        $password = trim($data['password']);
        $check_email = $conn->prepare("SELECT user_email FROM user WHERE user_email = '$email'");
        $check_email->bindParam(":email", $email);
        $check_email->execute();
        $row = $check_email->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo json_encode(["warning" => "มีอีเมลนี้อยู่ในระบบแล้ว", "exist" => true]);
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO user(user_firstname, user_lastname, user_email, user_password, user_urole, user_img) 
                                    VALUES('$firstname', '$lastname', '$email', '$passwordHash', 'M', null)");
            $stmt->execute();

            echo json_encode(["success" => "สมัครสมาชิกเรียบร้อยแล้ว", "exist" => false]);
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
