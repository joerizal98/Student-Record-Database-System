<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "session.php";
include "db.php";

// Check if the teacher is logged in
checkTeacherLogin();

// Check if the recordID parameter is provided
if (isset($_GET['id'])) {
    $recordID = $_GET['id'];

    // Check if the form is submitted for editing
    if (isset($_POST['editRecord'])) {
        // Get the updated record data from the form
        $updatedRecordType = $_POST['recordType'];
        $updatedDateTime = $_POST['dateTime'];
        $updatedDescription = $_POST['description'];

        // Perform the update query
        $query = "UPDATE record SET RecordType = ?, DateTime = ?, Description = ? WHERE RecordID = ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, 'sssi', $updatedRecordType, $updatedDateTime, $updatedDescription, $recordID);
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
    if (isset($_POST['deleteRecord'])) {
        // Perform the delete query
        $query = "DELETE FROM record WHERE RecordID = ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, 'i', $recordID);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            // Deletion successful
            $deleteSuccess = true;
            // Redirect back to the records page
            header("Location: teacherrecordstudent.php");
            exit();
        } else {
            // Deletion failed
            $deleteError = true;
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }

    // Fetch the record data from the database
    $query = "SELECT * FROM record WHERE RecordID = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 'i', $recordID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $studentID = $row['StudentID'];
    $courseID = $row['CourseID'];
    $teacherID = $row['TeacherID'];
    $recordType = $row['RecordType'];
    $dateTime = $row['DateTime'];
    $description = $row['Description'];

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    // Redirect back to the records page if the recordID parameter is not provided
    header("Location: teacherrecordstudent.php");
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
    <title>Edit/Delete Record</title>
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
                    <h1 class="mt-4">Edit/Delete Record</h1>
                   <!-- <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="teacherindex.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="teacherrecord.php">Records</a></li>
                        <li class="breadcrumb-item active">Edit/Delete Record</li>
                    </ol>-->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-edit me-1"></i>
                            Edit Record
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="recordType" name="recordType" required>
                                        <option value="attendance" <?php if ($recordType === 'attendance') echo 'selected'; ?>>Attendance</option>
                                        <option value="assignment" <?php if ($recordType === 'assignment') echo 'selected'; ?>>Assignment</option>
                                        <option value="exam" <?php if ($recordType === 'exam') echo 'selected'; ?>>Exam</option>
                                    </select>
                                    <label for="recordType">Record Type</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="dateTime" name="dateTime" type="datetime-local" value="<?php echo date('Y-m-d\TH:i', strtotime($dateTime)); ?>" required />
                                    <label for="dateTime">Date and Time</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $description; ?></textarea>
                                    <label for="description">Description</label>
                                </div>
                                <div class="mt-4 mb-0">
                                    <input type="submit" class="btn btn-primary" name="editRecord" value="Update Record" />
                                    <input type="submit" class="btn btn-danger" name="deleteRecord" value="Delete Record" onclick="return confirm('Are you sure you want to delete this record?');" data-bs-toggle="modal" data-bs-target="#deleteModal" />
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
                        <p>Record updated successfully!</p>
                    <?php } elseif (isset($deleteSuccess)) { ?>
                        <p>Record deleted successfully!</p>
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
                        <p>Failed to update record. Please try again.</p>
                    <?php } elseif (isset($deleteError)) { ?>
                        <p>Failed to delete record. Please try again.</p>
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
