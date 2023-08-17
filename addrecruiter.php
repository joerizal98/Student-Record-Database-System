<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "session.php";
include "db.php";

// Check if the admin is logged in
checkAdminLogin();
// Add recruiter
if (isset($_POST['add_recruiter'])) {

    $recruiterid = $_POST['recruiterid'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $registration_date = date('Y-m-d', strtotime($_POST['registration_date']));


    // Check if recruiter already exists
    $check_sql = "SELECT * FROM recruiter WHERE RecruiterID = '$recruiterid'";
    $check_result = $link->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "<script>alert('Recruiter already exists');</script>";
    } else {
        $sql = "INSERT INTO recruiter (RecruiterID, UserName, Password, FirstName, LastName, PhoneNumber, Address, RegistrationDate) VALUES ('$recruiterid','$username','$password', '$firstname', '$lastname', '$phone', '$address', '$registration_date')";

        if ($link->query($sql) === TRUE) {
            echo "<script>alert('Recruiter added successfully');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $link->error;
        }
    }
}

// Refresh recruiter list
if (isset($_POST['refresh'])) {
    // Fetch the updated list of recruiters from the database
    $recruiter_sql = "SELECT * FROM recruiter";
    $recruiter_result = $link->query($recruiter_sql);
} else {
    // Get initial list of recruiters
    $recruiter_sql = "SELECT * FROM recruiter";
    $recruiter_result = $link->query($recruiter_sql);
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
                    <h1 class="mt-4">Add New Recruiter</h1>
                    <br>
                    <form method="post" action="">
                        <input type="text" name="recruiterid" placeholder="Recruiter ID">
                        <input type="text" name="username" placeholder="User Name">
                        <input type="password" name="password" placeholder="Password">
                        <input type="text" name="firstname" placeholder="First Name">
                        <input type="text" name="lastname" placeholder="Last Name">
                        <input type="tel" name="phone" placeholder="Phone Number">
                        <input type="text" name="address" placeholder="Address">
                       <input type="date" name="registration_date" placeholder="Registration Date">

                        <button type="submit" name="add_recruiter">Add Recruiter</button>
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
                        if ($recruiter_result->num_rows > 0) {
                            while ($row = $recruiter_result->fetch_assoc()) {
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
