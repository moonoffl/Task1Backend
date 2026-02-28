<?php
// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

header('Content-Type: application/json');


// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "../includes/db_con.php";

// Check if ID is provided
if (!isset($_POST['ID']) || empty($_POST['ID'])) {
    echo json_encode([
        'status' => 0,
        'message' => 'ID is required'
    ]);
    exit;
}

// Validate and sanitize the ID
$id = filter_var($_POST['ID'], FILTER_VALIDATE_INT);
if ($id === false || $id <= 0) {
    echo json_encode([
        'status' => 0,
        'message' => 'Invalid ID format'
    ]);
    exit;
}

// Prepare the SQL statement
$sql = "SELECT * FROM course_details WHERE Course_Id = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Success - return the course data
            echo json_encode([
                'status' => 1,
                'message' => 'Course found',
                'data' => $row
            ]);
        } else {
            // No course found with that ID
            echo json_encode([
                'status' => 0,
                'message' => 'Course not found'
            ]);
        }
    } else {
        // Error executing statement
        echo json_encode([
            'status' => 0,
            'message' => 'Database query failed',
            'error' => mysqli_error($conn)
        ]);
    }
    
    mysqli_stmt_close($stmt);
} else {
    // Error preparing statement
    echo json_encode([
        'status' => 0,
        'message' => 'Failed to prepare statement',
        'error' => mysqli_error($conn)
    ]);
}

mysqli_close($conn);
?>