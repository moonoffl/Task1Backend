

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Member joining details</title>
        <script src="../jquery-3.7.1.min/jquery-3.7.1.min.js"></script>
        <script src="../jquery-3.7.1.min/jquery.dataTables.js"></script>
        <link rel="stylesheet" href="../jquery-3.7.1.min/jquery.dataTables.css">
        <link href="../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../bootstrap-icons-1.13.1/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="../Style/Member.css">
        <script src="../jquery-3.7.1.min/jquery.validate.min.js"></script>
        <script src="../jquery-3.7.1.min/additional-methods.min.js"></script>
        <!-- Add these lines to your head section -->
        <link href="../node_modules/cropperjs/dist/cropper.min.css" rel="stylesheet">
        <script src="../node_modules/cropperjs/dist/cropper.min.js"></script>
        <style>
            /* Image Cropper Styles */
            .cropper-container {
                direction: ltr;
                font-size: 0;
                line-height: 0;
                position: relative;
                -ms-touch-action: none;
                touch-action: none;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }

            .cropper-wrap-box,
            .cropper-canvas,
            .cropper-drag-box,
            .cropper-crop-box,
            .cropper-modal {
                bottom: 0;
                left: 0;
                position: absolute;
                right: 0;
                top: 0;
            }

            .cropper-view-box {
                display: block;
                height: 100%;
                outline: 1px solid #39f;
                outline-color: rgba(51, 153, 255, 0.75);
                overflow: hidden;
                width: 100%;
            }

            .cropper-dashed {
                border: 0 dashed #eee;
                display: block;
                position: absolute;
            }

            .preview-container {
                margin-top: 20px;
            }

            .preview-label {
                font-weight: bold;
                margin-bottom: 10px;
            }

            #preview {
                border-radius: 50%;
            }

            #cropImage {
                max-height: 400px;
            }

            /* Make the cropper container responsive */
            #cropImage {
                max-width: 100%;
                height: auto;
            }
        </style>
    </head>
    <body>
        <?php include "../includes/template.php"; ?>

        <!-- Page Content -->
        <div id="page-content" class="container">
            <div class="form-container">
                <h1><i class="bi bi-person"></i> Member Details</h1>

                <form id="MemberForm" enctype="multipart/form-data" class="p-4 bg-light rounded">
                    <input type="hidden" name="Oldcode" id="Oldcode">
                    <input type="hidden" name="Mid" id="Mid">
                    
                    <!-- First Line: Member Code and Member Name -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="Mcode" class="form-label fw-bold">Member Code :</label>
                                <input type="text" name="Mcode" id="Mcode" maxlength="5" class="form-control" required/>
                                <div class="form-text">Format: M1234 (Letter + 4 digits)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="Mname" class="form-label fw-bold">Member Name :</label>
                                <input type="text" name="Mname" id="Mname" maxlength="20" class="form-control" required/>
                                <div class="form-text">Maximum 20 characters, letters only</div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="Number" class="form-label fw-bold">Mobile Number :</label>
                                <input type="text" name="Number" id="Number"  maxlength="10" class="form-control" required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="Email" class="form-label fw-bold">Email :</label>
                                <input type="email" name="Email" id="Email" maxlength="30" class="form-control" required/>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Second Line: DOB and Course -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="DOB" class="form-label fw-bold">Date Of Birth :</label>
                                <input type="date" name="DOB" id="DOB" min="1900-01-01" max="<?= date('Y-m-d', strtotime('-18 years')) ?>" class="form-control" required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <?php
                                    include "../includes/db_con.php";
                                    $sql = "SELECT Course_Id, Course_Name FROM course_details";
                                    $result = $conn->query($sql);
                                ?>
                                <label for="Course" class="form-label fw-bold">Choose a Category:</label>
                                <select name="Course" id="Course" class="form-select">
                                    <option value="">--Select an option--</option>
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
                    
                    <!-- Third Line: DOJ, Gender, and Image Upload -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="DOJ" class="form-label fw-bold">Date Of Joining :</label>
                                <input type="date" name="DOJ" id="DOJ" min="2000-01-01" max="<?= date('Y-m-d') ?>" class="form-control" required/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="Gender" class="form-label fw-bold">Gender :</label>
                                <select name="Gender" id="Gender" class="form-select">
                                    <option value="">--Select Gender--</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="Image" class="form-label fw-bold">Upload Image :</label>
                                <input type="file" name="Image" id="Image" class="form-control pointer" accept="image/*"/>
                                <img id="PreviewImage" src="" class="mt-2" width="120" height="120"
                                    style="display:none; border:1px solid #ccc; padding:5px; border-radius: 50%;">
                            </div>
                        </div>
                    </div>
                </form>   
            </div>

            <div class="form-container">
                <h1><i class="bi bi-person"></i> Educational Details</h1>

                <form id="EducationalForm" enctype="multipart/form-data" class="p-4 bg-light rounded">  
                    <!-- First Row of Educational Details -->
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="Degree" class="form-label fw-bold">Degree :</label>
                                <select name="Degree" id="Degree" class="form-select">
                                    <option value="" >--Degree--</option>
                                    <option value="UG">UG</option>
                                    <option value="PG">PG</option>
                                    <option value="Diploma">Diploma</option>
                                    <option value="10th">10th</option>
                                    <option value="12th">12th</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="Institute" class="form-label fw-bold">Institute :</label>
                                <input type="text" name="Institute" id="Institute" class="form-control pointer"/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="RegNo" class="form-label fw-bold">Reg No :</label>
                                <input type="text" name="RegNo" id="RegNo" class="form-control pointer"/>
                            </div>
                        </div>
                    
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="Year" class="form-label fw-bold">Year :</label>
                                <input type="text" name="Year" id="Year"  max="<?= date('Y') ?>" class="form-control pointer"/>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="mb-3">
                                <label for="Percentage" class="form-label fw-bold">Percent:</label>
                                <input type="text" name="Percentage" id="Percentage" class="form-control pointer"/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="Class" id="Classes" class="form-label fw-bold">Class :</label>
                                <select name="Class" id="Class" class="form-select">
                                    <option value="">--Class--</option>
                                    <option value="I class with distinction">I class with distinction</option>
                                    <option value="I class">I class</option>
                                    <option value="II class">II class</option>
                                    <option value="III class">III class</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-center gap-3">
                            <button type="submit" id="Add" name="Add" class="btn btn-success px-4">
                                <i class="bi bi-plus"></i> ADD
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="row mt-3">
                    <div class="col-md-12">
                        <table class="table table-bordered" id="datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Degree</th>
                                    <th>Institute</th>
                                    <th>Reg No</th>
                                    <th>Year</th>
                                    <th>Percentage</th>
                                    <th>Class</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-12 d-flex justify-content-center gap-3">
                        <button type="button" id="Save" name="Save" class="btn btn-success px-4">
                            <i class="bi bi-save"></i> Save
                        </button>
                        <button type="button" value="Cancel" onclick="Reset()" class="btn btn-danger px-4">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                    </div>
                </div>
            </div>

            <div id="Member_Table" class="mt-4 p-3 bg-white rounded border"></div>
        </div>

        <!-- Image Crop Modal -->
        <div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cropModalLabel">Crop Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <img id="cropImage" src="" style="max-width: 100%; max-height: 400px;">
                            </div>
                            <div class="col-md-4">
                                <div class="preview-container">
                                    <div class="preview-label">Preview:</div>
                                    <div id="preview" style="width: 150px; height: 150px; overflow: hidden; border: 1px solid #ccc; border-radius: 50%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="cropButton">Crop & Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Educational Details Modal -->
        <div class="modal fade" id="educationModal" tabindex="-1" aria-labelledby="educationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="educationModalLabel">Educational Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="educationModalBody">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading educational details...</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let educationData = [];
            let cropper = null;
            let originalImageFile = null;

            $(document).ready(function () {
                // Load data initially
                loadData();

                /* Show / Hide Class */
                $("#Degree").on("change", function () {
                    if (["UG", "PG", "Diploma"].includes(this.value)) {
                        $("#Classes").show();
                        $("#Class").show();
                    } else {
                        $("#Class").hide().val("");
                        $("#Classes").hide().val("");
                    }
                });

                /* jQuery Validation for Educational Form */
                $("#EducationalForm").validate({
                    rules: {
                        Degree: { required: true },
                        Institute: { 
                            required: true,
                            pattern: /^[a-zA-Z0-9][a-zA-Z0-9 ]*$/,
                            minlength: 5,
                            maxlength: 200
                        },
                        RegNo: {
                            required: true,
                            minlength: 7,
                            maxlength: 8,
                            pattern: /^[a-zA-Z0-9]+$/
                        },
                        Year: {
                            required: true,
                            min: 1900,
                            max: new Date().getFullYear()
                        },
                        Percentage: {
                            required: true,
                            min: 0,
                            max: 100
                        }
                    },
                    messages: {
                        Degree: { required: "Degree is required" },
                        Institute: { 
                            required: "Institute is required",
                            pattern: "Start with letter or number, no starting spaces",
                            minlength: "Minimum 5 characters required",
                            maxlength: "Maximum 200 characters allowed"
                        },
                        RegNo: { 
                            required: "Reg No is required",
                            minlength: "Minimum 7 characters",
                            maxlength: "Maximum 8 characters",
                            pattern: "Only letters and numbers allowed"
                        },
                        Year: { 
                            required: "Year is required",
                            min: "Year must be after 1900",
                            max: "Year cannot be in the future"
                        },
                        Percentage: { 
                            required: "Percentage is required",
                            min: "Percentage cannot be negative",
                            max: "Percentage cannot exceed 100%"
                        }
                    },
                    errorClass: "text-danger",
                    errorElement: "small",
                    submitHandler: function (form, event) {
                        event.preventDefault();
                        addEducation();
                    }
                });

                // Initialize Member Form validation
                initializeMemberFormValidation();

                /* Save Button Click Handler */
                $("#Save").on("click", function() {
                    // First check if educational data exists
                    if (educationData.filter(e => e !== null).length === 0) {
                        alert("Add at least one educational detail");
                        return false;
                    }

                    // Trigger validation
                    if ($("#MemberForm").valid()) {
                        saveMemberData();
                    }
                });

                /* Image Upload with Cropping */
                $("#Image").on("change", function(){
                    const file = this.files[0];
                    if(file){
                        // Validate file type
                        if (!file.type.match('image.*')) {
                            alert("Please select an image file (JPEG, PNG, GIF)");
                            $(this).val('');
                            return;
                        }
                        
                        // Validate file size (max 5MB)
                        if (file.size > 5 * 1024 * 1024) {
                            alert("Image size should be less than 5MB");
                            $(this).val('');
                            return;
                        }
                        
                        originalImageFile = file;
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            // Show temporary preview
                            $("#PreviewImage").attr("src", e.target.result).show();
                            
                            // Open crop modal
                            const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
                            $('#cropImage').attr('src', e.target.result);
                            cropModal.show();
                            
                            // Initialize cropper after modal is shown
                            setTimeout(initializeCropper, 300);
                        };
                        
                        reader.readAsDataURL(file);
                    }
                });

                /* Crop button event */
                $("#cropButton").on("click", function() {
                    if (cropper) {
                        // Get cropped canvas
                        const canvas = cropper.getCroppedCanvas({
                            width: 300,
                            height: 300,
                            imageSmoothingEnabled: true,
                            imageSmoothingQuality: 'high'
                        });
                        
                        if (canvas) {
                            // Convert canvas to blob
                            canvas.toBlob(function(blob) {
                                // Update preview
                                const url = URL.createObjectURL(blob);
                                $("#PreviewImage").attr("src", url).show();
                                
                                // Create a new file from blob
                                const croppedFile = new File([blob], originalImageFile.name, {
                                    type: 'image/jpeg',
                                    lastModified: Date.now()
                                });
                                
                                // Update the file input
                                const dataTransfer = new DataTransfer();
                                dataTransfer.items.add(croppedFile);
                                $("#Image")[0].files = dataTransfer.files;
                                
                                // Close modal
                                bootstrap.Modal.getInstance(document.getElementById('cropModal')).hide();
                                
                                // Clean up cropper
                                if (cropper) {
                                    cropper.destroy();
                                    cropper = null;
                                }
                                
                                originalImageFile = null;
                                
                            }, 'image/jpeg', 0.9);
                        }
                    }
                });

                // Clean up when crop modal is closed
                $('#cropModal').on('hidden.bs.modal', function () {
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                    originalImageFile = null;
                });
            });

            function initializeCropper() {
                // Destroy previous cropper instance if exists
                if (cropper) {
                    cropper.destroy();
                }
                
                const image = document.getElementById('cropImage');
                
                if (image && image.src) {
                    cropper = new Cropper(image, {
                        aspectRatio: 1, // Square aspect ratio for profile image
                        minCropBoxWidth: 100,
                        minCropBoxHeight: 100,
                        viewMode: 2, // Restrict crop box to not go outside canvas
                        autoCropArea: 0.8,
                        responsive: true,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                        preview: '#preview',
                        ready: function() {
                            console.log('Cropper initialized');
                        }
                    });
                }
            }

            function initializeMemberFormValidation() {
                $("#MemberForm").validate({
                    rules: {
                        Mcode: {
                            required: true,
                            minlength: 5,
                            maxlength: 5,
                            pattern: /^[M]\d{4}$/
                        },
                        Mname: {
                            required: true,
                            pattern: /^[A-Za-z][A-Za-z ]{0,19}$/,
                            maxlength: 20
                        },
                        Number: {
                            required: true,
                            pattern: /^[0-9]{10}$/,
                            maxlength: 10
                        },
                        Email: {
                            required: true,
                            pattern: /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/,
                            maxlength: 30
                        },
                        DOB: {
                            required: true,
                            date: true,
                        },
                        Course: {
                            required: true
                        },
                        DOJ: {
                            required: true,
                            date: true,
                        },
                        Gender: {
                            required: true
                        }
                    },
                    messages: {
                        Mcode: { 
                            required: "Code is required", 
                            pattern: "Format: M1234 (M followed by 4 digits)", 
                            minlength: "Exactly 5 characters required", 
                            maxlength: "Exactly 5 characters required" 
                        }, 
                        Mname: { 
                            required: "Member name is required", 
                            pattern: "Only letters and spaces, starting with a letter", 
                            minlength: "Minimum 6 characters" 
                        },
                        Number: {
                            required: "Mobile number is required",
                            pattern: "10 digits only (e.g., 9345279246)",
                            maxlength: "10 digits only" 
                        },
                        Email: {
                            required: "Email is required",
                            pattern: "Valid email format (e.g., moon@gmail.com)",
                            maxlength: "Maximum 30 characters" 
                        },
                        DOB: {
                            required: "Date of birth is required"
                        },
                        DOJ: {
                            required: "Date of joining is required"
                        },
                        Course: {
                            required: "Course is required"
                        },
                        Gender: {
                            required: "Gender is required"
                        }
                    },
                    errorClass: "text-danger",
                    errorElement: "small",
                    submitHandler: function(form) {
                        return false; // Prevent auto-submit
                    }
                });
            }

            function saveMemberData() {
                var formData = new FormData($("#MemberForm")[0]);
                formData.append("educationData", JSON.stringify(educationData));
                
                $.ajax({
                    url: "Sam.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false, 
                    dataType: "json",
                    success: function(res){
                        if(res.status === 1 || res.status === 5){
                            alert(res.msg);           
                            Reset();
                            loadData();
                        } else {
                            alert(res.msg);          
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("Error saving data: " + error);
                    }
                });
            }

            /* ADD EDUCATION */
            function addEducation() {
                let Degree = $("#Degree").val();
                let Institute = $("#Institute").val();
                let RegNo = $("#RegNo").val();
                let Year = $("#Year").val();
                let Percentage = $("#Percentage").val();
                let ClassName = $("#Class").val();
                
                // Check if degree already exists
                let exists = educationData.some(e => e !== null && e.Degree === Degree);

                if (exists) {
                    alert(Degree + " already added");
                    return;
                }
                
                let index = educationData.length;

                educationData.push({
                    Degree,
                    Institute,
                    RegNo,
                    Year,
                    Percentage,
                    ClassName
                });

                $("#datatable tbody").append(`
                    <tr id="edu_${index}">
                        <td>${Degree}</td>
                        <td>${Institute}</td>
                        <td>${RegNo}</td>
                        <td>${Year}</td>
                        <td>${Percentage}</td>
                        <td>${ClassName}</td>
                        <td>
                            <button type="button"
                                    class="btn btn-danger btn-sm"
                                    onclick="deleteEdu(${index})">
                                Delete
                            </button>
                        </td>
                    </tr>
                `);

                // Reset education form only
                $("#EducationalForm")[0].reset();
                $("#Class").hide();
                $("#Classes").hide();
            }

            /* DELETE EDUCATION */
            function deleteEdu(index) {
                educationData[index] = null;
                $("#edu_" + index).remove();
            }

            function Reset(){
                $("#MemberForm")[0].reset();
                $("#EducationalForm")[0].reset();
                educationData = []; 
                $("#datatable tbody").empty();
                $("#Mid").val("");
                $("#PreviewImage").hide();
                $("#Class").hide();
                $("#Classes").hide();
                document.getElementById("Mcode").focus();
            }

            function loadData(){
                $.ajax({
                    url: "Member_Fetch.php",
                    success: function(data) {
                        $("#Member_Table").html(data);
                        $("#MemberTable").DataTable();
                        document.getElementById("Mcode").focus();
                    },
                    error: function() {
                        console.error("Error loading member data");
                    }
                });
            }  

            function confirmDelete(Mid){
                if(confirm("Delete this record?")){
                    $.ajax({
                        url:"Member_Delete.php",
                        type:"POST",
                        data:{Mid:Mid},
                        success:function(res){
                            alert(res);
                            loadData();
                        },
                        error: function() {
                            alert("Error deleting record");
                        }
                    });
                }
            }

            function confirmUpdate(Mid){
                document.getElementById("Mcode").focus();
                $.ajax({
                    url:"Member_Update.php",
                    type:"POST",
                    data:{Mid:Mid},
                    dataType: "json",
                    success:function(res){
                        // Populate member form
                        $('#Oldcode').val(res.Member_Code);
                        $('#Mid').val(res.Member_Id);
                        $('#Mcode').val(res.Member_Code);
                        $('#Mname').val(res.Member_Name);
                        $('#Number').val(res.Number);
                        $('#Email').val(res.Email);
                        $('#DOB').val(res.DOB);
                        $('#Course').val(res.Course_Id);
                        $('#DOJ').val(res.DOJ);
                        $('#Gender').val(res.Gender);
                        
                        if(res.Image && res.Image !== ""){
                            $("#PreviewImage").attr("src", "Images/" + res.Image).show();
                        } else {
                            $("#PreviewImage").hide();
                        }
                        
                        // Clear existing education data
                        educationData = [];
                        $("#datatable tbody").empty();
                        
                        // Check if education data exists and populate
                        if(res.education && res.education.length > 0){
                            res.education.forEach(function(edu, index){
                                educationData.push({
                                    Degree: edu.Degree,
                                    Institute: edu.Institute,
                                    RegNo: edu.RegNo,
                                    Year: edu.Year,
                                    Percentage: edu.Percentage,
                                    ClassName: edu.Class || ""
                                });
                                
                                $("#datatable tbody").append(`
                                    <tr id="edu_${index}">
                                        <td>${edu.Degree}</td>
                                        <td>${edu.Institute}</td>
                                        <td>${edu.RegNo}</td>
                                        <td>${edu.Year}</td>
                                        <td>${edu.Percentage}</td>
                                        <td>${edu.Class || ""}</td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="deleteEdu(${index})">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                `);
                            });
                        }
                        
                        // Show/hide Class field based on first degree
                        if (educationData.length > 0 && ["UG", "PG", "Diploma"].includes(educationData[0].Degree)) {
                            $("#Classes").show();
                            $("#Class").show();
                        } else {
                            $("#Class").hide();
                            $("#Classes").hide();
                        }
                    },
                    error: function() {
                        alert("Error loading member data for update");
                    }
                });
            }

            function confirmView(Mid){
                var myModal = new bootstrap.Modal(document.getElementById('educationModal'));
                myModal.show();
                $.ajax({
                    url: "Member_View.php",
                    type: "POST",
                    data: {Mid: Mid},
                    success: function(data) {
                        $("#educationModalBody").html(data);
                    },
                    error: function() {
                        $("#educationModalBody").html(
                            '<div class="alert alert-danger">Error loading educational details.</div>'
                        );
                    }
                });
            }
        </script>
        <script src="../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>