<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "session.php";
include "db.php";

// Check if the admin is logged in
checkAdminLogin();

// Get the count of pending approvals
$pendingApprovalCountQuery = "SELECT COUNT(*) as pendingApprovalCount FROM teachercourse WHERE Approval = 'Pending'";
$pendingApprovalCountResult = mysqli_query($link, $pendingApprovalCountQuery);
$pendingApprovalCountData = mysqli_fetch_assoc($pendingApprovalCountResult);
$pendingApprovalCount = $pendingApprovalCountData['pendingApprovalCount'];


$pendingApprovalCountQuery2 = "SELECT COUNT(*) as pendingApprovalCount FROM coursestudent WHERE Approval = 'Pending'";
$pendingApprovalCountResult2 = mysqli_query($link, $pendingApprovalCountQuery2);
$pendingApprovalCountData2 = mysqli_fetch_assoc($pendingApprovalCountResult2);
$pendingApprovalCount2 = $pendingApprovalCountData2['pendingApprovalCount'];

// Retrieve the count of registered student, teacher, and recruiter data from your database
// Assuming you have a database connection established

// Get the count of registered students
// Replace 'your_student_table' with the actual table name for students in your database
$studentCountQuery = "SELECT COUNT(*) as studentCount FROM student";
$studentCountResult = mysqli_query($link, $studentCountQuery);
$studentCountData = mysqli_fetch_assoc($studentCountResult);
$studentCount = $studentCountData['studentCount'];

// Get the count of registered teachers
// Replace 'your_teacher_table' with the actual table name for teachers in your database
$teacherCountQuery = "SELECT COUNT(*) as teacherCount FROM teacher";
$teacherCountResult = mysqli_query($link, $teacherCountQuery);
$teacherCountData = mysqli_fetch_assoc($teacherCountResult);
$teacherCount = $teacherCountData['teacherCount'];

// Get the count of registered recruiters
// Replace 'your_recruiter_table' with the actual table name for recruiters in your database
$recruiterCountQuery = "SELECT COUNT(*) as recruiterCount FROM recruiter";
$recruiterCountResult = mysqli_query($link, $recruiterCountQuery);
$recruiterCountData = mysqli_fetch_assoc($recruiterCountResult);
$recruiterCount = $recruiterCountData['recruiterCount'];

// Fetch the latest registered student, teacher, and recruiter data from your database
// Assuming you have a database connection established

// Fetch the latest student
// Replace 'your_student_table' with the actual table name for students in your database
$latestStudentQuery = "SELECT * FROM student ORDER BY RegistrationDate DESC LIMIT 1";
// Execute the query and retrieve the student data
// Replace 'your_database_connection' with your actual database connection variable
$latestStudentResult = mysqli_query($link, $latestStudentQuery);
$latestStudent = mysqli_fetch_assoc($latestStudentResult);

// Fetch the latest teacher
// Replace 'your_teacher_table' with the actual table name for teachers in your database
$latestTeacherQuery = "SELECT * FROM teacher ORDER BY RegistrationDate DESC LIMIT 1";
// Execute the query and retrieve the teacher data
$latestTeacherResult = mysqli_query($link, $latestTeacherQuery);
$latestTeacher = mysqli_fetch_assoc($latestTeacherResult);

// Fetch the latest recruiter
// Replace 'your_recruiter_table' with the actual table name for recruiters in your database
$latestRecruiterQuery = "SELECT * FROM recruiter ORDER BY RegistrationDate DESC LIMIT 1";
// Execute the query and retrieve the recruiter data
$latestRecruiterResult = mysqli_query($link, $latestRecruiterQuery);
$latestRecruiter = mysqli_fetch_assoc($latestRecruiterResult);

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
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .welcome-heading {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .users-heading {
            font-size: 24px;
            font-weight: bold;
            color: #555;
            margin-bottom: 20px;
        }

        .card {
            transition: background-color 0.3s;
        }

        .card:hover {
            background-color: #FFFFFF;
            cursor: pointer;
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

        .latest-user-section {
            display: flex;
            flex-direction: row;
            gap: 30px;
            align-items: center;
            justify-content: space-between;
            margin-top: 30px;
        }

        .latest-user-card {
            background-color: #F8F9FA;
            border: none;
        }

        .latest-user-card .card-body {
            padding: 20px;
        }

        .latest-user-card .card-title {
            margin-bottom: 15px;
        }

        .latest-user-card .card-text {
            margin-bottom: 8px;
        }

        .latest-user-card .card-text:last-child {
            margin-bottom: 0;
        }

        .latest-user-card .user-details {
            display: flex;
            flex-direction: column;
        }

        .latest-user-card .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
        }

        .latest-user-card .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #usersChart {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
       .course-pending-approvals-card {
        background-color: #ffc107;
        color: #fff;
        transition: background-color 0.3s;
    }

    .course-pending-approvals-card {
        background-color: #ffc107;
        color: #fff;
        transition: background-color 0.3s;
        text-decoration: none !important;
    }

    .course-pending-approvals-card:hover {
        background-color: #ffca28;
        cursor: pointer;
    }

    .course-pending-approvals-card .card-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .course-pending-approvals-card .card-text {
        font-size: 16px;
        margin-bottom: 0;
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

                <!--    <h2 class="users-heading">Registered Users Comparison</h2>  -->

       <div class="row">
    <?php if ($pendingApprovalCount > 0) : ?>
        <div class="col-md-3">
            <a href="manageteacher.php" class="card text-white course-pending-approvals-card">
                <div class="card-body">
                    <h5 class="card-title">Course Pending Approvals(Teacher)s</h5>
                    <div class="card-text">
                        <p><strong>Teacher:</strong> <?php echo $pendingApprovalCount; ?></p>
                    </div>
                </div>
            </a>
        </div>
    <?php endif; ?>

     <?php if ($pendingApprovalCount2 > 0) : ?>
    <div class="col-md-3">
        <a href="managestudent.php" class="card text-white course-pending-approvals-card">
            <div class="card-body">
                <h5 class="card-title">Course Pending Approvals(Student)</h5>
                <div class="card-text">
                    <p><strong>Student:</strong> <?php echo $pendingApprovalCount2; ?></p>

                </div>
            </div>
        </a>
    </div>
     <?php endif; ?>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Registered Users Chart</h5>
                <canvas id="usersChart"></canvas>
            </div>
        </div>
    </div>
</div>

                    <div class="latest-user-section">
                        <div class="col-md-4">
                            <div class="latest-user-card">
                                <div class="card-body">
                                    <h5 class="card-title">Latest Student</h5>
                                    <?php if ($latestStudent) : ?>
                                        <div class="user-details">
                                            <p class="card-text">Name: <?php echo $latestStudent['UserName']; ?></p>
                                            <p class="card-text">Registration Date: <?php echo $latestStudent['RegistrationDate']; ?></p>
                                        </div>
                                    <?php else : ?>
                                        <p class="card-text">No student found.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="latest-user-card">
                                <div class="card-body">
                                    <h5 class="card-title">Latest Teacher</h5>
                                    <?php if ($latestTeacher) : ?>
                                        <div class="user-details">
                                            <p class="card-text">Name: <?php echo $latestTeacher['UserName']; ?></p>
                                            <p class="card-text">Registration Date: <?php echo $latestTeacher['RegistrationDate']; ?></p>
                                        </div>
                                    <?php else : ?>
                                        <p class="card-text">No teacher found.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="latest-user-card">
                                <div class="card-body">
                                    <h5 class="card-title">Latest Recruiter</h5>
                                    <?php if ($latestRecruiter) : ?>
                                        <div class="user-details">
                                            <p class="card-text">Name: <?php echo $latestRecruiter['UserName']; ?></p>
                                            <p class="card-text">Registration Date: <?php echo $latestRecruiter['RegistrationDate']; ?></p>
                                        </div>
                                    <?php else : ?>
                                        <p class="card-text">No recruiter found.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include "footer.php"; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0"></script>
    <script>
        var ctx = document.getElementById('usersChart').getContext('2d');
        var usersChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Student', 'Teacher', 'Recruiter'],
                datasets: [{
                    label: 'Registered Users',
                    data: [<?php echo $studentCount; ?>, <?php echo $teacherCount; ?>, <?php echo $recruiterCount; ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                layout: {
                    padding: {
                        left: 50,
                        right: 50,
                        top: 0,
                        bottom: 50
                    }
                }
            }
        });
    </script>
</body>
</html>
