<?php
// getenrolledstudents.php

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the course ID from the AJAX request
    $courseID = $_POST['courseID'];

    // Query to fetch the total number of enrolled students
    $query = "SELECT COUNT(*) AS totalStudents FROM coursestudent WHERE CourseID = '$courseID'";
    $result = mysqli_query($link, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $totalStudents = $row['totalStudents'];

        // Return the total number of enrolled students as the response
        echo $totalStudents;
    } else {
        // Return an error message if the query fails
        echo 'Error fetching enrolled students.';
    }
} else {
    // Return an error message for invalid request method
    echo 'Invalid request.';
}
?>
