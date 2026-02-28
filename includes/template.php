<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>DashBoard</title>
<link href="../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../bootstrap-icons-1.13.1/bootstrap-icons.css" rel="stylesheet">
<style>
    /* Sidebar width - DECREASED from 250px to 220px */
    #desktop-sidebar {
        width: 220px;
        background-color: #212529;
        color: white;
        transition: all 0.3s;
    }

    /* Desktop: fixed sidebar and page content pushed */
    @media (min-width: 992px) {
        #desktop-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
        }
        #page-content {
            margin-left: 220px; /* Updated to match sidebar width */
            transition: margin-left 0.3s;
            margin-top: 56px
        }
        
    }

    /* Sidebar nav links */
    .nav-link i {
        margin-right: 10px;
    }

    /* Mobile close button larger */
    .offcanvas-header .btn-close {
        width: 2.5rem;
        height: 2.5rem;
    }

    /* Optional: hide desktop sidebar when collapsed */
    .collapsed-sidebar {
        width: 0 !important;
        overflow: hidden;
    }
    
    /* Sidebar content padding adjustments */
    #desktop-sidebar .d-flex {
        padding: 10px 15px;
    }
    
    #desktop-sidebar .nav {
        padding: 0 15px;
    }
    
    /* Smaller font size for sidebar items */
    #desktop-sidebar .nav-link {
        font-size: 0.95rem;
        padding: 8px 12px;
        margin-bottom: 5px;
        border-radius: 5px;
    }
    
    /* Active state styling */
    #desktop-sidebar .nav-link.active {
        background-color: #495057;
        font-weight: 600;
    }
    
    /* Hover effect */
    #desktop-sidebar .nav-link:hover:not(.active) {
        background-color: #343a40;
    }
</style>
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <!-- Hamburger toggle -->
        <button class="btn btn-outline-light" id="toggleSidebar">
            ☰
        </button>
        <a class="nav-link text-white <?= ($currentPage == 'DashBoard.php') ? 'active' : '' ?>"
                href="../Sign_in/DashBoard.php">
            <span class="navbar-brand ms-2">Home</span>
        </a>
    </div>
</nav>

<!-- Desktop Sidebar -->
<div id="desktop-sidebar" class="d-none d-lg-block bg-dark text-white">
    <div class="d-flex justify-content-end p-2">
        <!-- Close button for desktop sidebar -->
        <button class="btn btn-outline-light btn-sm" id="closeSidebar">☰</button>
    </div>
        <ul class="nav nav-pills flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage == 'DashBoard.php') ? 'active' : '' ?>"
                href="../Sign_in/DashBoard.php">
                    <i class="bi bi-house"></i> Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage == 'course_details.php') ? 'active' : '' ?>"
                href="../Course/course_details.php">
                    <i class="bi bi-book"></i> Course
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage == 'member_details.php') ? 'active' : '' ?>"
                href="../Member/member_details.php">
                    <i class="bi bi-person"></i> Member
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage == 'Report_details.php') ? 'active' : '' ?>"
                href="../Report/Report_details.php">
                    <i class="bi bi-file-earmark-text"></i> Report <!-- Changed icon for Report -->
                </a>
            </li>
            <?php
                // Ensure session is started
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                // Check if user is logged in AND is admin (UserType = 1)
                if (isset($_SESSION['UserType']) && $_SESSION['UserType'] == 1):
            ?>
                <li class="nav-item">
                    <a class="nav-link text-white <?= ($currentPage == 'AddUser.php') ? 'active' : '' ?>" href="../Admin/AddUser.php">
                        <i class="bi bi-person-circle"></i> Admin
                    </a>
                </li>
            <?php endif; ?>
            <li class="nav-item mt-3">
                <a class="nav-link text-danger <?= ($currentPage == '../Sign_in/Sign_out.php') ? 'active' : '' ?>" href="../Sign_in/Sign_out.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>

</div>

<!-- Mobile Sidebar (offcanvas) -->
<div class="offcanvas offcanvas-start bg-dark text-white d-lg-none" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        <h5 class="offcanvas-title ms-2">Menu</h5>
    </div>
    <div class="offcanvas-body p-0">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage == 'DashBoard.php') ? 'active' : '' ?>"
                href="../Sign_in/DashBoard.php">
                    <i class="bi bi-house"></i> Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage == 'course_details.php') ? 'active' : '' ?>"
                href="../Course/course_details.php">
                    <i class="bi bi-book"></i> Course
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage == 'member_details.php') ? 'active' : '' ?>"
                href="../Member/member_details.php">
                    <i class="bi bi-person"></i> Member
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage == 'Report_details.php') ? 'active' : '' ?>"
                href="../Report/Report_details.php">
                    <i class="bi bi-file-earmark-text"></i> Report <!-- Changed icon for Report -->
                </a>
            </li>
           <?php
// Ensure session is started
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                // Check if user is logged in AND is admin (UserType = 1)
                if (isset($_SESSION['UserType']) && $_SESSION['UserType'] == 1):
            ?>
                <li class="nav-item">
                    <a class="nav-link text-white <?= ($currentPage == 'AddUser.php') ? 'active' : '' ?>" href="../Admin/AddUser.php">
                        <i class="bi bi-person-circle"></i> Admin
                    </a>
                </li>
            <?php endif; ?>
            <li class="nav-item mt-3">
                <a class="nav-link text-danger <?= ($currentPage == '../Sign_in/Sign_out.php') ? 'active' : '' ?>" href="../Sign_in/Sign_out.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>

    </div>
</div>

<!-- Page Content -->


<script src="../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('desktop-sidebar');
    const pageContent = document.getElementById('page-content');

    // Toggle sidebar visibility on desktop
    document.getElementById('toggleSidebar').addEventListener('click', () => {
        sidebar.classList.toggle('collapsed-sidebar');
        if(sidebar.classList.contains('collapsed-sidebar')){
            pageContent.style.marginLeft = '0';
        } else {
            pageContent.style.marginLeft = '220px'; // Updated to match sidebar width
        }
    });

    // Close sidebar button for desktop
    document.getElementById('closeSidebar').addEventListener('click', () => {
        sidebar.classList.add('collapsed-sidebar');
        pageContent.style.marginLeft = '0';
    });
</script>
</body>
</html>