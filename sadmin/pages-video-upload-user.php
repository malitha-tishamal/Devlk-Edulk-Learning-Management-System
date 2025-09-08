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

// Fetch subject list
$subjectList = [];
$subjectQuery = $conn->query("SELECT * FROM subjects");
while ($row = $subjectQuery->fetch_assoc()) {
    $subjectList[] = $row;
}

// Fetch videos with uploader name & role
$videos = [];
$videoQuery = $conn->query("
    SELECT v.*,
           CASE 
               WHEN v.role = 'superadmin' THEN s.name
               WHEN v.role = 'lecture' THEN l.name
               ELSE 'Admin'
           END AS uploader_name
    FROM recordings v
    LEFT JOIN sadmins s ON v.created_by = s.id AND v.role = 'superadmin'
    LEFT JOIN lectures l ON v.created_by = l.id AND v.role = 'lecture'
    ORDER BY v.release_time DESC
");
while ($row = $videoQuery->fetch_assoc()) {
    $videos[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Upload Lecture Recording - Edulk</title>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />
  <?php include_once("../includes/css-links-inc.php"); ?>
  <style>
    :root {
      --primary: #4361ee;
      --secondary: #3f37c9;
      --success: #4cc9f0;
      --info: #4895ef;
      --warning: #f72585;
      --light: #f8f9fa;
      --dark: #212529;
      --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      --transition: all 0.3s ease;
    }
    
    body {
      background-color: #f8f9fa;
      font-family: 'Poppins', sans-serif;
      color: #495057;
    }
    
    .card {
      border-radius: 12px;
      box-shadow: var(--card-shadow);
      border: none;
      transition: var(--transition);
    }
    
    .card:hover {
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
    }
    
    .card-header {
      background: linear-gradient(120deg, var(--primary), var(--secondary));
      color: white;
      border-radius: 12px 12px 0 0 !important;
      font-weight: 600;
      padding: 1rem 1.5rem;
    }
    
    .btn-primary {
      background-color: var(--primary);
      border-color: var(--primary);
      border-radius: 8px;
      padding: 0.5rem 1.5rem;
      font-weight: 500;
      transition: var(--transition);
    }
    
    .btn-primary:hover {
      background-color: var(--secondary);
      border-color: var(--secondary);
      transform: translateY(-2px);
    }
    
    .btn-success {
      background-color: #2ecc71;
      border-color: #2ecc71;
      border-radius: 8px;
      padding: 0.5rem 1.5rem;
      font-weight: 500;
      transition: var(--transition);
    }
    
    .btn-success:hover {
      background-color: #27ae60;
      border-color: #27ae60;
      transform: translateY(-2px);
    }
    
    .btn-danger {
      background-color: #e74c3c;
      border-color: #e74c3c;
      border-radius: 8px;
      padding: 0.5rem 1rem;
      font-weight: 500;
      transition: var(--transition);
    }
    
    .btn-danger:hover {
      background-color: #c0392b;
      border-color: #c0392b;
      transform: translateY(-2px);
    }
    
    .progress { 
      height: 25px; 
      background: #f5f5f5; 
      border-radius: 4px; 
      overflow: hidden; 
      margin-bottom: 15px; 
    }
    
    .progress-bar { 
      height: 100%; 
      background: linear-gradient(120deg, var(--primary), var(--secondary));
      width: 0; 
      color: #fff; 
      text-align: center; 
      line-height: 25px; 
      transition: width 0.3s; 
    }
    
    .video-card { 
      cursor: pointer; 
      transition: 0.2s; 
      position: relative;
      overflow: hidden;
      border-radius: 12px;
    }
    
    .video-card:hover { 
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
    
    .video-card.disabled { 
      opacity: 0.7;
      background-color: #f8f9fa;
    }
    
    .video-card .btn { 
      min-width: 70px; 
      margin-bottom: 4px; 
      border-radius: 6px;
    }
    
    .resource-form { 
      border: 1px solid #ddd; 
      padding: 15px; 
      margin-top: 10px; 
      border-radius: 8px; 
      background: #f9f9f9; 
    }
    
    .resource-message { 
      margin-top: 8px; 
      font-size: 0.9em; 
    }
    
    .upload-area {
      border: 2px dashed #ced4da;
      border-radius: 12px;
      padding: 2rem;
      text-align: center;
      transition: var(--transition);
      background-color: #f8f9fa;
      cursor: pointer;
    }
    
    .upload-area:hover, .upload-area.dragover {
      border-color: var(--primary);
      background-color: #e8f4ff;
      transform: translateY(-3px);
    }
    
    .stat-card {
      text-align: center;
      padding: 1rem;
      border-radius: 12px;
      background: linear-gradient(120deg, #f8f9fa, #e9ecef);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      transition: var(--transition);
    }
    
    .stat-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .stat-number {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--primary);
    }
    
    .stat-label {
      font-size: 0.9rem;
      color: #6c757d;
      font-weight: 500;
    }
    
    .nav-tabs .nav-link {
      color: #6c757d;
      font-weight: 500;
      border: none;
      padding: 0.75rem 1.25rem;
      border-radius: 8px 8px 0 0;
    }
    
    .nav-tabs .nav-link.active {
      color: var(--primary);
      font-weight: 600;
      border-bottom: 3px solid var(--primary);
      background-color: transparent;
    }
    
    .history-table {
      font-size: 0.9rem;
    }
    
    .history-table tr {
      transition: var(--transition);
    }
    
    .history-table tr:hover {
      background-color: #f1f5f9;
      transform: translateX(4px);
    }
    
    .saved-report-info {
      background-color: #e8f4ff;
      border-left: 4px solid var(--info);
      padding: 1.25rem;
      margin-bottom: 1.5rem;
      border-radius: 8px;
    }
    
    .breadcrumb-item a {
      color: var(--primary);
      text-decoration: none;
      transition: var(--transition);
    }
    
    .breadcrumb-item a:hover {
      color: var(--secondary);
      text-decoration: underline;
    }
    
    .section-title {
      position: relative;
      padding-left: 1rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
    }
    
    .section-title:before {
      content: '';
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      height: 24px;
      width: 4px;
      background: linear-gradient(120deg, var(--primary), var(--secondary));
      border-radius: 4px;
    }
    
    @media (max-width: 768px) {
      .table-container {
        max-height: 50vh;
      }
      
      .stat-number {
        font-size: 1.5rem;
      }
      
      .upload-area {
        padding: 1.5rem;
      }
      
      .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
      }
    }
    
    .badge {
      font-weight: 500;
      padding: 0.5em 0.8em;
    }
    
    .form-control, .form-select {
      border-radius: 8px;
      padding: 0.75rem 1rem;
      border: 1px solid #ced4da;
      transition: var(--transition);
    }
    
    .form-control:focus, .form-select:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
    }
    
    .alert {
      border-radius: 8px;
      border: none;
      padding: 1rem 1.25rem;
    }
    
    .feature-icon {
      background: linear-gradient(120deg, var(--primary), var(--secondary));
      color: white;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 12px;
    }
    
    .video-thumbnail {
      height: 160px;
      object-fit: cover;
      border-radius: 8px 8px 0 0;
    }
    
    .video-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
      margin-top: 10px;
    }
    
    .resource-card {
      transition: var(--transition);
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 8px;
      background-color: white;
      border: 1px solid #eee;
    }
    
    .resource-card:hover {
      background-color: #f8f9fa;
      transform: translateX(3px);
    }
    
    .subject-header {
      background: linear-gradient(to right, #f8f9fa, #e9ecef);
      padding: 10px 15px;
      border-radius: 8px;
      margin: 20px 0 15px 0;
      border-left: 4px solid var(--primary);
    }
    
    .upload-stats {
      display: flex;
      gap: 15px;
      margin-top: 15px;
    }
    
    .stats-item {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 0.9rem;
      color: #6c757d;
    }
  </style>
</head>
<body>

<?php include_once("../includes/header.php"); ?>
<?php include_once("../includes/sadmin-sidebar.php"); ?>

<main id="main" class="main">
    <div class="pagetitle">
      <h1>Manage Video Uploads</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Manage Video Uploads</li>
        </ol>
      </nav>
    </div>

  <?php
  if (isset($_SESSION['message'])) {
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
              <i class='bi bi-check-circle me-2'></i>" . htmlspecialchars($_SESSION['message']) . "
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
      unset($_SESSION['message']);
  }
  ?>

  <div class="card shadow-sm mb-4">
    <div class="card-header">
      <div class="d-flex align-items-center">
        <div class="feature-icon">
          <i class="bi bi-cloud-upload"></i>
        </div>
        <h5 class="card-title mb-0">Upload New Video</h5>
      </div>
    </div>
    <div class="card-body">
      <form id="uploadForm" action="upload-recording-process.php" method="POST" enctype="multipart/form-data">
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Subject <span class="text-danger">*</span></label>
            <select name="subject_id" class="form-select" required>
              <option value="">-- Select Subject --</option>
              <?php foreach ($subjectList as $sub): ?>
                <option value="<?= htmlspecialchars($sub['id']) ?>"><?= htmlspecialchars($sub['code']) ?> - <?= htmlspecialchars($sub['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" required />
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Brief description of the video content"></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Lecture Type</label>
            <select name="lecture_type" class="form-select">
              <option value="Zoom">Zoom Session Record</option>
              <option value="physical">Physical Lecture Record</option>
              <option value="other">Other</option>
            </select>
            
            <label class="form-label mt-3">Access Level</label>
            <select name="access_level" class="form-select">
              <option value="public">Public</option>
              <option value="batch">Batch Only</option>
              <option value="private">Private</option>
            </select>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Thumbnail Image</label>
            <input type="file" name="thumbnail" accept="image/*" class="form-control" onchange="previewThumbnail(event)">
            <div class="mt-2">
              <img id="thumbPreview" src="#" style="display:none; max-height: 100px; border-radius: 8px;" />
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Video File (MP4) <span class="text-danger">*</span></label>
            <input type="file" name="video_file" accept="video/mp4" class="form-control" required>
            
            <label class="form-label mt-3">Student View Limit (minutes)</label>
            <input type="number" name="view_limit_minutes" class="form-control" placeholder="e.g. 3 (views)" min="0">
          </div>
        </div>

        <div class="progress mb-3" id="progressContainer" style="display:none;">
          <div class="progress-bar" id="progressBar">0%</div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
          <div>
            <button type="submit" class="btn btn-primary rounded-pill px-4">
              <i class="bi bi-cloud-upload me-2"></i>Upload Video
            </button>
            <button type="button" id="clearBtn" class="btn btn-outline-secondary rounded-pill ms-2 px-3">
              <i class="bi bi-x-circle me-2"></i>Clear Form
            </button>
          </div>
          
          <div class="upload-stats">
            <div class="stats-item">
              <i class="bi bi-collection-play text-primary"></i>
              <span><?php echo count($videos); ?> Videos</span>
            </div>
            <div class="stats-item">
              <i class="bi bi-list-check text-success"></i>
              <span><?php echo count($subjectList); ?> Subjects</span>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Uploaded Recordings -->
  <div class="card shadow-sm">
    <div class="card-header">
      <div class="d-flex align-items-center">
        <div class="feature-icon">
          <i class="bi bi-collection-play"></i>
        </div>
        <h5 class="card-title mb-0">Your Uploaded Recordings</h5>
      </div>
    </div>
    <div class="card-body">

      <?php
      $session_user_id = $_SESSION['lecture_id'] ?? $_SESSION['sadmin_id'] ?? null;
      $session_role = isset($_SESSION['lecture_id']) ? 'lecture' : 'superadmin';

      if ($session_user_id) {
          $stmt = $conn->prepare("
              SELECT r.*, s.name AS subject_name 
              FROM recordings r 
              JOIN subjects s ON r.subject_id = s.id 
              WHERE r.role = ? AND r.created_by = ?
              ORDER BY r.release_time DESC
          ");
          $stmt->bind_param("si", $session_role, $session_user_id);
          $stmt->execute();
          $recordings = $stmt->get_result();

          $current_subject = null;
          while ($row = $recordings->fetch_assoc()) {

              // Group by subject
              if ($current_subject !== $row['subject_name']) {
                  if ($current_subject !== null) echo "</div>";
                  echo "<div class='subject-header'>
                          <h4 class='mb-0'><i class='bi bi-journals me-2'></i>" . htmlspecialchars($row['subject_name']) . "</h4>
                        </div><div class='row'>";
                  $current_subject = $row['subject_name'];
              }

              // Uploader name
              $uploader_name = 'Unknown';
              if ($row['role'] === 'superadmin') {
                  $stmtUploader = $conn->prepare("SELECT name FROM sadmins WHERE id = ?");
              } elseif ($row['role'] === 'lecture') {
                  $stmtUploader = $conn->prepare("SELECT name FROM lectures WHERE id = ?");
              } else {
                  $stmtUploader = null;
              }

              if ($stmtUploader) {
                  $stmtUploader->bind_param("i", $row['created_by']);
                  $stmtUploader->execute();
                  $resultUploader = $stmtUploader->get_result();
                  if ($resultUploader->num_rows > 0) {
                      $uploaderRow = $resultUploader->fetch_assoc();
                      $uploader_name = $uploaderRow['name'];
                  }
                  $stmtUploader->close();
              }
              ?>

              <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card video-card h-100 <?= ($row['status'] === 'disabled') ? 'disabled' : '' ?>" data-video="<?= htmlspecialchars($row['video_path']) ?>" data-id="<?= $row['id'] ?>">
                  <?php if (!empty($row['thumbnail_path'])) { ?>
                    <img src="../<?= htmlspecialchars($row['thumbnail_path']) ?>" class="card-img-top video-thumbnail">
                  <?php } else { ?>
                    <div class="video-thumbnail bg-light d-flex align-items-center justify-content-center">
                      <i class="bi bi-play-circle-fill text-primary" style="font-size: 3rem;"></i>
                    </div>
                  <?php } ?>

                  <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-1" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($row['title']) ?></h6>
                    <p class="text-muted mb-2 small">
                      <i class="bi bi-calendar me-1"></i><?= date("M j, Y", strtotime($row['release_time'])) ?>
                    </p>
                    
                    <div class="d-flex justify-content-between mb-2">
                      <span class="badge bg-light text-dark">
                        <i class="bi bi-play-fill me-1"></i><?= intval($row['play_count']) ?>
                      </span>
                      <span class="badge bg-light text-dark">
                        <i class="bi bi-download me-1"></i><?= intval($row['download_count']) ?>
                      </span>
                    </div>

                    <?php
                    // Resources
                    $resources = $conn->prepare("SELECT * FROM recording_resources WHERE recording_id = ?");
                    $resources->bind_param("i", $row['id']);
                    $resources->execute();
                    $resResult = $resources->get_result();
                    ?>

                    <div class="flex-grow-1 overflow-auto mb-2" style="max-height: 120px;">
                      <div class="text-primary small fw-bold mb-1"><i class="bi bi-paperclip me-1"></i>Resources</div>
                      <?php
                      if ($resResult->num_rows === 0) {
                          echo '<p class="text-muted small">No resources added.</p>';
                      } else {
                          while ($res = $resResult->fetch_assoc()) { ?>
                            <div id="resource-card-<?= $res['id'] ?>" class="resource-card small <?= ($res['status'] ?? '') === 'disabled' ? 'disabled' : '' ?>">
                              <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                  <?php if (($res['type'] ?? '') === 'file') { ?>
                                    <i class="bi bi-file-earmark me-2 text-primary"></i>
                                  <?php } elseif (($res['type'] ?? '') === 'link') { ?>
                                    <i class="bi bi-link-45deg me-2 text-info"></i>
                                  <?php } else { ?>
                                    <i class="bi bi-question-circle me-2 text-secondary"></i>
                                  <?php } ?>
                                  <span class="text-truncate" style="max-width: 120px;"><?= htmlspecialchars($res['title'] ?? 'No Title') ?></span>
                                </div>
                                
                                <div class="d-flex">
                                  <?php if (($res['type'] ?? '') === 'file' && !empty($res['file_path'])) { ?>
                                    <a href="../<?= htmlspecialchars($res['file_path']) ?>" target="_blank" download class="btn btn-sm btn-outline-primary p-1">
                                      <i class="bi bi-download"></i>
                                    </a>
                                  <?php } elseif (($res['type'] ?? '') === 'link' && !empty($res['link_url'])) { ?>
                                    <a href="<?= htmlspecialchars($res['link_url']) ?>" target="_blank" class="btn btn-sm btn-outline-info p-1">
                                      <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                  <?php } ?>
                                </div>
                              </div>
                            </div>
                      <?php }
                      } ?>
                    </div>

                    <!-- Add Resource Button -->
                    <button class="btn btn-sm btn-outline-primary mt-auto" onclick="event.stopPropagation(); toggleResourceForm(<?= $row['id'] ?>)">
                      <i class="bi bi-plus-circle me-1"></i>Add Resource
                    </button>
                    
                    <!-- Resource Upload Form -->
                    <div id="resourceForm-<?= $row['id'] ?>" class="resource-form mt-2" style="display:none;">
                      <form onsubmit="return uploadResource(event, <?= $row['id'] ?>)">
                        <div class="mb-2">
                          <input type="text" name="title" placeholder="Resource title" required class="form-control form-control-sm" />
                        </div>
                        <div class="mb-2">
                          <select name="type" onchange="toggleResourceInput(this, <?= $row['id'] ?>)" class="form-select form-select-sm" required>
                            <option value="">Select type</option>
                            <option value="file">File</option>
                            <option value="link">Link</option>
                          </select>
                        </div>
                        <div class="mb-2" id="fileInputContainer-<?= $row['id'] ?>" style="display:none;">
                          <input type="file" name="resource_file" class="form-control form-control-sm" />
                        </div>
                        <div class="mb-2" id="linkInputContainer-<?= $row['id'] ?>" style="display:none;">
                          <input type="url" name="link_url" placeholder="https://example.com" class="form-control form-control-sm" />
                        </div>
                        <div class="progress mb-2" style="height: 20px; display:none;" id="uploadProgress-<?= $row['id'] ?>">
                          <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%">0%</div>
                        </div>
                        <button type="submit" class="btn btn-sm btn-success">
                          <i class="bi bi-upload me-1"></i>Upload Resource
                        </button>
                      </form>
                      <div id="resourceMessage-<?= $row['id'] ?>" class="resource-message small"></div>
                    </div>

                  </div>

                  <div class="card-footer bg-transparent">
                    <div class="video-actions">
                      <button class="btn btn-sm btn-success" onclick="event.stopPropagation(); updateStatus(<?= $row['id'] ?>, 'active', this)" <?= ($row['status'] === 'active') ? 'disabled' : '' ?>>
                        <i class="bi bi-play-circle me-1"></i>Activate
                      </button>
                      <button class="btn btn-sm btn-warning" onclick="event.stopPropagation(); updateStatus(<?= $row['id'] ?>, 'disabled', this)" <?= ($row['status'] === 'disabled') ? 'disabled' : '' ?>>
                        <i class="bi bi-pause-circle me-1"></i>Disable
                      </button>
                      <button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); openEditModal(<?= $row['id'] ?>)">
                        <i class="bi bi-pencil me-1"></i>Edit
                      </button>
                      <button class="btn btn-sm btn-info" onclick="event.stopPropagation(); downloadVideo('<?= htmlspecialchars($row['video_path']) ?>', <?= $row['id'] ?>, this)">
                        <i class="bi bi-download me-1"></i>Download
                      </button>
                      <button class="btn btn-sm btn-danger" onclick="event.stopPropagation(); deleteRecording(<?= $row['id'] ?>)">
                        <i class="bi bi-trash me-1"></i>Delete
                      </button>
                    </div>
                  </div>
                </div>
              </div>

          <?php } // end while recordings

          if ($current_subject !== null) echo "</div>"; // close last subject row
      } else {
        echo "<div class='text-center py-5'>
                <i class='bi bi-collection-play display-4 text-muted'></i>
                <h5 class='mt-3 text-muted'>No videos uploaded yet</h5>
                <p class='text-muted'>Upload your first video to get started</p>
              </div>";
      }
      ?>

    </div>
  </div>
</main>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Recording</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editForm">
        <div class="modal-body">
          <input type="hidden" name="id" id="editId" />
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="editTitle" class="form-label">Title</label>
              <input type="text" class="form-control" name="title" id="editTitle" required />
            </div>
            <div class="col-md-6 mb-3">
              <label for="editSubject" class="form-label">Subject</label>
              <select name="subject_id" id="editSubject" class="form-select" required>
                <option value="">-- Select Subject --</option>
                <?php foreach ($subjectList as $sub): ?>
                  <option value="<?= htmlspecialchars($sub['id']) ?>"><?= htmlspecialchars($sub['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          
          <div class="mb-3">
            <label for="editDescription" class="form-label">Description</label>
            <textarea class="form-control" name="description" id="editDescription" rows="3"></textarea>
          </div>
          
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="editLectureType" class="form-label">Lecture Type</label>
              <select name="lecture_type" id="editLectureType" class="form-select">
                <option value="Zoom">Zoom Session Record</option>
                <option value="physical">Physical Lecture Record</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label for="editAccessLevel" class="form-label">Access Level</label>
              <select name="access_level" id="editAccessLevel" class="form-select">
                <option value="public">Public</option>
                <option value="batch">Batch Only</option>
                <option value="private">Private</option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label for="editViewLimit" class="form-label">View Limit (minutes)</label>
              <input type="number" class="form-control" name="view_limit_minutes" id="editViewLimit" min="0" />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include_once("../includes/footer.php"); ?>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<?php include_once("../includes/js-links-inc.php"); ?>

<!-- Video play modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-play-btn me-2"></i>Lecture Video</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <video id="modalVideo" controls style="width: 100%;" preload="metadata"></video>
      </div>
    </div>
  </div>
</div>


<script>
    function toggleResourceStatus(resourceId) {
      fetch('toggle_resource_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: resourceId })
      })
      .then(res => res.json())
      .then(data => {
        if (data.error) {
          alert('Error: ' + data.error);
          return;
        }
        const card = document.getElementById('resource-card-' + resourceId);
        if (!card) return;

        const toggleBtn = card.querySelector('button[title="Toggle Active/Disable"]');
        const links = card.querySelectorAll('.resource-actions a, a.btn');

        if (data.status === 'disabled') {
          card.classList.add('disabled');
          toggleBtn.textContent = 'Enable';
          // Disable links/buttons except toggle and delete
          card.querySelectorAll('a.btn').forEach(el => el.style.pointerEvents = 'none');
        } else {
          card.classList.remove('disabled');
          toggleBtn.textContent = 'Disable';
          card.querySelectorAll('a.btn').forEach(el => el.style.pointerEvents = '');
        }
      })
      .catch(err => {
        alert('Error toggling status: ' + err);
      });
    }

    function deleteResource(resourceId) {
      if (!confirm('Are you sure you want to delete this resource?')) return;
      fetch('delete-video-resource.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: resourceId })
      })
      .then(res => res.json())
      .then(data => {
        if (data.error) {
          alert('Error deleting resource: ' + data.error);
          return;
        }
        const card = document.getElementById('resource-card-' + resourceId);
        if (card) card.remove();
      })
      .catch(err => {
        alert('Error deleting resource: ' + err);
      });
    }
  </script>
<script>
function previewThumbnail(event) {
  const reader = new FileReader();
  reader.onload = function () {
    const preview = document.getElementById('thumbPreview');
    preview.src = reader.result;
    preview.style.display = 'block';
  };
  reader.readAsDataURL(event.target.files[0]);
}

document.getElementById('clearBtn').addEventListener('click', function() {
  document.getElementById('uploadForm').reset();
  document.getElementById('thumbPreview').style.display = 'none';
});

document.getElementById('uploadForm').addEventListener('submit', function(e) {
  const form = e.target;
  const formData = new FormData(form);
  const xhr = new XMLHttpRequest();
  const progressContainer = document.getElementById('progressContainer');
  const progressBar = document.getElementById('progressBar');

  e.preventDefault();
  xhr.open('POST', form.action, true);

  xhr.upload.addEventListener('progress', function(e) {
    if (e.lengthComputable) {
      const percent = Math.round((e.loaded / e.total) * 100);
      progressBar.style.width = percent + '%';
      progressBar.textContent = percent + '%';
      progressContainer.style.display = 'block';
    }
  });

  xhr.onload = function() {
    if (xhr.status === 200) {
      alert('Upload complete!');
      form.reset();
      progressContainer.style.display = 'none';
      progressBar.style.width = '0%';
      document.getElementById('thumbPreview').style.display = 'none';
      location.reload();
    } else {
      alert('Upload failed: ' + xhr.responseText);
    }
  };

  xhr.send(formData);
});

document.querySelectorAll('.video-card img.card-img-top').forEach(thumbnail => {
  thumbnail.addEventListener('click', function (e) {
    e.stopPropagation();
    const card = this.closest('.video-card');
    const videoPath = card.dataset.video;
    const recordingId = parseInt(card.dataset.id);
    openModal(videoPath, recordingId);
  });
});

function openModal(videoPath, recordingId) {
  const modalVideo = document.getElementById('modalVideo');
  modalVideo.src = 'stream-video.php?file=' + encodeURIComponent(videoPath.split('/').pop());
  const modal = new bootstrap.Modal(document.getElementById('videoModal'));
  modal.show();

  // Fetch related resources
  fetch('get-video-resources.php?recording_id=' + recordingId)
    .then(res => res.json())
    .then(data => {
      const container = document.getElementById('resourceContainer');
      container.innerHTML = '';
      if (data.length === 0) {
        container.innerHTML = "<p class='text-muted'>No additional resources available.</p>";
        return;
      }

      data.forEach(res => {
        const item = document.createElement('div');
        item.className = 'd-flex justify-content-between align-items-center mb-2 p-2 rounded ' + (res.status === 'disabled' ? 'bg-light text-muted opacity-50' : 'bg-white');

        item.innerHTML = `
          <div>
            <strong>${res.name}</strong><br/>
            <small>${res.type} - <a href="${res.path}" target="_blank">Open</a></small>
          </div>
          <div>
            <button class="btn btn-sm btn-warning me-2" onclick="toggleResourceStatus(${res.id}, '${res.status}')">${res.status === 'active' ? 'Disable' : 'Enable'}</button>
            <button class="btn btn-sm btn-danger" onclick="deleteResource(${res.id})">Delete</button>
          </div>
        `;
        container.appendChild(item);
      });
    });

  // Increment play count on modal open
  fetch('update-play-count.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ id: recordingId })
  })
  .then(res => res.json())
  .then(data => {
    if (!data.error) {
      // Update play count UI
      const cards = document.querySelectorAll('.video-card');
      cards.forEach(card => {
        if (parseInt(card.dataset.id) === recordingId) {
          const btns = card.querySelectorAll('button.btn-secondary');
          btns[0].textContent = `Play Count: ${data.count}`;
        }
      });
    }
  });
}

function updateStatus(id, newStatus, btn) {
  if (!confirm(`Are you sure you want to mark this video as "${newStatus}"?`)) return;

  fetch('update-recording-status.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ id, status: newStatus })
  })
  .then(res => res.json())
  .then(data => {
    if (data.error) return alert('Error: ' + data.error);
    alert(data.success);

    const card = btn.closest('.video-card');
    if (newStatus === 'disabled') {
      card.classList.add('disabled');
      card.querySelector('.btn-warning').disabled = true;
      card.querySelector('.btn-success').disabled = false;
    } else {
      card.classList.remove('disabled');
      card.querySelector('.btn-success').disabled = true;
      card.querySelector('.btn-warning').disabled = false;
    }
  })
  .catch(err => alert('Error: ' + err));
}

function downloadVideo(videoPath, recordingId, btn) {
  const link = document.createElement('a');
  link.href = 'stream-video.php?file=' + encodeURIComponent(videoPath.split('/').pop());
  link.download = '';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);

  fetch('update-download-count.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: recordingId })
  })
  .then(res => res.json())
  .then(data => {
    if (!data.error) {
      // Live update the download count button
      const card = document.querySelector(`.video-card[data-id='${recordingId}']`);
      if (card) {
        const btns = card.querySelectorAll('.btn-secondary');
        if (btns.length > 1) {
          btns[1].textContent = `Download Count: ${data.count}`;
        }
      }
    }
  });
}


function deleteRecording(id) {
  if (!confirm('Are you sure you want to delete this recording?')) return;

  fetch('delete-recording.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ id })
  })
  .then(res => res.text())
  .then(data => {
    alert(data);
    location.reload();
  })
  .catch(err => alert('Error: ' + err));
}

// Disable right click on video elements to block "Save as"
document.addEventListener('contextmenu', function(e) {
  if (e.target.tagName === 'VIDEO') {
    e.preventDefault();
  }
});

// Edit modal code
const editModal = new bootstrap.Modal(document.getElementById('editModal'));

function openEditModal(id) {
  fetch('get-recording.php?id=' + id)
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        alert('Error fetching recording data: ' + data.error);
        return;
      }
      document.getElementById('editId').value = data.id;
      document.getElementById('editTitle').value = data.title;
      document.getElementById('editDescription').value = data.description || '';
      document.getElementById('editSubject').value = data.subject_id;
      document.getElementById('editLectureType').value = data.lecture_type || 'Zoom';
      document.getElementById('editAccessLevel').value = data.access_level || 'public';
      document.getElementById('editViewLimit').value = data.view_limit_minutes || '';
      editModal.show();
    })
    .catch(err => alert('Failed to load recording data: ' + err));
}

document.getElementById('editForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  fetch('update-recording.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.error) {
      alert('Failed to update: ' + data.error);
    } else {
      alert('Recording updated successfully!');
      editModal.hide();
      location.reload();
    }
  })
  .catch(err => alert('Error: ' + err));
});

// --- New Add Resource Feature ---

function toggleResourceForm(recordingId) {
  const form = document.getElementById(`resourceForm-${recordingId}`);
  if (form.style.display === 'none' || form.style.display === '') {
    form.style.display = 'block';
  } else {
    form.style.display = 'none';
  }
}

function toggleResourceInput(selectElem, recordingId) {
  const fileContainer = document.getElementById(`fileInputContainer-${recordingId}`);
  const linkContainer = document.getElementById(`linkInputContainer-${recordingId}`);

  if (selectElem.value === 'file') {
    fileContainer.style.display = 'block';
    linkContainer.style.display = 'none';
    fileContainer.querySelector('input').required = true;
    linkContainer.querySelector('input').required = false;
  } else if (selectElem.value === 'link') {
    fileContainer.style.display = 'none';
    linkContainer.style.display = 'block';
    fileContainer.querySelector('input').required = false;
    linkContainer.querySelector('input').required = true;
  } else {
    fileContainer.style.display = 'none';
    linkContainer.style.display = 'none';
    fileContainer.querySelector('input').required = false;
    linkContainer.querySelector('input').required = false;
  }
}



function uploadResource(event, recordingId) {
  event.preventDefault();

  const form = event.target;
  const messageDiv = document.getElementById(`resourceMessage-${recordingId}`);
  const progressBarContainer = document.getElementById(`uploadProgress-${recordingId}`);
  const progressBar = progressBarContainer.querySelector('.progress-bar');

  messageDiv.textContent = '';
  progressBarContainer.style.display = 'block';
  progressBar.style.width = '0%';
  progressBar.textContent = '0%';

  const formData = new FormData(form);
  formData.append('recording_id', recordingId);

  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'add-video-resource.php');

  xhr.upload.onprogress = function(e) {
    if (e.lengthComputable) {
      const percent = Math.round((e.loaded / e.total) * 100);
      progressBar.style.width = percent + '%';
      progressBar.textContent = percent + '%';
    }
  };

  xhr.onload = function() {
    progressBar.style.width = '100%';
    progressBar.textContent = 'Upload complete';

    if (xhr.status === 200) {
      let data;
      try {
        data = JSON.parse(xhr.responseText);
      } catch (err) {
        messageDiv.style.color = 'red';
        messageDiv.textContent = 'Invalid server response';
        progressBarContainer.style.display = 'none';
        return;
      }

      if (data.error) {
        messageDiv.style.color = 'red';
        messageDiv.textContent = data.error;
      } else {
        messageDiv.style.color = 'green';
        messageDiv.textContent = 'Resource uploaded successfully!';
        form.reset();
        form.querySelector('select[name="type"]').value = '';
        toggleResourceInput(form.querySelector('select[name="type"]'), recordingId);

        setTimeout(() => {
          location.reload();
        }, 500);
      }
    } else {
      messageDiv.style.color = 'red';
      messageDiv.textContent = `Upload failed: ${xhr.statusText}`;
    }

    setTimeout(() => {
      progressBarContainer.style.display = 'none';
      progressBar.style.width = '0%';
      progressBar.textContent = '0%';
    }, 3000);
  };

  xhr.onerror = function() {
    messageDiv.style.color = 'red';
    messageDiv.textContent = 'Upload failed due to a network error.';
    progressBarContainer.style.display = 'none';
  };

  xhr.send(formData);

  return false;
}


</script>

<!-- Video play modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Lecture Video</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <video id="modalVideo" controls style="width: 1080px;" preload="metadata"></video>
      </div>
    </div>
  </div>
</div>

</body>
</html>
