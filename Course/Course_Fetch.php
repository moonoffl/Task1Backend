<?php
// Course_Fetch.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include "../includes/db_con.php";

$data = array();

$sql = "SELECT * FROM course_details ORDER BY Course_Code";
$stmt = mysqli_prepare($conn, $sql);


mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$courses = array();
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = array(
        'ID' => $row['Course_Id'],
        'Code' => $row['Course_Code'],
        'Name' => $row['Course_Name'],
        'Duration' => $row['Course_Duration']
    );
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

echo json_encode($courses);
?>