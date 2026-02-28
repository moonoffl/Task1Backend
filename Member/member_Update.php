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

// Initialize response array
$response = [
    'status' => 0,
    'message' => '',
    'data' => null
];

// Check database connection
if (!$conn) {
    $response['message'] = 'Database connection failed';
    echo json_encode($response);
    exit;
}

// Check if Mid is provided
if (!isset($_POST['Mid']) || empty($_POST['Mid'])) {
    $response['message'] = 'Member ID is required';
    echo json_encode($response);
    exit;
}

// Validate and sanitize the ID
$Mid = filter_var($_POST['Mid'], FILTER_VALIDATE_INT);
if ($Mid === false || $Mid <= 0) {
    $response['message'] = 'Invalid Member ID format';
    echo json_encode($response);
    exit;
}

// Prepare statement for member details
$sql = "SELECT * FROM member_details WHERE Member_Id = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $Mid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Fetch educational details for this member
        $edu_sql = "SELECT * FROM educational_details WHERE Member_Id = ? ORDER BY Year DESC";
        $edu_stmt = mysqli_prepare($conn, $edu_sql);
        
        if ($edu_stmt) {
            mysqli_stmt_bind_param($edu_stmt, "i", $Mid);
            mysqli_stmt_execute($edu_stmt);
            $edu_result = mysqli_stmt_get_result($edu_stmt);
            
            $education = array();
            while($edu_row = mysqli_fetch_assoc($edu_result)) {
                $education[] = $edu_row;
            }
            
            // Add education data to the response
            $row['education'] = $education;
            
            // Success response
            $response['status'] = 1;
            $response['message'] = 'Member details found';
            $response['data'] = $row;
            
            mysqli_stmt_close($edu_stmt);
        } else {
            // Still return member data even if education fetch fails
            $row['education'] = [];
            $response['status'] = 1;
            $response['message'] = 'Member details found (education fetch failed)';
            $response['data'] = $row;
        }
    } else {
        $response['message'] = 'Member not found';
    }
    
    mysqli_stmt_close($stmt);
} else {
    $response['message'] = 'Failed to prepare statement';
    $response['error'] = mysqli_error($conn);
}

// Return JSON response
echo json_encode($response);

// Close connection
mysqli_close($conn);
?>