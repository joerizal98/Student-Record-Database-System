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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .profile-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f7f7f7;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-container h1 {
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .profile-image {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-image img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-details {
            margin-bottom: 30px;
        }

        .profile-details p {
            margin-bottom: 10px;
            color: #555;
        }

        .profile-details p label {
            font-weight: bold;
        }

        .profile-actions {
            display: flex;
            justify-content: center;
        }

        .profile-actions a {
            margin-right: 10px;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .profile-actions a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <?php include 'navbar.php'; ?>
    <div id="layoutSidenav">
        <?php include 'sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container mt-4">
                    <div class="profile-container">
                        <h1>Student Profile</h1>
                        <div class="profile-image">
                            <?php if ($profile['ProfileImage']): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($profile['ProfileImage']); ?>" alt="Profile Image">
                            <?php else: ?>
                                <img src="images/default-profile-image.jpg" alt="Profile Image">
                            <?php endif; ?>
                        </div>
                        <div class="profile-details">
                            <p><label>Student ID:</label> <?php echo $profile['StudentID']; ?></p>
                            <p><label>Full Name:</label> <?php echo $profile['FirstName'] . ' ' . $profile['LastName']; ?></p>
                            <p><label>Email:</label> <?php echo $profile['Email']; ?></p>
                            <p><label>Username:</label> <?php echo $profile['UserName']; ?></p>
                            <p><label>Phone Number:</label> <?php echo $profile['PhoneNumber']; ?></p>
                            <p><label>Address:</label> <?php echo $profile['Address']; ?></p>
                        </div>
                        <div class="profile-actions">
                            <a href="studenteditprofile.php" class="btn btn-primary">Edit Profile</a>
                        </div>
                    </div>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
</body>
</html>
