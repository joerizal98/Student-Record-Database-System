<?php
require_once 'db.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];

    // Check if the email exists in any of the user tables
    $adminQuery = "SELECT * FROM admin WHERE Email = '$email'";
    $teacherQuery = "SELECT * FROM teacher WHERE Email = '$email'";
    $studentQuery = "SELECT * FROM student WHERE Email = '$email'";
    $recruiterQuery = "SELECT * FROM recruiter WHERE Email = '$email'";

    // Perform the database queries
    $adminResult = mysqli_query($link, $adminQuery);
    $teacherResult = mysqli_query($link, $teacherQuery);
    $studentResult = mysqli_query($link, $studentQuery);
    $recruiterResult = mysqli_query($link, $recruiterQuery);

    // Check if any query returned a result
    if (mysqli_num_rows($adminResult) == 1 || mysqli_num_rows($teacherResult) == 1 || mysqli_num_rows($studentResult) == 1 || mysqli_num_rows($recruiterResult) == 1) {
        // Generate a unique token for password reset
        $token = md5(uniqid());

        // Store the token and email in a password reset table
        $resetQuery = "INSERT INTO password_reset (Email, Token) VALUES ('$email', '$token')";
        mysqli_query($link, $resetQuery);

        // Send the password reset link to the user's email
        $resetLink = "http://example.com/reset_password.php?token=$token";
        // Replace "example.com" with your actual domain name

        // You can use a library like PHPMailer to send the email
        // Here's an example using PHPMailer:
        // require 'vendor/autoload.php';
        // $mail = new PHPMailer\PHPMailer\PHPMailer();
        // ...
        // $mail->addAddress($email);
        // $mail->Subject = "Password Reset";
        // $mail->Body = "Click the following link to reset your password: $resetLink";
        // $mail->send();

        // Redirect the user to a success page
        header("Location: password_reset_success.php");
        exit();
    } else {
        // Email does not exist in any user table
        $error = "Email address not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>
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

                <form class="login100-form validate-form" method="POST" action="">
                    <span class="login100-form-title">
                        Forgot Password
                    </span>

                    <div class="wrap-input100 validate-input" data-validate="Email is required">
                        <input class="input100" type="email" name="email" placeholder="Email">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            Reset Password
                        </button>
                    </div>

                    <?php if (isset($error)) { ?>
                        <p><?php echo $error; ?></p>
                    <?php } ?>

                    <div class="text-center p-t-136">
                        <a class="txt2" href="login.php">
                            Back to Login
                            <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
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
