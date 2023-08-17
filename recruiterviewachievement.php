<?php
include 'db.php';
include 'session.php';
checkRecruiterLogin();
$recruiterID = $_SESSION['username3'];

$studentID = $_GET['student_id'];

// Fetch the student achievements from the database
$query = "SELECT a.AchievementName, a.AchievementType, a.StudentID, CONCAT(s.FirstName, ' ', s.LastName) AS FullName
                    FROM achievement a
                    JOIN student s ON a.StudentID = s.StudentID
                    WHERE a.StudentID = '$studentID'
                    ORDER BY a.AchievementName";

$result = mysqli_query($link, $query);

// Check if there are any achievements
if (mysqli_num_rows($result) > 0) {
        // Create an array to store the achievements
        $achievements = array();

        // Fetch each row from the result set
        while ($row = mysqli_fetch_assoc($result)) {
                // Add the achievement to the array
                $achievements[] = $row;
        }
} else {
        // No achievements found
        $achievements = array();
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
        <title>Student Achievements</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
                /* Style for the page header */
                header {
                        background-color: #004466;
                        color: white;
                        padding: 10px;
                        text-align: center;
                }

                /* Style for the page content */
                main {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        padding: 20px;
                }

                /* Style for the achievement list */
                .achievement-list {
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                        gap: 20px;
                        list-style-type: none;
                        padding: 0;
                        margin: 0;
                        max-width: 1000px;
                        width: 100%;
                }

                /* Style for each achievement item */
                .achievement-item {
                        border: 1px solid #ddd;
                        padding: 20px;
                        background-color: #f5f5f5;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                        transition: box-shadow 0.3s ease;
                }

                .achievement-item:hover {
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                }

                /* Style for the achievement name */
                .achievement-name {
                        font-weight: bold;
                        margin-bottom: 5px;
                }

                /* Style for the full name */
                .full-name {
                        font-style: italic;
                        color: #777;
                }
        </style>
</head>
<body class="sb-nav-fixed">
<?php include 'navbar.php'; ?>
<div id="layoutSidenav">
        <?php include 'sidebar.php'; ?>
        <div id="layoutSidenav_content">
                <main>
                        <?php if (empty($achievements)): ?>
                                <header>
                                        <h1>No achievements found for this student.</h1>
                                </header>
                        <?php else: ?>
                                <header>
                                        <h1>Student Achievements for <?php echo $achievements[0]["FullName"]; ?></h1>
                                </header>
                                <br>
                                <div>
                                        <label for="achievement-type-filter">Filter by Achievement Type:</label>
                                        <select id="achievement-type-filter">
                                                <option value="all">All</option>
                                                <option value="academic">Academic</option>
                                                <option value="award">Award</option>
                                                <option value="competition">Competition</option>
                                        </select>
                                </div>
                                <ul class="achievement-list">
                                        <?php foreach ($achievements as $achievement): ?>
                                                <li class="achievement-item" data-achievement-type="<?php echo $achievement["AchievementType"]; ?>">
                                                        <div class="achievement-name"><?php echo $achievement["AchievementName"]; ?></div>
                                                </li>
                                        <?php endforeach; ?>
                                </ul>
                        <?php endif; ?>
                </main>
                <?php include 'footer.php'; ?>
        </div>
</div>
<script>
        // Filter the achievements based on the selected achievement type
        document.getElementById('achievement-type-filter').addEventListener('change', function () {
                var filter = this.value;

                var achievementItems = document.querySelectorAll('.achievement-item');

                achievementItems.forEach(function (item) {
                        var achievementType = item.dataset.achievementType;

                        if (filter === 'all' || filter === achievementType) {
                                item.style.display = 'block';
                        } else {
                                item.style.display = 'none';
                        }
                });
        });
</script>
</body>
</html>
