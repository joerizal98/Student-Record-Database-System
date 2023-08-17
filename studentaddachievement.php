<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "session.php";
include "db.php";

// Check if the student is logged in
checkStudentLogin();

// Define variables for success and error messages
$successMessage = "";
$errorMessage = "";

// Check if the form is submitted for adding a new achievement
if (isset($_POST['addAchievement'])) {
    // Get the achievement data from the form
    $newAchievementName = $_POST['achievementName'];
    $newAchievementType = $_POST['achievementType'];
    $studentID = $_SESSION['studentID'];

    // Validate the achievement type
    $allowedTypes = array('academic', 'award', 'competition');
    if (!in_array($newAchievementType, $allowedTypes)) {
        $errorMessage = "Invalid achievement type. Please choose from 'academic', 'award', or 'competition'.";
    } else {
        // Perform the insert query
        $query = "INSERT INTO achievement (StudentID, AchievementName, AchievementType) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, 'iss', $studentID, $newAchievementName, $newAchievementType);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            // Insert successful
            $successMessage = "Achievement added successfully!";
        } else {
            // Insert failed
            $errorMessage = "Failed to add achievement. Please try again.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }
}

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
    <title>Add Achievement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <?php include "navbar.php"; ?>
    <div id="layoutSidenav">
        <?php include "sidebar.php"; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container">
                        <h1 class="mt-4">Add Achievement</h1>  
                     <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="studentindex.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="studentachievement.php">Achievements</a></li>
                        <li class="breadcrumb-item active">Edit/Delete Achievement</li>
                    </ol>
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">Add Achievement</div>
                                <div class="card-body">
                                    <form method="POST" action="studentaddachievement.php">
                                        <div class="mb-3">
                                            <label for="achievementName" class="form-label">Achievement Name</label>
                                            <input type="text" class="form-control" id="achievementName" name="achievementName" required>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="achievementType" name="achievementType" required>
                                                <option value="academic">Academic</option>
                                                <option value="award">Award</option>
                                                <option value="competition">Competition</option>
                                            </select>
                                            <label for="achievementType">Achievement Type</label>
                                        </div>
                                        <button type="submit" class="btn btn-primary" name="addAchievement">Add Achievement</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Success Modal -->
                <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="successModalLabel">Success</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <?php echo $successMessage; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Error Modal -->
                <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <?php echo $errorMessage; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include "footer.php"; ?>
        </div>
    </div>

    <script>
        // Show the success or error modal based on the messages
        $(document).ready(function() {
            <?php if (!empty($successMessage)): ?>
                $("#successModal").modal("show");
            <?php endif; ?>

            <?php if (!empty($errorMessage)): ?>
                $("#errorModal").modal("show");
            <?php endif; ?>
        });
    </script>
</body>
</html>
