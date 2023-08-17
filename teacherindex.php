<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';
include 'session.php';

// Check if the teacher is logged in
checkTeacherLogin();

$teacherID = $_SESSION['teacherID'];

// Retrieve the teacher's courses and the number of enrolled students
$coursesQuery = "SELECT c.CourseName, COUNT(cs.StudentID) AS EnrolledStudents
                FROM course c
                JOIN teachercourse tc ON c.CourseID = tc.CourseID
                LEFT JOIN coursestudent cs ON c.CourseID = cs.CourseID
                WHERE tc.TeacherID = '$teacherID'
                AND tc.Approval = 'approved'
                GROUP BY c.CourseID";
$coursesResult = mysqli_query($link, $coursesQuery);

// Prepare the data for the chart
$chartLabels = [];
$chartData = [];

while ($course = mysqli_fetch_assoc($coursesResult)) {
    $chartLabels[] = $course['CourseName'];
    $chartData[] = $course['EnrolledStudents'];
}

// Convert the chart data to JSON format for JavaScript usage
$chartLabelsJSON = json_encode($chartLabels);
$chartDataJSON = json_encode($chartData);

// Retrieve the latest enrolled students with course and enrollment date
$latestStudentsQuery = "SELECT s.FirstName, s.LastName, c.CourseName, cs.EnrollDate
                        FROM student s
                        JOIN coursestudent cs ON s.StudentID = cs.StudentID
                        JOIN teachercourse tc ON cs.CourseID = tc.CourseID
                        JOIN course c ON cs.CourseID = c.CourseID
                        WHERE tc.TeacherID = '$teacherID'
                        AND tc.Approval = 'approved'
                        ORDER BY cs.EnrollDate DESC
                        LIMIT 5";
$latestStudentsResult = mysqli_query($link, $latestStudentsQuery);

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
                <div class="container-fluid">
                    <h1 class="mt-4">Dashboard</h1>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Courses and Enrolled Students
                                </div>
                                <div class="card-body">
                                    <canvas id="courseChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-user-plus me-1"></i>
                                    Latest Enrolled Students
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <?php while ($student = mysqli_fetch_assoc($latestStudentsResult)) : ?>
                                            <li class="list-group-item">
                                                <?php echo $student['FirstName'] . ' ' . $student['LastName']; ?><br>
                                                <small>Course: <?php echo $student['CourseName']; ?></small><br>
                                                <small>Enrollment Date: <?php echo $student['EnrollDate']; ?></small>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
    <script>
        // Get the chart labels and data from PHP
        var chartLabels = <?php echo $chartLabelsJSON; ?>;
        var chartData = <?php echo $chartDataJSON; ?>;

        // Create the chart
        var ctx = document.getElementById('courseChart').getContext('2d');
        var courseChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Enrolled Students',
                    data: chartData,
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0,
                        stepSize: 1
                    }
                }
            }
        });
    </script>
</body>
</html>
