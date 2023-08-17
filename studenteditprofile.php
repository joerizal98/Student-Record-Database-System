<?php
include 'db.php';
include 'session.php';

// Check if the student is logged in
checkStudentLogin();

$studentID = $_SESSION['studentID'];

// Fetch the student profile from the database
$profileQuery = "SELECT * FROM student WHERE StudentID = '$studentID'";
$profileResult = mysqli_query($link, $profileQuery);
$profile = mysqli_fetch_assoc($profileResult);

// Update student profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $phoneNumber = $_POST['phoneNumber'];
    $address = $_POST['address'];
    $email = $_POST['email'];

    // Upload profile image
    if (!empty($_FILES['profileImage']['name'])) {
        $profileImage = $_FILES['profileImage']['tmp_name'];
        $profileImageContent = addslashes(file_get_contents($profileImage));
        $updateQuery = "UPDATE student SET ProfileImage = '$profileImageContent' WHERE StudentID = '$studentID'";
        mysqli_query($link, $updateQuery);
    }

    $updateQuery = "UPDATE student SET FirstName = '$firstName', LastName = '$lastName', UserName = '$username', PhoneNumber = '$phoneNumber', Address = '$address', Email = '$email' WHERE StudentID = '$studentID'";
    if (mysqli_query($link, $updateQuery)) {
        // Profile updated successfully
        echo '<script>alert("Profile updated successfully.");</script>';
    } else {
        // Error updating profile
        echo '<script>alert("Error updating profile. Please try again.");</script>';
    }

    echo '<script>window.location.href = "studentprofile.php";</script>';
    exit();
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
    <title>Edit Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        textarea,
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        input[type="file"] {
            margin-top: 5px;
        }

        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .btn-danger {
            background-color: #f44336;
            border-color: #f44336;
        }

        .btn {
            padding: 10px 20px;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary:hover,
        .btn-danger:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <?php include 'navbar.php'; ?>
    <div id="layoutSidenav">
        <?php include 'sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container">
                    <h1>Edit Student Profile</h1>
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" name="firstName" id="firstName" value="<?php echo $profile['FirstName']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" name="lastName" id="lastName" value="<?php echo $profile['LastName']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" value="<?php echo $profile['UserName']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="text" name="phoneNumber" id="phoneNumber" value="<?php echo $profile['PhoneNumber']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea name="address" id="address" required><?php echo $profile['Address']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" value="<?php echo $profile['Email']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="profileImage">Profile Image</label>
                            <input type="file" name="profileImage" id="profileImage">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="studentprofile.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
</body>
</html>
