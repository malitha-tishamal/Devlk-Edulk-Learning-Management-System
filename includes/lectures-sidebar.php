<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!--li class="nav-item">
            <a class="nav-link" href="index.php">
                <i class="bi bi-house-door"></i> <span>Home</span>
            </a>
        </li-->

        <li class="nav-item">
            <a class="nav-link" href="pages-home.php">
                <i class="bi bi-house-door"></i> <span>Home</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#lectures-nav" role="button">
               <i class="bi bi-person-gear"></i> <span>Lectures</span> <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="lectures-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="pages-add-lecture.php">
                        <i class="bi bi-circle"></i> <span>Add Lecture</span>
                    </a>
                </li>
                <li>
                    <a href="manage-lectures.php">
                        <i class="bi bi-circle"></i> <span>See Lectures</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#analyze-nav" role="button">
               <i class="bi bi-person-lines-fill"></i> <span>Batch Representer</span> <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="analyze-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="pages-add-batchadmin.php">
                        <i class="bi bi-circle"></i> <span>Add Representer </span>
                    </a>
                </li>
                <li>
                    <a href="manage-batchadmins.php">
                        <i class="bi bi-circle"></i> <span>Manage Representers </span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#wards-nav" role="button">
               <i class="bi bi-people"></i> <span>Active Students</span> <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="wards-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="pages-add-new-student.php">
                        <i class="bi bi-circle"></i> <span>Add Student</span>
                    </a>
                </li>
                <li>
                    <a href="manage-students.php">
                        <i class="bi bi-circle"></i> <span>Manage Students</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#course-nav" role="button">
                <i class="bi bi-book"></i> <span>Course Content</span> <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="course-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="pages-add-subject.php">
                        <i class="bi bi-circle"></i> <span>Add Subject</span>
                    </a>
                </li>

                <li>
                    <a href="pages-courses.php">
                        <i class="bi bi-circle"></i> <span>Manage Course Content</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#wards-nav2" role="button">
               <i class="bi bi-download"></i> <span>Downloads</span> <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="wards-nav2" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="manage-files.php">
                        <i class="bi bi-circle"></i> <span>Upload / Manage</span>
                    </a>
                </li>
                <li>
                    <a href="pages-download-files.php">
                        <i class="bi bi-circle"></i> <span>Download Files</span>
                    </a>
                </li>
                
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#meetings-nav" role="button">
               <i class="bi bi-people-fill"></i> <span>Meetings</span> <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="meetings-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="pages-meeting-manage-lectures.php">
                        <i class="bi bi-circle"></i> <span>Add New Meeting</span>
                    </a>
                </li>
                <li>
                    <a href="pages-meeting-manage-all.php">
                        <i class="bi bi-circle"></i> <span>All Saved Meetings</span>
                    </a>
                </li>

                <li>
                    <a href="pages-meeting-attendance.php">
                        <i class="bi bi-circle"></i> <span>Meeting Attendance</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#video-nav" role="button">
               <i class="bi bi-file-play"></i> <span>Recordings</span> <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="video-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="pages-video-upload-user.php">
                        <i class="bi bi-circle"></i> <span>Manage Uploaded Videos</span>
                    </a>
                </li>
                <li>
                    <a href="pages-video-upload.php">
                        <i class="bi bi-circle"></i> <span>All Video Uploads</span>
                    </a>
                </li>

                <li>
                    <a href="#.php">
                        <i class="bi bi-circle"></i> <span>#</span>
                    </a>
                </li>
            </ul>
        </li>


        <li class="nav-item">
            <a class="nav-link collapsed" href="user-profile.php">
                <i class="bi bi-person-circle"></i> <span>Profile</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="pages-lectures-drive.php">
                <i class="bi bi-hdd"></i> <span>My Drive</span>
            </a>
        </li>


        <li class="nav-item">
            <a class="nav-link collapsed" href="pages-reviews.php">
                <i class="bi bi-flag-fill"></i> <span>Reviews & Reports</span>
        </a>
    </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="logout.php">
                <i class="bi bi-box-arrow-right"></i> <span>Log Out</span>
            </a>
        </li>

         <li class="nav-item">
            <a class="nav-link collapsed" href="https://malithatishamal.42web.io">
                <i class="bi bi-code-square"></i> <span>Developer<br>About Me</span>
            </a>
        </li>

    </ul>
</aside><!-- End Sidebar -->
