<?php
include "session.php";
include "db.php";

// Check if the teacher is logged in
checkTeacherLogin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected course ID from the form
    $courseID = $_POST['courseSelect'];

    // Get the teacher ID from the session
    $teacherID = $_SESSION['teacherID'];

    // Check if the teacher is already enrolled in the selected course
    $enrollmentCheckQuery = "SELECT * FROM TeacherCourse WHERE CourseID = '$courseID' AND TeacherID = '$teacherID'";
    $enrollmentCheckResult = mysqli_query($link, $enrollmentCheckQuery);

    if (mysqli_num_rows($enrollmentCheckResult) > 0) {
        // Teacher is already enrolled in the course
        $message = "You are already enrolled in this course.";
    } else {
        // Insert a new enrollment record into the database with the current datetime and 'pending' approval status
        $enrollQuery = "INSERT INTO TeacherCourse (CourseID, TeacherID, Approval) VALUES ('$courseID', '$teacherID', 'pending')";
        $enrollResult = mysqli_query($link, $enrollQuery);

        if ($enrollResult) {
            $message = "Enrollment successful. Please inform the admin to approve the request.";
        } else {
            $message = "Enrollment failed. Please try again.";
        }
    }

    // Display the message in a JavaScript alert and redirect
    echo "<script>alert('$message'); window.location.href = 'teacherviewcourse.php';</script>";
}
?>
