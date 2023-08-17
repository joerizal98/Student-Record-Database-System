<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
 include 'db.php';
                        include 'session.php';

                        // Check if the student is logged in
                        checkStudentLogin();

                        $studentID = $_SESSION['studentID'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>E-Resume</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="fontawesome/css/all.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.js"></script>

    <style>
        .resume-card {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #f7f7f7;
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
            border: none;
            background-color: transparent;
            color: #007bff;
            cursor: pointer;
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

        #edit-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }

        #edit-popup textarea {
            width: 100%;
            height: 100px;
            resize: none;
        }

        #edit-popup button {
            margin-top: 10px;
        }
        .generate-resume-button {
        padding: 5px 5px; /* Adjust the padding to change the button size */
        font-size: 18px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        position: fixed;
        bottom: 45px; /* Adjust the bottom position to change the vertical position */
        right: 420px; /* Adjust the right position to change the horizontal position */
    }
    .hide-in-pdf {
            display: none;
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
                        <button class="generate-resume-button">Generate Resume</button>
                        <?php


                        // Fetch data from the eResume table joined with the student table
                        $query = "SELECT e.*, s.FirstName, s.LastName, s.PhoneNumber,s.Email, s.Address, s.ProfileImage, e.Summary, e.Education, e.Experience, e.Skills
                                    FROM eResume e
                                    INNER JOIN student s ON e.StudentID = s.StudentID
                                    WHERE s.StudentID = '$studentID'";

                        $result = mysqli_query($link, $query);

                        // Check if there are any records in the table
                        if (mysqli_num_rows($result) > 0) {
                            // Loop through each row
                            while ($row = mysqli_fetch_assoc($result)) {
                                $resumeID = $row['EResumeID'];
                                $firstName = $row['FirstName'];
                                $lastName = $row['LastName'];
                                $phoneNumber = $row['PhoneNumber'];
                                $email = $row['Email'];
                                $address = $row['Address'];
                                $profileImage = $row['ProfileImage'];
                                $summary = $row['Summary'];
                                $education = $row['Education'];
                                $experience = $row['Experience'];
                                $skills = $row['Skills'];

                                // Generate the resume HTML
                                echo '
                                <div class="resume-card">
                                    <div class="profile">
                                        <div class="profile-image">
                                            <img src="data:image/jpeg;base64,' . base64_encode($profileImage) . '" alt="Profile Image">
                                        </div>
                                        <div class="profile-details">
                                            <h3 class="profile-name">' . $firstName . ' ' . $lastName . '</h3>
                                            <p class="profile-email"><i class="fas fa-phone"></i><span>' . $phoneNumber . '</span></p>
                                            <p class="profile-email"><i class="fas fa-envelope"></i><span>' . $email . '</span></p>
                                            <p class="profile-email"><i class="fa fa-map-marker"></i><span>' . $address . '</span></p>
                                        </div>
                                    </div>
                                    <div class="section">
                                        <div class="section-title">
                                            <span>Summary</span>
                                            <button class="edit-button" onclick="editContent(this)" data-section="summary">Edit</button>
                                        </div>
                                        <div class="section-content" id="summary">' . (!empty($summary) ? nl2br($summary) : 'Add your summary here') . '</div>
                                    </div>
                                    <div class="section">
                                        <div class="section-title">
                                            <span>Education</span>
                                            <button class="edit-button" onclick="editContent(this)" data-section="education">Edit</button>
                                        </div>
                                      <div class="section-content" id="education">' . (!empty($education) ? nl2br($education) : 'Add your education here') . '</div>
                                    </div>
                                    <div class="section">
                                        <div class="section-title">
                                            <span>Experience</span>
                                            <button class="edit-button" onclick="editContent(this)" data-section="experience">Edit</button>
                                        </div>
                                        <div class="section-content" id="experience">' . (!empty($experience) ? nl2br($experience) : 'Add your experience here') . '</div>
                                    </div>
                                    <div class="section">
                                        <div class="section-title">
                                            <span>Skills</span>
                                            <button class="edit-button" onclick="editContent(this)" data-section="skills">Edit</button>
                                        </div>
                                        <div class="section-content" id="skills">' . (!empty($skills) ? nl2br($skills) : 'Add your skills here') . '</div>
                                    </div>
                                </div>
                                ';
                            }
                        } else {
                            echo '<p>No resume records found.</p>';
                        }

                        // Close database connection
                        mysqli_close($link);
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<div id="edit-popup">
    <h4 id="edit-title"></h4>
    <textarea id="edit-textarea"></textarea>
    <button onclick="saveContent()">Save</button>
    <button onclick="cancelEdit()">Cancel</button>
</div>

<script>
        function generatePDF() {
            var editButtons = document.querySelectorAll('.section-title button');

            // Hide the edit buttons before generating the PDF
            editButtons.forEach(function(button) {
                button.classList.add('hide-in-pdf');
            });

            var resumeCard = document.querySelector('.resume-card');
            var resumeHTML = resumeCard.outerHTML;
            var styleElement = document.createElement('style');
            styleElement.innerHTML = `
                @font-face {
                    font-family: 'Font Awesome 6';
                    font-weight: 900;
                    font-style: normal;
                    src: url(fontawesome/webfonts/fa-solid-900.woff2) format('woff2');
                }
                @font-face {
                    font-family: 'Font Awesome 6';
                    font-weight: 400;
                    font-style: normal;
                    src: url(fontawesome/webfonts/fa-regular-400.woff2) format('woff2');
                }
            `;
            document.body.appendChild(styleElement);
            html2pdf().set({ html2canvas: { scale: 1.5 } });


            html2pdf().set({
    filename: 'Student Resume.pdf',
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: {
        unit: 'in',
        format: 'A4',
        orientation: 'portrait',
        margins: { top: 0, bottom: 0, left: 0, right: 0 }
    }
})
.from(resumeHTML)
.save();


            // Show the edit buttons again after generating the PDF
            editButtons.forEach(function(button) {
                button.classList.remove('hide-in-pdf');
            });
        }

        document.querySelector('.generate-resume-button').addEventListener('click', generatePDF);



    var activeSection;

   function editContent(button) {
    activeSection = button.dataset.section;
    var sectionContent = button.parentElement.nextElementSibling.innerHTML;
    sectionContent = sectionContent.replace(/<br>/g, "\n"); // Replace <br> tags with newlines
    document.getElementById('edit-title').innerHTML = activeSection.charAt(0).toUpperCase() + activeSection.slice(1);
    document.getElementById('edit-textarea').value = sectionContent;
    document.getElementById('edit-popup').style.display = 'block';
}


    function saveContent() {
        var updatedContent = document.getElementById('edit-textarea').value;
        document.querySelector('[data-section="' + activeSection + '"]').parentElement.nextElementSibling.innerHTML = updatedContent;
        document.getElementById('edit-popup').style.display = 'none';

        // Make an AJAX request to save the updated content
        var studentID = <?php echo $_SESSION['studentID']; ?>;
        var content = encodeURIComponent(updatedContent);
        var url = 'studentsaveresume.php?studentID=' + studentID + '&sectionID=' + activeSection + '&content=' + content;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4) {
                if (this.status === 200) {
                    console.log('Content saved successfully');
                } else {
                    console.log('Failed to save content');
                }
            }
        };
        xhttp.open('GET', url, true);
        xhttp.send();
    }

    function cancelEdit() {
        document.getElementById('edit-popup').style.display = 'none';
    }

    // Adjust the jsPDF margins
    html2pdf().set({
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait', margins: { top: 0.5, bottom: 0.5, left: 0.5, right: 0.5 } }
    });
</script>

</script>
</body>
</html>