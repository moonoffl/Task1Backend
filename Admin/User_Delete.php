<?php
// Set headers FIRST
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include database connection
include "../includes/db_con.php";

// Initialize response array
$data = array();

// Check if connected to database
if (!$conn) {
    $data['status'] = 0;
    $data['msg'] = "Database connection failed";
    echo json_encode($data);
    exit();
}

// Get POST data with validation
$id = isset($_POST['ID']) ? trim($_POST['ID']) : '';

// Validate ID
if (empty($id) || !is_numeric($id)) {
    $data['status'] = 0;
    $data['msg'] = "Invalid User ID";
    echo json_encode($data);
    exit();
}

$check_Member = "SELECT 1 FROM member_details WHERE Added_By = ? || Update_By =?";
$Member_stmt = mysqli_prepare($conn, $check_Member);
mysqli_stmt_bind_param($Member_stmt, "ii", $id,$id);
mysqli_stmt_execute($Member_stmt);
mysqli_stmt_store_result($Member_stmt);

if (mysqli_stmt_num_rows($Member_stmt) > 0) {
    $data['status'] = 2;
    $data['msg'] = "This User is currently being used by members and cannot be deleted";
    mysqli_stmt_close($Member_stmt);
    echo json_encode($data);
    exit();
}
mysqli_stmt_close($Member_stmt);

$check_Course = "SELECT 1 FROM course_details WHERE Added_By = ?|| Update_By =?";
mysqli_stmt_bind_param($Course_stmt, "ii", $id,$id);
mysqli_stmt_execute($Course_stmt);
mysqli_stmt_store_result($Course_stmt);

if (mysqli_stmt_num_rows($Course_stmt) > 0) {
    $data['status'] = 3;
    $data['msg'] = "This User is currently being used by courses and cannot be deleted";
    mysqli_stmt_close($Course_stmt);
    echo json_encode($data);
    exit();
}
mysqli_stmt_close($Course_stmt);


// Delete the course
$delete_sql = "DELETE FROM user_details WHERE User_Id = ?";
$stmt = mysqli_prepare($conn, $delete_sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        
        if ($affected_rows > 0) {
            $data['status'] = 1;
            $data['msg'] = "User deleted successfully";
        } else {
            $data['status'] = 0;
            $data['msg'] = "User not found or already deleted";
        }
    } else {
        $data['status'] = 0;
        $data['msg'] = "Delete failed: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
} else {
    $data['status'] = 0;
    $data['msg'] = "SQL prepare failed";
}

// Send JSON response
echo json_encode($data);

// Close connection
mysqli_close($conn);
exit();
?>