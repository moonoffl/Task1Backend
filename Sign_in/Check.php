<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

include "../includes/db_con.php";

$UserName = trim($_POST['UserName'] ?? '');
$Password = trim($_POST['Password'] ?? '');

if ($UserName == "" || $Password == "") {
    echo json_encode(["status"=>0, "msg"=>"All fields required"]);
    exit;
}

$Encrypt_Password = md5($Password);

$stmt = $conn->prepare("SELECT User_Id, UserName, UserType FROM user_details WHERE UserName=? AND Encrypt=?");
$stmt->bind_param("ss", $UserName, $Encrypt_Password);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 1) {
    $stmt->bind_result($user_id, $user_name, $user_type);
    $stmt->fetch();
    
    session_regenerate_id(true);
    $_SESSION['User_Id'] = $user_id;
    $_SESSION['UserName'] = $user_name;
    $_SESSION['UserType'] = $user_type;
    
    echo json_encode([
        "status" => 1,
        "msg" => "Login successful",
        "data" => [
            "userId" => $user_id,
            "UserName" => $user_name,
            "UserType" => $user_type
        ]
    ]);
} else {
    echo json_encode([
        "status" => 2,
        "msg" => "Username or Password incorrect"
    ]);
}

$stmt->close();
$conn->close();
exit;
?>