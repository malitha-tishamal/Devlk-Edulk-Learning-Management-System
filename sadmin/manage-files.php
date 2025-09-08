<?php
session_start();
require_once '../includes/db-conn.php';

if (!isset($_SESSION['sadmin_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['sadmin_id'];
$sql = "SELECT name, email, nic, mobile, profile_picture FROM sadmins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$semesters = [];
$semQuery = $conn->query("SELECT DISTINCT semester FROM subjects ORDER BY semester ASC");
while ($row = $semQuery->fetch_assoc()) {
    $semesters[] = $row['semester'];
}
$uploader_name = $user['name'];
$uploader_role = 'admin'; 

$selectedSemester = $_GET['semester'] ?? '';
$selectedSubjectId = $_GET['subject'] ?? '';

$subjects = [];
if ($selectedSemester !== '') {
    $subjectQuery = $conn->prepare("SELECT id, name FROM subjects WHERE semester = ? ORDER BY name ASC");
    $subjectQuery->bind_param("s", $selectedSemester);
    $subjectQuery->execute();
    $resultSubjects = $subjectQuery->get_result();
    while ($row = $resultSubjects->fetch_assoc()) {
        $subjects[] = $row;
    }
    $subjectQuery->close();
}

// Function for colored file icons by extension
function getFileIconColored($ext) {
    $ext = strtolower($ext);
    switch ($ext) {
        case 'pdf':
            return '<i class="fa-solid fa-file-pdf text-danger"></i>';
        case 'doc':
        case 'docx':
            return '<i class="fa-solid fa-file-word text-primary"></i>';
        case 'xls':
        case 'xlsx':
            return '<i class="fa-solid fa-file-excel text-success"></i>';
        case 'ppt':
        case 'pptx':
            return '<i class="fa-solid fa-file-powerpoint text-warning"></i>';
        case 'zip':
        case 'rar':
            return '<i class="fa-solid fa-file-archive" style="color:#fd7e14;"></i>';
        default:
            return '<i class="fa-solid fa-file text-secondary"></i>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Manage Resources - EduWide</title>
    <?php include_once("../includes/css-links-inc.php"); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --danger: #e63946;
            --light: #f8f9fa;
            --dark: #212529;
        }
        
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: none;
            margin-bottom: 1.5rem;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .page-title {
            color: #343a40;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .breadcrumb-item a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .breadcrumb-item a:hover {
            color: var(--secondary);
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.6rem 1.2rem;
            transition: all 0.2s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        
        .btn-sm {
            padding: 0.35rem 0.65rem;
            font-size: 0.875rem;
        }
        
        .upload-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px dashed #dee2e6;
            transition: all 0.3s;
        }
        
        .upload-section:hover {
            background-color: #eef2ff;
            border-color: var(--primary);
        }
        
        .week-subheading {
            font-weight: 600;
            color: var(--primary);
            margin-top: 25px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--primary);
        }
        
        .table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            margin-bottom: 1.5rem;
        }
        
        .table th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            padding: 1rem;
            border-top: 1px solid #dee2e6;
        }
        
        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
            border-radius: 6px;
        }
        
        .file-icon {
            font-size: 1.2rem;
            margin-right: 8px;
        }
        
        .action-buttons {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }
        
        .progress {
            height: 25px;
            border-radius: 8px;
            background-color: #e9ecef;
        }
        
        .progress-bar {
            border-radius: 8px;
            transition: width 0.3s ease;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #dee2e6;
        }
        
        @media (max-width: 768px) {
            .upload-section .row {
                margin-bottom: 10px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .table-responsive {
                overflow-x: auto;
            }
            
            .table th, .table td {
                padding: 0.75rem 0.5rem;
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body>
<?php include_once("../includes/header.php") ?>
<?php include_once("../includes/sadmin-sidebar.php") ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1 class="page-title">Manage Resources</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Manage Resources</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Select Semester & Subject</h5>
                        <form method="GET" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <select name="semester" class="form-select" onchange="this.form.submit()">
                                        <option value="">-- Select Semester --</option>
                                        <?php foreach ($semesters as $sem): ?>
                                            <option value="<?= htmlspecialchars($sem) ?>" <?= $sem == $selectedSemester ? 'selected' : '' ?>>
                                                Semester <?= htmlspecialchars($sem) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php if (!empty($subjects)): ?>
                                    <div class="col-md-4">
                                        <select name="subject" class="form-select" onchange="this.form.submit()">
                                            <option value="">-- All Subjects --</option>
                                            <?php foreach ($subjects as $subject): ?>
                                                <option value="<?= htmlspecialchars($subject['id']) ?>" <?= $selectedSubjectId == $subject['id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($subject['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                <?php if ($selectedSemester !== ''): ?>
                                    <div class="col-md-4">
                                        <a href="manage-resources.php" class="btn btn-outline-secondary">Clear Filters</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </form>

                        <?php if ($selectedSemester !== ''): ?>
                            <h5 class="card-title">Upload New Resources</h5>
                            <form id="multiFileForm" class="mb-4" enctype="multipart/form-data">
                                <input type="hidden" name="semester" value="<?= htmlspecialchars($selectedSemester) ?>">
                                <div id="upload-sections">
                                    <div class="upload-section">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <input type="text" name="title[]" class="form-control" placeholder="File Title" required>
                                            </div>
                                            <div class="col-md-3">
                                                <select name="subject_id[]" class="form-select" required>
                                                    <option value="">Select Subject</option>
                                                    <?php foreach ($subjects as $subject): ?>
                                                        <option value="<?= htmlspecialchars($subject['id']) ?>"><?= htmlspecialchars($subject['name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select name="category[]" class="form-select" required>
                                                    <option value="">Category</option>
                                                    <option value="Pass Papers">Pass Papers</option>
                                                    <option value="Model Paper">Model Papers</option>
                                                    <option value="Notes">Notes</option>
                                                    <option value="Lecturer Notes">Notes - Lecturer Upload</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="file" name="file[]" class="form-control" required>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center">
                                                <button type="button" class="btn btn-danger btn-sm remove-section">X</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-outline-primary" id="addSectionBtn">
                                        <i class="fas fa-plus me-1"></i> Add More Files
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload me-1"></i> Upload All Files
                                    </button>
                                </div>
                                <div class="progress mt-3" style="display: none;" id="uploadProgressBar">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                         style="width: 0%;" id="progressBarText">0%
                                    </div>
                                </div>
                            </form>

                            <h5 class="card-title mt-5">Uploaded Resources</h5>

                            <?php
                            // Fetch subjects for this semester
                            $subjectGroupQuery = $conn->prepare("
                                SELECT DISTINCT s.id, s.name 
                                FROM tuition_files tf 
                                JOIN subjects s ON tf.subject_id = s.id 
                                WHERE s.semester = ? 
                                ORDER BY s.name ASC
                            ");
                            $subjectGroupQuery->bind_param("s", $selectedSemester);
                            $subjectGroupQuery->execute();
                            $subjectResult = $subjectGroupQuery->get_result();

                            if ($subjectResult->num_rows === 0): ?>
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <h4>No Resources Found</h4>
                                    <p>No resources have been uploaded for this semester yet.</p>
                                </div>
                            <?php else:
                                while ($subject = $subjectResult->fetch_assoc()):
                                    // If a specific subject is selected, skip others
                                    if ($selectedSubjectId && $selectedSubjectId != $subject['id']) {
                                        continue;
                                    }
                                    ?>
                                    <h5 class="mt-4 text-primary"><?= htmlspecialchars($subject['name']) ?></h5>

                                    <?php
                                    // Fetch files for this subject, grouped by Notes Weeks and Others
                                    $stmtFiles = $conn->prepare("SELECT * FROM tuition_files WHERE subject_id = ? ORDER BY uploaded_at DESC");
                                    $notesByWeek = [];
                                    $otherFiles = [];

                                    if ($stmtFiles) {
                                        $stmtFiles->bind_param("i", $subject['id']);
                                        $stmtFiles->execute();
                                        $resultFiles = $stmtFiles->get_result();

                                        while ($file = $resultFiles->fetch_assoc()) {
                                            if (strtolower($file['category']) === 'notes' && preg_match('/^(Week\s*\d+)/i', $file['title'], $matches)) {
                                                $weekKey = $matches[1]; // e.g. "Week 1"
                                                $notesByWeek[$weekKey][] = $file;
                                            } else {
                                                $otherFiles[] = $file;
                                            }
                                        }
                                        $stmtFiles->close();
                                    }

                                    // Sort weeks naturally
                                    if (!empty($notesByWeek)) {
                                        ksort($notesByWeek, SORT_NATURAL | SORT_FLAG_CASE);
                                    }

                                    // Display Notes grouped by week
                                    foreach ($notesByWeek as $week => $files):
                                        ?>
                                        <h6 class="week-subheading"><?= htmlspecialchars($week) ?></h6>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Category</th>
                                                    <th>Type</th>
                                                    <th>Uploaded</th>
                                                    <th>File</th>
                                                    <th>Uploader</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($files as $f): 
                                                    $ext = pathinfo($f['filename'], PATHINFO_EXTENSION);
                                                    ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($f['title']) ?></td>
                                                        <td><span class="badge bg-light text-dark"><?= htmlspecialchars($f['category']) ?></span></td>
                                                        <td><?= getFileIconColored($ext) . ' ' . strtoupper(htmlspecialchars($ext)) ?></td>
                                                        <td><?= date('M j, Y', strtotime($f['uploaded_at'])) ?></td>
                                                        <td><a href="../uploads/<?= rawurlencode($f['filename']) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">View</a></td>
                                                        <td><?= htmlspecialchars($f['uploaded_by_name']) ?> <br><small class="text-muted">(<?= htmlspecialchars($f['uploaded_by_role']) ?>)</small></td>
                                                        <td>
                                                            <span class="badge bg-<?= $f['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                                <?= ucfirst(htmlspecialchars($f['status'])) ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <?php if ($f['status'] !== 'active'): ?>
                                                                    <a href="change-file-status.php?id=<?= $f['id'] ?>&status=active&semester=<?= urlencode($selectedSemester) ?>&subject=<?= urlencode($selectedSubjectId) ?>" class="btn btn-sm btn-success">Activate</a>
                                                                <?php endif; ?>
                                                                <?php if ($f['status'] !== 'inactive'): ?>
                                                                    <a href="change-file-status.php?id=<?= $f['id'] ?>&status=inactive&semester=<?= urlencode($selectedSemester) ?>&subject=<?= urlencode($selectedSubjectId) ?>" class="btn btn-sm btn-warning">Disable</a>
                                                                <?php endif; ?>
                                                                <a href="delete-file.php?id=<?= $f['id'] ?>&semester=<?= urlencode($selectedSemester) ?>&subject=<?= urlencode($selectedSubjectId) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this file?')">Delete</a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endforeach; ?>

                                    <?php if (!empty($otherFiles)): ?>
                                        <h6 class="week-subheading">Other Files</h6>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Category</th>
                                                    <th>Type</th>
                                                    <th>Uploaded</th>
                                                    <th>File</th>
                                                    <th>Uploader</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($otherFiles as $f):
                                                    $ext = pathinfo($f['filename'], PATHINFO_EXTENSION);
                                                    ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($f['title']) ?></td>
                                                        <td><span class="badge bg-light text-dark"><?= htmlspecialchars($f['category']) ?></span></td>
                                                        <td><?= getFileIconColored($ext) . ' ' . strtoupper(htmlspecialchars($ext)) ?></td>
                                                        <td><?= date('M j, Y', strtotime($f['uploaded_at'])) ?></td>
                                                        <td><a href="../uploads/<?= rawurlencode($f['filename']) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">View</a></td>
                                                        <td><?= htmlspecialchars($f['uploaded_by_name']) ?> <br><small class="text-muted">(<?= htmlspecialchars($f['uploaded_by_role']) ?>)</small></td>
                                                        <td>
                                                            <span class="badge bg-<?= $f['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                                <?= ucfirst(htmlspecialchars($f['status'])) ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <?php if ($f['status'] !== 'active'): ?>
                                                                    <a href="change-file-status.php?id=<?= $f['id'] ?>&status=active&semester=<?= urlencode($selectedSemester) ?>&subject=<?= urlencode($selectedSubjectId) ?>" class="btn btn-sm btn-success">Activate</a>
                                                                <?php endif; ?>
                                                                <?php if ($f['status'] !== 'inactive'): ?>
                                                                    <a href="change-file-status.php?id=<?= $f['id'] ?>&status=inactive&semester=<?= urlencode($selectedSemester) ?>&subject=<?= urlencode($selectedSubjectId) ?>" class="btn btn-sm btn-warning">Disable</a>
                                                                <?php endif; ?>
                                                                <a href="delete-file.php?id=<?= $f['id'] ?>&semester=<?= urlencode($selectedSemester) ?>&subject=<?= urlencode($selectedSubjectId) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this file?')">Delete</a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>

                                <?php endwhile; 
                            endif;
                            $subjectGroupQuery->close(); ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="bi bi-folder2-open"></i>
                                <h4>Select a Semester</h4>
                                <p>Please select a semester to view and manage resources.</p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include_once("../includes/footer.php") ?>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<?php include_once("../includes/js-links-inc.php") ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Add more file upload sections
        document.getElementById("addSectionBtn").addEventListener("click", function() {
            const section = document.querySelector(".upload-section");
            const clone = section.cloneNode(true);
            clone.querySelectorAll("input, select").forEach(el => el.value = "");
            document.getElementById("upload-sections").appendChild(clone);
        });

        // Remove file upload sections
        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("remove-section")) {
                const total = document.querySelectorAll(".upload-section").length;
                if (total > 1) {
                    e.target.closest(".upload-section").remove();
                }
            }
        });

        // Handle form submission with AJAX
        document.getElementById("multiFileForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();

            const progressContainer = document.getElementById("uploadProgressBar");
            const progressBar = document.getElementById("progressBarText");

            progressContainer.style.display = "block";
            progressBar.style.width = "0%";
            progressBar.innerText = "0%";

            xhr.upload.addEventListener("progress", function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percent + "%";
                    progressBar.innerText = percent + "%";
                }
            });

            xhr.addEventListener("load", function() {
                if (xhr.status === 200) {
                    progressBar.classList.remove("bg-danger");
                    progressBar.classList.add("bg-success");
                    progressBar.innerText = "Upload Complete";
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    progressBar.classList.remove("bg-success");
                    progressBar.classList.add("bg-danger");
                    progressBar.innerText = "Upload Failed";
                }
            });

            xhr.open("POST", "upload-file.php");
            xhr.send(formData);
        });
    });
</script>
</body>
</html>
<?php $conn->close(); ?>