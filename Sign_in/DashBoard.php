<?php include "../includes/Session_con.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>DashBoard</title>
    <?php include "../includes/template.php"; ?>
    <script src="../jquery-3.7.1.min/jquery-3.7.1.min.js"></script>
    <link href="../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../bootstrap-icons-1.13.1/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Custom styling for dashboard */
        body {
            background-color: #f8f9fa;
        }
        
        .container {
            margin-top: 20px;
            padding: 20px;
        }
        
        .dashboard-header {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .dashboard-header h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .stats-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            text-align: center;
        }
        
        .stats-card i {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: #3498db;
        }
        
        .stats-card h3 {
            font-size: 2rem;
            color: #2c3e50;
            margin: 10px 0;
        }
        
        .stats-card p {
            color: #666;
            margin: 0;
        }
    </style>
</head>
<body>

<div id="page-content" class="container">
    <div class="dashboard-header">
        <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
        <p class="text-muted">Welcome back! Here's an overview of your data.</p>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="stats-card">
                <i class="bi bi-people"></i>
                <h3 id="total-members">0</h3>
                <p>Total Members</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stats-card">
                <i class="bi bi-book"></i>
                <h3 id="total-courses">0</h3>
                <p>Total Courses</p>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    function loadData(){
        $.ajax({
            url: "DashBoard_Fetch.php",
            type: "GET",
            dataType: "json",
            success: function(response) {
                    $("#total-members").text(response.total_members);
                    $("#total-courses").text(response.total_courses);
            },
        });
    }
    loadData();
});
</script>

<script src="../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>