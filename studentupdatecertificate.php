<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';
include 'session.php';

// Check if the student is logged in
checkStudentLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $certificateId = $_POST['certificateId'];
    $newDescription = $_POST['newDescription'];

    // Update the certificate description in the database
    $updateQuery = "UPDATE certificate SET Description = '$newDescription' WHERE CertificateID = '$certificateId'";
    $updateResult = mysqli_query($link, $updateQuery);

    if ($updateResult) {
        // Check if a new certificate image file is uploaded
        if (isset($_FILES['newCertificate']) && $_FILES['newCertificate']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['newCertificate']['tmp_name'];
            $fileName = $_FILES['newCertificate']['name'];
            $fileSize = $_FILES['newCertificate']['size'];
            $fileType = $_FILES['newCertificate']['type'];

            // Validate the file type (assuming only JPEG images are allowed)
            if ($fileType === 'image/jpeg') {
                // Read the contents of the uploaded file
                $certificateData = file_get_contents($fileTmpPath);

                // Update the certificate image in the database
                $updateImageQuery = "UPDATE certificate SET Certificate = ? WHERE CertificateID = '$certificateId'";
                $stmt = mysqli_prepare($link, $updateImageQuery);
                mysqli_stmt_bind_param($stmt, 's', $certificateData);
                $updateImageResult = mysqli_stmt_execute($stmt);

                if ($updateImageResult) {
                    // Successful update
                    echo "<script>alert('Successfully Updated'); window.location.href = 'studentcertificate.php';</script>";
                } else {
                    // Failed to update
                    echo 'error';
                }
            } else {
                // Invalid file type
                echo 'invalid_file_type';
            }
        } else {
            // Successful update
            echo "<script>alert('Successfully Updated'); window.location.href = 'studentcertificate.php';</script>"; 
        }
    } else {
        // Failed to update
        echo 'error';
    }
}
?>
