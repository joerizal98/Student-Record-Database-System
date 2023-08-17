<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';
include 'session.php';

// Check if the teacher is logged in
checkTeacherLogin();

$teacherID = $_SESSION['teacherID'];

// Get the list of courses for the teacher
$query = "SELECT c.CourseID, c.CourseName, c.Image, CONCAT(c.CourseTimeStart, '-', c.CourseTimeEnd, ' (', c.CourseDay, ')') AS CourseTimeTable
            FROM course c
            INNER JOIN teachercourse tc ON c.CourseID = tc.CourseID
            WHERE tc.TeacherID = '$teacherID'
            AND tc.Approval = 'approved'";

$result = mysqli_query($link, $query);

// Fetch the courses
$courses = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['Image'] = base64_encode($row['Image']); // Convert image to base64
    $courses[] = $row;
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
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            flex-grow: 1;
        }
        .enroll-course-btn {
            margin-bottom: 20px;
            position: absolute;
            top: 25px;
            right: 10px;
        }
    </style>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Handler for "View Details" button click
        $(".view-details").click(function(e) {
            e.preventDefault();

            // Get the course ID
            var courseID = $(this).data("course-id");
            var courseTimeTable = $(this).data("course-timetable");

            // Perform AJAX request to fetch the total number of enrolled students
            $.ajax({
                url: "getenrolledstudents.php",
                method: "POST",
                data: { courseID: courseID },
                success: function(response) {
                    // Display the total number of enrolled students and course timetable
                    $("#enrolled-students-modal .modal-body").html("Total Students Enrolled: " + response + "<br>Course TimeTable: " + courseTimeTable);
                    // Display the course ID
                    $("#enrolled-students-modal .modal-title").text("Course ID: " + courseID);
                    // Show the modal
                    $("#enrolled-students-modal").modal("show");
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
    </script>
</head>
<body class="sb-nav-fixed">
    <?php include 'navbar.php'; ?>
    <div id="layoutSidenav">
        <?php include 'sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Teacher Course List</h1>
                    <?php if (empty($courses)) { ?>
                        <div class="alert alert-info" role="alert">
                            You are not assigned to any courses.
                        </div>
                    <?php } else { ?>
                        <div class="row">
                            <?php foreach ($courses as $course) { ?>
                                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                                    <div class="card">
                                        <img src="data:image/jpeg;base64,<?php echo $course['Image']; ?>" class="card-img-top" alt="Course Image">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $course['CourseName']; ?></h5>
                                            <!--<p class="card-text"><?php echo $course['CourseTimeTable']; ?></p> -->
                                            <a href="#" class="btn btn-primary view-details" data-course-id="<?php echo $course['CourseID']; ?>" data-course-timetable="<?php echo $course['CourseTimeTable']; ?>">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>

                 <!-- Enroll Course Button -->
                <button class="btn btn-primary enroll-course-btn">Teach a New Course</button>

                    <!-- Enroll Course Modal -->
                    <div class="modal fade" id="enrollCourseModal" tabindex="-1" aria-labelledby="enrollCourseModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="enrollCourseModalLabel">Teach a new course</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="teacherenroll.php" method="post">
                                        <div class="mb-3">
                                            <label for="courseSelect" class="form-label">Select a Course:</label>
                                            <select class="form-select" id="courseSelect" name="courseSelect">
                                                <option selected disabled>Select a course...</option>
                                                <?php
                                                // Assuming you have a database connection established

                                                // Replace 'your_courses_table' with the actual table name for courses in your database
                                                $courseQuery = "SELECT * FROM course";
                                                $courseResult = mysqli_query($link, $courseQuery);

                                                if ($courseResult && mysqli_num_rows($courseResult) > 0) {
                                                    while ($course = mysqli_fetch_assoc($courseResult)) {
                                                        echo "<option value='" . $course['CourseID'] . "'>" . $course['CourseName'] . "</option>";
                                                    }
                                                } else {
                                                    echo "<option disabled>No courses available.</option>";
                                                }

                                                // Close the course result set
                                                mysqli_free_result($courseResult);
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Enroll</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>

    <!-- Modal for Enrolled Students -->
    <div class="modal fade" id="enrolled-students-modal" tabindex="-1" aria-labelledby="enrolled-students-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enrolled-students-modal-label"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
     <script>
        var enrollCourseBtn = document.querySelector('.enroll-course-btn');
        var enrollCourseModal = new bootstrap.Modal(document.getElementById('enrollCourseModal'));

        enrollCourseBtn.addEventListener('click', function () {
            enrollCourseModal.show();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>
</html>
