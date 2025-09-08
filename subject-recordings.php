<?php
session_start();
require_once 'includes/db-conn.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['student_id'];
$sql = "SELECT name, email, nic, mobile, profile_picture FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$subject_id = isset($_GET['subject']) ? intval($_GET['subject']) : 0;
if ($subject_id <= 0) {
    die("Invalid subject");
}

// Get subject details
$subjectStmt = $conn->prepare("SELECT id, code, name, semester FROM subjects WHERE id = ?");
$subjectStmt->bind_param("i", $subject_id);
$subjectStmt->execute();
$subjectResult = $subjectStmt->get_result();
$subject = $subjectResult->fetch_assoc();
$subjectStmt->close();

if (!$subject) {
    die("Subject not found");
}

// Get recordings for this subject
$recordings = [];
$recordingStmt = $conn->prepare("
    SELECT 
    r.*,
    CASE 
        WHEN r.role = 'superadmin' THEN sa.name
        WHEN r.role = 'lecture' THEN l.name
        ELSE 'Admin'
    END AS uploader_name,
    COALESCE(rsp.plays_left, r.view_limit_minutes) AS plays_left
FROM recordings r
LEFT JOIN sadmins sa 
       ON r.created_by = sa.id 
      AND r.role = 'superadmin'
LEFT JOIN lectures l 
       ON r.created_by = l.id 
      AND r.role = 'lecture'
LEFT JOIN recording_student_plays rsp 
       ON rsp.recording_id = r.id 
      AND rsp.student_id = ?
WHERE r.subject_id = ? 
  AND r.status = 'active'
  AND r.access_level IN ('public', 'batch')   -- ✅ filter like in your previous query
ORDER BY r.release_time DESC;

");
$recordingStmt->bind_param("ii", $user_id, $subject_id);
$recordingStmt->execute();
$recordingResult = $recordingStmt->get_result();
while ($row = $recordingResult->fetch_assoc()) {
    $recordings[] = $row;
}
$recordingStmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($subject['code'] . " - " . $subject['name']) ?> Recordings - Edulk</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <?php include_once("includes/css-links-inc.php"); ?>
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #6c757d;
            --light: #f8f9fa;
            --dark: #343a40;
            --success: #28a745;
            --card-shadow: 0 12px 30px rgba(0,0,0,0.08);
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f5f7ff;
            font-family: 'Poppins', sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .subject-header {
            background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%);
            color: white;
        }

        .subject-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .subject-meta {
            display: flex;
            gap: 20px;
            font-size: 1.1rem;
            margin-top: 15px;
        }

        .subject-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 20px;
            border-radius: 30px;
        }

        .subject-meta-item i {
            font-size: 1.2rem;
        }

        .recordings-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(67, 97, 238, 0.2);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            background: rgba(67, 97, 238, 0.1);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .no-recordings {
            text-align: center;
            padding: 50px;
            color: var(--secondary);
        }

        .no-recordings i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #d1d5e0;
        }

        .recording-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 30px;
            margin-top: 10px;
        }

        .recording-card {
            width : 500px;
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .recording-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }

        .video-container {
            position: relative;
            height: 200px;
            overflow: hidden;
            background: #e0e7ff;
        }

        .video-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .video-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .recording-card:hover .video-thumb {
            transform: scale(1.05);
        }

        .play-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: var(--transition);
        }

        .recording-card:hover .play-overlay {
            opacity: 1;
        }

        .play-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .play-icon i {
            font-size: 28px;
            color: var(--primary);
            margin-left: 5px;
        }

        .recording-info {
            padding: 20px;
        }

        .recording-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .recording-meta {
            display: flex;
            justify-content: space-between;
            color: var(--secondary);
            font-size: 0.9rem;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .recording-meta div {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .recording-description {
            margin-bottom: 20px;
            color: #555;
        }

        .resource-section {
            margin-top: 0px;
        }

        .resource-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .resource-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .resource-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            background: #f8f9ff;
            border-radius: 8px;
            transition: var(--transition);
        }

        .resource-item:hover {
            background: #edf1ff;
            transform: translateX(5px);
        }

        .resource-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: rgba(67, 97, 238, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.2rem;
        }

        .resource-details {
            flex-grow: 1;
        }

        .resource-name {
            font-weight: 500;
            margin-bottom: 3px;
            color: var(--dark);
        }

        .resource-type {
            font-size: 0.85rem;
            color: var(--secondary);
        }

        .resource-action {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
            border: none;
            border-radius: 6px;
            padding: 6px 12px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .resource-action:hover {
            background: var(--primary);
            color: white;
        }

        .back-btn:hover {
            background: #edf1ff;
            transform: translateY(-3px);
        }


        @media (max-width: 768px) {
            .recording-grid {
                grid-template-columns: 1fr;
            }
            
            .subject-title {
                font-size: 1rem;
            }
            
            .subject-meta {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <?php include_once("includes/header.php") ?>
    <?php include_once("includes/student-sidebar.php") ?>
    
   <main id="main" class="main">
        <div class="pagetitle">
            <h1>Lecture Recordings</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Lecture Recordings</li>
                </ol>
            </nav>
        </div>

        <div class="subject-header">
        <div class="recordings p-4">
            
            <h2 class="subject-title"><?= htmlspecialchars($subject['code'] . " - " . $subject['name']) ?></h2>
            <p>Lecture recordings and resources for this subject</p>
            
            <div class="subject-meta">
                <div class="subject-meta-item">
                    <i class="fa-solid fa-calendar"></i>
                    <?= htmlspecialchars($subject['semester']) ?>
                </div>
                <div class="subject-meta-item">
                    <i class="fa-solid fa-video"></i>
                    <?= count($recordings) ?> Recording<?= count($recordings) !== 1 ? 's' : '' ?>
                </div>
            </div>
        </div>
    </div>
        
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
            <h2 class="section-title">
                <i class="fa-solid fa-film"></i>
                Lecture Recordings
            </h2>
            
            <?php if (!empty($recordings)): ?>
                <div class="recording-grid">
                    <?php foreach ($recordings as $rec): ?>
                        <div class="recording-card">
                            <div class="video-container">
                                <?php if (!empty($rec['thumbnail_path'])): ?>
                                    <img src="<?= htmlspecialchars($rec['thumbnail_path']) ?>" class="video-thumb" alt="Video thumbnail">
                                <?php else: ?>
                                    <div style="display: flex; align-items: center; justify-content: center; height: 100%;">
                                        <i class="fa-solid fa-video" style="font-size: 3rem; color: var(--primary);"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="play-overlay">
                                    <div class="play-icon" onclick="openVideoModal('<?= htmlspecialchars($rec['video_path']) ?>', <?= $rec['id'] ?>)">
                                        <i class="fa-solid fa-play"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="recording-info">
                                <h3 class="recording-title">
                                    <i class="fa-solid fa-video"></i> 
                                    <?= htmlspecialchars($rec['title'] ?? 'Lecture Recording') ?>
                                </h3>

                                <div class="recording-meta">
                                    <div>
                                        <i class="fa-solid fa-clapperboard"></i> 
                                        <strong>Recording Type:</strong> <?= htmlspecialchars($rec['lecture_type']) ?>
                                    </div>
                                    <div>
                                        <i class="fa-solid fa-lock"></i> 
                                        <strong>Limitation:</strong> <?= htmlspecialchars($rec['access_level']) ?>
                                    </div>
                                </div>

                                <div class="recording-meta">
                                    <div>
                                        <i class="fa-solid fa-user-tie"></i> 
                                        <?= htmlspecialchars($rec['uploader_name']) ?> (<?= htmlspecialchars($rec['role']) ?>)
                                    </div>
                                    <div>
                                        <i class="fa-solid fa-calendar-day"></i> 
                                        <?= date("M d, Y", strtotime($rec['release_time'])) ?>
                                    </div>
                                </div>

                                <div class="recording-meta">
                                    <div>
                                        <i class="fa-solid fa-download"></i> 
                                        <strong>Downloads:</strong> <?= htmlspecialchars($rec['download_count']) ?>
                                    </div>
                                    <div>
                                        <i class="fa-solid fa-play"></i> 
                                        <strong>Views:</strong> <?= htmlspecialchars($rec['play_count']) ?>
                                    </div>
                                </div>

                               <div>
    <i class="fa-solid fa-play"></i> 
    <strong>Your Play Count Access Remaining:</strong>
    <span class="remaining-plays" data-recording="<?= $rec['id'] ?>">
        <?= $rec['plays_left'] ?>
    </span>
</div>





                                <?php if (!empty($rec['description'])): ?>
                                    <p class="recording-description">
                                        <i class="fa-solid fa-align-left"></i> 
                                        <?= htmlspecialchars($rec['description']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                                
                                <div class="resource-section">
                                    <h4 class="resource-title">
                                        <i class="fa-solid fa-file-lines"></i>
                                        Resources
                                    </h4>
                                    
                                    <div class="resource-list">
                                        <?php
                                        // Get resources for this recording
                                        $resources = [];
                                        $resourceStmt = $conn->prepare("SELECT * FROM recording_resources WHERE recording_id = ? AND status = 'active'");
                                        $resourceStmt->bind_param("i", $rec['id']);
                                        $resourceStmt->execute();
                                        $resourceResult = $resourceStmt->get_result();
                                        while ($res = $resourceResult->fetch_assoc()) {
                                            $resources[] = $res;
                                        }
                                        $resourceStmt->close();
                                        ?>
                                        
                                        <?php if (!empty($resources)): ?>
                                            <?php foreach ($resources as $res): ?>
                                                <div class="resource-item">
                                                    <div class="resource-icon">
                                                        <?php if ($res['type'] === 'file'): ?>
                                                            <i class="fa-solid fa-file"></i>
                                                        <?php else: ?>
                                                            <i class="fa-solid fa-link"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="resource-details">
                                                        <div class="resource-name"><?= htmlspecialchars($res['title']) ?></div>
                                                        <div class="resource-type">
                                                            <?= $res['type'] === 'file' ? 'Document' : 'Web Link' ?>
                                                        </div>
                                                    </div>
                                                    <a href="<?= $res['type'] === 'file' ? htmlspecialchars($res['file_path']) : htmlspecialchars($res['link_url']) ?>" 
                                                       class="resource-action" 
                                                       target="_blank"
                                                       <?= $res['type'] === 'file' ? 'download' : '' ?>>
                                                        <i class="fa-solid fa-<?= $res['type'] === 'file' ? 'download' : 'external-link-alt' ?>"></i>
                                                    </a>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="resource-item">
                                                <div class="resource-icon">
                                                    <i class="fa-solid fa-info-circle"></i>
                                                </div>
                                                <div class="resource-details">
                                                    <div class="resource-name">No resources available</div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-recordings">
                    <i class="fa-solid fa-video-slash"></i>
                    <h3>No Recordings Available</h3>
                    <p>There are currently no lecture recordings for this subject.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Video Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lecture Recording</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <video id="modalVideo" controls style="width: 100%;" preload="metadata"></video>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="downloadBtn" disabled>
        <i class="fa-solid fa-download me-1"></i> Download Video
    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
function openVideoModal(videoPath, recordingId) {
    const modalVideo = document.getElementById('modalVideo');

    // Call backend to update student play count and get remaining plays
    fetch('students_plays.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: recordingId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }

        // Set video source
        const filename = videoPath.split('/').pop();
        modalVideo.src = 'stream-video.php?file=' + encodeURIComponent(filename);

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('videoModal'));
        modal.show();

        // Update global play count on card
        const cardPlayCount = document.querySelector(`.recording-card[data-recording="${recordingId}"] .play-count`);
        if (cardPlayCount) cardPlayCount.textContent = data.global_count + ' views';

        // Update remaining plays dynamically
        const remainingSpan = document.querySelector(`.remaining-plays[data-recording="${recordingId}"]`);
        if (remainingSpan) remainingSpan.textContent = data.plays_left;
    })
    .catch(err => console.error(err));
}

// Optional: function to update remaining plays separately
function updateRemainingPlays(recordingId) {
    fetch('students_plays.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: recordingId })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.error) {
            const span = document.querySelector(`.remaining-plays[data-recording="${recordingId}"]`);
            if (span) span.textContent = data.plays_left;
        }
    })
    .catch(err => console.error(err));
}

// Optional: function to update global play count only
function updatePlayCount(recordingId) {
    fetch('update-play-count.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: recordingId })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.error) {
            const card = document.querySelector(`.recording-card[data-recording="${recordingId}"] .play-count`);
            if (card) card.textContent = data.count + ' views';

            // Update remaining plays as well
            updateRemainingPlays(recordingId);
        }
    })
    .catch(err => console.error(err));
}
</script>
<script>
// Function to update counts for a specific recording card
function refreshRecordingCard(recordingId) {
    fetch('students_plays.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: recordingId })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.error) {
            // Update global play count
            const cardPlayCount = document.querySelector(`.recording-card[data-recording="${recordingId}"] .play-count`);
            if (cardPlayCount) cardPlayCount.textContent = data.global_count + ' views';

            // Update remaining plays
            const remainingSpan = document.querySelector(`.recording-card[data-recording="${recordingId}"] .remaining-plays`);
            if (remainingSpan) remainingSpan.textContent = data.plays_left;
        }
    })
    .catch(err => console.error(err));
}

// Function to refresh all recording cards periodically
function refreshAllRecordingCards() {
    document.querySelectorAll('.recording-card').forEach(card => {
        const recordingId = card.getAttribute('data-recording');
        refreshRecordingCard(recordingId);
    });
}

// Refresh every 5 seconds (5000 ms)
setInterval(refreshAllRecordingCards, 5000);

// Optional: refresh immediately on page load
refreshAllRecordingCards();
</script>



    <?php include_once("includes/footer.php"); ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <?php include_once("includes/js-links-inc.php"); ?>
</body>
</html>