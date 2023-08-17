<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'db.php';

// Define the initial approval status
define('INITIAL_APPROVAL_STATUS', 'pending');

// Function to insert a new record into the eResume table
function insertResume($studentID) {
    global $link;

    // Prepare the query
    $query = "INSERT INTO eresume (studentID) VALUES ('$studentID')";

    // Execute the query
    $result = mysqli_query($link, $query);

    // Check if the query was successful
    if ($result) {
        echo "eResume created successfully";
    } else {
        echo "Failed to create eResume";
    }
}

// Function to check if the UserName already exists in the database
function isUsernameTaken($username) {
    global $link;

    // Prepare the queries
    $queries = [
        "SELECT UserName FROM student WHERE UserName = '$username'",
        "SELECT UserName FROM teacher WHERE UserName = '$username'",
        "SELECT UserName FROM recruiter WHERE UserName = '$username'"
    ];

    // Iterate through the queries
    foreach ($queries as $query) {
        $result = mysqli_query($link, $query);

        // Check if any rows were returned
        if (mysqli_num_rows($result) > 0) {
            return true; // Username is taken
        }
    }

    return false; // Username is available
}

// Check if form data is present
if (!empty($_POST['UserName']) && !empty($_POST['Password']) && !empty($_POST['FirstName']) && !empty($_POST['LastName']) && !empty($_POST['PhoneNumber']) && !empty($_POST['Address']) && !empty($_POST['role'])) {
    // Get form data
    $username = $_POST['UserName'];
    $password = $_POST['Password'];
    $firstname = $_POST['FirstName'];
    $lastname = $_POST['LastName'];
    $phone = $_POST['PhoneNumber'];
    $address = $_POST['Address'];
   /* $email = $_POST['Email']; */
    $role = $_POST['role'];

    // Validate form data (you can add more validation if needed)

    // Check if the username is already taken
    if (isUsernameTaken($username)) {
        echo "<script>alert('Username is already taken. Please enter a new one.');</script>";
    } else {
        // Prepare and execute the query based on the selected role
        $table = '';
        switch ($role) {
            case 'student':
                $table = 'student';
                break;
            case 'teacher':
                $table = 'teacher';
                break;
            case 'recruiter':
                $table = 'recruiter';
                break;
            default:
                // Invalid role, handle accordingly
                break;
        }

        if ($table !== '') {
            // Add registration date to the query
            $registrationDate = date('Y-m-d H:i:s');
           $query = "INSERT INTO $table (UserName, Password, FirstName, LastName, PhoneNumber, Address,  RegistrationDate, Approval)
            VALUES ('$username', '$password', '$firstname', '$lastname', '$phone', '$address', '$registrationDate', '" . INITIAL_APPROVAL_STATUS . "')";
             $result = mysqli_query($link, $query);
            if ($result) {
                // Get the student ID of the newly registered student
                $studentID = mysqli_insert_id($link);

                // Insert a new record into the eResume table for the student
                insertResume($studentID);

                echo "<script>
                    alert('Successfully Registered');
                    window.location.href = 'login.php';
                </script>";
                exit();
            } else {
                // Registration failed, handle accordingly
                $error = "Registration failed. Please try again.";
                echo "<script>alert('$error');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!--===============================================================================================-->
</head>
<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="images/img-01.png" alt="IMG">
                </div>

                <form class="login100-form validate-form" method="POST" action="register.php">
                    <span class="login100-form-title">
                        Sign Up
                    </span>

                    <div class="wrap-input100 validate-input" data-validate="Username is required">
                        <input class="input100" type="text" name="UserName" placeholder="Username">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="Password" placeholder="Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Full Name is required">
                        <input class="input100" type="text" name="FirstName" placeholder="First Name">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Last Name is required">
                        <input class="input100" type="text" name="LastName" placeholder="Last Name">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Phone Number is required">
                        <input class="input100" type="text" name="PhoneNumber" placeholder="Phone Number">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Address is required">
                        <input class="input100" type="text" name="Address" placeholder="Address">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                        </span>
                    </div>

                   <!-- <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                        <input class="input100" type="text" name="Email" placeholder="Email">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>-->

                    <div class="wrap-input100 validate-input" data-validate="Role is required">
                        <select class="input100" name="role">
                            <option value="">Select Role</option>
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                            <option value="recruiter">Recruiter</option>
                        </select>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn">
                            Sign Up
                        </button>
                    </div>

                    <div class="text-center p-t-12">
                        <span class="txt1">
                            Already have an account?
                        </span>
                        <a class="txt2" href="login.php">
                            Login here
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--===============================================================================================-->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/tilt/tilt.jquery.min.js"></script>
    <script>
        $('.js-tilt').tilt({
            scale: 1.1
        })
    </script>
    <!--===============================================================================================-->
    <script src="js/main.js"></script>

</body>
</html>
