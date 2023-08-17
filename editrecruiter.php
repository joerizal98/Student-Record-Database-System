<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "session.php";
include "db.php";

// Check if the admin is logged in
checkAdminLogin();
// Edit student
if (isset($_POST['edit_recruiter'])) {
    $recruiteridd = $_POST['recruiteridd'];
    $new_recruiteridd = $_POST['new_recruiteridd'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "UPDATE recruiter SET RecruiterID = '$new_recruiteridd', UserName = '$username', Password = '$password', FirstName = '$firstname', LastName = '$lastname', PhoneNumber = '$phone', Address = '$address' WHERE RecruiterID = '$recruiteridd'";

    if ($link->query($sql) === TRUE) {
        echo "<script>alert('recruiter updated successfully');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $link->error;
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

                        <h1 class="mt-4">Edit recruiter</h1>
        <form method="post" action="">
                <select name="recruiteridd">
                        <?php
                        if ($recruiter_result->num_rows > 0) {
                         while ($row = $recruiter_result->fetch_assoc()) {
                $recruiterid = $row['RecruiterID'];


                    echo "<option value='$recruiterid'>$recruiterid:</option>";
                }
            }
            ?>
                </select>
                <br><br>
                <input type="text" name="new_recruiteridd" placeholder="Recruiter ID">
        <input type="text" name="username" placeholder="User Name">
         <input type="text" name="password" placeholder="Password">
        <input type="text" name="firstname" placeholder="First Name">
        <input type="text" name="lastname" placeholder="Last Name">
        <input type="tel" name="phone" placeholder="Phone Number">
        <input type="text" name="address" placeholder="Address">
                <button type="submit" name="edit_recruiter">Edit recruiter</button>
        </form>

    <h2 class="mt-4">Recruiter List</h2>

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
