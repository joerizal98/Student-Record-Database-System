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

        .search-form {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-form input[type="text"] {
            flex: 1;
            height: 40px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-form button {
            height: 40px;
            padding: 0 20px;
            margin-left: 10px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-results {
            margin-top: 20px;
        }

        .search-results h2 {
            margin-bottom: 10px;
            font-size: 24px;
        }

        .search-results table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f8f9fa;
        }

        .search-results th,
        .search-results td {
            padding: 12px;
            border: 1px solid #dee2e6;
            text-align: left;
            font-size: 14px;
        }

        .search-results th {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .search-results td button {
            padding: 5px 10px;
            font-size: 14px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .no-results {
            margin-top: 20px;
            font-size: 16px;
            color: #f00;
        }

        .student-info {
            margin-top: 20px;
        }

        .student-info h3 {
            margin-bottom: 10px;
            font-size: 24px;
        }

        .student-info table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f8f9fa;
        }

        .student-info th,
        .student-info td {
            padding: 12px;
            border: 1px solid #dee2e6;
            text-align: left;
            font-size: 14px;
        }

        .student-info th {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .student-info td:first-child {
            width: 40%;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <?php include 'navbar.php'; ?>
    <div id="layoutSidenav">
        <?php include 'sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Welcome, Recruiter!</h1>

                 <form class="search-form" action="<?php echo $_SERVER['PHP_SELF']; ?>?t=<?php echo time(); ?>" method="GET">
    <input type="text" id="search" name="search" placeholder="Enter search criteria">
    <button type="submit">Search</button>
</form>


                    <?php if (count($students) > 0) : ?>
                        <?php if (isset($_GET['student_id'])) : ?>
                            <?php $selectedStudent = getStudent($_GET['student_id']); ?>
                            <?php if ($selectedStudent) : ?>
                                <div class="student-info">
                                    <h3>Student Details</h3>
                                    <table>
                                        <tr>
                                            <th>Full Name</th>
                                            <td><?php echo $selectedStudent['FullName']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Phone Number</th>
                                            <td><?php echo $selectedStudent['PhoneNumber']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Achievement Name</th>
                                            <td><?php echo $selectedStudent['AchievementName']; ?></td>
                                        </tr>
                                        <!-- Add more student information as needed -->
                                    </table>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="search-results">
                            <h2>Search Results:</h2>
                            <table>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Phone Number</th>
                                    <th>Achievement Name</th>
                                    <th></th>
                                </tr>
                                <?php foreach ($students as $student) : ?>
                                    <tr>
                                        <td><?php echo $student['FullName']; ?></td>
                                        <td><?php echo $student['PhoneNumber']; ?></td>
                                        <td><?php echo $student['AchievementName']; ?></td>
                                        <td><button onclick="window.location.href='recruiterviewresume.php?student_id=<?php echo $student['StudentID']; ?>'">View More Information</button></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    <?php else : ?>
                        <p class="no-results">No students found.</p>
                    <?php endif; ?>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
</body>
</html>
