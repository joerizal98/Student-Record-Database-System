<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "session.php";
include "db.php";

// Check if the admin is logged in
checkAdminLogin();

// Check if the form is submitted
if (isset($_POST['delete_course'])) {
    // Get the selected course ID from the form
    $course_id = $_POST['course_id'];

    // Prepare and execute the DELETE query
    $delete_query = "DELETE FROM course WHERE CourseID = '$course_id'";
    $result = $link->query($delete_query);

    // Check if the query was successful
    if ($result) {
      echo "<script>alert('Course deleted succesfully');</script>";
    } else {
        echo "<script>alert('Error deleting course');</script>" . $link->error;
    }
}
// Get list of courses
$courses_sql = "SELECT * FROM course";
$courses_result = $link->query($courses_sql);
// Refresh course list
if (isset($_POST['refresh'])) {
    // Fetch the updated list of courses from the database
    $courses_sql = "SELECT * FROM course";
    $courses_result1 = $link->query($courses_sql);
}
else {
    // Get initial list of courses
    $courses_sql = "SELECT * FROM course";
    $courses_result1 = $link->query($courses_sql);
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

                        <h1 class="mt-4">Delete Course</h1>
       <form method="post" action="">
        <select name="course_id">
            <?php
            if ($courses_result->num_rows > 0) {
                while ($row = $courses_result->fetch_assoc()) {
                    $course_id = $row['CourseID'];
                    $course_name = $row['CourseName'];

                    echo "<option value='$course_id'>$course_id: $course_name</option>";
                }
            }
            ?>
        </select>
        <button type="submit" name="delete_course">Delete Course</button>
        </form>

    <h2 class="mt-4">Courses List</h2>


             <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Day</th>
                            </tr>
                        </thead>
                        <?php
                        if ($courses_result1->num_rows > 0) {
                            while ($row = $courses_result1->fetch_assoc()) {
                                $course_id = $row['CourseID'];
                                $course_name = $row['CourseName'];
                                $course_image = $row['Image'];
                                $course_time_start = $row['CourseTimeStart'];
                                $course_time_end = $row['CourseTimeEnd'];
                                $course_day = $row['CourseDay'];

                                echo "<tr><td>$course_id</td><td>$course_name</td><td><img src='data:image/jpeg;base64," . base64_encode($course_image) . "' width='100' height='100'></td><td>$course_time_start</td><td>$course_time_end</td><td>$course_day</td></tr>";
                            }
                        }
                        ?>
                    </table>
     <form method="post" action="">
        <button type="submit" name="refresh">Refresh List</button>
    </form>

                    </div>
                </main>
               <?php include 'footer.php'; ?>  
            </div>
        </div>
    </body>
</html>


<?php
$link->close();
?>
