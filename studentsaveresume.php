<?php
include 'db.php';
include 'session.php';

// Check if the student is logged in
checkStudentLogin();

$studentID = $_SESSION['studentID'];
$sectionID = $_GET['sectionID'];
$content = $_GET['content'];

// Check if the student has an existing eResume record
$query = "SELECT * FROM eResume WHERE StudentID = '$studentID'";
$result = mysqli_query($link, $query);

if (mysqli_num_rows($result) > 0) {
    // Update the existing eResume record with the new content
    $updateQuery = "UPDATE eResume SET $sectionID = '$content' WHERE StudentID = '$studentID'";
    $updateResult = mysqli_query($link, $updateQuery);

    if ($updateResult) {
        // Content updated successfully
        http_response_code(200);
    } else {
        // Failed to update content
        http_response_code(500);
    }
} else {
    // Insert a new eResume record for the student
    $insertQuery = "INSERT INTO eResume (StudentID, $sectionID) VALUES ('$studentID', '$content')";
    $insertResult = mysqli_query($link, $insertQuery);

    if ($insertResult) {
        // Content inserted successfully
        http_response_code(200);
    } else {
        // Failed to insert content
        http_response_code(500);
    }
}

// Close database connection
mysqli_close($link);
?>
