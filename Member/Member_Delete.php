<?php
include "../includes/db_con.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Get Member ID
$Mid = $_POST['Mid'];

if(empty($Mid)){
    echo json_encode(["status"=>0, "msg"=>"Member ID required"]);
    exit();
}

// Get image name
$sql = "SELECT Image FROM member_details WHERE Member_Id='$Mid'";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);
$image = $row['Image'];

// Delete education details first
mysqli_query($conn, "DELETE FROM educational_details WHERE Member_Id='$Mid'");

// Delete member
$del = mysqli_query($conn, "DELETE FROM member_details WHERE Member_Id='$Mid'");

if($del){
    
    // Delete image file
    if($image != "" && file_exists("Images/".$image)){
        unlink("Images/".$image);
    }

    echo json_encode(["status"=>1, "msg"=>"Member Deleted Successfully"]);
}
else{
    echo json_encode(["status"=>0, "msg"=>"Delete Failed"]);
}

mysqli_close($conn);
?>
