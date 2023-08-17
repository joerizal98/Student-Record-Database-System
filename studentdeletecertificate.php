<?php
include 'db.php';
include 'session.php';

// Check if the student is logged in
checkStudentLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $certificateId = $_POST['certificateId'];

    // Delete the certificate from the database
    $deleteQuery = "DELETE FROM certificate WHERE CertificateID = '$certificateId'";
    $deleteResult = mysqli_query($link, $deleteQuery);

    if ($deleteResult) {
        // Certificate deleted successfully
        // You can redirect the user to a success page or perform any other desired actions
        header("Location: studentcertificate.php");
        exit();
    } else {
        // Error occurred while deleting the certificate
        // You can redirect the user to an error page or perform any other desired actions
        header("Location: error.php");
        exit();
    }
} else {
    // If the request method is not POST, redirect the user to an error page or perform any other desired actions
    header("Location: error.php");
    exit();
}
