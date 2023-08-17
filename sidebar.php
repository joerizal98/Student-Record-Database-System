<?php
require_once 'session.php';
?>
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <!-- <div class="sb-sidenav-menu-heading">Core</div>    -->
                 <?php if (isset($_SESSION['username1'])) : ?>
                <a class="nav-link" href="studentindex.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                    <div class="sb-sidenav-menu-heading">Student Management</div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fa fa-trophy"></i></div>
                        Achievement
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="studentachievement.php">View Achievement</a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts9" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fa fa-certificate"></i></div>
                        Certificate
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts9" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="studentcertificate.php">View Certificate</a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts10" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-newspaper"></i></div>
                        Resume
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts10" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="studentresume.php">View Resume</a>
                        </nav>
                    </div>
                <?php elseif (isset($_SESSION['username2'])) : ?>
                 <a class="nav-link" href="teacherindex.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                 <div class="sb-sidenav-menu-heading">Teacher Management</div>
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="teacherviewcourse.php">View My Courses</a>
                            <a class="nav-link" href="teacherviewstudent.php">View Student List</a>
                            <a class="nav-link" href="teacherrecordstudent.php">Manage Student Record</a>
                         <!--   <a class="nav-link" href="editteacher.php">Edit Existing Teacher</a>
                            <a class="nav-link" href="deleteteacher.php">Delete Existing Teacher</a>-->
                        </nav>



                <?php elseif (isset($_SESSION['username3'])) : ?>
                 <a class="nav-link" href="recruiterindex.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-search"></i></div>
                    Search for talent
                </a>
                   <!-- <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts1" aria-expanded="false" aria-controls="collapseLayouts4">
                        <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                        Recruiter
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>-->
                   <!-- <div class="collapse" id="collapseLayouts1" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="addrecruiter.php">Add Recruiter</a>
                            <a class="nav-link" href="editrecruiter.php">Edit Existing Recruiter</a>
                            <a class="nav-link" href="deleterecruiter.php">Delete Existing Recruiter</a>
                        </nav>
                    </div>-->
                <?php elseif (isset($_SESSION['admin'])) : ?>
                 <a class="nav-link" href="adminindex.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                    <div class="sb-sidenav-menu-heading">User Management</div>
                     <a class="nav-link" href="managenewuser.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    New User Management
                </a>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>
                        Student
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="addstudent.php">Add Student</a>
                            <a class="nav-link" href="editstudent.php">Edit Existing Student</a>
                            <a class="nav-link" href="deletestudent.php">Delete Existing Student</a>
                             <a class="nav-link" href="managestudent.php">Manage Student Course</a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts4" aria-expanded="false" aria-controls="collapseLayouts4">
                        <div class="sb-nav-link-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        Teacher
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts4" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="addteacher.php">Add Teacher</a>
                            <a class="nav-link" href="editteacher.php">Edit Existing Teacher</a>
                            <a class="nav-link" href="deleteteacher.php">Delete Existing Teacher</a>
                             <a class="nav-link" href="manageteacher.php">Manage Teacher Course</a>
                        </nav>
                    </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts1" aria-expanded="false" aria-controls="collapseLayouts4">
                                                <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                                                Recruiter
                                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                        </a>
                                        <div class="collapse" id="collapseLayouts1" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                                <nav class="sb-sidenav-menu-nested nav">
                                                        <a class="nav-link" href="addrecruiter.php">Add Recruiter</a>
                                                        <a class="nav-link" href="editrecruiter.php">Edit Existing Recruiter</a>
                                                        <a class="nav-link" href="deleterecruiter.php">Delete Existing Recruiter</a>
                                                </nav>
                                        </div>
                         <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts2" aria-expanded="false" aria-controls="collapseLayouts4">
                    <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                    Course
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts2" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="addcourse.php">Add Course</a>
                        <a class="nav-link" href="editcourse.php">Edit Existing Course</a>
                        <a class="nav-link" href="deletecourse.php">Delete Existing Course</a>
                    </nav>
                </div>

              <!--  <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts3" aria-expanded="false" aria-controls="collapseLayouts4">
                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                    Record
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts3" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="addrecord.php">Add Record</a>
                        <a class="nav-link" href="editrecord.php">Edit Existing Record</a>
                        <a class="nav-link" href="deleterecord.php">Delete Existing Record</a>
                    </nav>
                </div>-->

                <?php else : ?>
                    <!-- Default fallback content if no role matches -->
                    <p>Unknown role</p>
                <?php endif; ?>


            </div>
        </div>
    </nav>
</div>
