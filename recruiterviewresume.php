<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Recruiter Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .resume-card {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #f7f7f7;
            position: relative; /* Added */
        }

        .resume-card .profile {
            display: flex;
            align-items: center;
        }

        .resume-card .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 10px; /* Changed to square shape */
            overflow: hidden;
            margin-right: 20px;
        }

        .resume-card .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .resume-card .profile-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .resume-card .profile-details {
            font-size: 18px;
            color: #777;
            margin-bottom: 10px;
        }

        .resume-card .profile-email {
            display: flex;
            align-items: center;
            font-size: 18px;
            color: #777;
            margin-bottom: 10px; /* Add margin bottom to create space between phone and email */
        }

        .resume-card .profile-email i {
            margin-right: 5px; /* Add margin right to create space between icon and details */
        }

        .resume-card .profile-email span {
            margin-left: 5px; /* Add margin left to create space between icon and details */
        }

        .resume-card .section {
            margin-bottom: 30px;
        }

        .resume-card .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .resume-card .section-title button {
            display: none;
        }

        .resume-card .section-content {
            font-size: 18px;
            line-height: 1.5;
            color: #555;
        }

        .resume-card textarea {
            width: 100%;
            height: auto;
            resize: vertical;
        }

        .buttons-container {
            position: absolute;
            bottom: 20px;
            right: 20px;
        }

        .view-achievement-button,
        .view-certificate-button {
            margin-left: 10px;
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
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <?php
                            include 'db.php';
                            // Check if the recruiter is logged in
                            include 'session.php';
                            checkRecruiterLogin();
                            $recruiterID = $_SESSION['username3'];

                            $studentID = $_GET['student_id']; // Update 'studentID' to 'student_id'


                            // Fetch data from the eResume table joined with the student table
                            $query = "SELECT e.*, CONCAT(s.FirstName, ' ', s.LastName) AS full_name, s.Email, s.Address, s.ProfileImage, s.PhoneNumber
                                        FROM eResume e
                                        JOIN student s ON e.StudentID = s.StudentID
                                        WHERE e.StudentID = '$studentID'";
                            $result = mysqli_query($link, $query);

                            if (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                ?>

                                <div class="resume-card">
                                    <div class="profile">
                                        <div class="profile-image">
                                            <?php if (!empty($row['ProfileImage'])): ?>
                                                <img src="data:image/jpeg;base64,<?php echo base64_encode($row['ProfileImage']); ?>" alt="Profile Image">
                                            <?php else: ?>
                                                <img src="images/profile.jpg" alt="Profile Image">
                                            <?php endif; ?>
                                        </div>
                                        <div class="profile-details">
                                            <div class="profile-name"><?php echo $row['full_name']; ?></div>
                                            <div class="profile-email"><i class="fas fa-phone"></i><span><?php echo $row['PhoneNumber']; ?></span></div>
                                            <div class="profile-email"><i class="fas fa-envelope"></i><span><?php echo $row['Email']; ?></span></div>
                                            <div class="profile-email"><i class="fa fa-map-marker"></i><span><?php echo $row['Address']; ?></span></div>
                                        </div>
                                    </div>
                                    <div class="section">
                                        <div class="section-title">
                                            <span>Summary</span>
                                        </div>
                                        <div class="section-content">
                                            <?php echo $row['Summary']; ?>
                                        </div>
                                    </div>

                                    <div class="section">
    <div class="section-title">
        <span>Education</span>
    </div>
    <div class="section-content">
        <?php echo nl2br($row['Education']); ?>
    </div>
</div>

<div class="section">
    <div class="section-title">
        <span>Experience</span>
    </div>
    <div class="section-content">
        <?php echo nl2br($row['Experience']); ?>
    </div>
</div>

<div class="section">
    <div class="section-title">
        <span>Skills</span>
    </div>
    <div class="section-content">
        <?php echo nl2br($row['Skills']); ?>
    </div>
</div>

                                    <!--  <div class="section">
                                        <div class="section-title">
                                            <span>Projects</span>
                                        </div>
                                        <div class="section-content">
                                            <?php echo $row['projects']; ?>
                                        </div>
                                    </div>-->

                                    <!-- Add achievement and certificate buttons -->
                                    <div class="buttons-container">
                                       <button class="view-achievement-button" onclick="redirectToViewAchievement('<?php echo $row['StudentID']; ?>')">View Achievement</button>
                                       <button class="view-achievement-button" onclick="redirectToViewCertificate('<?php echo $row['StudentID']; ?>')">View Certificate</button>
                                    </div>
                                </div>

                                <?php
                            } else {
                                echo "No eResume found for the specified student.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
    function redirectToViewAchievement(studentID) {
        // Redirect to recruiterviewachievement.php with the student ID as a query parameter
        window.location.href = 'recruiterviewachievement.php?student_id=' + encodeURIComponent(studentID);
    }

      function redirectToViewCertificate(studentID) {
        // Redirect to recruiterviewachievement.php with the student ID as a query parameter
        window.location.href = 'recruiterviewcertificate.php?student_id=' + encodeURIComponent(studentID);
    }
</script>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/main.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
