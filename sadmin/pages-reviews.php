<?php
session_start();
require_once '../includes/db-conn.php';

// Redirect if not logged in
if (!isset($_SESSION['sadmin_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch logged in superadmin details
$user_id = $_SESSION['sadmin_id'];
$sql = "SELECT name, email, nic, mobile, profile_picture FROM sadmins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch reviews
$admin_reviews   = $conn->query("SELECT ar.*, a.profile_picture FROM admins_reviews ar LEFT JOIN admins a ON ar.admin_id = a.id ORDER BY ar.created_at DESC");
$lecture_reviews = $conn->query("SELECT sr.*, s.profile_picture, s.name FROM lectures_reviews sr LEFT JOIN lectures s ON sr.lecture_id = s.id ORDER BY sr.created_at DESC");
$student_reviews = $conn->query("SELECT sr.*, s.profile_picture, s.name, s.regno, s.batch_year FROM student_reviews sr LEFT JOIN students s ON sr.student_id = s.id ORDER BY sr.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Reviews - EduWide</title>
  <?php include_once("../includes/css-links-inc.php"); ?>
  <style>
    /* --- Your existing CSS --- */
    :root {
      --primary: #4361ee;
      --secondary: #3a0ca3;
      --success: #4cc9f0;
      --light: #f8f9fa;
      --dark: #212529;
      --gray: #6c757d;
      --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    body { background-color: #f5f7fb; color: #495057; font-family: 'Inter', sans-serif; }
    .main-container { padding: 20px; }
    .page-title { font-weight: 700; color: var(--dark); margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid rgba(0,0,0,0.05); }
    .card { border-radius: 12px; border: none; box-shadow: var(--card-shadow); margin-bottom: 24px; }
    .card-header { background: white; border-bottom: 1px solid rgba(0,0,0,0.05); padding: 16px 24px; border-radius: 12px 12px 0 0 !important; font-weight: 600; }
    .nav-tabs { border-bottom: none; background: white; border-radius: 10px; padding: 5px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .nav-tabs .nav-link { border: none; padding: 12px 20px; border-radius: 8px; color: var(--gray); font-weight: 500; transition: all 0.3s; }
    .nav-tabs .nav-link.active { background: var(--primary); color: white; }
    .nav-tabs .nav-link:hover:not(.active) { background: rgba(67, 97, 238, 0.1); color: var(--primary); }
    .review-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .review-table th { background-color: #f8f9fa; padding: 12px 15px; font-weight: 600; color: var(--dark); border-bottom: 2px solid rgba(0,0,0,0.05); }
    .review-table td { padding: 16px 15px; vertical-align: middle; border-bottom: 1px solid rgba(0,0,0,0.05); }
    .review-table tr:last-child td { border-bottom: none; }
    .review-table tr:hover { background-color: rgba(67, 97, 238, 0.03); }
    .review-img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; margin: 2px; cursor: pointer; transition: transform 0.2s; }
    .review-img:hover { transform: scale(1.05); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .badge { padding: 8px 12px; border-radius: 6px; font-weight: 500; }
    .btn-sm { padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 500; }
    .btn-primary { background: var(--primary); border: none; }
    .btn-primary:hover { background: var(--secondary); }
    .btn-success { background: #2ecc71; border: none; }
    .btn-warning { background: #f39c12; border: none; }
    .btn-danger { background: #e74c3c; border: none; }
    .action-buttons { display: flex; gap: 8px; flex-wrap: wrap; }
    .review-text { max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .review-text:hover { white-space: normal; overflow: visible; }
    .modal-content { border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
    .modal-header { background: var(--primary); color: white; border-radius: 12px 12px 0 0; }
    .modal-header .btn-close { filter: invert(1); }
    .stats-card { text-align: center; padding: 20px; border-radius: 12px; background: white; box-shadow: var(--card-shadow); transition: transform 0.3s; }
    .stats-card:hover { transform: translateY(-5px); }
    .stats-number { font-size: 28px; font-weight: 700; color: var(--primary); margin: 10px 0; }
    .stats-label { color: var(--gray); font-weight: 500; }
    @media (max-width: 768px) {
      .review-table { display: block; overflow-x: auto; }
      .action-buttons { flex-direction: column; }
    }
  </style>
</head>
<body>
<?php include_once("../includes/header.php"); ?>
<?php include_once("../includes/sadmin-sidebar.php"); ?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Manage Reviews</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active">Reviews</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row mb-4">
      <div class="col-md-4"><div class="stats-card"><div class="stats-number"><?= $admin_reviews->num_rows ?></div><div class="stats-label">Admin Reviews</div></div></div>
      <div class="col-md-4"><div class="stats-card"><div class="stats-number"><?= $lecture_reviews->num_rows ?></div><div class="stats-label">Lecture Reviews</div></div></div>
      <div class="col-md-4"><div class="stats-card"><div class="stats-number"><?= $student_reviews->num_rows ?></div><div class="stats-label">Student Reviews</div></div></div>
    </div>

    <div class="card">
      <div class="card-body">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#admins">Admin Reviews</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#lectures">Lecture Reviews</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#students">Student Reviews</a></li>
        </ul>

        <div class="tab-content pt-3">
          <!-- Admins -->
          <div class="tab-pane fade show active" id="admins">
            <div class="table-responsive">
              <table class="table review-table">
                <thead>
                  <tr>
                    <th>ID</th><th>Profile Picture</th><th>Name</th><th>Review</th><th>Images</th><th>Reply</th><th>Status</th><th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                <?php while($r=$admin_reviews->fetch_assoc()){ ?>
                  <tr>
                    <td><?= $r['id'] ?></td>
                    <td>
                      <?php if(!empty($r['profile_picture']) && file_exists("../admin/".$r['profile_picture'])): ?>
                        <img src="../admin/<?= htmlspecialchars($r['profile_picture']) ?>" class="rounded-circle" style="width:150px;height:150px;object-fit:cover;">
                      <?php else: ?>
                        <img src="../admin/uploads/profile_pictures/default.jpg" class="rounded-circle" style="width:150px;height:150px;object-fit:cover;">
                      <?php endif; ?>
                    </td>
                    <td><?= !empty($r['name']) ? htmlspecialchars($r['name']) : "Anonymous" ?></td>
                    <td class="review-text" title="<?= htmlspecialchars($r['review']) ?>"><?= htmlspecialchars($r['review']) ?></td>
                    <td>
                      <?php
                      $imgs = json_decode($r['images'], true) ?? [];
                      if($imgs) foreach($imgs as $img) echo "<img src='../admin/uploads/reviews/$img' class='review-img previewImg' data-src='../admin/uploads/reviews/$img'>";
                      if(!$imgs) echo "<span class='text-muted'>No images</span>";
                      ?>
                    </td>
                    <td><?= $r['admin_reply'] ?: "<span class='text-muted'>No reply</span>" ?></td>
                    <td><?= $r['admin_reply_read'] ? "<span class='badge bg-success'>Read</span>" : "<span class='badge bg-warning'>Unread</span>" ?></td>
                    <td>
                      <div class="action-buttons">
                        <button class="btn btn-primary btn-sm replyBtn" data-type="admin" data-id="<?= $r['id'] ?>"><i class="bi bi-reply"></i> Reply</button>
                        <?php if($r['admin_reply_read']): ?>
                          <a href="process-reviews.php?mark_unread_admin=<?= $r['id'] ?>" class="btn btn-warning btn-sm mark-action"><i class="bi bi-eye-slash"></i> Unread</a>
                        <?php else: ?>
                          <a href="process-reviews.php?mark_read_admin=<?= $r['id'] ?>" class="btn btn-success btn-sm mark-action"><i class="bi bi-eye"></i> Read</a>
                        <?php endif; ?>
                        <a href="process-reviews.php?delete_admin=<?= $r['id'] ?>" class="btn btn-danger btn-sm delete-action" onclick="return confirm('Delete this review?')"><i class="bi bi-trash"></i> Delete</a>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Lectures -->
          <div class="tab-pane fade" id="lectures">
            <div class="table-responsive">
              <table class="table review-table">
                <thead>
                  <tr>
                    <th>ID</th><th>Profile Picture</th><th>Name</th><th>Review</th><th>Images</th><th>Reply</th><th>Status</th><th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                <?php while($r=$lecture_reviews->fetch_assoc()){ ?>
                  <tr>
                    <td><?= $r['id'] ?></td>
                    <td>
                      <?php if(!empty($r['profile_picture']) && file_exists("../lectures/".$r['profile_picture'])): ?>
                        <img src="../lectures/<?= htmlspecialchars($r['profile_picture']) ?>" style="width:150px;height:150px;object-fit:cover;">
                      <?php else: ?>
                        <img src="../lectures/uploads/profile_pictures/default.png" style="width:150px;height:150px;object-fit:cover;">
                      <?php endif; ?>
                    </td>
                    <td><?= !empty($r['name']) ? htmlspecialchars($r['name']) : "Anonymous" ?></td>
                    <td class="review-text" title="<?= htmlspecialchars($r['review']) ?>"><?= htmlspecialchars($r['review']) ?></td>
                    <td>
                      <?php
                      $imgs = json_decode($r['images'], true) ?? [];
                      if($imgs) foreach($imgs as $img) echo "<img src='../lectures/uploads/reviews/$img' class='review-img previewImg' data-src='../lectures/uploads/reviews/$img'>";
                      if(!$imgs) echo "<span class='text-muted'>No images</span>";
                      ?>
                    </td>
                    <td><?= $r['admin_reply'] ?: "<span class='text-muted'>No reply</span>" ?></td>
                    <td><?= $r['admin_reply_read'] ? "<span class='badge bg-success'>Read</span>" : "<span class='badge bg-warning'>Unread</span>" ?></td>
                    <td>
                      <div class="action-buttons">
                        <button class="btn btn-primary btn-sm replyBtn" data-type="lecture" data-id="<?= $r['id'] ?>"><i class="bi bi-reply"></i> Reply</button>
                        <?php if($r['admin_reply_read']): ?>
                          <a href="process-reviews.php?mark_unread_lecture=<?= $r['id'] ?>" class="btn btn-warning btn-sm mark-action"><i class="bi bi-eye-slash"></i> Unread</a>
                        <?php else: ?>
                          <a href="process-reviews.php?mark_read_lecture=<?= $r['id'] ?>" class="btn btn-success btn-sm mark-action"><i class="bi bi-eye"></i> Read</a>
                        <?php endif; ?>
                        <a href="process-reviews.php?delete_lecture=<?= $r['id'] ?>" class="btn btn-danger btn-sm delete-action" onclick="return confirm('Delete this review?')"><i class="bi bi-trash"></i> Delete</a>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Students -->
          <div class="tab-pane fade" id="students">
            <div class="table-responsive">
              <table class="table review-table">
                <thead>
                  <tr>
                    <th>ID</th><th>Profile Picture</th><th>Name</th><th>RegNo</th><th>Year</th><th>Review</th><th>Images</th><th>Reply</th><th>Status</th><th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                <?php while($r=$student_reviews->fetch_assoc()){ ?>
                  <tr>
                    <td><?= $r['id'] ?></td>
                    <td>
                      <?php if(!empty($r['profile_picture']) && file_exists("../".$r['profile_picture'])): ?>
                        <img src="../<?= htmlspecialchars($r['profile_picture']) ?>" class="rounded-circle" style="width:150px;height:150px;object-fit:cover;">
                      <?php else: ?>
                        <img src="../uploads/profile_pictures/default.png" class="rounded-circle" style="width:150px;height:150px;object-fit:cover;">
                      <?php endif; ?>
                    </td>
                    <td><?= !empty($r['name']) ? htmlspecialchars($r['name']) : "Anonymous" ?></td>
                    <td><?= htmlspecialchars($r['regno']) ?></td>
                    <td><?= htmlspecialchars($r['batch_year']) ?></td>
                    <td class="review-text" title="<?= htmlspecialchars($r['review']) ?>"><?= htmlspecialchars($r['review']) ?></td>
                    <td>
                      <?php
                      $imgs = json_decode($r['images'], true) ?? [];
                      if($imgs) foreach($imgs as $img) echo "<img src='../uploads/reviews/$img' class='review-img previewImg' data-src='../uploads/reviews/$img'>";
                      if(!$imgs) echo "<span class='text-muted'>No images</span>";
                      ?>
                    </td>
                    <td><?= $r['admin_reply'] ?: "<span class='text-muted'>No reply</span>" ?></td>
                    <td><?= $r['admin_reply_read'] ? "<span class='badge bg-success'>Read</span>" : "<span class='badge bg-warning'>Unread</span>" ?></td>
                    <td>
                      <div class="action-buttons">
                        <button class="btn btn-primary btn-sm replyBtn" data-type="student" data-id="<?= $r['id'] ?>"><i class="bi bi-reply"></i> Reply</button>
                        <?php if($r['admin_reply_read']): ?>
                          <a href="process-reviews.php?mark_unread_student=<?= $r['id'] ?>" class="btn btn-warning btn-sm mark-action"><i class="bi bi-eye-slash"></i> Unread</a>
                        <?php else: ?>
                          <a href="process-reviews.php?mark_read_student=<?= $r['id'] ?>" class="btn btn-success btn-sm mark-action"><i class="bi bi-eye"></i> Read</a>
                        <?php endif; ?>
                        <a href="process-reviews.php?delete_student=<?= $r['id'] ?>" class="btn btn-danger btn-sm delete-action" onclick="return confirm('Delete this review?')"><i class="bi bi-trash"></i> Delete</a>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
</main>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reply to Review</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="replyForm" method="post" action="process-reviews.php">
        <div class="modal-body">
          <input type="hidden" name="review_id" id="review_id">
          <input type="hidden" name="review_type" id="review_type">
          <textarea name="reply_text" id="reply_text" class="form-control" rows="5" placeholder="Type your reply..."></textarea>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Send Reply</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include_once("../includes/js-links-inc.php"); ?>
<script>
document.addEventListener("DOMContentLoaded", function(){
  // --- Restore last active tab ---
  const activeTab = localStorage.getItem('activeReviewTab');
  if(activeTab){
    const tab = document.querySelector(`.nav-link[href="#${activeTab}"]`);
    if(tab) new bootstrap.Tab(tab).show();
  }

  // --- Save active tab ---
  document.querySelectorAll('.nav-link').forEach(tab => {
    tab.addEventListener('shown.bs.tab', function(e){
      const id = e.target.getAttribute('href').substring(1);
      localStorage.setItem('activeReviewTab', id);
      updateActionLinks(id);
    });
  });

  // --- Update Read/Unread/Delete links to preserve active tab ---
  function updateActionLinks(tabId){
    document.querySelectorAll('.mark-action, .delete-action').forEach(link=>{
      let url = new URL(link.href, window.location.origin);
      url.searchParams.set('active_tab', tabId);
      link.href = url.toString();
    });
  }

  // --- Initial update ---
  updateActionLinks(activeTab || 'admins');

  // --- Reply modal ---
  const replyModal = new bootstrap.Modal(document.getElementById('replyModal'));
  document.querySelectorAll('.replyBtn').forEach(btn=>{
    btn.addEventListener('click', function(){
      document.getElementById('review_id').value = this.dataset.id;
      document.getElementById('review_type').value = this.dataset.type;
      document.getElementById('reply_text').value = '';
      replyModal.show();
    });
  });

  // --- Image preview ---
  document.querySelectorAll('.previewImg').forEach(img=>{
    img.addEventListener('click', function(){
      const src = this.dataset.src;
      const modalHtml = `
        <div class="modal fade" id="imgPreviewModal" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
              <div class="modal-body text-center p-0">
                <img src="${src}" class="img-fluid">
              </div>
            </div>
          </div>
        </div>`;
      document.body.insertAdjacentHTML('beforeend', modalHtml);
      const imgModal = new bootstrap.Modal(document.getElementById('imgPreviewModal'));
      imgModal.show();
      document.getElementById('imgPreviewModal').addEventListener('hidden.bs.modal', function(){ this.remove(); });
    });
  });
});
</script>
</body>
</html>
