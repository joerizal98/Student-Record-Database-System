<?php
include 'db.php';
include 'session.php';
checkRecruiterLogin();
$recruiterID = $_SESSION['username3'];

$studentID = $_GET['student_id'];

// Fetch the student's full name
$query = "SELECT CONCAT(FirstName, ' ', LastName) AS FullName FROM student WHERE StudentID = '$studentID'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_assoc($result);
$fullName = $row['FullName'];

// Fetch the student certificates from the database
$query = "SELECT Certificate FROM certificate WHERE StudentID = '$studentID'";
$result = mysqli_query($link, $query);

// Check if there are any certificates
if (mysqli_num_rows($result) > 0) {
    // Create an array to store the certificates
    $certificates = array();

    // Fetch each row from the result set
    while ($row = mysqli_fetch_assoc($result)) {
        // Add the certificate to the array
        $certificates[] = $row;
    }
} else {
    // No certificates found
    $certificates = array();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Recruiter View Certificates</title>
    <link href="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/css/lightbox.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/js/lightbox-plus-jquery.min.js"></script>
    <style>
        /* Style for the page header */
        header {
            background-color: #004466;
            color: white;
            padding: 10px;
            text-align: center;
        }

        /* Style for the page content */
        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        /* Style for the certificate gallery */
        .certificate-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            grid-gap: 20px;
            max-width: 1000px;
            margin-top: 20px;
        }

        /* Style for each certificate image */
        .certificate-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .certificate-item:hover {
            transform: scale(1.05);
        }

        .certificate-image {
            width: 100%;
            height: 250px;
            cursor: pointer;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <?php include 'navbar.php'; ?>
    <div id="layoutSidenav">
        <?php include 'sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <?php if (empty($certificates)): ?>
                    <header>
                        <h1>No certificates found for this student.</h1>
                    </header>
                <?php else: ?>
                    <header>
                        <h1>Certificates for Student <?php echo $fullName; ?></h1>
                    </header>
                    <div class="certificate-gallery">
                        <?php foreach ($certificates as $certificate): ?>
                            <div class="certificate-item">
                                <a href="data:image/jpeg;base64,<?php echo base64_encode($certificate['Certificate']); ?>" data-lightbox="certificates">
                                    <img class="certificate-image" src="data:image/jpeg;base64,<?php echo base64_encode($certificate['Certificate']); ?>" alt="Certificate">
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
</body>
</html>
