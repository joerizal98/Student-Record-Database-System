<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "session.php";
include "db.php";

// Check if the admin is logged in
checkAdminLogin();

// Add student
if (isset($_POST['add_teacher'])) {

    $teacherid = $_POST['teacherid'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $registration_date = date('Y-m-d', strtotime($_POST['registration_date']));

     // Check if teacher already exists
    $check_sql = "SELECT * FROM teacher WHERE teacherID = '$teacherid'";
    $check_result = $link->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "<script>alert('teacher already exists');</script>";
    } else {
        $sql = "INSERT INTO teacher (TeacherID, UserName, Password, FirstName, LastName, PhoneNumber, Address, RegistrationDate) VALUES ('$teacherid','$username', '$password', '$firstname', '$lastname', '$phone', '$address','$registration_date')";

        if ($link->query($sql) === TRUE) {
           echo "<script>alert('Teacher added succesfully');</script>";   ;
        } else {
            echo "Error: " . $sql . "<br>" . $link->error;
        }
    }
}
// Refresh course list
if (isset($_POST['refresh'])) {
    // Fetch the updated list of courses from the database
    $teacher_sql = "SELECT * FROM teacher";
    $teacher_result = $link->query($teacher_sql);
}
else {
    // Get initial list of courses
    $teacher_sql = "SELECT * FROM teacher";
    $teacher_result = $link->query($teacher_sql);
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

                        <h1 class="mt-4">Add New teacher</h1>
                                        <br>
                         <form method="post" action="">
        <input type="text" name="teacherid" placeholder="Teacher ID">
        <input type="text" name="username" placeholder="User Name">
        <input type="password" name="password" placeholder="Password">
        <input type="text" name="firstname" placeholder="First Name">
        <input type="text" name="lastname" placeholder="Last Name">
        <input type="tel" name="phone" placeholder="Phone Number">
        <input type="text" name="address" placeholder="Address">
            <input type="date" name="registration_date" placeholder="Registration Date">
        <button type="submit" name="add_teacher">Add teacher</button>
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
        if ($teacher_result->num_rows > 0) {
            while ($row = $teacher_result->fetch_assoc()) {
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
