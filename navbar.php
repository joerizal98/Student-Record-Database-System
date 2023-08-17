<?php
include 'db.php';
include 'session.php';

// Check if the search query is submitted
if (isset($_POST['searchInput'])) {
    // ... (existing code for search query)
}
?>

<style>
    .welcome-message {
        font-size: 18px;
        font-weight: bold;
        color: #fff;
        text-transform: uppercase;
        margin-right: 10px;
    }
</style>

<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-1" href="">
        <?php
        if (isset($_SESSION['admin'])) {
            echo '<span class="welcome-message">Welcome Admin</span>';
        } elseif (isset($_SESSION['username2'])) {
        $username = $_SESSION['username2'];
        echo '<span class="welcome-message">Welcome Teacher:</span> ' . $username;
        } elseif (isset($_SESSION['username3'])) {
           $username = $_SESSION['username3'];
            echo '<span class="welcome-message">Welcome Recruiter:</span> ' . $username;
        } elseif (isset($_SESSION['username1'])) {
            $username = $_SESSION['username1'];
            echo '<span class="welcome-message">Welcome Student:</span> ' . $username;
        } else {
            echo '<span class="welcome-message">Welcome</span>';
        }
        ?>
    </a>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" method="post" action="">
        <!-- ... (existing code for search form) -->
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <?php
        if (isset($_SESSION['username1'])) {
            // Display "Profile" button for students
            echo '<li class="nav-item dropdown">';
            echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i> Profile</a>';
            echo '<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">';
            echo '<li><a class="dropdown-item" href="studentprofile.php">View Profile</a></li>';
            echo '<li><hr class="dropdown-divider"></li>';
            echo '<li><form method="post"><button class="dropdown-item" type="submit" name="logout">Logout</button></form></li>';
            echo '</ul>';
            echo '</li>';
        } elseif (isset($_SESSION['username2'])) {
            // Display "Profile" button for teachers
          echo '<li class="nav-item">';
            echo '<form method="post">';
            echo '<button class="btn btn-primary" type="submit" name="logout">Logout</button>';
            echo '</form>';
            echo '</li>';
        } elseif (isset($_SESSION['username3'])) {
            // Display "Profile" button for recruiters
             echo '<li class="nav-item">';
            echo '<form method="post">';
            echo '<button class="btn btn-primary" type="submit" name="logout">Logout</button>';
            echo '</form>';
            echo '</li>';
        } elseif (isset($_SESSION['admin'])) {
            // Display "Logout" button for admins
            echo '<li class="nav-item">';
            echo '<form method="post">';
            echo '<button class="btn btn-primary" type="submit" name="logout">Logout</button>';
            echo '</form>';
            echo '</li>';
        }
        ?>
    </ul>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
</nav>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>

<script>
    // Show dropdown on hover
    $('.dropdown').hover(
        function () {
            $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
        },
        function () {
            $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
        }
    );
</script>
