<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "session.php";
include "db.php";

// Check if the admin is logged in
checkAdminLogin();

// Check if the form is submitted
if (isset($_POST['delete_teacher'])) {
    // Get the selected course ID from the form
    $teacherid = $_POST['teacherid'];

    // Prepare and execute the DELETE query
    $delete_query = "DELETE FROM teacher WHERE TeacherID = '$teacherid'";
    $result = $link->query($delete_query);

    // Check if the query was successful
    if ($result) {
      echo "<script>alert('Teacher deleted succesfully');</script>";
    } else {
        echo "<script>alert('Error deleting Teacher');</script>" . $link->error;
    }
}
// Get list of courses
$teacher_sql = "SELECT * FROM teacher";
$teacher_result = $link->query($teacher_sql);
// Refresh course list
if (isset($_POST['refresh'])) {
    // Fetch the updated list of courses from the database
    $teacher_sql = "SELECT * FROM teacher";
    $teacher_result1 = $link->query($teacher_sql);
}
else {
    // Get initial list of courses
    $teacher_sql = "SELECT * FROM teacher";
    $teacher_result1 = $link->query($teacher_sql);
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

                        <h1 class="mt-4">Delete teacher</h1>
       <form method="post" action="">
        <select name="teacherid">
            <?php
            if ($teacher_result->num_rows > 0) {
                while ($row = $teacher_result->fetch_assoc()) {
                $teacherid = $row['TeacherID'];


                    echo "<option value='$teacherid'>$teacherid:</option>";
                }
            }
            ?>
        </select>
        <button type="submit" name="delete_teacher">Delete teacher</button>
        </form>

    <h2 class="mt-4">Teacher List</h2>



    <table id="datatablesSimple">
        <thead>
        <tr>
            <th>Teacher ID</th>
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
        if ($teacher_result1->num_rows > 0) {
            while ($row = $teacher_result1->fetch_assoc()) {
                $teacher_id = $row['TeacherID'];
                $teacheruser_name = $row['UserName'];
                $teacher_password = $row['Password'];
                 $teacherfirst_name = $row['FirstName'];
                  $teacherlast_name = $row['LastName'];
                   $teacher_phone_number = $row['PhoneNumber'];
                    $teacher_address = $row['Address'];
                     $registration_date = $row['RegistrationDate'];


                echo "<tr><td>$teacher_id</td><td>$teacheruser_name</td><td>$teacher_password</td><td> $teacherfirst_name</td><td> $teacherlast_name</td><td>$teacher_phone_number</td><td>$teacher_address</td><td>$registration_date</td></tr>";
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
