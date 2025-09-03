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

// Get all distinct study years from regno
$yearQuery = "SELECT DISTINCT batch_year AS year FROM students ORDER BY year DESC";
$yearResult = $conn->query($yearQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Manage Students - EduWide</title>
  <?php include_once("../includes/css-links-inc.php"); ?>
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
    
    .card {
      border-radius: 12px;
      box-shadow: var(--card-shadow);
      border: none;
      margin-bottom: 1.5rem;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }
    
    .card-body {
      padding: 1.5rem;
    }
    
    .btn {
      border-radius: 8px;
      font-weight: 500;
      padding: 0.5rem 1rem;
      transition: all 0.2s ease;
    }
    
    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .btn-sm {
      padding: 0.4rem 0.8rem;
      font-size: 0.875rem;
    }
    
    .table th {
      border-top: none;
      font-weight: 600;
      color: #495057;
      background-color: #f8f9fa;
      padding: 1rem 0.5rem;
    }
    
    .table td {
      padding: 1rem 0.5rem;
      vertical-align: middle;
    }
    
    .student-avatar {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #fff;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .status-badge {
      border-radius: 50px;
      padding: 0.4rem 0.8rem;
      font-size: 0.75rem;
      font-weight: 600;
    }
    
    .filter-section {
      background: linear-gradient(120deg, #f8f9fa 0%, #e9ecef 100%);
      border-radius: 10px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      border: 1px solid rgba(0,0,0,0.05);
    }
    
    .action-buttons .btn {
      margin: 0 3px;
      width: 36px;
      height: 36px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    
    .page-title {
      color: #343a40;
      font-weight: 700;
      margin-bottom: 0.5rem;
      font-size: 1.8rem;
    }
    
    .breadcrumb-item a {
      color: var(--primary);
      text-decoration: none;
      transition: color 0.2s;
    }
    
    .breadcrumb-item a:hover {
      color: var(--secondary);
    }
    
    .table-responsive {
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .table-hover tbody tr {
      transition: background-color 0.2s;
    }
    
    .table-hover tbody tr:hover {
      background-color: rgba(67, 97, 238, 0.05);
    }
    
    .pagination .page-link {
      border-radius: 6px;
      margin: 0 3px;
      color: var(--primary);
      border: none;
      padding: 0.5rem 0.9rem;
    }
    
    .pagination .page-item.active .page-link {
      background-color: var(--primary);
      border-color: var(--primary);
      box-shadow: 0 2px 5px rgba(67, 97, 238, 0.3);
    }
    
    .stats-card {
      text-align: center;
      padding: 1.5rem;
      border-radius: 10px;
      background: white;
      box-shadow: var(--card-shadow);
      transition: transform 0.3s;
    }
    
    .stats-card:hover {
      transform: translateY(-5px);
    }
    
    .stats-card i {
      font-size: 2rem;
      margin-bottom: 1rem;
      color: var(--primary);
    }
    
    .stats-card h3 {
      font-size: 1.8rem;
      margin-bottom: 0.5rem;
      color: var(--dark);
    }
    
    .stats-card p {
      color: #6c757d;
      margin-bottom: 0;
    }
    
    /* Modern form elements */
    .form-control, .form-select {
      border-radius: 8px;
      padding: 0.75rem 1rem;
      border: 1px solid #ced4da;
      transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
      border-color: var(--primary);
    }
    
    /* Custom badge colors */
    .bg-success {
      background: linear-gradient(45deg, #2ecc71, #27ae60) !important;
    }
    
    .bg-warning {
      background: linear-gradient(45deg, #f39c12, #e67e22) !important;
    }
    
    .bg-danger {
      background: linear-gradient(45deg, #e74c3c, #c0392b) !important;
    }
    
    .bg-secondary {
      background: linear-gradient(45deg, #95a5a6, #7f8c8d) !important;
    }
    
    /* Animation for buttons */
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }
    
    .btn-primary {
      background: linear-gradient(45deg, var(--primary), var(--secondary));
      border: none;
      animation: pulse 2s infinite;
    }
    
    .btn-primary:hover {
      background: linear-gradient(45deg, var(--secondary), var(--primary));
      transform: translateY(-2px);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .card-body {
        padding: 1rem;
      }
      
      .action-buttons .btn {
        margin-bottom: 5px;
      }
      
      .table-responsive {
        overflow-x: auto;
      }
    }
  </style>
</head>
<body>
  <?php include_once("../includes/header.php") ?>
  <?php include_once("../includes/sadmin-sidebar.php") ?>

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
          <div class="stats-card">
            <i class="bi bi-people"></i>
            <h3>1,258</h3>
            <p>Total Students</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stats-card">
            <i class="bi bi-check-circle"></i>
            <h3>924</h3>
            <p>Active Students</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stats-card">
            <i class="bi bi-clock-history"></i>
            <h3>186</h3>
            <p>Pending Approval</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="stats-card">
            <i class="bi bi-x-circle"></i>
            <h3>148</h3>
            <p>Disabled Accounts</p>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">Student Records</h5>
                <a href="pages-add-student.php" class="btn btn-primary">
                  <i class="bi bi-plus-circle me-1"></i> Add New Student
                </a>
              </div>

              <!-- Filters -->
              <div class="filter-section">
                <form method="GET" action="">
                  <div class="row">
                    <div class="col-md-3 mb-2">
                      <label class="form-label small text-muted">Search</label>
                      <input type="text" name="search" class="form-control" placeholder="Name or Registration ID" value="<?= htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-3 mb-2">
                      <label class="form-label small text-muted">Batch Year</label>
                      <select name="study_year" class="form-select">
                        <option value="">All Years</option>
                        <?php
                        if ($yearResult->num_rows > 0) {
                          while ($y = $yearResult->fetch_assoc()) {
                            $yearVal = $y['year'];
                            $selected = ($study_year == $yearVal) ? 'selected' : '';
                            echo "<option value='$yearVal' $selected>$yearVal</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-md-3 mb-2">
                      <label class="form-label small text-muted">Status</label>
                      <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" <?= ($status == 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="pending" <?= ($status == 'pending') ? 'selected' : '' ?>>Pending</option>
                        <option value="disabled" <?= ($status == 'disabled') ? 'selected' : '' ?>>Disabled</option>
                      </select>
                    </div>
                    <div class="col-md-3 mb-2 d-flex align-items-end">
                      <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter me-1"></i> Apply Filters
                      </button>
                    </div>
                  </div>
                </form>
              </div>

              <!-- Table -->
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Student</th>
                      <th scope="col">Reg ID</th>
                      <th scope="col">Email</th>
                      <th scope="col">Batch Year</th>
                      <th scope="col">Status</th>
                      <th scope="col" class="text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        $status = strtolower($row['status']);
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>
                                <div class='d-flex align-items-center'>
                                  <img src='../{$row['profile_picture']}' class='student-avatar me-3'>
                                  <div>
                                    <div class='fw-semibold'>{$row['name']}</div>
                                    <small class='text-muted'>{$row['nic']}</small>
                                  </div>
                                </div>
                              </td>";
                        echo "<td>{$row['regno']}</td>";
                        echo "<td>{$row['email']}</td>";
                        echo "<td>{$row['batch_year']}</td>";
                        
                        // Status Badge
                        echo "<td>";
                        switch ($status) {
                            case 'active':
                            case 'approved':
                                echo "<span class='badge bg-success status-badge'>Active</span>";
                                break;
                            case 'disabled':
                            case 'rejected':
                                echo "<span class='badge bg-danger status-badge'>Disabled</span>";
                                break;
                            case 'pending':
                                echo "<span class='badge bg-warning status-badge'>Pending</span>";
                                break;
                            default:
                                echo "<span class='badge bg-secondary status-badge'>" . ucfirst($row['status']) . "</span>";
                        }
                        echo "</td>";
                        
                        // Action buttons
                        echo "<td class='text-center action-buttons'>";
                        
                        // View button
                        echo "<button class='btn btn-sm btn-info view-btn' data-id='{$row['id']}' data-name='{$row['name']}' data-email='{$row['email']}' data-nic='{$row['nic']}' data-batch='{$row['batch_year']}' data-mobile='{$row['mobile']}' data-address='{$row['address']}' data-profile='../{$row['profile_picture']}' data-bs-toggle='tooltip' title='View Details'>
                                <i class='bi bi-eye'></i>
                              </button>";
                        
                        // Edit button
                        echo "<a href='edit-student.php?id={$row['id']}' class='btn btn-sm btn-primary' data-bs-toggle='tooltip' title='Edit'>
                                <i class='bi bi-pencil'></i>
                              </a>";
                        
                        // Approve button
                        $approve_disabled = ($status === 'active' || $status === 'approved') ? "disabled" : "";
                        echo "<button class='btn btn-sm btn-success approve-btn' data-id='{$row['id']}' $approve_disabled data-bs-toggle='tooltip' title='Approve'>
                                <i class='bi bi-check-circle'></i>
                              </button>";
                        
                        // Disable button
                        $disable_disabled = ($status === 'disabled' || $status === 'rejected') ? "disabled" : "";
                        echo "<button class='btn btn-sm btn-warning disable-btn' data-id='{$row['id']}' $disable_disabled data-bs-toggle='tooltip' title='Disable'>
                                <i class='bi bi-slash-circle'></i>
                              </button>";
                        
                        // Delete button
                        echo "<button class='btn btn-sm btn-danger delete-btn' data-id='{$row['id']}' data-bs-toggle='tooltip' title='Delete'>
                                <i class='bi bi-trash'></i>
                              </button>";
                        
                        echo "</td>";
                        echo "</tr>";
                      }
                    } else {
                      echo "<tr><td colspan='7' class='text-center py-4 text-muted'>No students found matching your criteria.</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mt-4">
                  <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                  </li>
                  <li class="page-item active"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                  </li>
                </ul>
              </nav>

            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- View Student Modal -->
  <div class="modal fade" id="viewStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Student Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="studentDetails">
          <!-- Details will be loaded via JavaScript -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <?php include_once("../includes/footer.php"); ?>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <?php include_once("../includes/js-links-inc.php"); ?>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Initialize tooltips
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
      });
      
      // Approve student
      document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          const id = btn.getAttribute('data-id');
          if (confirm("Are you sure you want to approve this student?")) {
            window.location.href = `process-students.php?approve_id=${id}`;
          }
        });
      });
      
      // Disable student
      document.querySelectorAll('.disable-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          const id = btn.getAttribute('data-id');
          if (confirm("Are you sure you want to disable this student?")) {
            window.location.href = `process-students.php?disable_id=${id}`;
          }
        });
      });
      
      // Delete student
      document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          const id = btn.getAttribute('data-id');
          if (confirm("Are you sure you want to delete this student? This action cannot be undone.")) {
            window.location.href = `process-students.php?delete_id=${id}`;
          }
        });
      });
      
      // View student details
      document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          const id = btn.getAttribute('data-id');
          const name = btn.getAttribute('data-name');
          const email = btn.getAttribute('data-email');
          const nic = btn.getAttribute('data-nic');
          const batch = btn.getAttribute('data-batch');
          const mobile = btn.getAttribute('data-mobile');
          const address = btn.getAttribute('data-address');
          const profile = btn.getAttribute('data-profile');
          
          document.getElementById('studentDetails').innerHTML = `
            <div class="row">
              <div class="col-md-4 text-center">
                <img src="${profile}" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <h4>${name}</h4>
                <p class="text-muted">Registration ID: ${id}</p>
              </div>
              <div class="col-md-8">
                <div class="row mb-3">
                  <div class="col-sm-4">
                    <p class="mb-0 fw-semibold">Email</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">${email}</p>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-4">
                    <p class="mb-0 fw-semibold">NIC</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">${nic}</p>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-4">
                    <p class="mb-0 fw-semibold">Batch Year</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">${batch}</p>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-4">
                    <p class="mb-0 fw-semibold">Mobile</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">${mobile}</p>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-4">
                    <p class="mb-0 fw-semibold">Address</p>
                  </div>
                  <div class="col-sm-8">
                    <p class="text-muted mb-0">${address}</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-4">
                    <p class="mb-0 fw-semibold">Status</p>
                  </div>
                  <div class="col-sm-8">
                    <span class="badge bg-success status-badge">Active</span>
                  </div>
                </div>
              </div>
            </div>
          `;
          
          const myModal = new bootstrap.Modal(document.getElementById('viewStudentModal'));
          myModal.show();
        });
      });
    });
  </script>
</body>
</html>

<?php $conn->close(); ?>