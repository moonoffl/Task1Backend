<?php
include "../includes/Session_con.php"; 
if ($_SESSION['UserType'] != 1) {
    header("Location: ../Sign_In/DashBoard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <head>
    <title>Admin </title>
    <script src="../jquery-3.7.1.min/jquery-3.7.1.min.js"></script>
    <script src="../jquery-3.7.1.min/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="../jquery-3.7.1.min/jquery.dataTables.css">
    <link href="../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../bootstrap-icons-1.13.1/bootstrap-icons.css" rel="stylesheet">
    
    <!-- CORRECT ORDER: jQuery Validate FIRST, then Additional Methods -->
    <script src="../jquery-3.7.1.min/jquery.validate.min.js"></script>
    <script src="../jquery-3.7.1.min/additional-methods.min.js"></script>
    
    <link rel="stylesheet" href="../Style/Course.css">
    <style>
        .text-danger {
            color: red;
            font-size: 13px;
        }
        input.error {
            border: 2px solid red;
        }
    </style> 
</head>
    </head>
    <body>
        <?php include "../includes/template.php"; ?>

        <!-- Page Content -->
        <div id="page-content" class="container">
            <div class="form-container">        
                <h1>User details :</h1>
                <form id="UserForm" class="p-4 bg-light rounded">
                    <input type="hidden" id="OldUser" name="OldUser">
                    <input type="hidden" id="ID" name="ID">

                    <!-- First Line: Course Code and Course Name -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="UserName" class="form-label fw-bold">User Name :</label>
                                <input type="text" id="UserName" name="UserName" maxlength="20" class="form-control" required>
                                <div id="error" class="form-text text-danger"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="UserType" class="form-label fw-bold">UserType :</label>
                                <select name="UserType" id="UserType" class="form-select">
                                    <option value="">--Select User--</option>
                                    <option value="1">Admin</option>
                                    <option value="2">User</option>
                                </select>
                                <div id="error" class="form-text text-danger"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="Password" class="form-label fw-bold">Password :</label>
                                <input type="password" id="Password" name="Password" maxlength="40" class="form-control" required>
                                <div class="form-text">Minimum 8 Maximum 30 characters, letters only</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-center gap-3">
                            <button type="submit" name="Save" class="btn btn-success px-4">
                                <i class="bi bi-save"></i> Save
                            </button>
                            <button type="reset" onclick="Reset()" class="btn btn-danger px-4">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                        </div>
                    </div>
                </form>
        
                <div id="User_Table" class="mt-4 p-3 bg-white rounded border"></div>
            </div>
        </div>

        <script>
            $(document).ready(function(){
                loadData(); 
                $("#UserForm").validate({

                    rules: {
                        Password: {
                            required: true,
                            minlength: 8,
                            maxlength: 30,
                            pattern: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$/
                        },
                        UserName: {
                            required: true,
                            minlength: 4,
                            maxlength: 30,
                        },
                        UserType:{
                            required: true
                        }
                    },

                    messages: {
                        Password: {
                            required: "Password is required",
                            pattern: "Like this An@nd123",
                            minlength: "Minimum 8 characters",
                            maxlength: "Maximum 30 characters"
                        },
                        UserName: {
                            required: "User name is required",
                            minlength: "Minimum 5 characters",
                            maxlength: "Maximum 30 characters"
                        },
                        UserType:{
                            required: "UserType is required"
                        }
                    },

                    errorClass: "text-danger",
                    errorElement: "small",
                    submitHandler: function (form) {
                        loadData();                                         
                        $.ajax({
                            url:"Save.php",
                            type:"POST",
                            data:{
                                ID:$('#ID').val(),
                                OldUser:$('#OldUser').val(),
                                UserName:$('#UserName').val(),
                                UserType:$('#UserType').val(),
                                Password:$('#Password').val()
                            },
                            success:function(res){
                                if(res.status === 1){
                                    alert(res.msg);           
                                    $("#UserForm")[0].reset();
                                    $("#ID").val("");
                                    loadData();
                                } else if(res.status === 2){
                                    alert(res.msg);           
                                } else if(res.status === 3){
                                    alert(res.msg);
                                } else if(res.status === 5){
                                    alert(res.msg);           
                                    $("#UserForm")[0].reset();
                                    $("#ID").val("");
                                    loadData();
                                } else if(res.status === 6){
                                    alert(res.msg); 
                                } else {
                                    alert(res.msg);          
                                }
                            }
                        });
                    }
                });
            });

            function loadData(){
                $.ajax({
                    url: "User_Fetch.php",
                    success: function(data) {
                        $("#User_Table").html(data);
                        $("#UserTable").DataTable();
                        document.getElementById("UserName").focus();
                    }
                });
            }  
        
            function confirmDelete(ID){
                if(confirm("Delete this record?")){
                    $.ajax({
                        url:"User_Delete.php",
                        type:"POST",
                        data:{ID:ID},
                        success:function(res){
                            alert(res);
                            loadData();
                        },
                        error: function(){
                            alert("Delete failed");
                        }
                    });
                }
            }

            function Reset(){
                $("#UserForm")[0].reset();
                $("#ID").val("");
                loadData();
            }

            function confirmUpdate(ID){
                $.ajax({
                    url:"User_Update.php",
                    type:"POST",
                    data:{ID:ID},
                    dataType :"json",
                    success:function(res){
                        $('#ID').val(res.User_Id);
                        $('#OldUser').val(res.UserName);
                        $('#UserName').val(res.UserName);
                        $('#UserType').val(res.UserType);
                        $('#Password').val(res.Password);
                    }
                });
            }
        </script>
        
        <script src="../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>