<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "session.php";
include "db.php";

// Check if the admin is logged in
checkAdminLogin();

// Refresh registration list
if (isset($_POST['refresh'])) {
    // Fetch the updated list of registrations from the database
    $registration_sql = "SELECT StudentID AS UserID, UserName, FirstName, LastName, 'student' AS Role, Approval FROM student
                         UNION ALL
                         SELECT RecruiterID AS UserID, UserName, FirstName, LastName, 'recruiter' AS Role, Approval FROM recruiter
                         UNION ALL
                         SELECT TeacherID AS UserID, UserName, FirstName, LastName, 'teacher' AS Role, Approval FROM teacher";
    $registration_result = $link->query($registration_sql);
} else {
    // Get initial list of registrations
    $registration_sql = "SELECT StudentID AS UserID, UserName, FirstName, LastName, 'student' AS Role, Approval FROM student
                         UNION ALL
                         SELECT RecruiterID AS UserID, UserName, FirstName, LastName, 'recruiter' AS Role, Approval FROM recruiter
                         UNION ALL
                         SELECT TeacherID AS UserID, UserName, FirstName, LastName, 'teacher' AS Role, Approval FROM teacher";
    $registration_result = $link->query($registration_sql);
}

// Save approval status
if (isset($_POST['save_approval'])) {
    $user_ids = $_POST['user_id'];
    $user_roles = $_POST['user_role'];
    $approval_statuses = $_POST['approval_status'];

    for ($i = 0; $i < count($user_ids); $i++) {
        $user_id = $user_ids[$i];
        $user_role = $user_roles[$i];
        $approval_status = $approval_statuses[$i];

        // Update the approval status in the respective user table
        $update_sql = "UPDATE $user_role SET Approval = '$approval_status' WHERE $user_role"."ID = '$user_id'";
        if ($link->query($update_sql) !== TRUE) {
            // Error message
            $error_message = "Error updating approval status: " . $link->error;
        }
    }

    // Redirect or display success message
    if (isset($error_message)) {
        // Display error message
        echo "<script>alert('$error_message');</script>";
    } else {
        // Success message
        $success_message = "Approval status updated successfully";
        echo "<script>alert('$success_message');</script>";
    }
}

// Delete approval status
if (isset($_POST['delete_approval'])) {
    $user_id = $_POST['delete_user_id'];
    $user_role = $_POST['delete_user_role'];

    // Delete the approval status in the respective user table
    $delete_sql = "UPDATE $user_role SET Approval = NULL WHERE $user_role"."ID = '$user_id'";
    if ($link->query($delete_sql) === TRUE) {
        // Success message
        $success_message = "Approval deleted successfully";
        echo "<script>alert('$success_message');</script>";
    } else {
        // Error message
        $error_message = "Error deleting approval: " . $link->error;
        echo "<script>alert('$error_message');</script>";
    }
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
    <title>User Management</title>
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
                    <h1 class="mt-4">New User Approval</h1>
                    <br>

                  <form method="post" action="">
    <table id="datatablesSimple">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Role</th>
                <th>Approval Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($registration_result->num_rows > 0) {
                while ($row = $registration_result->fetch_assoc()) {
                    $user_id = $row['UserID'];
                    $username = $row['UserName'];
                    $first_name = $row['FirstName'];
                    $last_name = $row['LastName'];
                    $role = $row['Role'];
                    $approval_status = $row['Approval'];

                    echo "<tr>
                            <td>$user_id</td>
                            <td>$username</td>
                            <td>$first_name</td>
                            <td>$last_name</td>
                            <td>$role</td>
                            <td>
                                <select name='approval_status[]'>
                                    <option value='pending' " . ($approval_status == 'pending' ? 'selected' : '') . ">Pending</option>
                                    <option value='approved' " . ($approval_status == 'approved' ? 'selected' : '') . ">Approved</option>
                                    <option value='rejected' " . ($approval_status == 'rejected' ? 'selected' : '') . ">Rejected</option>
                                    <option value='' " . ($approval_status == '' ? 'selected' : '') . ">-</option>
                                </select>
                            </td>
                            <td>
                                <input type='hidden' name='user_id[]' value='$user_id'>
                                <input type='hidden' name='user_role[]' value='$role'>
                                <button type='submit' name='save_approval'>Save</button>
                            </td>
                        </tr>";
                }
            }
            ?>
        </tbody>
    </table>
    <button type="submit" name="refresh">Refresh List</button>
</form>

<form method="post" action="">
    <input type='hidden' name='delete_user_id' value=''>
    <input type='hidden' name='delete_user_role' value=''>
    <button type='submit' name='delete_approval'>Delete</button>
</form>
                </div>

            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/script.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>

<?php
// Save approval status
if (isset($_POST['save_approval'])) {
    $user_ids = $_POST['user_id'];
    $user_roles = $_POST['user_role'];
    $approval_statuses = $_POST['approval_status'];

    for ($i = 0; $i < count($user_ids); $i++) {
        $user_id = $user_ids[$i];
        $user_role = $user_roles[$i];
        $approval_status = $approval_statuses[$i];

        // Update the approval status in the respective user table
        $update_sql = "UPDATE $user_role SET Approval = '$approval_status' WHERE UserID = '$user_id'";
        if ($link->query($update_sql) !== TRUE) {
            // Error message
            $error_message = "Error updating approval status: " . $link->error;
        }
    }

    // Redirect or display success message
    if (isset($error_message)) {
        // Display error message
        echo "<script>alert('$error_message');</script>";
    }
}

$link->close();
?>