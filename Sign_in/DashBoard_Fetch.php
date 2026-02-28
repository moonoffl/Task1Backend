<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
include "../includes/db_con.php";



// Count Courses
$courseQuery = $conn->query("SELECT COUNT(*) as total_courses FROM course_details");
$courseRow = $courseQuery->fetch_assoc();
$CourseCount = $courseRow['total_courses'] ?? 0;

// Count Members
$memberQuery = $conn->query("SELECT COUNT(*) as total_members FROM member_details");
$memberRow = $memberQuery->fetch_assoc();
$MemberCount = $memberRow['total_members'] ?? 0;

// Response JSON
$response = [
    "status" => "success",
    "total_courses" => (int)$CourseCount,
    "total_members" => (int)$MemberCount,
    "msg" => "Data fetched successfully"
];

echo json_encode($response);
exit;
?>
