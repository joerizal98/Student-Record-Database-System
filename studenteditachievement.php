<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "session.php";
include "db.php";

// Check if the student is logged in
checkStudentLogin();

// Check if the achievementID parameter is provided
if (isset($_GET['id'])) {
    $achievementID = $_GET['id'];

    // Check if the form is submitted for editing
    if (isset($_POST['editAchievement'])) {
        // Get the updated achievement data from the form
        $updatedAchievementName = $_POST['achievementName'];
        $updatedAchievementType = $_POST['achievementType'];

        // Validate the achievement type
        $allowedTypes = array('academic', 'award', 'competition');
        if (!in_array($updatedAchievementType, $allowedTypes)) {
            echo "Invalid achievement type. Please choose from 'academic', 'award', or 'competition'.";
            exit;
        }

        // Perform the update query
        $query = "UPDATE achievement SET AchievementName = ?, AchievementType = ? WHERE AchievementID = ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, 'ssi', $updatedAchievementName, $updatedAchievementType, $achievementID);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            // Update successful
            $editSuccess = true;
        } else {
            // Update failed
            $editError = true;
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }

    // Check if the form is submitted for deletion
    if (isset($_POST['deleteAchievement'])) {
        // Perform the delete query
        $query = "DELETE FROM achievement WHERE AchievementID = ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, 'i', $achievementID);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            // Deletion successful
            $deleteSuccess = true;
            // Redirect back to the achievements page
            header("Location: studentachievement.php");
            exit();
        } else {
            // Deletion failed
            $deleteError = true;
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }

    // Fetch the achievement data from the database
    $query = "SELECT * FROM achievement WHERE AchievementID = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 'i', $achievementID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $achievementName = $row['AchievementName'];
    $achievementType = $row['AchievementType'];

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    // Redirect back to the achievements page if the achievementID parameter is not provided
    header("Location: studentachievement.php");
    exit();
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
    <title>Edit/Delete Achievement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.4/dist/umd/popper.min.js" integrity="sha384-Zv4DcI1mSb6MRA9OpQJ6/lCxp2zmMTHsARj99RphglAHxOPnX9Bf8CHg1IDfr05Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <?php include "navbar.php"; ?>
    <div id="layoutSidenav">
        <?php include "sidebar.php"; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Edit/Delete Achievement</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="studentindex.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="studentachievement.php">Achievements</a></li>
                        <li class="breadcrumb-item active">Edit/Delete Achievement</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-edit me-1"></i>
                            Edit Achievement
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="achievementName" name="achievementName" type="text" value="<?php echo $achievementName; ?>" required />
                                    <label for="achievementName">Achievement Name</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="achievementType" name="achievementType" required>
                                        <option value="academic" <?php if ($achievementType === 'academic') echo 'selected'; ?>>Academic</option>
                                        <option value="award" <?php if ($achievementType === 'award') echo 'selected'; ?>>Award</option>
                                        <option value="competition" <?php if ($achievementType === 'competition') echo 'selected'; ?>>Competition</option>
                                    </select>
                                    <label for="achievementType">Achievement Type</label>
                                </div>
                                <div class="mt-4 mb-0">
                                    <input type="submit" class="btn btn-primary" name="editAchievement" value="Update Achievement" />
                                   <input type="submit" class="btn btn-danger" name="deleteAchievement" value="Delete Achievement" onclick="return confirm('Are you sure you want to delete this achievement?');" data-bs-toggle="modal" data-bs-target="#deleteModal" />

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <?php include "footer.php"; ?>
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
                    <?php if (isset($editSuccess)) { ?>
                        <p>Achievement updated successfully!</p>
                    <?php } elseif (isset($deleteSuccess)) { ?>
                        <p>Achievement deleted successfully!</p>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
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
                    <?php if (isset($editError)) { ?>
                        <p>Failed to update achievement. Please try again.</p>
                    <?php } elseif (isset($deleteError)) { ?>
                        <p>Failed to delete achievement. Please try again.</p>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show success or error modal based on PHP variables
        <?php if (isset($editSuccess) || isset($deleteSuccess) || isset($editError) || isset($deleteError)) { ?>
            $(document).ready(function() {
                <?php if (isset($editSuccess) || isset($deleteSuccess)) { ?>
                    $('#successModal').modal('show');
                <?php } elseif (isset($editError) || isset($deleteError)) { ?>
                    $('#errorModal').modal('show');
                <?php } ?>
            });
        <?php } ?>
    </script>
</body>
</html>
