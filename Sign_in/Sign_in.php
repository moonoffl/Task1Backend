
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../Style/Sign_in.css">
    <title>Sign In Page</title>
    <script src="../jquery-3.7.1.min/jquery-3.7.1.min.js"></script>
    <script src="../jquery-3.7.1.min/jquery.validate.min.js"></script>
    <script src="../jquery-3.7.1.min/additional-methods.min.js"></script>
    <style>
        .text-danger {
        color: red;
        font-size: 12px;
        display: block;
        margin-top: 5px;
    }
    input.error {
        border: 2px solid red !important;
    }
    .form-container {
        max-width: 400px;
        margin: 50px auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .form-buttons {
        margin-top: 20px;
        display: flex;
        gap: 10px;
    }
    </style>
    <script>
    
        function Reset(){
            $("#Sign_in_Form")[0].reset();
            document.getElementById("UserName").focus();
        }

    </script>
</head>
<body>

    <div class="form-container">
        <h1>Log In</h1>

        <form id="Sign_in_Form">

            <label for="UserName">User Name</label>
            <input type="text" name="UserName" id="UserName"  maxlength="20" required>

            <label for="Password">Password</label>
            <input type="password" name="Password" id="Password"  maxlength="30" class="hidden-text" required>

            <div class="form-buttons">
                <button type="submit" id="Save">LogIn</button>
                <button type="reset" id="Cancel" onclick="Reset()">Cancel</button>
            </div>
        </form>
    </div>
    <script>
                
         $(document).ready(function(){
                $("#Sign_in_Form").validate({

                    rules: {
                        UserName: {
                            required: true,
                            maxlength: 20
                        },
                        Password: {
                            required: true,
                            minlength: 8,
                            maxlength: 30
                        }
                    },

                    messages: {
                        UserName: {
                            required: "User name is required",
                            maxlength: "Maximum 30 characters"
                        },
                        Password: {
                            required: "Password is required",
                            minlength: "Minimum 8 characters",
                            maxlength: "Maximum 20 characters" 
                        }
                    },

                    errorClass: "text-danger",
                    errorElement: "small",
                    submitHandler: function (form) {                                   
                        $.ajax({
                    url:"Check.php",
                    type:"POST",
                    data:{
                        UserName:$('#UserName').val(),
                        Password:$('#Password').val()
                    },
                    dataType: "json",
                            success: function(res){
                                if(res.status === 1){
                                    $("#Sign_in_Form")[0].reset();
                                    window.location.href = "DashBoard.php";
                                    
                                } else {
                                    alert(res.msg);
                                    $("#Password").focus();
                                    $("#Password").val("");
                                }
                            }
                        });
                    }
         });
                });  
               /* $(document).ready(function(){
                    document.getElementById("UserName").focus();
                    $("#Sign_in_Form").submit(function(e){
                        e.preventDefault();
                        if(!validateForm()){
                            return;
                        }
                        let formData = new FormData(this);
                        $.ajax({
                            url: "Check.php",
                            type: "POST",
                            data: formData,
                            contentType: false,
                            processData: false, 
                            dataType: "json",
                            success: function(res){
                                if(res.status === 1){
                                    $("#Sign_in_Form")[0].reset();
                                    window.location.href = "DashBoard.php";
                                    
                                } else {
                                    alert(res.msg);
                                    $("#Password").focus();
                                    $("#Password").val("");
                                }
                            }
                        });
                    });
                }); */ 
    </script>

</body>
</html>
