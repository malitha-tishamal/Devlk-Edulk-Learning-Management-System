<?php
session_start();
require_once '../includes/db-conn.php';

if (!isset($_SESSION['sadmin_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['sadmin_id'];
$user_role = 'sadmin';
$user_folder_name = $user_role . '_' . $user_id;
$uploadDir = 'uploads/drive/' . $user_folder_name . '/';

function escapeHtml($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function getFileIconHtml($ext, $url = '') {
    $ext = strtolower($ext);
    $imgExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
    if (in_array($ext, $imgExts) && $url !== '') {
        return '<img src="' . escapeHtml($url) . '" class="card-img-top file-thumbnail" alt="img">';
    }

    switch ($ext) {
        case 'pdf': return '<i class="fa-solid fa-file-pdf text-danger file-icon"></i>';
        case 'doc': case 'docx': return '<i class="fa-solid fa-file-word text-primary file-icon"></i>';
        case 'xls': case 'xlsx': return '<i class="fa-solid fa-file-excel text-success file-icon"></i>';
        case 'ppt': case 'pptx': return '<i class="fa-solid fa-file-powerpoint text-warning file-icon"></i>';
        case 'zip': case 'rar': return '<i class="fa-solid fa-file-archive file-icon" style="color:#fd7e14;"></i>';
        case 'mp3': case 'wav': case 'ogg': return '<i class="fa-solid fa-file-audio file-icon" style="color:#9b59b6;"></i>';
        case 'mp4': case 'mov': case 'avi': return '<i class="fa-solid fa-file-video file-icon" style="color:#e74c3c;"></i>';
        case 'txt': return '<i class="fa-solid fa-file-lines file-icon" style="color:#95a5a6;"></i>';
        default: return '<i class="fa-solid fa-file text-secondary file-icon"></i>';
    }
}

// Handle file deletion via AJAX
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['filename'])) {
    $fileToDelete = basename($_POST['filename']);
    $fullPath = $uploadDir . $fileToDelete;
    if (file_exists($fullPath)) {
        unlink($fullPath);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'File not found']);
    }
    exit();
}

// Handle uploads
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['action'])) {
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Calculate current usage
    $filesNow = array_filter(scandir($uploadDir), function($f){ return $f !== '.' && $f !== '..'; });
    $totalUsedBytes = 0;
    foreach ($filesNow as $file) {
        $totalUsedBytes += filesize($uploadDir . $file);
    }
    $maxQuotaBytes = 100 * 1024 * 1024; // 100MB max

    $uploadedFiles = [];
    $skippedFiles = [];
    foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['files']['name'][$key]);
        $file_size = $_FILES['files']['size'][$key];

        if (($totalUsedBytes + $file_size) > $maxQuotaBytes) {
            $skippedFiles[] = $file_name;
            continue;
        }

        $file_path = $uploadDir . $file_name;
        if (move_uploaded_file($tmp_name, $file_path)) {
            $uploadedFiles[] = $file_name;
            $totalUsedBytes += $file_size;
        }
    }

    $responseFiles = [];
    foreach ($uploadedFiles as $fName) {
        $path = $uploadDir . $fName;
        $responseFiles[] = [
            'name' => $fName,
            'size' => filesize($path),
            'ext' => pathinfo($fName, PATHINFO_EXTENSION),
            'url' => 'uploads/drive/' . $user_folder_name . '/' . rawurlencode($fName),
        ];
    }

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true, 
        'uploaded' => $responseFiles,
        'skipped' => $skippedFiles
    ]);
    exit();
}

// Fetch user info
$stmt = $conn->prepare("SELECT * FROM sadmins WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Display existing files
$files = [];
if (is_dir($uploadDir)) {
    foreach (scandir($uploadDir) as $f) {
        if ($f !== '.' && $f !== '..') {
            $filePath = $uploadDir . $f;
            $files[] = [
                'name' => $f,
                'size' => filesize($filePath),
                'ext' => pathinfo($f, PATHINFO_EXTENSION),
                'url' => 'uploads/drive/' . $user_folder_name . '/' . rawurlencode($f),
                'mtime' => filemtime($filePath),
            ];
        }
    }
}

// Sort files by upload time (newest first)
usort($files, function($a, $b) {
    return $b['mtime'] - $a['mtime'];
});

// Calculate total used bytes & quota
$totalUsedBytes = 0;
foreach ($files as $file) {
    $totalUsedBytes += $file['size'];
}
$maxQuotaBytes = 100 * 1024 * 1024;
$usagePercent = min(100, ($totalUsedBytes / $maxQuotaBytes) * 100);
$remainingSpace = $maxQuotaBytes - $totalUsedBytes;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>My Drive - EduWide</title>
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
      padding: 1.5rem;
    }
    
    .page-title {
      color: #343a40;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }
    
    .breadcrumb-item a {
      color: var(--primary);
      text-decoration: none;
    }
    
    .breadcrumb-item.active {
      color: #6c757d;
    }
    
    .file-thumbnail {
      height: 150px;
      object-fit: cover;
      border-bottom: 1px solid #eee;
      border-radius: 8px 8px 0 0;
    }
    
    .file-icon {
      font-size: 3.5rem;
      display: block;
      margin: 20px auto 15px auto;
      opacity: 0.8;
    }
    
    .card-file {
      width: 220px;
      margin: 15px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
      border-radius: 8px;
      background: #fff;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    
    .card-file:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .file-name {
      font-weight: 600;
      font-size: 0.95rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 8px;
    }
    
    .file-info {
      font-size: 0.8rem;
      color: #6c757d;
      margin-bottom: 12px;
      line-height: 1.4;
    }
    
    .btn-group {
      display: flex;
      justify-content: center;
      gap: 8px;
    }
    
    .btn-sm {
      font-size: 0.8rem;
      padding: 5px 10px;
      border-radius: 6px;
    }
    
    .btn-view {
      background-color: var(--primary);
      color: white;
    }
    
    .btn-view:hover {
      background-color: var(--secondary);
      color: white;
    }
    
    .btn-download {
      background-color: var(--success);
      color: white;
    }
    
    .btn-download:hover {
      background-color: #3ab7dc;
      color: white;
    }
    
    .btn-delete {
      background-color: var(--danger);
      color: white;
    }
    
    .btn-delete:hover {
      background-color: #d32f2f;
      color: white;
    }
    
    .storage-card {
      background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%);
      color: white;
      border-radius: 12px;
    }
    
    .storage-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }
    
    .storage-details {
      font-size: 0.9rem;
      margin-top: 5px;
    }
    
    .progress {
      height: 10px;
      border-radius: 5px;
      background-color: rgba(255, 255, 255, 0.3);
    }
    
    .progress-bar {
      border-radius: 5px;
    }
    
    .upload-area {
      border: 2px dashed #dee2e6;
      border-radius: 8px;
      padding: 20px;
      text-align: center;
      transition: all 0.3s;
      background-color: #f8f9fa;
    }
    
    .upload-area:hover {
      border-color: var(--primary);
      background-color: #eef2ff;
    }
    
    .upload-area.dragover {
      border-color: var(--primary);
      background-color: #e8edff;
    }
    
    .file-grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: flex-start;
      margin: 0 -15px;
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
      .card-file {
        width: 100%;
        margin: 10px 0;
      }
      
      .file-grid {
        margin: 0;
      }
      
      .storage-info {
        flex-direction: column;
        align-items: flex-start;
      }
    }
  </style>
</head>
<body>
<?php include_once("../includes/header.php") ?>
<?php include_once("../includes/sadmin-sidebar.php") ?>

<main id="main" class="main">
  <div class="pagetitle mb-4">
    <h1 class="page-title">My Drive</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">My Drive</li>
      </ol>
    </nav>
  </div>

  <!-- Storage Usage Card -->
  <div class="card storage-card mb-4">
    <div class="card-body">
      <div class="storage-info">
        <div>
          <h5 class="card-title text-white">Storage</h5>
          <div class="storage-details">
            <?= number_format($totalUsedBytes / 1024 / 1024, 2) ?> MB of 100 MB used
            <?php if ($remainingSpace > 0): ?>
              <br><small><?= number_format($remainingSpace / 1024 / 1024, 2) ?> MB remaining</small>
            <?php endif; ?>
          </div>
        </div>
        <div class="text-end">
          <h4 class="text-white"><?= number_format($usagePercent, 1) ?>%</h4>
        </div>
      </div>
      <div class="progress">
        <div 
          class="progress-bar <?= ($usagePercent > 90) ? 'bg-danger' : 'bg-light' ?>" 
          role="progressbar" 
          style="width: <?= $usagePercent ?>%;"
          aria-valuenow="<?= $usagePercent ?>" 
          aria-valuemin="0" 
          aria-valuemax="100"
        ></div>
      </div>
    </div>
  </div>

  <!-- Upload Card -->
  <div class="card mb-4">
    <div class="card-body">
      <h5 class="card-title mb-3">Upload Files</h5>
      
      <div class="upload-area" id="dropZone">
        <i class="fas fa-cloud-upload-alt mb-3" style="font-size: 3rem; color: #4361ee;"></i>
        <p class="mb-3">Drag & drop files here or click to browse</p>
        <input
          type="file"
          name="files[]"
          id="fileInput"
          class="d-none"
          multiple
          <?= ($totalUsedBytes >= $maxQuotaBytes) ? 'disabled' : '' ?>
        />
        <button class="btn btn-primary" id="browseBtn">
          <i class="fas fa-folder-open me-2"></i>Browse Files
        </button>
        <p class="small text-muted mt-2">Maximum file size: 100MB total</p>
      </div>

      <div id="uploadProgressContainer" class="upload-progress-container mt-3"></div>
      <div id="uploadStatus" class="mt-3"></div>
    </div>
  </div>

  <!-- Files Grid -->
  <div class="card">
    <div class="card-body">
      <h5 class="card-title mb-4">Your Files</h5>
      
      <?php if (count($files) === 0): ?>
        <div class="empty-state">
          <i class="bi bi-inbox"></i>
          <h4>No Files Yet</h4>
          <p>Upload your first file to get started</p>
        </div>
      <?php else: ?>
        <div class="file-grid">
          <?php foreach ($files as $file):
            $uploadTime = date('M j, Y g:i A', $file['mtime']);
            $fileSize = $file['size'] > 1024 * 1024 
              ? number_format($file['size'] / 1024 / 1024, 2) . ' MB'
              : number_format($file['size'] / 1024, 2) . ' KB';
          ?>
            <div class="card card-file" data-filename="<?= escapeHtml($file['name']) ?>">
              <?= getFileIconHtml($file['ext'], $file['url']) ?>
              <div class="card-body">
                <div class="file-name" title="<?= escapeHtml($file['name']) ?>">
                  <?= escapeHtml($file['name']) ?>
                </div>
                <div class="file-info">
                  Size: <?= $fileSize ?><br />
                  Uploaded: <?= $uploadTime ?>
                </div>
                <div class="btn-group">
                  <a href="<?= $file['url'] ?>" class="btn btn-sm btn-download" download="<?= escapeHtml($file['name']) ?>" title="Download">
                    <i class="fas fa-download"></i>
                  </a>
                  <a href="<?= $file['url'] ?>" target="_blank" class="btn btn-sm btn-view" title="Preview">
                    <i class="fas fa-eye"></i>
                  </a>
                  <button
                    class="btn btn-sm btn-delete btn-delete-file"
                    title="Delete"
                    data-filename="<?= escapeHtml($file['name']) ?>"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<script>
  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  function formatFileSize(bytes) {
    if (bytes >= 1024 * 1024) {
      return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    } else {
      return (bytes / 1024).toFixed(2) + ' KB';
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('uploadForm');
    const fileInput = document.getElementById('fileInput');
    const browseBtn = document.getElementById('browseBtn');
    const dropZone = document.getElementById('dropZone');
    const uploadStatus = document.getElementById('uploadStatus');
    const progressContainer = document.getElementById('uploadProgressContainer');

    // Browse button click handler
    browseBtn.addEventListener('click', function() {
      fileInput.click();
    });

    // File input change handler
    fileInput.addEventListener('change', function() {
      if (this.files.length > 0) {
        handleFiles(this.files);
      }
    });

    // Drag and drop handlers
    dropZone.addEventListener('dragover', function(e) {
      e.preventDefault();
      this.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', function() {
      this.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', function(e) {
      e.preventDefault();
      this.classList.remove('dragover');
      
      if (e.dataTransfer.files.length > 0) {
        handleFiles(e.dataTransfer.files);
      }
    });

    // Handle file processing
    function handleFiles(files) {
      progressContainer.innerHTML = '';
      uploadStatus.innerHTML = '';

      const formData = new FormData();
      for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
      }

      const xhr = new XMLHttpRequest();
      xhr.open('POST', '', true);

      // Create progress bar
      const progressWrapper = document.createElement('div');
      progressWrapper.className = 'mb-2';

      const label = document.createElement('div');
      label.textContent = `Uploading ${files.length} file(s)`;
      label.style.fontWeight = '600';

      const progress = document.createElement('progress');
      progress.className = 'w-100';
      progress.max = 100;
      progress.value = 0;
      progress.style.height = '20px';

      progressWrapper.appendChild(label);
      progressWrapper.appendChild(progress);
      progressContainer.appendChild(progressWrapper);

      xhr.upload.addEventListener('progress', e => {
        if (e.lengthComputable) {
          const percent = Math.round((e.loaded / e.total) * 100);
          progress.value = percent;
          label.textContent = `Uploading ${files.length} file(s) - ${percent}%`;
        }
      });

      xhr.onload = () => {
        if (xhr.status === 200) {
          try {
            const res = JSON.parse(xhr.responseText);
            if (res.success) {
              if (res.skipped && res.skipped.length > 0) {
                uploadStatus.innerHTML = `
                  <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${res.uploaded.length} file(s) uploaded successfully, 
                    but ${res.skipped.length} file(s) were skipped due to storage limit.
                  </div>
                `;
              } else {
                uploadStatus.innerHTML = `
                  <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    ${res.uploaded.length} file(s) uploaded successfully.
                  </div>
                `;
              }
              // Reload after a short delay
              setTimeout(() => location.reload(), 1500);
            } else {
              uploadStatus.innerHTML = `
                <div class="alert alert-danger">
                  <i class="fas fa-times-circle me-2"></i>
                  Upload failed.
                </div>
              `;
            }
          } catch {
            uploadStatus.innerHTML = `
              <div class="alert alert-danger">
                <i class="fas fa-times-circle me-2"></i>
                Invalid server response.
              </div>
            `;
          }
        } else {
          uploadStatus.innerHTML = `
            <div class="alert alert-danger">
              <i class="fas fa-times-circle me-2"></i>
              Upload failed. Status: ${xhr.status}
            </div>
          `;
        }
        fileInput.disabled = false;
        browseBtn.disabled = false;
        progressContainer.innerHTML = '';
      };

      xhr.onerror = () => {
        uploadStatus.innerHTML = `
          <div class="alert alert-danger">
            <i class="fas fa-times-circle me-2"></i>
            Upload error occurred.
          </div>
        `;
        fileInput.disabled = false;
        browseBtn.disabled = false;
        progressContainer.innerHTML = '';
      };

      fileInput.disabled = true;
      browseBtn.disabled = true;
      xhr.send(formData);
    }

    // File deletion
    document.body.addEventListener('click', function(e) {
      if (e.target.closest('.btn-delete-file')) {
        const btn = e.target.closest('.btn-delete-file');
        const filename = btn.dataset.filename;

        if (!filename) return;

        if (!confirm(`Are you sure you want to delete "${filename}"?`)) return;

        btn.disabled = true;

        fetch('', {
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: new URLSearchParams({action: 'delete', filename: filename})
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const card = document.querySelector(`.card-file[data-filename="${CSS.escape(filename)}"]`);
            if (card) card.remove();
            // Show success message
            uploadStatus.innerHTML = `
              <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                File deleted successfully.
              </div>
            `;
            // Reload after a short delay to update storage info
            setTimeout(() => location.reload(), 1500);
          } else {
            alert(data.message || 'Failed to delete file.');
            btn.disabled = false;
          }
        })
        .catch(() => {
          alert('Failed to delete file due to a network error.');
          btn.disabled = false;
        });
      }
    });
  });
</script>

<?php include_once ("../includes/footer.php") ?>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center">
  <i class="bi bi-arrow-up-short"></i>
</a>
<?php include_once ("../includes/js-links-inc.php") ?>

</body>
</html>

<?php $conn->close(); ?>