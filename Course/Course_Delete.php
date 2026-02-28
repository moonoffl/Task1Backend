<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "../includes/db_con.php";

// Always return JSON
$response = array();

if (!$conn) {
    $response['status'] = 0;
    $response['msg'] = "Database connection failed";
    echo json_encode($response);
    exit();
}

// Get POST data
$id = isset($_POST['ID']) ? trim($_POST['ID']) : '';

if (empty($id) || !is_numeric($id)) {
    $response['status'] = 0;
    $response['msg'] = "Invalid Course ID";
    echo json_encode($response);
    exit();
}

// Check if course is used by members
$check_sql = "SELECT 1 FROM member_details WHERE Course_Id = ?";
$check_stmt = mysqli_prepare($conn, $check_sql);
mysqli_stmt_bind_param($check_stmt, "i", $id);
mysqli_stmt_execute($check_stmt);
mysqli_stmt_store_result($check_stmt);

if (mysqli_stmt_num_rows($check_stmt) > 0) {
    $response['status'] = 2;
    $response['msg'] = "This course is currently being used by members and cannot be deleted";
    mysqli_stmt_close($check_stmt);
    echo json_encode($response);
    exit();
}
mysqli_stmt_close($check_stmt);

// Delete the course
$delete_sql = "DELETE FROM course_details WHERE Course_Id = ?";
$stmt = mysqli_prepare($conn, $delete_sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $response['status'] = 1;
        $response['msg'] = "Course deleted successfully";
    } else {
        $response['status'] = 0;
        $response['msg'] = "Course not found or already deleted";
    }
} else {
    $response['status'] = 0;
    $response['msg'] = "Delete failed: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

echo json_encode($response);
exit();
?>