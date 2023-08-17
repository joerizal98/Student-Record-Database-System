<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';
include 'session.php';

// Check if the teacher is logged in
checkTeacherLogin();

$teacherID = $_SESSION['teacherID'];

// Get the list of courses for the teacher
$query = "SELECT c.CourseID, c.CourseName
                    FROM course c
                    INNER JOIN teachercourse tc ON c.CourseID = tc.CourseID
                    WHERE tc.TeacherID = '$teacherID'
                    AND tc.Approval = 'approved'";

$result = mysqli_query($link, $query);

// Fetch the courses
$courses = [];
while ($row = mysqli_fetch_assoc($result)) {
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
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
        <?php include 'navbar.php'; ?>
        <div id="layoutSidenav">
                <?php include 'sidebar.php'; ?>
                <div id="layoutSidenav_content">
                        <main>
                                <div class="container-fluid px-4">
                                        <h1 class="mt-4">Student List</h1>
                                        <?php if (empty($courses)) { ?>
                                                <div class="alert alert-info" role="alert">
                                                        You are not assigned to any courses.
                                                </div>
                                        <?php } else { ?>
                                                <?php foreach ($courses as $course) { ?>
                                                        <div class="card mb-4">
                                                                <div class="card-header">
                                                                        <i class="fas fa-table me-1"></i>
                                                                        Students for Course: <?php echo $course['CourseName']; ?>
                                                                </div>
                                                                <div class="card-body">
                                                                        <div class="table-responsive">
                                                                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                                                        <thead>
                                                                                                <tr>
                                                                                                        <th>Student ID</th>
                                                                                                        <th>Full Name</th>
                                                                                                        <th>Enroll Date</th>
                                                                                                </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                                <?php
                                                                                                $courseID = $course['CourseID'];
                                                                                                $query = "SELECT s.StudentID, CONCAT(s.FirstName, ' ', s.LastName) AS FullName, cs.EnrollDate
                                                                                                                    FROM coursestudent cs
                                                                                                                    INNER JOIN student s ON cs.StudentID = s.StudentID
                                                                                                                    WHERE cs.CourseID = '$courseID'";

                                                                                                $result = mysqli_query($link, $query);

                                                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                                                        echo '<tr>';
                                                                                                        echo '<td>' . $row['StudentID'] . '</td>';
                                                                                                        echo '<td>' . $row['FullName'] . '</td>';
                                                                                                        echo '<td>' . $row['EnrollDate'] . '</td>';
                                                                                                        echo '</tr>';
                                                                                                }
                                                                                                ?>
                                                                                        </tbody>
                                                                                </table>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                <?php } ?>
                                        <?php } ?>
                                </div>
                        </main>
                        <?php include 'footer.php'; ?>
                </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
</body>
</html>
