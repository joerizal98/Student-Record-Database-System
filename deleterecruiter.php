<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "session.php";
include "db.php";

// Check if the admin is logged in
checkAdminLogin();
// Check if the form is submitted
if (isset($_POST['delete_recruiter'])) {
    // Get the selected course ID from the form
    $recruiterid = $_POST['recruiterid'];

    // Prepare and execute the DELETE query
    $delete_query = "DELETE FROM recruiter WHERE RecruiterID = '$recruiterid'";
    $result = $link->query($delete_query);

    // Check if the query was successful
    if ($result) {
      echo "<script>alert('Recruiter deleted succesfully');</script>";
    } else {
        echo "<script>alert('Error deleting recruiter');</script>" . $link->error;
    }
}
// Get list of courses
$recruiter_sql = "SELECT * FROM recruiter";
$recruiter_result = $link->query($recruiter_sql);
// Refresh course list
if (isset($_POST['refresh'])) {
    // Fetch the updated list of courses from the database
    $recruiter_sql = "SELECT * FROM recruiter";
    $recruiter_result1 = $link->query($recruiter_sql);
}
else {
    // Get initial list of courses
    $recruiter_sql = "SELECT * FROM recruiter";
    $recruiter_result1 = $link->query($recruiter_sql);
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
    <title>Admin Dashboard</title>
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

                        <h1 class="mt-4">Delete recruiter</h1>
       <form method="post" action="">
        <select name="recruiterid">
            <?php
            if ($recruiter_result->num_rows > 0) {
                while ($row = $recruiter_result->fetch_assoc()) {
                $recruiterid = $row['RecruiterID'];


                    echo "<option value='$recruiterid'>$recruiterid:</option>";
                }
            }
            ?>
        </select>
        <button type="submit" name="delete_recruiter">Delete recruiter</button>
        </form>

    <h2 class="mt-4">Courses List</h2>


   <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Recruiter ID</th>
                                <th>User Name</th>
                                <th>Password</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>Registration Date</th>
                            </tr>
                        </thead>
                        <?php
                        if ($recruiter_result1->num_rows > 0) {
                            while ($row = $recruiter_result1->fetch_assoc()) {
                                $recruiter_id = $row['RecruiterID'];
                                $recruiteruser_name = $row['UserName'];
                                $recruiter_password = $row['Password'];
                                $recruiterfirst_name = $row['FirstName'];
                                $recruiterlast_name = $row['LastName'];
                                $recruiter_phone_number = $row['PhoneNumber'];
                                $recruiter_address = $row['Address'];
                                $registration_date = $row['RegistrationDate'];

                                echo "<tr><td>$recruiter_id</td><td>$recruiteruser_name</td><td>$recruiter_password</td><td> $recruiterfirst_name</td><td> $recruiterlast_name</td><td>$recruiter_phone_number</td><td>$recruiter_address</td><td>$registration_date</td></tr>";
                            }
                        }
                        ?>
                    </table>
     <form method="post" action="">
        <button type="submit" name="refresh">Refresh List</button>
    </form>

                    </div>
                </main>
                 <?php include 'footer.php'; ?>
            </div>
        </div>

    </body>
</html>


<?php
$link->close();
?>
