<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "session.php";
include "db.php";

// Check if the admin is logged in
checkAdminLogin();

// Refresh student list
if (isset($_POST['refresh'])) {
    // Fetch the updated list of students from the database
    $student_sql = "SELECT s.StudentID, CONCAT(s.FirstName, ' ', s.LastName) AS StudentName, c.CourseID, c.CourseName, cs.Approval
                    FROM student AS s
                    JOIN coursestudent AS cs ON s.StudentID = cs.StudentID
                    JOIN course AS c ON c.CourseID = cs.CourseID";
    $student_result = $link->query($student_sql);
} else {
    // Get initial list of students
    $student_sql = "SELECT s.StudentID, CONCAT(s.FirstName, ' ', s.LastName) AS StudentName, c.CourseID, c.CourseName, cs.Approval
                    FROM student AS s
                    JOIN coursestudent AS cs ON s.StudentID = cs.StudentID
                    JOIN course AS c ON c.CourseID = cs.CourseID";
    $student_result = $link->query($student_sql);
}

// Delete approval status
if (isset($_POST['delete_approval'])) {
    $student_course = $_POST['delete_student_course'];
    $student_course_parts = explode('|', $student_course);
    $student_id = $student_course_parts[0];
    $course_id = $student_course_parts[1];

    // Delete the student-course relationship from the database
    $delete_sql = "DELETE FROM coursestudent WHERE StudentID = '$student_id' AND CourseID = '$course_id'";
    if ($link->query($delete_sql) === TRUE) {
        // Success message
        $success_message = "Approval deleted successfully";
    } else {
        // Error message
        $error_message = "Error deleting approval: " . $link->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <?php include 'navbar.php'; ?>
    <div id="layoutSidenav">
        <?php include 'sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Course Student Approval</h1>
                    <br>
                    <form method="post" action="">
                        <!-- Add student form fields -->
                    </form>

                    <h2 class="mt-4">Student List</h2>

                    <form method="post" action="">
                        <label for="delete_student_course">Delete Approval:</label>
                        <select name="delete_student_course" id="delete_student_course">
                            <?php
                            $delete_student_sql = "SELECT s.StudentID, CONCAT(s.FirstName, ' ', s.LastName) AS StudentName, c.CourseID, c.CourseName
                                                    FROM student AS s
                                                    JOIN coursestudent AS cs ON s.StudentID = cs.StudentID
                                                    JOIN course AS c ON c.CourseID = cs.CourseID";
                            $delete_student_result = $link->query($delete_student_sql);

                            if ($delete_student_result->num_rows > 0) {
                                while ($row = $delete_student_result->fetch_assoc()) {
                                    $student_id = $row['StudentID'];
                                    $student_name = $row['StudentName'];
                                    $course_id = $row['CourseID'];
                                    $course_name = $row['CourseName'];

                                    echo "<option value='$student_id|$course_id'>$student_name - $course_name</option>";
                                }
                            }
                            ?>
                        </select>
                        <button type="submit" name="delete_approval">Delete</button>
                    </form>
                    <br>

                    <form method="post" action="">
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Course ID</th>
                                    <th>Course Name</th>
                                    <th>Approval Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($student_result->num_rows > 0) {
                                    while ($row = $student_result->fetch_assoc()) {
                                        $student_id = $row['StudentID'];
                                        $student_name = $row['StudentName'];
                                        $course_id = $row['CourseID'];
                                        $course_name = $row['CourseName'];
                                        $approval_status = $row['Approval'];

                                        echo "<tr>
                                                <td>$student_id</td>
                                                <td>$student_name</td>
                                                <td>$course_id</td>
                                                <td>$course_name</td>
                                                <td>
                                                    <select name='approval_status[]'>
                                                        <option value='pending' " . ($approval_status == 'pending' ? 'selected' : '') . ">Pending</option>
                                                        <option value='approved' " . ($approval_status == 'approved' ? 'selected' : '') . ">Approved</option>
                                                        <option value='rejected' " . ($approval_status == 'rejected' ? 'selected' : '') . ">Rejected</option>
                                                        <option value='' " . ($approval_status == '' ? 'selected' : '') . ">-</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type='hidden' name='student_id[]' value='$student_id'>
                                                    <input type='hidden' name='course_id[]' value='$course_id'>
                                                    <button type='submit' name='save_approval'>Save</button>
                                                </td>
                                            </tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <button type="submit" name="refresh">Refresh List</button>
                    </form>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/script.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>

<?php
// Save approval status
if (isset($_POST['save_approval'])) {
    $student_ids = $_POST['student_id'];
    $course_ids = $_POST['course_id'];
    $approval_statuses = $_POST['approval_status'];

    for ($i = 0; $i < count($student_ids); $i++) {
        $student_id = $student_ids[$i];
        $course_id = $course_ids[$i];
        $approval_status = $approval_statuses[$i];

        // Update the approval status in the database
        $update_sql = "UPDATE coursestudent SET Approval = '$approval_status' WHERE StudentID = '$student_id' AND CourseID = '$course_id'";
        if ($link->query($update_sql) === TRUE) {
            // Success message
            $success_message = "Approval status updated successfully";
        } else {
            // Error message
            $error_message = "Error updating approval status: " . $link->error;
        }
    }

    // Redirect or display success message
    if (isset($success_message)) {
        // Redirect to another page
        header("Location: managestudent.php?success_message=" . urlencode($success_message));
        exit();
    } elseif (isset($error_message)) {
        // Display error message
        echo "<script>alert('$error_message');</script>";
    }
}

$link->close();
?>
