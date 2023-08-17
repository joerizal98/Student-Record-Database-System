<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';
include 'session.php';

// Check if the student is logged in
checkStudentLogin();

$studentID = $_SESSION['studentID'];

// Fetch the certificate images of the currently logged-in student from the database
$certificatesQuery = "SELECT * FROM certificate WHERE StudentID = '$studentID'";
$certificatesResult = mysqli_query($link, $certificatesQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Student Certificate Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <style>
        .certificate-item {
            position: relative;
            display: inline-block;
            margin: 10px;
            text-align: center;
            cursor: pointer;
        }

        .certificate-item:hover img {
            transform: scale(1.1);
        }

        .certificate-item .certificate-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .certificate-item:hover .certificate-overlay {
            opacity: 1;
        }

        .certificate-item .certificate-overlay i {
            font-size: 3rem;
        }

        .edit-button {
            background-color: transparent;
            border: none;
            color: #fff;
            cursor: pointer;
            display: none;
        }

        .edit-form {
            display: none;
        }

        .delete-button {
            background-color: transparent;
            border: none;
            color: #fff;
            cursor: pointer;
            display: none;
        }

        .cancel-button {
            background-color: transparent;
            border: none;
            color: #fff;
            cursor: pointer;
        }

        .cancel-button.hide {
            display: none;
        }
        .certificate-image {
    height: 200px; /* Set the desired height */
    width: 300px; /* Set the desired width */
    object-fit: cover; /* Maintain aspect ratio and cover the container */
}
    </style>
</head>
<body class="sb-nav-fixed">
    <?php include 'navbar.php'; ?>
    <div id="layoutSidenav">
        <?php include 'sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container mt-5">
                    <div class="row">
                        <div class="col-9">
                            <h1>My Certificate Gallery</h1>
                        </div>
                        <div class="col-3 text-end">
                            <a href="studentaddcertificate.php" class="btn btn-primary">Add New Certificate</a>
                            <button id="edit-button" class="btn btn-secondary">Edit</button>
                            <button id="cancel-button" class="btn btn-secondary cancel-button hide">Cancel</button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <?php while ($certificate = mysqli_fetch_assoc($certificatesResult)) : ?>
                           <div class="col-md-4 mb-4">
                               <div class="card certificate-item">
                               <img src="data:image/jpeg;base64,<?php echo base64_encode($certificate['Certificate']); ?>" class="card-img-top certificate-image" alt="Certificate Image">
                                    <div class="certificate-overlay">
                                        <a href="data:image/jpeg;base64,<?php echo base64_encode($certificate['Certificate']); ?>" data-lightbox="certificate-gallery">
                                            <i class="fas fa-search-plus"></i>
                                        </a>
                                        <form class="edit-form" method="post" enctype="multipart/form-data" action="studentupdatecertificate.php">
                                            <input type="hidden" name="certificateId" value="<?php echo $certificate['CertificateID']; ?>">
                                            <div class="form-group">
                                                <input type="file" name="newCertificate" accept="image/jpeg">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="newDescription" class="form-control" value="<?php echo $certificate['Description']; ?>">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </form>
                                        <form class="delete-form" method="post" action="studentdeletecertificate.php">
                                            <input type="hidden" name="certificateId" value="<?php echo $certificate['CertificateID']; ?>">
                                            <!-- Delete button will only be displayed when the edit button is clicked -->
                                            <button type="submit" class="btn btn-danger delete-button">Delete</button>
                                        </form>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $certificate['Description']; ?></h5>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script>
        const editButton = document.getElementById('edit-button');
        const cancelButton = document.getElementById('cancel-button');
        const editForms = document.querySelectorAll('.edit-form');
        const deleteButtons = document.querySelectorAll('.delete-button');

        editButton.addEventListener('click', () => {
            editForms.forEach(form => {
                form.style.display = 'block';
            });

            deleteButtons.forEach(button => {
                button.style.display = 'block';
            });

            editButton.style.display = 'none';
            cancelButton.classList.remove('hide');
        });

        cancelButton.addEventListener('click', () => {
            editForms.forEach(form => {
                form.style.display = 'none';
            });

            deleteButtons.forEach(button => {
                button.style.display = 'none';
            });

            editButton.style.display = 'inline-block';
            cancelButton.classList.add('hide');
        });
    </script>
</body>
</html>
