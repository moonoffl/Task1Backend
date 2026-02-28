
<!DOCTYPE html>
<html>
<head>
    <title>Course Joining Form</title>
    <script src="../jquery-3.7.1.min/jquery-3.7.1.min.js"></script>
    <script src="../jquery-3.7.1.min/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="../jquery-3.7.1.min/jquery.dataTables.css">
    <link href="../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../bootstrap-icons-1.13.1/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../Style/Course.css">
    <script src="../jquery-3.7.1.min/jquery.validate.min.js"></script>
    <script src="../jquery-3.7.1.min/additional-methods.min.js"></script>

</head>
<body>
    <?php include "../includes/template.php"; ?>

    <!-- Page Content -->
    <div id="page-content" class="container">
        <div class="form-container">        
            <h1>Course Details :</h1>
            <form id="CourseForm" class="p-4 bg-light rounded">
                <input type="hidden" id="Oldcode" name="Oldcode">
                <input type="hidden" id="ID" name="ID">

                <!-- First Line: Course Code and Course Name -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="Code" class="form-label fw-bold">Course Code :</label>
                            <input type="text" id="Code" name="Code" maxlength="5" class="form-control" required>
                            <div id="error" class="form-text text-danger"></div>
                            <div class="form-text">Format: C1234 (Letter + 4 digits)</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="Name" class="form-label fw-bold">Course Name :</label>
                            <input type="text" id="Name" name="Name" maxlength="20" class="form-control" required>
                            <div class="form-text">Maximum 20 characters, letters only</div>
                        </div>
                    </div>
                </div>
                
                <!-- Second Line: Duration -->
                <div class="row mb-4">
                    <div class="col-md-4 offset-md-4">
                        <div class="mb-3">
                            <label for="Duration" class="form-label fw-bold">Duration (Months) :</label>
                            <input type="text" id="Duration" name="Duration" maxlength="2" class="form-control" required>
                            <div class="form-text">Between 1 and 36 months</div>
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
       
            <div id="Course_Table" class="mt-4 p-3 bg-white rounded border"></div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            loadData();  
                $("#CourseForm").validate({

                    rules: {
                        Code: {
                            required: true,
                            minlength: 4,
                            maxlength: 5,
                            pattern: /^[C]\d{4}$/
                        },
                        Name: {
                            required: true,
                            pattern: /^[A-Za-z][A-Za-z ]{0,19}$/,
                            maxlength: 20
                        },
                        Duration: {
                            required: true,
                            pattern:/^([1-9]|[1-2][0-9]|3[0-6])$/
                        }
                    },

                    messages: {
                        Code: {
                            required: "Code is required",
                            pattern: "Like this C1234",
                            minlength: "Minimum 5 characters",
                            maxlength: "Maximum 30 characters"
                        },
                        Name: {
                            required: "Course name is required",
                            pattern: "Only letters",
                            minlength: "Minimum 6 characters"
                        },
                        Duration: {
                            required: "Duration is required",
                            pattern:"1-36"
                        }
                    },

                    errorClass: "text-danger",
                    errorElement: "small",
                    submitHandler: function (form) {
                        loadData();                                         
                        $.ajax({
                    url:"Course_Save.php",
                    type:"POST",
                    data:{
                        ID:$('#ID').val(),
                        Oldcode:$('#Oldcode').val(),
                        Code:$('#Code').val(),
                        Name:$('#Name').val(),
                        Duration:$('#Duration').val()
                    },
                    success:function(res){
                        if(res.status === 1){
                            alert(res.msg);           
                            $("#CourseForm")[0].reset();
                            $("#ID").val("");
                            loadData();
                        } else if(res.status === 2){
                            alert(res.msg);           
                        } else if(res.status === 3){
                            alert(res.msg);
                        } else if(res.status === 5){
                            alert(res.msg);           
                            $("#CourseForm")[0].reset();
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
                url: "Course_Fetch.php",
                success: function(data) {
                    $("#Course_Table").html(data);
                    $("#CourseTable").DataTable();
                    document.getElementById("Code").focus();
                }
            });
        }  
    
        function confirmDelete(ID){
            if(confirm("Delete this record?")){
                $.ajax({
                    url:"Course_Delete.php",
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
            $("#CourseForm")[0].reset();
            $("#ID").val("");
            loadData();
        }

        function confirmUpdate(ID){
            document.getElementById("Code").focus();
            $.ajax({
                url:"Course_Update.php",
                type:"POST",
                data:{ID:ID},
                dataType :"json",
                success:function(res){
                    $('#ID').val(res.Course_Id);
                    $('#Oldcode').val(res.Course_Code);
                    $('#Code').val(res.Course_Code);
                    $('#Name').val(res.Course_Name);
                    $('#Duration').val(res.Course_Duration);
                }
            });
        }
    </script>
    
    <script src="../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>