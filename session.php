<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Set the session name
/*define('SESSION_NAME', 'PHPSESSID'); */

// Include the session configuration file
require_once 'sessionsave.php';

// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to check if the admin is logged in
if (!function_exists('checkAdminLogin')) {
    function checkAdminLogin() {
        if (empty($_SESSION['admin'])) {
            // Admin is not logged in, redirect to login page
            header("Location:login.php");
            exit();
        }
    }
}

// Check if the user is logged in as a teacher
if (!function_exists('checkTeacherLogin')) {
    function checkTeacherLogin() {
         if (!isset($_SESSION['teacherID']) || !isset($_SESSION['username2'])) {
            // Teacher is not logged in, redirect to login page or display an error message
            header("Location: login.php");
            exit();
        }
    }
}

// Check if the user is logged in as a recruiter
if (!function_exists('checkRecruiterLogin')) {
    function checkRecruiterLogin() {
       if (!isset($_SESSION['recruiterID']) || !isset($_SESSION['username3'])) {
            // Recruiter is not logged in, redirect to login page or display an error message
            header("Location: login.php");
            exit();
        }
    }
}

// Check if the student is logged in
if (!function_exists('checkStudentLogin')) {
    function checkStudentLogin() {
        if (!isset($_SESSION['studentID']) || !isset($_SESSION['username1'])) {
            // Redirect the user to the login page or display an error message
            header("Location: login.php");
            exit();
        }
    }
}



// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
