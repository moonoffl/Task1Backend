<?php
include "../includes/Session_con.php";
include "../includes/db_con.php";

header('Content-Type: application/json');

$User = $_SESSION["User_Id"];
$Add_Time = date('Y-m-d H:i:s');
$Update_Time = date('Y-m-d H:i:s');

/* ================= MEMBER DATA ================= */
$Mid     = trim($_POST['Mid'] ?? '');
$Oldcode = trim($_POST['Oldcode'] ?? '');
$Mcode   = trim($_POST['Mcode'] ?? '');
$Mname   = trim($_POST['Mname'] ?? '');
$Number  = trim($_POST['Number'] ?? '');
$Email   = trim($_POST['Email'] ?? '');
$DOB     = trim($_POST['DOB'] ?? '');
$Course  = trim($_POST['Course'] ?? '');
$DOJ     = trim($_POST['DOJ'] ?? '');
$Gender  = trim($_POST['Gender'] ?? '');

/* ================= EDUCATION DATA ================= */
$educationData = json_decode($_POST['educationData'] ?? '[]', true);

/* ================= IMAGE UPLOAD FUNCTION ================= */
function uploadImage($oldImage = "")
{
    if (empty($_FILES['Image']['name'])) {
        return $oldImage;
    }

    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($_FILES['Image']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        return false;
    }

    if ($_FILES['Image']['size'] > 5 * 1024 * 1024) {
        return false;
    }

    if (!is_dir("Images")) {
        mkdir("Images", 0777, true);
    }

    $newName = time() . "_" . $_FILES['Image']['name'];
    if (!move_uploaded_file($_FILES['Image']['tmp_name'], "Images/" . $newName)) {
        return false;
    }

    // Delete old image if exists and not empty
    if ($oldImage && $oldImage !== "" && file_exists("Images/" . $oldImage)) {
        unlink("Images/" . $oldImage);
    }

    return $newName;
}

/* ================= VALIDATION ================= */
if (empty($Mcode)) {
    echo json_encode(['status' => 0, 'msg' => 'Member code is required']);
    exit;
}

if (empty($educationData) || count(array_filter($educationData)) === 0) {
    echo json_encode(['status' => 0, 'msg' => 'Add at least one educational detail']);
    exit;
}

/* ================= DUPLICATE MEMBER CODE CHECK ================= */
if (empty($Mid)) {
    // For new member
    $stmt = $conn->prepare("SELECT Member_Id FROM member_details WHERE Member_Code = ?");
    $stmt->bind_param("s", $Mcode);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo json_encode(['status' => 3, 'msg' => 'Member code already exists']);
        exit;
    }
} else {
    // For update, check if code changed and is duplicate
    if ($Mcode !== $Oldcode) {
        $stmt = $conn->prepare("SELECT Member_Id FROM member_details WHERE Member_Code = ? AND Member_Id != ?");
        $stmt->bind_param("si", $Mcode, $Mid);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            echo json_encode(['status' => 3, 'msg' => 'Member code already exists']);
            exit;
        }
    }
}

/* ================= SAVE (INSERT) ================= */
if (empty($Mid)) {
    // Handle image for new member
    $Image = uploadImage();
    if ($Image === false) {
        echo json_encode(['status' => 0, 'msg' => 'Invalid image or upload failed']);
        exit;
    }
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Insert member
        $stmt = $conn->prepare("
            INSERT INTO member_details 
            (Member_Code, Member_Name, Number, Email, DOB, Course_Id, DOJ, Gender, Image, Added_By, Add_Date_Time) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        if (!$stmt) {
            throw new Exception("Member prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param(
            "sssssssssss",
            $Mcode, $Mname, $Number, $Email, $DOB, $Course, $DOJ, $Gender, $Image, $User, $Add_Time
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Member insert failed: " . $stmt->error);
        }
        
        $memberId = $conn->insert_id;
        
        // Insert educational details
        $eduStmt = $conn->prepare("
            INSERT INTO educational_details 
            (Member_Id, Degree, Institute, RegNo, Year, Percentage, Class) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        if (!$eduStmt) {
            throw new Exception("Education prepare failed: " . $conn->error);
        }
        
        foreach ($educationData as $edu) {
            if ($edu === null) continue;
            
            $eduStmt->bind_param(
                "isssids",
                $memberId,
                $edu['Degree'],
                $edu['Institute'],
                $edu['RegNo'],
                $edu['Year'],
                $edu['Percentage'],
                $edu['ClassName']
            );
            
            if (!$eduStmt->execute()) {
                throw new Exception("Education insert failed: " . $eduStmt->error);
            }
        }
        
        // Commit transaction
        $conn->commit();
        
        echo json_encode(['status' => 1, 'msg' => 'Saved Successfully']);
        
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo json_encode(['status' => 0, 'msg' => 'Error: ' . $e->getMessage()]);
    }
    
    exit;
}

/* ================= UPDATE ================= */
else {
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Get old image for update
        $res = $conn->query("SELECT Image FROM member_details WHERE Member_Id = '$Mid'");
        $row = $res->fetch_assoc();
        $oldImage = $row['Image'] ?? '';
        
        // Upload new image if provided
        $Image = uploadImage($oldImage);
        if ($Image === false) {
            throw new Exception("Image upload failed");
        }
        
        // If no new image uploaded, keep the old one
        if (empty($Image)) {
            $Image = $oldImage;
        }
        
        // Update member
        $stmt = $conn->prepare("
            UPDATE member_details SET
            Member_Code = ?, Member_Name = ?, Number = ?, Email = ?, DOB = ?,
            Course_Id = ?, DOJ = ?, Gender = ?, Image = ?,
            Update_By = ?, Update_Date_Time = ?
            WHERE Member_Id = ?
        ");
        
        if (!$stmt) {
            throw new Exception("Member update prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param(
            "sssssssssssi",
            $Mcode, $Mname, $Number, $Email, $DOB,
            $Course, $DOJ, $Gender,
            $Image, $User, $Update_Time, $Mid
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
            if ($edu === null) continue;
            
            $eduStmt->bind_param(
                "isssids",
                $Mid,
                $edu['Degree'],
                $edu['Institute'],
                $edu['RegNo'],
                $edu['Year'],
                $edu['Percentage'],
                $edu['ClassName']
            );
            
            if (!$eduStmt->execute()) {
                throw new Exception("Education insert failed: " . $eduStmt->error);
            }
        }
        
        // Commit transaction
        $conn->commit();
        
        echo json_encode(['status' => 5, 'msg' => 'Updated Successfully']);
        
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo json_encode(['status' => 0, 'msg' => 'Update Error: ' . $e->getMessage()]);
    }
    
    exit;
}
?>