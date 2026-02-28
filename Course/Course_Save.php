<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set headers FIRST
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include database connection
include "../includes/db_con.php";

// Start session for user tracking
session_start();

$data = array();

// Check if connected to database
if (!$conn) {
    $data['status'] = 0;
    $data['msg'] = "Database connection failed";
    echo json_encode($data);
    exit;
}

// Get POST data
$Id = isset($_POST['ID']) ? trim($_POST['ID']) : '';
$Code = isset($_POST['Code']) ? trim($_POST['Code']) : '';
$Name = isset($_POST['Name']) ? trim($_POST['Name']) : '';
$Duration = isset($_POST['Duration']) ? trim($_POST['Duration']) : '';
$Oldcode = isset($_POST['Oldcode']) ? trim($_POST['Oldcode']) : '';

// Validate required fields
if (empty($Code) || empty($Name) || empty($Duration)) {
    $data['status'] = 0;
    $data['msg'] = "All fields are required";
    echo json_encode($data);
    exit;
}

// Set default user (modify this based on your authentication)
if (isset($_SESSION["User_Id"]) && !empty($_SESSION["User_Id"])) {
    $User = $_SESSION["User_Id"];
} else {
    // Default to user ID 1 (make sure this exists in your user_details table)
    $User = 1;
    
    // Or get the first available user
    $result = mysqli_query($conn, "SELECT User_Id FROM user_details LIMIT 1");
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $User = $row['User_Id'];
    }
}

$Add_Time = date('Y-m-d H:i:s');
$Update_Time = date('Y-m-d H:i:s');

// INSERT operation (when ID is empty)
if (empty($Id)) {
    // Check if course code already exists
    $check_sql = "SELECT Course_Id FROM course_details WHERE Course_Code = ?";
    $stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($stmt, "s", $Code);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $data['status'] = 3;
        $data['msg'] = "Course code already exists";
        mysqli_stmt_close($stmt);
        echo json_encode($data);
        exit;
    }
    mysqli_stmt_close($stmt);
    
    // Validate lengths
    if (strlen($Code) >= 3 && strlen($Code) <= 5 &&
        strlen($Name) >= 1 && strlen($Name) <= 20) {
        
        // Insert new course
        $insert_sql = "INSERT INTO course_details (Course_Code , 
                   Course_Name, 
                   Course_Duration ,   
                   Add_Date_Time )
                   VALUES (?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($stmt, "ssss", $Code, $Name, $Duration, $Add_Time);
        
        if (mysqli_stmt_execute($stmt)) {
            $data['status'] = 1;
            $data['msg'] = "Course saved successfully";
            $data['id'] = mysqli_insert_id($conn);
            $data['code'] = $Code;
            $data['name'] = $Name;
            $data['duration'] = $Duration;
        } else {
            $data['status'] = 2;
            $data['msg'] = "Save failed: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
        
    } else {
        $data['status'] = 4;
        $data['msg'] = "Invalid data length";
    }
    
} else {
    // UPDATE operation
    if ($Code != $Oldcode) {
        // Check if new code already exists (excluding current course)
        $check_sql = "SELECT Course_Id FROM course_details WHERE Course_Code = ? AND Course_Id != ?";
        $stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($stmt, "si", $Code, $Id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $data['status'] = 3;
            $data['msg'] = "Course code already exists";
            mysqli_stmt_close($stmt);
            echo json_encode($data);
            exit;
        }
        mysqli_stmt_close($stmt);
    }
    
    // Update course
    $update_sql = "UPDATE course_details SET 
                   Course_Code = ?, 
                   Course_Name = ?, 
                   Course_Duration = ?, 
                   Update_By = ?, 
                   Update_Date_Time = ? 
                   WHERE Course_Id = ?";
    
    $stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt, "sssisi", $Code, $Name, $Duration, $User, $Update_Time, $Id);
    
    if (mysqli_stmt_execute($stmt)) {
        $data['status'] = 1;
        $data['msg'] = "Course updated successfully";
        $data['code'] = $Code;
        $data['name'] = $Name;
        $data['duration'] = $Duration;
    } else {
        $data['status'] = 2;
        $data['msg'] = "Update failed: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Send response
echo json_encode($data);

// Close connection
mysqli_close($conn);
?>