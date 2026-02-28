<?php
// Course_Fetch.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include "../includes/db_con.php";

$data = array();


$sql = "SELECT * FROM user_details ORDER BY User_Id";
$stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$Admin = array();
while ($row = mysqli_fetch_assoc($result)) {
    $Admin[] = array(
        'ID' => $row['User_Id'],
        'User_Name' => $row['UserName'],
        'User_Type' => $row['UserType'],
        'Password' => $row['Password']
    );
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

echo json_encode($Admin);
?>