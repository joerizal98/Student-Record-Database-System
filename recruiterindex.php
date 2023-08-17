<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';
include 'session.php';



// Check if the recruiter is logged in
checkRecruiterLogin();

$recruiterID = $_SESSION['username3'];

// Perform the search based on the criteria
if (isset($_GET['search'])) {
    $searchCriteria = $_GET['search'];
    $students = searchStudents($searchCriteria);
} else {
    $students = [];
}

function searchStudents($searchCriteria) {
    global $link;
    $searchCriteria = mysqli_real_escape_string($link, $searchCriteria);

    $searchQuery = "SELECT s.*, CONCAT(s.FirstName, ' ', s.LastName) AS FullName, a.AchievementName
                    FROM student s
                    LEFT JOIN achievement a ON s.StudentID = a.StudentID
                    WHERE s.FirstName LIKE '%$searchCriteria%'
                    OR s.LastName LIKE '%$searchCriteria%'
                    OR a.AchievementName LIKE '%$searchCriteria%'";

    $searchResult = mysqli_query($link, $searchQuery);

    $students = [];

    while ($student = mysqli_fetch_assoc($searchResult)) {
        $students[] = $student;
    }

    return $students;
}


// Get Achievement Name for a student
function getAchievementName($studentID) {
    global $link;

    $achievementQuery = "SELECT AchievementName FROM achievement WHERE StudentID = '$studentID'";
    $achievementResult = mysqli_query($link, $achievementQuery);
    $achievement = mysqli_fetch_assoc($achievementResult);

    return $achievement ? $achievement['AchievementName'] : '';
}

// Clear previously selected student when the page is refreshed
if (!isset($_GET['student_id'])) {
    unset($_GET['student_id']);
}

// Rest of the code...
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>Recruiter Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .container-fluid {
            padding-top: 20px;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <?php include 'navbar.php'; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include 'sidebar.php'; ?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Search Student</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Search Criteria
                        </div>
                        <div class="card-body">
                            <form action="" method="get">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" placeholder="Enter search criteria" required>
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Search Results
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <!--<th>Student ID</th> -->
                                            <th>Student Name</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Achievement Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($students as $student) : ?>
                                            <tr>
                                               <!-- <td><?= $student['StudentID']; ?></td> -->
                                                <td><?= $student['FullName']; ?></td>
                                                <td><?= $student['Email']; ?></td>
                                                <td><?= $student['PhoneNumber']; ?></td>
                                                 <td><?= $student['AchievementName']; ?></td>
                                                <td>
    <a href="recruiterviewresume.php?student_id=<?= $student['StudentID']; ?>" class="btn btn-primary btn-sm">View More Information</a>
</td>
                                                <!--<td><?= $student['GPA']; ?></td>-->
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/simple-datatables.js"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>
