<?php
include "session.php";
include "db.php";

// Check if the student is logged in
checkStudentLogin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected course ID from the form
    $courseID = $_POST['courseSelect'];

    // Get the student ID from the session
    $studentID = $_SESSION['studentID'];

   // Check if the teacher is already enrolled in the selected course
    $enrollmentCheckQuery = "SELECT * FROM coursestudent WHERE CourseID = '$courseID' AND StudentID = '$studentID'";
    $enrollmentCheckResult = mysqli_query($link, $enrollmentCheckQuery);

    if (mysqli_num_rows($enrollmentCheckResult) > 0) {
        // Teacher is already enrolled in the course
        $message = "You are already enrolled in this course.";
    } else {
        // Insert a new enrollment record into the database with the current datetime and 'pending' approval status
            $enrollDate = date("Y-m-d H:i:s"); // Get the current datetime
            $enrollQuery = "INSERT INTO coursestudent (CourseID, StudentID, EnrollDate, Approval) VALUES ('$courseID', '$studentID', '$enrollDate', 'pending')";
            $enrollResult = mysqli_query($link, $enrollQuery);


        if ($enrollResult) {
            $message = "Enrollment successful. Please wait the admin to approve the request.";
        } else {
            $message = "Enrollment failed. Please try again.";
        }
    }

    // Display the message in a JavaScript alert and redirect
    echo "<script>alert('$message'); window.location.href = 'studentindex.php';</script>";
}
?>
