<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

include "../includes/db_con.php";


$sql = "SELECT m.member_id, m.member_code, m.member_name, m.number, m.email, 
               m.dob, m.doj, m.gender, m.course_id, c.course_name, m.image
        FROM member_details m
        LEFT JOIN course_details c ON m.course_id = c.course_id  ORDER BY m.member_code";



$stmt = mysqli_prepare($conn, $sql);


mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        "Mid"    => $row['member_id'],
        "Mcode"  => $row['member_code'],
        "Mname"  => $row['member_name'],
        "Number" => $row['number'],
        "Email"  => $row['email'],
        "DOB"    => $row['dob'],     // YYYY-MM-DD
        "DOJ"    => $row['doj'],
        "Gender" => $row['gender'],
     // for dropdown
        "Course" => $row['course_name'], // display
        "Image"  => $row['image']
    ];
}

echo json_encode([
    "status" => 1,
    "data" => $data
]);
?>
