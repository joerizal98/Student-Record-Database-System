<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "session.php";
include "db.php";

// Check if the admin is logged in
checkAdminLogin();

// Edit course
if (isset($_POST['edit_course'])) {
    $course_id = $_POST['course_id'];
    $new_course_id = $_POST['new_course_id'];
    $course_name = $_POST['course_name'];
    $course_image = '';
    $course_time_start = $_POST['course_time_start'];
    $course_time_end = $_POST['course_time_end'];
    $course_day = $_POST['course_day'];

    if (!empty($_FILES['course_image']['tmp_name'])) {
        $course_image = addslashes(file_get_contents($_FILES['course_image']['tmp_name']));
    }

    // Check if the time slot is already taken
    $time_slot_sql = "SELECT * FROM course WHERE CourseID <> '$course_id' AND CourseDay = '$course_day' AND ((CourseTimeStart <= '$course_time_start' AND CourseTimeEnd >= '$course_time_start') OR (CourseTimeStart <= '$course_time_end' AND CourseTimeEnd >= '$course_time_end'))";
    $time_slot_result = $link->query($time_slot_sql);

    if ($time_slot_result->num_rows > 0) {
        echo "<script>alert('Time slot already taken. Please choose another time.');</script>";
    } else {
        $sql = "UPDATE course SET CourseName='$course_name'";

        if (!empty($new_course_id)) {
            $sql .= ", CourseID='$new_course_id'";
        }

        if (!empty($course_image)) {
            $sql .= ", Image='$course_image'";
        }

        $sql .= ", CourseTimeStart='$course_time_start', CourseTimeEnd='$course_time_end', CourseDay='$course_day'";

        $sql .= " WHERE CourseID=$course_id";

        if ($link->query($sql) === TRUE) {
            echo "<script>alert('Course updated successfully');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $link->error;
        }
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
} else {
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

                    <h1 class="mt-4">Edit Course</h1>
                    <form method="post" action="" enctype="multipart/form-data">
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
                        <br><br>
                        <input type="text" name="new_course_id" placeholder="New Course ID">
                        <input type="text" name="course_name" placeholder="New Course Name">
                        <input type="time" name="course_time_start" placeholder="Start Time" required>
                        <input type="time" name="course_time_end" placeholder="End Time" required>
                        <select name="course_day" required>
                            <option value="" disabled selected>Select Day</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                        <span style="color: red;">* Please choose a course image</span>
                        <input type="file" name="course_image">
                        <button type="submit" name="edit_course">Edit Course</button>
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
