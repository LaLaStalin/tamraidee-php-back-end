<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connect.php';

$request_data = file_get_contents("php://input");
$data = json_decode($request_data, true);

if (isset($data['user_id']) && isset($data['firstname']) && isset($data['lastname'])) {
    try {
        $img_user = $data['exist_img'];
        if (isset($data['uploadImg'])) {
            $DIR = "../imgs/profile/";
            $image = $data['uploadImg'];
            $file_chunks = explode(";base64,", $image);
            $fileType = explode("image/", $file_chunks[0]);
            $image_type = $fileType[1];
            $base64Img = base64_decode($file_chunks[1]);

            $file = $DIR . uniqid() . '.' . $image_type;
            $extract_file = explode("/", $file)[3];
            file_put_contents($file, $base64Img);
            if (isset($data['deleteOldImg'])) {
                $path = "../imgs/profile/";
                $filename = $data['deleteOldImg'];
                unlink($path . $filename);
            }
        }
        if (isset($img_user)) {
            $extract_file = $img_user;
        }

        $id = $data['user_id'];
        $firstname = trim($data['firstname']);
        $lastname = trim($data['lastname']);



        if (isset($data['email']) && isset($data['role'])) {
            $email = trim($data['email']);
            $role = trim($data['role']);

            $sql = $conn->prepare("UPDATE user SET user_firstname = '$firstname', user_lastname = '$lastname',
                                    user_img = '$extract_file', user_email = '$email', user_urole = '$role' 
                                    WHERE user_id = $id");
            $sql->execute();
        } else {
            $sql = $conn->prepare("UPDATE user SET user_firstname = '$firstname', user_lastname = '$lastname',  user_img = '$extract_file' 
            WHERE user_id = $id");
            $sql->execute();
        }


        $check_data = $conn->prepare("SELECT * FROM `user` where user_id = $id");
        $check_data->execute();
        $row = $check_data->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo json_encode(["success" => "อัพเดทข้อมูลเรียบร้อยแล้ว", "dataUser" => $row]);
        } else {
            echo json_encode(["error" => "ข้อมูลไม่ได้รับการอัพเดต"]);
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
