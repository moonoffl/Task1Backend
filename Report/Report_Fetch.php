<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    include "../includes/db_con.php";

    //header('Content-Type: application/json');
    $data = array();

    $Mcode  = trim($_POST['MCode'] ?? '');
    $Course = trim($_POST['Course'] ?? '');

    $sql = "SELECT m.member_id,m.member_code,m.member_name,m.number,m.email,m.dob,c.course_name,m.doj,m.gender,m.image
            FROM member_details m
            JOIN course_details c 
            ON m.course_id = c.course_id WHERE 1=1";

    if(!empty($Course)&& empty($Mcode)){
        $sql .= " AND m.course_id = '$Course' ORDER BY m.member_name";
    }
    elseif(!empty($Mcode)&& empty($Course)){
        $sql .= " AND m.member_code = '$Mcode' ORDER BY m.member_name";
    }
    elseif(!empty($Mcode) && !empty($Course)){
       $sql .= " AND m.course_id = '$Course' AND m.member_code = '$Mcode' ORDER BY m.member_name";
    }
    else{
        $sql .= " ORDER BY m.member_name";
    }

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
        "Course" => $row['course_name'], // display
        "Image"  => $row['image']
    ];
}

echo json_encode([
    "status" => 1,
    "data" => $data
]);

?>