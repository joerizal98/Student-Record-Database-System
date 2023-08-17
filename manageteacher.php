<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "session.php";
include "db.php";

// Check if the admin is logged in
checkAdminLogin();
// Refresh teacher list
if (isset($_POST['refresh'])) {
    // Fetch the updated list of teachers from the database
    $teacher_sql = "SELECT t.TeacherID, CONCAT(t.FirstName, ' ', t.LastName) AS TeacherName, c.CourseID, c.CourseName, tc.Approval
                    FROM teacher AS t
                    JOIN teachercourse AS tc ON t.TeacherID = tc.TeacherID
                    JOIN course AS c ON c.CourseID = tc.CourseID";
    $teacher_result = $link->query($teacher_sql);
} else {
    // Get initial list of teachers
    $teacher_sql = "SELECT t.TeacherID, CONCAT(t.FirstName, ' ', t.LastName) AS TeacherName, c.CourseID, c.CourseName, tc.Approval
                    FROM teacher AS t
                    JOIN teachercourse AS tc ON t.TeacherID = tc.TeacherID
                    JOIN course AS c ON c.CourseID = tc.CourseID";
    $teacher_result = $link->query($teacher_sql);
}

// Delete approval status
if (isset($_POST['delete_approval'])) {
    $teacher_course = $_POST['delete_teacher_course'];
    $teacher_course_parts = explode('|', $teacher_course);
    $teacher_id = $teacher_course_parts[0];
    $course_id = $teacher_course_parts[1];

    // Delete the teacher-course relationship from the database
    $delete_sql = "DELETE FROM teachercourse WHERE TeacherID = '$teacher_id' AND CourseID = '$course_id'";
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
                    <h1 class="mt-4">Teacher Course Approval</h1>
                    <br>
                    <form method="post" action="">
                        <!-- Add teacher form fields -->
                    </form>

                   <!-- <h2 class="mt-4">Teacher List</h2> -->
                       <form method="post" action="">
                        <label for="delete_teacher_course">Delete Approval:</label>
                        <select name="delete_teacher_course" id="delete_teacher_course">
                            <?php
                            $delete_teacher_sql = "SELECT t.TeacherID, CONCAT(t.FirstName, ' ', t.LastName) AS TeacherName, c.CourseID, c.CourseName
                                                    FROM teacher AS t
                                                    JOIN teachercourse AS tc ON t.TeacherID = tc.TeacherID
                                                    JOIN course AS c ON c.CourseID = tc.CourseID";
                            $delete_teacher_result = $link->query($delete_teacher_sql);

                            if ($delete_teacher_result->num_rows > 0) {
                                while ($row = $delete_teacher_result->fetch_assoc()) {
                                    $teacher_id = $row['TeacherID'];
                                    $teacher_name = $row['TeacherName'];
                                    $course_id = $row['CourseID'];
                                    $course_name = $row['CourseName'];

                                    echo "<option value='$teacher_id|$course_id'>$teacher_name - $course_name</option>";
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
                                    <th>Teacher ID</th>
                                    <th>Teacher Name</th>
                                    <th>Course ID</th>
                                    <th>Course Name</th>
                                    <th>Approval Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($teacher_result->num_rows > 0) {
                                    while ($row = $teacher_result->fetch_assoc()) {
                                        $teacher_id = $row['TeacherID'];
                                        $teacher_name = $row['TeacherName'];
                                        $course_id = $row['CourseID'];
                                        $course_name = $row['CourseName'];
                                        $approval_status = $row['Approval'];

                                        echo "<tr>
                                                <td>$teacher_id</td>
                                                <td>$teacher_name</td>
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
                                                    <input type='hidden' name='teacher_id[]' value='$teacher_id'>
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
    $teacher_ids = $_POST['teacher_id'];
    $course_ids = $_POST['course_id'];
    $approval_statuses = $_POST['approval_status'];

    for ($i = 0; $i < count($teacher_ids); $i++) {
        $teacher_id = $teacher_ids[$i];
        $course_id = $course_ids[$i];
        $approval_status = $approval_statuses[$i];

        // Update the approval status in the database
        $update_sql = "UPDATE teachercourse SET Approval = '$approval_status' WHERE TeacherID = '$teacher_id' AND CourseID = '$course_id'";
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
        header("Location: manageteacher.php?success_message=" . urlencode($success_message));
        exit();
    } elseif (isset($error_message)) {
        // Display error message
        echo "<script>alert('$error_message');</script>";
    }
}

$link->close();
?>
