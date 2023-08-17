<?php include "session.php";
include "db.php";

// Check if the admin is logged in
checkAdminLogin();

?>
<?php

// Edit student
if (isset($_POST['edit_student'])) {
    $studentidd = $_POST['studentidd'];
    $new_studentidd = $_POST['new_studentidd'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "UPDATE student SET StudentID = '$new_studentidd', UserName = '$username', Password = '$password', FirstName = '$firstname', LastName = '$lastname', PhoneNumber = '$phone', Address = '$address' WHERE StudentID = '$studentidd'";

    if ($link->query($sql) === TRUE) {
        echo "<script>alert('Student updated successfully');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $link->error;
    }
}
// Get list of courses
$student_sql = "SELECT * FROM student";
$student_result = $link->query($student_sql);
// Refresh course list
if (isset($_POST['refresh'])) {
    // Fetch the updated list of courses from the database
    $student_sql = "SELECT * FROM student";
    $student_result1 = $link->query($student_sql);
}
else {
    // Get initial list of courses
    $student_sql = "SELECT * FROM student";
    $student_result1 = $link->query($student_sql);
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

                        <h1 class="mt-4">Edit Student</h1>
        <form method="post" action="">
                <select name="studentidd">
                        <?php
                        if ($student_result->num_rows > 0) {
                         while ($row = $student_result->fetch_assoc()) {
                $studentid = $row['StudentID'];


                    echo "<option value='$studentid'>$studentid:</option>";
                }
            }
            ?>
                </select>
                <br><br>
                <input type="text" name="new_studentidd" placeholder="Student ID">
        <input type="text" name="username" placeholder="User Name">
        <input type="text" name="password" placeholder="Password">
        <input type="text" name="firstname" placeholder="First Name">
        <input type="text" name="lastname" placeholder="Last Name">
        <input type="tel" name="phone" placeholder="Phone Number">
        <input type="text" name="address" placeholder="Address">
                <button type="submit" name="edit_student">Edit Student</button>
        </form>

    <h2 class="mt-4">Student List</h2>

     <table id="datatablesSimple">
        <thead>
        <tr>
            <th>Student ID</th>
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
        if ($student_result1->num_rows > 0) {
            while ($row = $student_result1->fetch_assoc()) {
                $student_id = $row['StudentID'];
                $studentuser_name = $row['UserName'];
                $student_password = $row['Password'];
                 $studentfirst_name = $row['FirstName'];
                  $studentlast_name = $row['LastName'];
                   $student_phone_number = $row['PhoneNumber'];
                    $student_address = $row['Address'];
                    $registration_date = $row['RegistrationDate'];


                echo "<tr><td>$student_id</td><td>$studentuser_name</td><td>$student_password</td><td> $studentfirst_name</td><td> $studentlast_name</td><td>$student_phone_number</td><td>$student_address</td><td>$registration_date</td></tr>";
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
