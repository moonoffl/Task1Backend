<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set headers
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

// Start session
session_start();

$data = array();

// Check database connection
if (!$conn) {
    $data['status'] = 0;
    $data['msg'] = "Database connection failed";
    echo json_encode($data);
    exit;
}

// Get POST data
$Id = isset($_POST['ID']) ? trim($_POST['ID']) : '';
$UserName = isset($_POST['UserName']) ? trim($_POST['UserName']) : '';
$UserType = isset($_POST['UserType']) ? trim($_POST['UserType']) : '';
$Password = isset($_POST['Password']) ? $_POST['Password'] : '';
$OldUser = isset($_POST['OldUser']) ? trim($_POST['OldUser']) : '';

// Validate required fields
if (empty($UserName)) {
    $data['status'] = 0;
    $data['msg'] = "Username is required";
    echo json_encode($data);
    exit;
}

if (empty($UserType)) {
    $data['status'] = 0;
    $data['msg'] = "User type is required";
    echo json_encode($data);
    exit;
}

// For new users, password is required
if (empty($Id) && empty($Password)) {
    $data['status'] = 0;
    $data['msg'] = "Password is required for new users";
    echo json_encode($data);
    exit;
}

// Encrypt password using MD5 (as shown in your table)
$Encry = !empty($Password) ? md5($Password) : '';

// Get current user (from session or default)
$CurrentUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

// INSERT operation (new user)
if (empty($Id) || $Id == '0') {
    // Check if username already exists
    $check_sql = "SELECT User_Id FROM user_details WHERE UserName = ?";
    $stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($stmt, "s", $UserName);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $data['status'] = 3;
        $data['msg'] = "Username already exists";
        mysqli_stmt_close($stmt);
        echo json_encode($data);
        exit;
    }
    mysqli_stmt_close($stmt);
    
    // Insert new user - Note: Including both Password and Encrypt columns
    $insert_sql = "INSERT INTO user_details (UserName, Password, Encrypt, UserType) 
                   VALUES (?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($stmt, "ssss", $UserName, $Password, $Encry, $UserType);
    
    if (mysqli_stmt_execute($stmt)) {
        $data['status'] = 1;
        $data['msg'] = "User saved successfully";
        $data['ID'] = mysqli_insert_id($conn);
        $data['UserName'] = $UserName;
        $data['UserType'] = $UserType;
    } else {
        $data['status'] = 2;
        $data['msg'] = "Save failed: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
    
} else {
    // UPDATE operation
    if ($UserName != $OldUser) {
        // Check if new username already exists (excluding current user)
        $check_sql = "SELECT User_Id FROM user_details WHERE UserName = ? AND User_Id != ?";
        $stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($stmt, "si", $UserName, $Id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $data['status'] = 3;
            $data['msg'] = "Username already exists";
            mysqli_stmt_close($stmt);
            echo json_encode($data);
            exit;
        }
        mysqli_stmt_close($stmt);
    }
    
    // Update based on whether password is provided
    if (!empty($Password)) {
        // Update with new password (both Password and Encrypt columns)
        $update_sql = "UPDATE user_details SET 
                       UserName = ?, 
                       Password = ?, 
                       Encrypt = ?, 
                       UserType = ? 
                       WHERE User_Id = ?";
        
        $stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $UserName, $Password, $Encry, $UserType, $Id);
    } else {
        // Update without changing password
        $update_sql = "UPDATE user_details SET 
                       UserName = ?, 
                       UserType = ? 
                       WHERE User_Id = ?";
        
        $stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt, "ssi", $UserName, $UserType, $Id);
    }
    
    if (mysqli_stmt_execute($stmt)) {
        $data['status'] = 1;
        $data['msg'] = "User updated successfully";
        $data['UserName'] = $UserName;
        $data['UserType'] = $UserType;
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