<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "db.php";
include "session.php";

// Check if the teacher is logged in
checkTeacherLogin();

// Get the logged-in teacher's ID
$teacherID = $_SESSION['teacherID'];

// Initialize variables
$recordType = '';
$dateTime = '';
$description = '';
$studentID = '';
$courseID = '';

// Get the list of students enrolled in the teacher's courses
$studentQuery = "SELECT DISTINCT s.StudentID, s.FirstName
                FROM student s
                INNER JOIN coursestudent cs ON s.StudentID = cs.StudentID
                INNER JOIN teachercourse tc ON cs.CourseID = tc.CourseID
                WHERE tc.TeacherID = '$teacherID'";
$studentResult = mysqli_query($link, $studentQuery);


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $recordType = $_POST['recordType'];
        $dateTime = $_POST['dateTime'];
        $description = $_POST['description'];
        $studentID = $_POST['studentID'];
        $courseID = $_POST['courseID'];

        // Validate form data (you can add more validation if needed)
        if (empty($recordType) || empty($dateTime) || empty($description) || empty($studentID) || empty($courseID)) {
                $addError = "Please fill in all the fields.";
        } else {
                // Prepare the insert statement
                $stmt = mysqli_prepare($link, "INSERT INTO record (RecordType, DateTime, Description, StudentID, CourseID, TeacherID) VALUES (?, ?, ?, ?, ?, ?)");

                // Bind parameters to the statement
                mysqli_stmt_bind_param($stmt, 'ssssss', $recordType, $dateTime, $description, $studentID, $courseID, $teacherID);

                // Execute the statement
                if (mysqli_stmt_execute($stmt)) {
                        $addSuccess = true;
                } else {
                        $addError = "Failed to add record. Please try again.";
                }

                // Close the statement
                mysqli_stmt_close($stmt);
        }
}

// Close the database connection
mysqli_close($link);
?>


<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Add Record</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.4/dist/umd/popper.min.js" integrity="sha384-Zv4DcI1mSb6MRA9OpQJ6/lCxp2zmMTHsARj99RphglAHxOPnX9Bf8CHg1IDfr05Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
        <?php include "navbar.php"; ?>
        <div id="layoutSidenav">
                <?php include "sidebar.php"; ?>
                <div id="layoutSidenav_content">
                        <main>
                                <div class="container">
                                        <div class="row justify-content-center">
                                                <div class="col-lg-6">
                                                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                                                                <div class="card-header">
                                                                        <h3 class="text-center font-weight-light my-4">Add Record</h3>
                                                                </div>
                                                                <div class="card-body">
                                                                        <?php if (isset($addSuccess)) { ?>
                                                                                <div class="alert alert-success" role="alert">
                                                                                        Record added successfully!
                                                                                </div>
                                                                        <?php } elseif (isset($addError)) { ?>
                                                                                <div class="alert alert-danger" role="alert">
                                                                                        <?php echo $addError; ?>
                                                                                </div>
                                                                        <?php } ?>
                                                                        <form method="POST">
                                                                                <div class="form-floating mb-3">
                                                                                        <select class="form-select" id="recordType" name="recordType" required>
                                                                                                <option value="">Select Record Type</option>
                                                                                                <option value="Attendance">Attendance</option>
                                                                                                <option value="Grades">Grades</option>
                                                                                                <option value="Behavior">Behavior</option>
                                                                                        </select>
                                                                                        <label for="recordType">Record Type</label>
                                                                                </div>
                                                                                <div class="form-floating mb-3">
                                                                                        <input class="form-control" id="dateTime" name="dateTime" type="datetime-local" required />
                                                                                        <label for="dateTime">Date and Time</label>
                                                                                </div>
                                                                                <div class="form-floating mb-3">
                                                                                        <textarea class="form-control" id="description" name="description" required></textarea>
                                                                                        <label for="description">Description</label>
                                                                                </div>
                                                                             <div class="form-floating mb-3">
        <select class="form-select" id="studentID" name="studentID" required>
                <option value="">Select Student</option>
                <?php
                while ($studentRow = mysqli_fetch_assoc($studentResult)) {
                        $studentID = $studentRow['StudentID'];
                        $studentName = $studentRow['FirstName'];
                        echo "<option value='$studentID'>$studentName ($studentID)</option>";
                }
                ?>
        </select>
        <label for="studentID">Student</label>
</div>
<div class="form-floating mb-3">
        <select class="form-select" id="courseID" name="courseID" required>
                <option value="">Select Course</option>
                <?php
                if (!empty($studentID)) {
                        $studentCourseQuery = "SELECT c.CourseID, c.CourseName
                                                                    FROM course c
                                                                    INNER JOIN coursestudent cs ON c.CourseID = cs.CourseID
                                                                    INNER JOIN teachercourse tc ON c.CourseID = tc.CourseID
                                                                    WHERE cs.StudentID = '$studentID' AND tc.TeacherID = '$teacherID'";
                        $studentCourseResult = mysqli_query($link, $studentCourseQuery);
                        while ($courseRow = mysqli_fetch_assoc($studentCourseResult)) {
                                $courseID = $courseRow['CourseID'];
                                $courseName = $courseRow['CourseName'];
                                echo "<option value='$courseID'>$courseName ($courseID)</option>";
                        }
                }
                ?>
        </select>
        <label for="courseID">Course</label>
</div>

                                                                                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                                                        <button class="btn btn-primary" type="submit">Add Record</button>
                                                                                </div>
                                                                        </form>
                                                                </div>
                                                               <!-- <div class="card-footer text-center py-3">
                                                                        <div class="small"><a href="index.php">Back to Dashboard</a></div>
                                                                </div>-->
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        </main>
                        <?php include "footer.php"; ?>
                </div>
        </div>
</body>
</html>
