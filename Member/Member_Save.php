<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

include "../includes/db_con.php";
session_start();

if (!$conn) {
    echo json_encode(["status"=>0,"msg"=>"DB Connection Failed"]);
    exit;
}

// ================= GET DATA =================
$Mid     = $_POST['Mid'] ?? '';
$Mcode   = $_POST['Mcode'] ?? '';
$Mname   = $_POST['Mname'] ?? '';
$Number  = $_POST['Number'] ?? '';
$Email   = $_POST['Email'] ?? '';
$DOB     = $_POST['DOB'] ?? '';
$Course  = $_POST['Course'] ?? '';
$DOJ     = $_POST['DOJ'] ?? '';
$Gender  = $_POST['Gender'] ?? '';
$Oldcode = $_POST['Oldcode'] ?? '';

$educationData = json_decode($_POST['educationData'] ?? '[]', true);

// ================= VALIDATION =================
if(!$Mcode || !$Mname || !$Number || !$Email || !$DOB || !$Course || !$DOJ || !$Gender){
    echo json_encode(["status"=>0,"msg"=>"All fields required"]);
    exit;
}

if (empty($educationData)) {
    echo json_encode(["status"=>6,"msg"=>"Add at least one education detail"]);
    exit;
}

// ================= IMAGE UPLOAD FUNCTION =================
function uploadImage($oldImage = "") {

    if (!isset($_FILES['Image']) || $_FILES['Image']['error'] === UPLOAD_ERR_NO_FILE) {
        return $oldImage;
    }

    if ($_FILES['Image']['error'] !== UPLOAD_ERR_OK) {
        return $oldImage;
    }

    $allowed = ['jpg','jpeg','png','gif','webp'];
    $ext = strtolower(pathinfo($_FILES['Image']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) return $oldImage;
    if ($_FILES['Image']['size'] > 5 * 1024 * 1024) return $oldImage;

    $uploadDir = "C:/xampp/htdocs/backend/Member/Images/";

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    if (!is_writable($uploadDir)) chmod($uploadDir, 0777);

    $newName = time() . "_" . uniqid() . "." . $ext;
    $uploadPath = $uploadDir . $newName;

    if (move_uploaded_file($_FILES['Image']['tmp_name'], $uploadPath)) {

        if (!empty($oldImage) && file_exists($uploadDir . $oldImage)) {
            unlink($uploadDir . $oldImage);
        }

        return $newName;
    }

    return $oldImage;
}

// ================= GET OLD IMAGE =================
$oldImage = "";

if ($Mid) {
    $stmt = $conn->prepare("SELECT Image FROM member_details WHERE Member_Id=?");
    $stmt->bind_param("i", $Mid);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $oldImage = $row['Image'];
    }
    $stmt->close();
}

$Image = uploadImage($oldImage);

// ================= ESCAPE =================
$Mcode = $conn->real_escape_string($Mcode);
$Mname = $conn->real_escape_string($Mname);
$Number = $conn->real_escape_string($Number);
$Email = $conn->real_escape_string($Email);
$DOB = $conn->real_escape_string($DOB);
$Course = $conn->real_escape_string($Course);
$DOJ = $conn->real_escape_string($DOJ);
$Gender = $conn->real_escape_string($Gender);
$Image = $conn->real_escape_string($Image);

// ================= INSERT MEMBER + EDUCATION =================
if (empty($Mid)) {

    // Check duplicate member code
    $stmt = $conn->prepare("SELECT 1 FROM member_details WHERE Member_Code=?");
    $stmt->bind_param("s", $Mcode);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(["status"=>3,"msg"=>"Member Code Exists"]);
        exit;
    }
    $stmt->close();

    if (empty($Image)) {
        echo json_encode(["status"=>5,"msg"=>"Upload Image"]);
        exit;
    }

    $conn->begin_transaction();

    try {

        // Insert member
        $stmt = $conn->prepare("
            INSERT INTO member_details 
            (Member_Code, Member_Name, Number, Email, DOB, Course_Id, DOJ, Gender, Image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssssssss", $Mcode, $Mname, $Number, $Email, $DOB, $Course, $DOJ, $Gender, $Image);
        $stmt->execute();

        $memberId = $conn->insert_id; // ✅ Correct place
        $stmt->close();

        // Insert Education Details
        $eduStmt = $conn->prepare("
            INSERT INTO educational_details
            (Member_Id, Degree, Institute, RegNo, Year, Percentage, Class)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        foreach ($educationData as $edu) {
            if (!$edu) continue;

            $Degree = $edu['Degree'];
            $Institute = $edu['Institute'];
            $RegNo = $edu['RegNo'];
            $Year = $edu['Year'];
            $Percentage = $edu['Percentage'];
            $Class = $edu['Class']; // ✅ Correct key

            $eduStmt->bind_param(
                "isssids",
                $memberId,
                $Degree,
                $Institute,
                $RegNo,
                $Year,
                $Percentage,
                $Class
            );

            $eduStmt->execute();
        }

        $eduStmt->close();
        $conn->commit();

        echo json_encode([
            "status"=>1,
            "msg"=>"Saved Successfully",
            "member_id"=>$memberId,
            "image"=>$Image
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["status"=>2,"msg"=>"Error: ".$e->getMessage()]);
    }
}

// ================= UPDATE MEMBER =================
else {
    // Check duplicate member code for update (excluding current member)
    $stmt = $conn->prepare("SELECT 1 FROM member_details WHERE Member_Code=? AND Member_Id != ?");
    $stmt->bind_param("si", $Mcode, $Mid);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(["status"=>3,"msg"=>"Member Code Exists"]);
        exit;
    }
    $stmt->close();

    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Update member
        $stmt = $conn->prepare("
            UPDATE member_details SET
            Member_Code = ?, Member_Name = ?, Number = ?, Email = ?, DOB = ?,
            Course_Id = ?, DOJ = ?, Gender = ?, Image = ?
            WHERE Member_Id = ?
        ");
        
        if (!$stmt) {
            throw new Exception("Member update prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param(
            "sssssssssi",
            $Mcode, $Mname, $Number, $Email, $DOB,
            $Course, $DOJ, $Gender, $Image, $Mid
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Member update failed: " . $stmt->error);
        }
        
        // Delete existing educational details
        $deleteStmt = $conn->prepare("DELETE FROM educational_details WHERE Member_Id = ?");
        if (!$deleteStmt) {
            throw new Exception("Education delete prepare failed: " . $conn->error);
        }
        
        $deleteStmt->bind_param("i", $Mid);
        if (!$deleteStmt->execute()) {
            throw new Exception("Education delete failed: " . $deleteStmt->error);
        }
        
        // Insert new educational details
        $eduStmt = $conn->prepare("
            INSERT INTO educational_details 
            (Member_Id, Degree, Institute, RegNo, Year, Percentage, Class) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        if (!$eduStmt) {
            throw new Exception("Education insert prepare failed: " . $conn->error);
        }
        
        foreach ($educationData as $edu) {
            if (!$edu) continue;
            
            $Degree = $edu['Degree'];
            $Institute = $edu['Institute'];
            $RegNo = $edu['RegNo'];
            $Year = $edu['Year'];
            $Percentage = $edu['Percentage'];
            $Class = $edu['Class'];
            
            $eduStmt->bind_param(
                "isssids",
                $Mid,
                $Degree,
                $Institute,
                $RegNo,
                $Year,
                $Percentage,
                $Class
            );
            
            if (!$eduStmt->execute()) {
                throw new Exception("Education insert failed: " . $eduStmt->error);
            }
        }
        
        // Commit transaction
        $conn->commit();
        
        echo json_encode(['status' => 1, 'msg' => 'Updated Successfully']);
        
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo json_encode(['status' => 2, 'msg' => 'Update Error: ' . $e->getMessage()]);
    }
    
    exit;
}
?>