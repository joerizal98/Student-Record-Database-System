<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "session.php";
include "db.php";

// Check if the admin is logged in
checkAdminLogin();
// Add course
if (isset($_POST['add_course'])) {
    $course_name = $_POST['course_name'];
    $course_time_start = $_POST['course_time_start'];
    $course_time_end = $_POST['course_time_end'];
    $course_day = $_POST['course_day'];

    // Check if course already exists
    $check_sql = "SELECT * FROM course WHERE CourseName = '$course_name'";
    $check_result = $link->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "<script>alert('Course already exists');</script>";
    } else {
        // Check if the time slot is already taken
        $time_slot_sql = "SELECT * FROM course WHERE CourseDay = '$course_day' AND ((CourseTimeStart <= '$course_time_start' AND CourseTimeEnd >= '$course_time_start') OR (CourseTimeStart <= '$course_time_end' AND CourseTimeEnd >= '$course_time_end'))";
        $time_slot_result = $link->query($time_slot_sql);

        if ($time_slot_result->num_rows > 0) {
            echo "<script>alert('Time slot already taken. Please choose another time.');</script>";
        } else {
            $course_image = addslashes(file_get_contents($_FILES['course_image']['tmp_name']));

            $sql = "INSERT INTO course (CourseName, Image, CourseTimeStart, CourseTimeEnd, CourseDay) VALUES ('$course_name', '$course_image', '$course_time_start', '$course_time_end', '$course_day')";

            if ($link->query($sql) === TRUE) {
                echo "<script>alert('Course added successfully');</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $link->error;
            }
        }
    }
}

// Refresh course list
if (isset($_POST['refresh'])) {
    // Fetch the updated list of courses from the database
    $courses_sql = "SELECT * FROM course";
    $courses_result = $link->query($courses_sql);
} else {
    // Get initial list of courses
    $courses_sql = "SELECT * FROM course";
    $courses_result = $link->query($courses_sql);
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

                    <h1 class="mt-4">Add New Course</h1>
                    <br>
                    <form method="post" action="" enctype="multipart/form-data">
                        <input type="text" name="course_name" placeholder="Course Name">
                        <input type="time" name="course_time_start" placeholder="Start Time" required>
                        <input type="time" name="course_time_end" placeholder="End Time" required>
                        <select name="course_day" required>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                        <span style="color: red;">* Please choose a course image</span>
                        <input type="file" name="course_image" required>
                        <button type="submit" name="add_course">Add Course</button>
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
                        if ($courses_result->num_rows > 0) {
                            while ($row = $courses_result->fetch_assoc()) {
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
