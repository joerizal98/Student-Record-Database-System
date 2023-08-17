<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'session.php';
require_once 'db.php';

// Start the session
/*session_start();  */

// Check if the form is submitted
if (isset($_POST['uname']) && isset($_POST['pass'])) {
    $username = $_POST['uname'];
    $password = $_POST['pass'];

    // Validate login credentials based on user role
    $teacherQuery = "SELECT * FROM teacher WHERE UserName = '$username' AND Password = '$password' AND Approval = 'approved'";
    $studentQuery = "SELECT * FROM student WHERE UserName = '$username' AND Password = '$password' AND Approval = 'approved'";
    $adminQuery = "SELECT * FROM admin WHERE UserName = '$username' AND Password = '$password'";
    $recruiterQuery = "SELECT * FROM recruiter WHERE UserName = '$username' AND Password = '$password' AND Approval = 'approved'";

    // Perform the database queries
    $teacherResult = mysqli_query($link, $teacherQuery);
    $studentResult = mysqli_query($link, $studentQuery);
    $adminResult = mysqli_query($link, $adminQuery);
    $recruiterResult = mysqli_query($link, $recruiterQuery);

    // Check if any query returned a result
    if (mysqli_num_rows($teacherResult) == 1) {
        // Password is correct for teacher role
        $teacherData = mysqli_fetch_assoc($teacherResult);
        $_SESSION['teacherID'] = $teacherData['TeacherID'];
        $_SESSION['username2'] = $username;
        echo "<script>alert('You logged in as a teacher'); window.location.href = 'teacherindex.php';</script>";
        exit();
    } elseif (mysqli_num_rows($studentResult) == 1) {
        // Password is correct for student role
        $studentData = mysqli_fetch_assoc($studentResult);
        $_SESSION['studentID'] = $studentData['StudentID'];
        $_SESSION['username1'] = $username;
        echo "<script>alert('You logged in as a student'); window.location.href = 'studentindex.php';</script>";
        exit();
    } elseif (mysqli_num_rows($adminResult) == 1) {
        // Password is correct for admin role
        $_SESSION['admin'] = true;
        echo "<script>alert('You logged in as an admin'); window.location.href = 'adminindex.php';</script>";
        exit();
    } elseif (mysqli_num_rows($recruiterResult) == 1) {
        // Password is correct for recruiter role
        $recruiterData = mysqli_fetch_assoc($recruiterResult);
        $_SESSION['recruiterID'] = $recruiterData['RecruiterID'];
        $_SESSION['username3'] = $username;
        echo "<script>alert('You logged in as a recruiter'); window.location.href = 'recruiterindex.php';</script>";
        exit();
    } else {
        // Invalid username or password
        $error = "Invalid username or password";
    }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <!--<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>-->
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!--===============================================================================================-->
</head>
<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="images/img-01.png" alt="IMG">
                </div>

                <form class="login100-form validate-form" method="POST" action="">
                    <span class="login100-form-title">
                       User Login
                    </span>

                    <div class="wrap-input100 validate-input" data-validate="Username is required">
                        <input class="input100" type="text" name="uname" placeholder="Username">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="pass" placeholder="Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            Login
                        </button>
                    </div>

                    <?php if (isset($error)) { ?>
                        <p><?php echo $error; ?></p>
                    <?php } ?>

                    <div class="text-center p-t-12">
                        <span class="txt1">
                            Home
                        </span>
                        <a class="txt2" href="index.php">
                            Page
                        </a>
                    </div>

                    <div class="text-center p-t-136">
                        <a class="txt2" href="register.php">
                            Create your Account
                            <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--===============================================================================================-->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/tilt/tilt.jquery.min.js"></script>
    <script>
        $('.js-tilt').tilt({
            scale: 1.1
        })
    </script>
    <!--===============================================================================================-->
    <script src="js/main.js"></script>
</body>
</html>
