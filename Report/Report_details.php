<?php
    include "../includes/Session_con.php";
?>

<html>
    <head>
        <script src="../jquery-3.7.1.min/jquery-3.7.1.min.js"></script>
        <script src="../jquery-3.7.1.min/jquery.dataTables.js"></script>
        <link rel="stylesheet" href="../jquery-3.7.1.min/jquery.dataTables.css">
        <link href="../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../bootstrap-icons-1.13.1/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="../Style/Report.css">
        <script src="../jquery-3.7.1.min/jquery.validate.min.js"></script>
        <script src="../jquery-3.7.1.min/additional-methods.min.js"></script>
        <title>Report details</title>
    </head>
    <body>
        <?php include "../includes/template.php"; ?>
        
        <!-- Page Content -->
        <div id="page-content" class="container">
            <div class="form-container">
                <h1><i class="bi bi-file-earmark-text"></i> Member Details Report</h1>

                <form id="ReportForm" enctype="multipart/form-data" class="p-4 bg-light rounded">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="Mcode" class="form-label fw-bold">Member Code :</label>
                                <input type="text" name="Mcode" id="Mcode" maxlength="5" class="form-control" placeholder="Enter member code (e.g., M1234)">
                                <div class="form-text">Leave blank to see all members</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <?php
                                    include "../includes/db_con.php";
                                    $sql = "SELECT Course_Id, Course_Name FROM course_details";
                                    $result = $conn->query($sql);
                                ?>
                                <label for="Course" class="form-label fw-bold">Course</label>
                                <select name="Course" id="Course" class="form-select">
                                    <option value="">All Courses</option>
                                    <?php
                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                echo '<option value="' . $row['Course_Id'] . '">' . htmlspecialchars($row['Course_Name']) . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No Course found</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-center gap-3">
                            <button type="submit" id="Search" name="Search" class="btn btn-primary px-4">
                                <i class="bi bi-search"></i> Search
                            </button>
                            <button type="reset" value="Cancel" onclick="Reset()" class="btn btn-danger px-4">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                        </div>
                    </div>
                </form>

                <div id="Report_Table" class="mt-4 p-3 bg-white rounded border"></div>

                <script>
                    $(document).ready(function(){
                        $("#ReportForm").validate({

                            rules: {
                                Mcode: {
                                    minlength: 4,
                                    maxlength: 5,
                                    pattern: /^[M]\d{4}$/
                                }
                            },
                            messages: {
                                Mcode: {
                                    pattern: "Like this M1234",
                                    minlength: "Minimum 5 characters",
                                    maxlength: "Maximum 30 characters"
                                }
                            },

                            errorClass: "text-danger",
                            errorElement: "small",
                            submitHandler: function (form) {
                                let formData = new FormData(form);
                                $.ajax({
                                    url: "Report_Fetch.php",
                                    type: "POST",
                                    data: formData,
                                    contentType: false,
                                    processData: false, 
                                    success: function(res){ 
                                        $("#Report_Table").html(res).show();  
                                        $('#ReportTable').DataTable();
                                        document.getElementById("Mcode").focus();
                                    }
                                });
                            }
                        });
                    });
                    
                    function Reset(){
                        $("#Report_Table").hide();
                        $("#ReportForm")[0].reset();
                        document.getElementById("Mcode").focus();
                    }
                </script>
            </div>
        </div> 
        <script src="../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>