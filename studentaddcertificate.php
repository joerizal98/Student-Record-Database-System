<?php
include 'db.php';
include 'session.php';

// Check if the student is logged in
checkStudentLogin();

$studentID = $_SESSION['studentID'];

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];

    // Validate and handle the uploaded certificate image
    if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] === UPLOAD_ERR_OK) {
        $certificate = file_get_contents($_FILES['certificate']['tmp_name']);

        // Prepare and execute the insert query
        $insertQuery = "INSERT INTO certificate (StudentID, Certificate, Description) VALUES ('$studentID', ?, '$description')";
        $insertStmt = mysqli_prepare($link, $insertQuery);
        mysqli_stmt_bind_param($insertStmt, 's', $certificate);

        if (mysqli_stmt_execute($insertStmt)) {
            // Redirect to the certificate gallery after successful insertion
            header('Location: studentcertificate.php');
            exit;
        } else {
            $error = "Failed to insert the certificate. Please try again.";
        }

        mysqli_stmt_close($insertStmt);
    } else {
        $error = "Error uploading the certificate. Please try again.";
    }
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
    <title>Add Certificate</title>
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
                <div class="container mt-5">
                    <h1>Add New Certificate</h1>
                    <?php if (isset($error)) : ?>
                        <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form method="POST" enctype="multipart/form-data" class="mt-4">
                        <div class="mb-3">
                            <label for="certificate" class="form-label">Please upload your certificate (only image file accepted)</label>
                            <input type="file" class="form-control" id="certificate" name="certificate" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload Certificate</button>
                    </form>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
</body>
</html>
