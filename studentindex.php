<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "session.php";
include "db.php";
// Start by setting the session cookie path

// Check if the student is logged in
checkStudentLogin();

// Fetch the list of currently registered courses for the student from the database
// Assuming you have a database connection established

// Replace 'your_registered_courses_table' with the actual table name for registered courses in your database
$studentID = $_SESSION['username1']; // Assuming the student ID is stored in the session
$registeredCoursesQuery = "SELECT c.CourseID, c.CourseName, c.Image, s.UserName AS student_name, CONCAT(c.CourseTimeStart, '-', c.CourseTimeEnd, ' (', c.CourseDay, ')') AS CourseTimeTable, CONCAT(t.FirstName, ' ', t.LastName) AS teacher_name
    FROM course c
    JOIN CourseStudent cs ON c.CourseID = cs.CourseID
    JOIN student s ON cs.StudentID = s.StudentID
    JOIN teachercourse tc ON c.CourseID = tc.CourseID
    JOIN teacher t ON tc.TeacherID = t.TeacherID
    WHERE s.UserName = '$studentID'
    AND cs.Approval = 'approved'";

$registeredCoursesResult = mysqli_query($link, $registeredCoursesQuery);

// Fetch the list of available courses for enrollment from the database
// Assuming you have a database connection established


// Fetch the list of available courses for enrollment from the database, excluding courses without teachers
$availableCoursesQuery = "SELECT c.CourseID, c.CourseName
    FROM course c
    LEFT JOIN teachercourse tc ON c.CourseID = tc.CourseID
    WHERE c.CourseID NOT IN (SELECT CourseID FROM CourseStudent WHERE StudentID = '$studentID')
    AND (tc.TeacherID IS NULL OR tc.TeacherID <> '')";



$availableCoursesResult = mysqli_query($link, $availableCoursesQuery);

// ...



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .welcome-heading {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .registered-courses-heading {
            font-size: 24px;
            font-weight: bold;
            color: #555;
            margin-bottom: 20px;
        }
        .enroll-course-btn {
            margin-bottom: 20px;
            position: absolute;
            top: 100px;
            right: 10px;
        }

        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-text {
            font-size: 16px;
            color: #555;
        }

        .registered-courses-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 30px;
        }

        .course-image {
            height: 200px; /* Set the desired height for the images */
            object-fit: cover; /* Maintain aspect ratio and cover the container */
        }

        .modal-dialog {
            max-width: 600px;
        }

        .enroll-course-btn {
            margin-bottom: 20px;
        }
    </style>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <?php include "navbar.php"; ?>
    <div id="layoutSidenav">
        <?php include "sidebar.php"; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container mt-5">
                    <h1 class="welcome-heading">Dashboard</h1>

                    <h2 class="registered-courses-heading">Registered Courses</h2>

                    <?php if (mysqli_num_rows($registeredCoursesResult) > 0) : ?>
                        <div class="registered-courses-section">
                            <?php while ($course = mysqli_fetch_assoc($registeredCoursesResult)) : ?>
                                <div class="card" data-bs-toggle="modal" data-bs-target="#courseModal<?php echo $course['CourseID']; ?>" title="Click for more information">
    <?php
    $imageData = $course['Image'];
    $imageSrc = 'data:image/jpeg;base64,' . base64_encode($imageData);
    ?>
    <img src="<?php echo $imageSrc; ?>" alt="Course Image" class="course-image">
    <div class="card-body">
        <h5 class="card-title"><?php echo $course['CourseName']; ?></h5>
        <!-- ... -->
    </div>
</div>

                                <!-- Course Modal -->
                                <div class="modal fade" id="courseModal<?php echo $course['CourseID']; ?>" tabindex="-1" aria-labelledby="courseModalLabel<?php echo $course['CourseID']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="courseModalLabel<?php echo $course['CourseID']; ?>">Course Information</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Course ID</th>
                                                            <th>Course Name</th>
                                                             <th>Course TimeTable</th>
                                                            <th>Teacher Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><?php echo $course['CourseID']; ?></td>
                                                            <td><?php echo $course['CourseName']; ?></td>
                                                             <td><?php echo $course['CourseTimeTable']; ?></td>
                                                            <td><?php echo $course['teacher_name']; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <!-- Add more details from the 'record' table here -->
                                                <h5>Course Records:</h5>
                                                <?php
                                                // Assuming you have a database connection established

                                                // Replace 'your_record_table' with the actual table name for records in your database
                                                $courseID = $course['CourseID'];
                                                $recordQuery = "SELECT r.RecordType, r.DateTime, r.Description, t.FirstName
                                                    FROM record r
                                                    JOIN teacher t ON r.TeacherID = t.TeacherID
                                                    WHERE r.CourseID = '$courseID'";
                                                $recordResult = mysqli_query($link, $recordQuery);

                                                if ($recordResult && mysqli_num_rows($recordResult) > 0) {
                                                    echo "<table class='table'>
                                                            <thead>
                                                                <tr>
                                                                    <th>Record Type</th>
                                                                    <th>Date/Time</th>
                                                                    <th>Description</th>
                                                                    <th>Recorded by Teacher</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>";
                                                    while ($record = mysqli_fetch_assoc($recordResult)) {
                                                        echo "<tr>
                                                                    <td>" . $record['RecordType'] . "</td>
                                                                    <td>" . $record['DateTime'] . "</td>
                                                                    <td>" . $record['Description'] . "</td>
                                                                    <td>" . $record['FirstName'] . "</td>
                                                                </tr>";
                                                    }
                                                    echo "</tbody></table>";
                                                } else {
                                                    echo "No records found.";
                                                }

                                                // Close the record result set
                                                mysqli_free_result($recordResult);
                                                ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endwhile; ?>
                        </div>
                    <?php else : ?>
                        <p>No course enrolled.</p>
                    <?php endif; ?>

                 <!-- Enroll Course Button -->
                <button class="btn btn-primary enroll-course-btn">Enroll a New Course</button>

                    <!-- Enroll Course Modal -->
                    <div class="modal fade" id="enrollCourseModal" tabindex="-1" aria-labelledby="enrollCourseModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="enrollCourseModalLabel">Enroll a New Course</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="studentenroll.php" method="post">
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
            <?php include "footer.php"; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var enrollCourseBtn = document.querySelector('.enroll-course-btn');
        var enrollCourseModal = new bootstrap.Modal(document.getElementById('enrollCourseModal'));

        enrollCourseBtn.addEventListener('click', function () {
            enrollCourseModal.show();
        });
    </script>
</body>
</html>
