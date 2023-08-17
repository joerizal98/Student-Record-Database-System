<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "db.php";
include "session.php";

// Check if the student is logged in
checkStudentLogin();

// Get the student ID from the session
$studentID = $_SESSION['studentID'];

// Prepare the query
$query = "SELECT * FROM achievement WHERE StudentID = ?";

// Get the filter value from the form submission
$filterType = isset($_POST['filter-type']) ? $_POST['filter-type'] : '';

// Modify the query to include the filter condition
if (!empty($filterType)) {
    $query .= " AND AchievementType LIKE ?";
    $filterValue = "%" . $filterType . "%";
}

$stmt = mysqli_prepare($link, $query);

if (!empty($filterType)) {
    mysqli_stmt_bind_param($stmt, 'ss', $studentID, $filterValue);
} else {
    mysqli_stmt_bind_param($stmt, 's', $studentID);
}


// Execute the statement
mysqli_stmt_execute($stmt);

// Get the result
$result = mysqli_stmt_get_result($stmt);

// Close the statement
mysqli_stmt_close($stmt);

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
    <title>Student Achievement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <?php include "navbar.php"; ?>
    <div id="layoutSidenav">
        <?php include "sidebar.php"; ?>
        <div id="layoutSidenav_content">
            <main>

                <div class="container">
                    <div class="row flex-lg-nowrap">
                        <div class="col">
                            <div class="e-tabs mb-3 px-3">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item"><a class="nav-link active" href="#">My Achievements list</a></li>
                                </ul>
                            </div>

                            <div class="row">
                               <div class="col-md-4 mb-3">
    <form method="POST">
        <label for="filter-type">Achievement Type:</label>
        <select class="form-control" id="filter-type" name="filter-type">
            <option value="">All Types</option>
            <option value="academic">Academic</option>
            <option value="award">Award</option>
            <option value="competition">Competition</option>
        </select>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">Apply Filter</button>
        </div>
    </form>
</div>

                            </div>

                            <div class="row flex-lg-nowrap">
                                <div class="col mb-3">
                                    <div class="e-panel card">
                                        <div class="card-body">
                                            <div class="card-title">
                                                <h6 class="mr-2"><span>Achievements List</span><small class="px-1"></small></h6>
                                            </div>
                                            <div class="e-table">
                                                <div class="table-responsive table-lg mt-3">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="align-top">
                                                                    <div class="custom-control custom-control-inline custom-checkbox custom-control-nameless m-0">
                                                                        <input type="checkbox" class="custom-control-input" id="all-items">
                                                                        <label class="custom-control-label" for="all-items"></label>
                                                                    </div>
                                                                </th>
                                                                <th class="max-width">Achievement ID</th>
                                                                <th class="sortable">Achievement Name</th>
                                                                <th class="sortable">Achievement Type</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            // Loop through the fetched data and display each row
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                $achievementID = $row['AchievementID'];
                                                                $studentID = $row['StudentID'];
                                                                $achievementType = $row['AchievementType'];
                                                                $date = $row['AchievementName'];

                                                                // Apply the filter condition
                                                                if (!empty($filterType) && !stristr($achievementType, $filterType)) {
                                                                    continue; // Skip this row if it doesn't match the filter
                                                                }

                                                                // Output the table row with the fetched data
                                                                echo "<tr>";
                                                                echo "<td class='align-middle'>
                                                                        <div class='custom-control custom-control-inline custom-checkbox custom-control-nameless m-0 align-top'>
                                                                            <input type='checkbox' class='custom-control-input' id='item-{$achievementID}'>
                                                                            <label class='custom-control-label' for='item-{$achievementID}'></label>
                                                                        </div>
                                                                    </td>";
                                                                echo "<td class='align-middle max-width'>
                                                                        <div class='text-muted d-inline-block align-middle'>{$achievementID}</div>
                                                                    </td>";
                                                                echo "<td class='align-middle'>
                                                                        <div class='text-muted'>{$date}</div>
                                                                    </td>";
                                                                echo "<td class='align-middle text-center'>
                                                                        <div class='text-muted'>{$achievementType}</div>
                                                                    </td>";
                                                                echo "<td class='align-middle text-center'>
                                                                        <div class='btn-group btn-group-sm d-inline-flex'>
                                                                            <a href='studenteditachievement.php?id={$achievementID}' class='btn btn-outline-danger'>Edit/Delete</a>
                                                                        </div>
                                                                    </td>";
                                                                echo "</tr>";
                                                            }

                                                            // Check if no achievements were found
                                                            if (mysqli_num_rows($result) == 0) {
                                                                echo "<tr><td colspan='5'>No achievements found.</td></tr>";
                                                            }

                                                            // Display the "Add" button
                                                            echo "<tr><td colspan='5' class='align-middle text-center'>";
                                                            echo "<div class='btn-group btn-group-sm d-inline-flex'>";
                                                            echo "<a href='studentaddachievement.php' class='btn btn-outline-primary'>Add</a>";
                                                            echo "</div>";
                                                            echo "</td></tr>";
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
            <?php include "footer.php"; ?>
        </div>
    </div>
</body>
</html>
