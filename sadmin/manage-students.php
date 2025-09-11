<?php
session_start();
require_once '../includes/db-conn.php';

// Redirect if not logged in
if (!isset($_SESSION['sadmin_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['sadmin_id'];
$sql = "SELECT name, email, nic, mobile, profile_picture FROM sadmins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Get filters
$search = $_GET['search'] ?? '';
$study_year = $_GET['study_year'] ?? '';
$status = $_GET['status'] ?? '';

// Build query
$sql = "SELECT * FROM students WHERE 1";
if ($search !== '') {
    $searchSafe = $conn->real_escape_string($search);
    $sql .= " AND (name LIKE '%$searchSafe%' OR regno LIKE '%$searchSafe%')";
}
if ($study_year !== '') {
    $sql .= " AND batch_year = '$study_year'";
}
if ($status !== '') {
    $sql .= " AND status = '$status'";
}

$result = $conn->query($sql);

// Get all distinct study years
$yearQuery = "SELECT DISTINCT batch_year AS year FROM students ORDER BY year DESC";
$yearResult = $conn->query($yearQuery);

// Get live statistics
$totalStudents = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$activeStudents = $conn->query("SELECT COUNT(*) as count FROM students WHERE status = 'active' OR status = 'approved'")->fetch_assoc()['count'];
$pendingStudents = $conn->query("SELECT COUNT(*) as count FROM students WHERE status = 'pending'")->fetch_assoc()['count'];
$disabledStudents = $conn->query("SELECT COUNT(*) as count FROM students WHERE status = 'disabled' OR status = 'rejected'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Manage Students - EduWide</title>
  <?php include_once("../includes/css-links-inc.php"); ?>
  <style>
        /* Styling for the popup */
        .popup-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px;
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            display: none; /* Hidden by default */
            z-index: 9999;
        }

        .error-popup {
            background-color: #dc3545;
        }
    </style>
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
      --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    .card { border-radius: 12px; box-shadow: var(--card-shadow); border: none; margin-bottom: 1.5rem; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
    .student-avatar { width: 200px; height: 200px; border-radius: 10%; object-fit: cover; }
    .status-badge { border-radius: 50px; padding: 0.4rem 0.8rem; font-size: 0.75rem; font-weight: 600; }
    .stats-card { text-align:center; padding:1.5rem; border-radius:10px; background:white; box-shadow:var(--card-shadow); }
    .stats-card:hover { transform:translateY(-5px); }
    .stats-card i { font-size:2rem; margin-bottom:1rem; color:var(--primary); }
    .stats-card h3 { font-size:1.8rem; margin-bottom:0.5rem; color:var(--dark); }
    .stats-card p { color:#6c757d; margin-bottom:0; }
    .action-btn { padding: 0.3rem 0.6rem; font-size: 0.875rem; }
    .student-row { cursor: pointer; transition: background-color 0.2s; }
    .student-row:hover { background-color: rgba(67, 97, 238, 0.05); }
    .detail-label { font-weight: 600; color: #495057; }
  </style>
</head>
<body>
  <?php include_once("../includes/header.php") ?>
  <?php include_once("../includes/sadmin-sidebar.php") ?>

   <!-- Displaying the message from the session -->
    <?php if (isset($_SESSION['status'])): ?>
        <div class="popup-message <?php echo ($_SESSION['status'] == 'success') ? '' : 'error-popup'; ?>" id="popup-alert">
            <?php echo $_SESSION['message']; ?>
        </div>

        <script>
            // Display the popup message
            document.getElementById('popup-alert').style.display = 'block';

            // Automatically hide the popup after 10 seconds
            setTimeout(function() {
                const popupAlert = document.getElementById('popup-alert');
                if (popupAlert) {
                    popupAlert.style.display = 'none';
                }
            }, 10000);

            // If success message, redirect to index.php after 10 seconds
            <?php if ($_SESSION['status'] == 'success'): ?>
                setTimeout(function() {
                    window.location.href = 'manage-students.php'; // Redirect after 10 seconds
                }, 10000); // Delay 10 seconds before redirecting
            <?php endif; ?>
        </script>

        <?php
        // Clear session variables after showing the message
        unset($_SESSION['status']);
        unset($_SESSION['message']);
        ?>
    <?php endif; ?>


  <main id="main" class="main">
    <div class="pagetitle">
      <h1 class="page-title">Student Management</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Students</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <!-- Statistics Cards -->
        <div class="col-lg-3 col-md-6">
          <div class="stats-card"><i class="bi bi-people"></i><h3><?= $totalStudents ?></h3><p>Total Students</p></div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stats-card"><i class="bi bi-check-circle"></i><h3><?= $activeStudents ?></h3><p>Active Students</p></div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stats-card"><i class="bi bi-clock-history"></i><h3><?= $pendingStudents ?></h3><p>Pending Approval</p></div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stats-card"><i class="bi bi-x-circle"></i><h3><?= $disabledStudents ?></h3><p>Disabled Accounts</p></div>
        </div>
      </div>

      <!-- Students Table -->
      <div class="card mt-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">Student Records</h5>
            <a href="pages-add-student.php" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Add New Student</a>
          </div>

          <!-- Filters -->
          <form method="GET" action="" class="row g-3 mb-4">
            <div class="col-md-3">
              <input type="text" name="search" class="form-control" placeholder="Name or Reg ID" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-3">
              <select name="study_year" class="form-select">
                <option value="">All Years</option>
                <?php while ($y = $yearResult->fetch_assoc()): ?>
                  <option value="<?= $y['year'] ?>" <?= $study_year == $y['year'] ? : '' ?>><?= $y['year'] ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="col-md-3">
              <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="active" <?= $status=='active'?'selected':'' ?>>Active</option>
                <option value="pending" <?= $status=='pending'?'selected':'' ?>>Pending</option>
                <option value="disabled" <?= $status=='disabled'?'selected':'' ?>>Disabled</option>
              </select>
            </div>
            <div class="col-md-3">
              <button type="submit" class="btn btn-primary"><i class="bi bi-filter"></i> Apply Filters</button>
            </div>
          </form>

          <!-- Table -->
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Student</th>
                  <th>Reg ID</th>
                  <th>Email</th>
                  <th>Batch</th>
                  <th>Status</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($result->num_rows > 0): ?>
                  <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="student-row" data-bs-toggle="modal" data-bs-target="#studentModal" data-student='<?= json_encode($row) ?>'>
                      <td><?= $row['id'] ?></td>
                      <td>
                        <div class="d-flex align-items-center">
                          <img src="../<?= $row['profile_picture'] ?>" class="student-avatar me-2">
                          <div><div class="fw-semibold"><?= $row['name'] ?></div><small><?= $row['nic'] ?></small></div>
                        </div>
                      </td>
                      <td><?= $row['regno'] ?></td>
                      <td><?= $row['email'] ?></td>
                      <td><?= $row['batch_year'] ?></td>
                      <td>
                        <?php if (in_array($row['status'], ['active','approved'])): ?>
                          <span class="badge bg-success">Active</span>
                        <?php elseif (in_array($row['status'], ['disabled','rejected'])): ?>
                          <span class="badge bg-danger">Disabled</span>
                        <?php elseif ($row['status']=='pending'): ?>
                          <span class="badge bg-warning">Pending</span>
                        <?php else: ?>
                          <span class="badge bg-secondary"><?= ucfirst($row['status']) ?></span>
                        <?php endif; ?>
                      </td>
                      <td class="text-center">
                        <div class="btn-group" role="group">
                          <button type="button" class="btn btn-sm btn-info action-btn view-details" data-bs-toggle="modal" data-bs-target="#studentModal" data-student='<?= json_encode($row) ?>'>
                            <i class="bi bi-eye"></i>
                          </button>
                          <a href="edit-student.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary action-btn">
                            <i class="bi bi-pencil"></i>
                          </a>
                          <a href="process-students.php?approve_id=<?= $row['id'] ?>" class="btn btn-sm btn-success action-btn">
                            <i class="bi bi-check-circle"></i>
                          </a>
                          <a href="process-students.php?disable_id=<?= $row['id'] ?>" class="btn btn-sm btn-warning action-btn">
                            <i class="bi bi-slash-circle"></i>
                          </a>
                          <a href="process-students.php?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger action-btn">
                            <i class="bi bi-trash"></i>
                          </a>
                          <form method="post" action="reset-password.php" onsubmit="return confirm('Are you sure you want to reset this password to 00000000?');">
    <input type="hidden" name="reset_id" value="<?php echo $row['id']; ?>">
    <button type="submit" class="btn btn-warning btn-sm"><i class="bi bi-recycle"></i></button>
</form>

                        </div>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr><td colspan="7" class="text-center text-muted">No students found.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Student Details Modal -->
  <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="studentModalLabel">Student Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4 text-center">
              <img id="modal-profile-picture" src="" class="student-avatar mb-3" alt="Profile Picture">
              <h4 id="modal-name" class="mb-1"></h4>
              <p id="modal-regno" class="text-muted"></p>
              <div id="modal-status" class="mb-3"></div>
            </div>
            <div class="col-md-8">
              <div class="row mb-3">
                <div class="col-sm-4 detail-label">NIC:</div>
                <div class="col-sm-8" id="modal-nic"></div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-4 detail-label">Email:</div>
                <div class="col-sm-8" id="modal-email"></div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-4 detail-label">Gender:</div>
                <div class="col-sm-8" id="modal-gender"></div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-4 detail-label">Birthday:</div>
                <div class="col-sm-8" id="modal-birthday"></div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-4 detail-label">Batch Year:</div>
                <div class="col-sm-8" id="modal-batch"></div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-4 detail-label">Address:</div>
                <div class="col-sm-8" id="modal-address"></div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-4 detail-label">Current Status:</div>
                <div class="col-sm-8" id="modal-nowstatus"></div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-4 detail-label">Primary Mobile:</div>
                <div class="col-sm-8" id="modal-mobile"></div>
              </div>
              <div class="row">
                <div class="col-sm-4 detail-label">Secondary Mobile:</div>
                <div class="col-sm-8" id="modal-mobile2"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <a href="#" id="modal-edit-btn" class="btn btn-primary">Edit Student</a>
        </div>
      </div>
    </div>
  </div>

  <?php include_once("../includes/footer.php"); ?>
  <?php include_once("../includes/js-links-inc.php"); ?>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const studentModal = document.getElementById('studentModal');
      
      studentModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const studentData = JSON.parse(button.getAttribute('data-student'));
        
        // Populate modal with student data
        document.getElementById('modal-profile-picture').src = '../' + studentData.profile_picture;
        document.getElementById('modal-name').textContent = studentData.name;
        document.getElementById('modal-regno').textContent = studentData.regno;
        document.getElementById('modal-nic').textContent = studentData.nic;
        document.getElementById('modal-email').textContent = studentData.email;
        document.getElementById('modal-gender').textContent = studentData.gender;
        document.getElementById('modal-birthday').textContent = studentData.birthday;
        document.getElementById('modal-batch').textContent = studentData.batch_year;
        document.getElementById('modal-address').textContent = studentData.address;
        document.getElementById('modal-nowstatus').textContent = studentData.nowstatus;
        document.getElementById('modal-mobile').textContent = studentData.mobile;
        document.getElementById('modal-mobile2').textContent = studentData.mobile2;
        document.getElementById('modal-edit-btn').href = 'edit-student.php?id=' + studentData.id;
        
        // Set status badge
        const statusElement = document.getElementById('modal-status');
        statusElement.innerHTML = '';
        
        let statusClass, statusText;
        if (['active', 'approved'].includes(studentData.status)) {
          statusClass = 'bg-success';
          statusText = 'Active';
        } else if (['disabled', 'rejected'].includes(studentData.status)) {
          statusClass = 'bg-danger';
          statusText = 'Disabled';
        } else if (studentData.status === 'pending') {
          statusClass = 'bg-warning';
          statusText = 'Pending';
        } else {
          statusClass = 'bg-secondary';
          statusText = studentData.status.charAt(0).toUpperCase() + studentData.status.slice(1);
        }
        
        const badge = document.createElement('span');
        badge.className = `badge ${statusClass} status-badge`;
        badge.textContent = statusText;
        statusElement.appendChild(badge);
      });
    });
  </script>
</body>
</html>
<?php $conn->close(); ?>