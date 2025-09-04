<?php
session_start();
require_once '../includes/db-conn.php';

// Redirect if not logged in
if (!isset($_SESSION['sadmin_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch logged-in superadmin details
$user_id = $_SESSION['sadmin_id'];
$sql = "SELECT name, email, nic, mobile, profile_picture FROM sadmins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch all admins
$sql = "SELECT * FROM sadmins";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>Admin Management - EduWide</title>

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
        
        .admin-avatar {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .admin-avatar-large {
            width: 250px;
            height: 250px;
            border-radius: 10%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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
        
        /* Alert styling */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .alert-success {
            background: linear-gradient(45deg, #2ecc71, #27ae60);
            color: white;
        }
        
        .alert-danger {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
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
            
            .admin-avatar {
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>

<body>

    <?php include_once("../includes/header.php") ?>
    <?php include_once("../includes/sadmin-sidebar.php") ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1 class="page-title">Admin Management</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item">Admin Management</li>
                    <li class="breadcrumb-item active">Manage Admins</li>
                </ol>
            </nav>
        </div>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'superadmin_protected') : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong> Superadmin account cannot be deleted!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success']) && $_GET['success'] == 'admin_deleted') : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success:</strong> Admin account deleted successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <section class="section">
            <div class="row">
                <!-- Statistics Cards -->
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <i class="bi bi-people"></i>
                        <?php
                        $totalAdmins = $result->num_rows;
                        $result->data_seek(0); // Reset pointer to reuse result
                        ?>
                        <h3><?php echo $totalAdmins; ?></h3>
                        <p>Total Admins</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <i class="bi bi-check-circle"></i>
                        <?php
                        $activeCount = 0;
                        $result->data_seek(0);
                        while ($row = $result->fetch_assoc()) {
                            if (strtolower($row['status']) === 'active' || strtolower($row['status']) === 'approved') {
                                $activeCount++;
                            }
                        }
                        $result->data_seek(0);
                        ?>
                        <h3><?php echo $activeCount; ?></h3>
                        <p>Active Admins</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <i class="bi bi-clock-history"></i>
                        <?php
                        $pendingCount = 0;
                        $result->data_seek(0);
                        while ($row = $result->fetch_assoc()) {
                            if (strtolower($row['status']) === 'pending') {
                                $pendingCount++;
                            }
                        }
                        $result->data_seek(0);
                        ?>
                        <h3><?php echo $pendingCount; ?></h3>
                        <p>Pending Admins</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <i class="bi bi-x-circle"></i>
                        <?php
                        $disabledCount = 0;
                        $result->data_seek(0);
                        while ($row = $result->fetch_assoc()) {
                            if (strtolower($row['status']) === 'disabled' || strtolower($row['status']) === 'rejected') {
                                $disabledCount++;
                            }
                        }
                        $result->data_seek(0);
                        ?>
                        <h3><?php echo $disabledCount; ?></h3>
                        <p>Disabled Admins</p>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="card-title mb-0">Admin Accounts</h5>
                                <a href="pages-add-admin.php" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Add New Admin
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Admin</th>
                                            <th scope="col">NIC</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Mobile</th>
                                            <th scope="col">Created</th>
                                            <th scope="col">Last Login</th>
                                            <th scope="col">Status</th>
                                            <th scope="col" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $status = strtolower($row['status']);
                                                $is_self = ($row['id'] == $_SESSION['sadmin_id']);
                                                $is_main_admin = ($row['id'] == 1);

                                                // Disable Approve if status is active/approved or self or main admin
                                                $approve_disabled = ($status === 'active' || $status === 'approved' || $is_self || $is_main_admin)
                                                    ? "disabled"
                                                    : "";

                                                // Disable Disable button if status is disabled or self or main admin
                                                $disable_disabled = ($status === 'rejected' || $is_self || $is_main_admin)
                                                    ? "disabled"
                                                    : "";

                                                // Delete disabled if self or main admin
                                                $delete_disabled = ($is_self || $is_main_admin)
                                                    ? "disabled"
                                                    : "";

                                                // Edit disabled if self
                                                $edit_disabled = $is_self
                                                    ? "disabled"
                                                    : "";

                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                                echo "<td>
                                                        <div class='d-flex align-items-center'>
                                                            <img src='" . htmlspecialchars($row["profile_picture"]) . "' class='admin-avatar me-3'>
                                                            <div>
                                                                <div class='fw-semibold'>" . htmlspecialchars($row['name']) . "</div>
                                                            </div>
                                                        </div>
                                                      </td>";
                                                echo "<td>" . htmlspecialchars($row['nic']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['mobile']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['last_login']) . "</td>";

                                                // Status Display
                                                echo "<td>";
                                                if ($status === 'active' || $status === 'approved') {
                                                    echo "<span class='badge bg-success status-badge'>Approved</span>";
                                                } elseif ($status === 'disabled') {
                                                    echo "<span class='badge bg-danger status-badge'>Disabled</span>";
                                                } elseif ($status === 'pending') {
                                                    echo "<span class='badge bg-warning status-badge'>Pending</span>";
                                                } else {
                                                    echo "<span class='badge bg-secondary status-badge'>" . ucfirst($row['status']) . "</span>";
                                                }
                                                echo "</td>";

                                                // Action buttons
                                                echo "<td class='text-center action-buttons'>";
                                                
                                                // View button
                                                echo "<button class='btn btn-sm btn-info view-btn' data-id='{$row['id']}' data-name='" . htmlspecialchars($row['name']) . "' data-email='" . htmlspecialchars($row['email']) . "' data-nic='" . htmlspecialchars($row['nic']) . "' data-mobile='" . htmlspecialchars($row['mobile']) . "' data-created='" . htmlspecialchars($row['created_at']) . "' data-login='" . htmlspecialchars($row['last_login']) . "' data-status='" . htmlspecialchars($row['status']) . "' data-profile='" . htmlspecialchars($row['profile_picture']) . "' data-bs-toggle='tooltip' title='View Details'>
                                                        <i class='bi bi-eye'></i>
                                                      </button>";
                                                      
                                                // Edit button
                                                echo "<a href='edit-admin.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary' data-bs-toggle='tooltip' title='Edit' $edit_disabled>
                                                        <i class='bi bi-pencil'></i>
                                                      </a>";

                                                // Approve button
                                                echo "<button class='btn btn-sm btn-success approve-btn' data-id='" . $row['id'] . "' data-bs-toggle='tooltip' title='Approve' $approve_disabled>
                                                        <i class='bi bi-check-circle'></i>
                                                      </button>";

                                                // Disable button
                                                echo "<button class='btn btn-sm btn-warning disable-btn' data-id='" . $row['id'] . "' data-bs-toggle='tooltip' title='Disable' $disable_disabled>
                                                        <i class='bi bi-slash-circle'></i>
                                                      </button>";

                                                // Delete button
                                                echo "<button class='btn btn-sm btn-danger delete-btn' data-id='" . $row['id'] . "' data-bs-toggle='tooltip' title='Delete' $delete_disabled>
                                                        <i class='bi bi-trash'></i>
                                                      </button>";

                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='9' class='text-center py-4 text-muted'>No admin accounts found.</td></tr>";
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

    <!-- View Admin Modal -->
    <div class="modal fade" id="viewAdminModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Admin Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="adminDetails">
                    <!-- Details will be loaded via JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php include_once("../includes/footer.php") ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <?php include_once("../includes/js-links-inc.php") ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Approve admin
            document.querySelectorAll('.approve-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    if (confirm("Are you sure you want to approve this admin?")) {
                        window.location.href = `process-admins.php?approve_id=${id}`;
                    }
                });
            });
            
            // Disable admin
            document.querySelectorAll('.disable-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    if (confirm("Are you sure you want to disable this admin?")) {
                        window.location.href = `process-admins.php?disable_id=${id}`;
                    }
                });
            });
            
            // Delete admin
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    if (confirm("Are you sure you want to delete this admin? This action cannot be undone.")) {
                        window.location.href = `process-admins.php?delete_id=${id}`;
                    }
                });
            });
            
            // View admin details
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    const name = btn.getAttribute('data-name');
                    const email = btn.getAttribute('data-email');
                    const nic = btn.getAttribute('data-nic');
                    const mobile = btn.getAttribute('data-mobile');
                    const created = btn.getAttribute('data-created');
                    const login = btn.getAttribute('data-login');
                    const status = btn.getAttribute('data-status');
                    const profile = btn.getAttribute('data-profile');
                    
                    // Display loading state
                    document.getElementById('adminDetails').innerHTML = `
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading admin details...</p>
                        </div>
                    `;
                    
                    const myModal = new bootstrap.Modal(document.getElementById('viewAdminModal'));
                    myModal.show();
                    
                    // Set a timeout to simulate loading, then display the data
                    setTimeout(() => {
                        let statusBadge = '';
                        switch(status.toLowerCase()) {
                            case 'active':
                            case 'approved':
                                statusBadge = '<span class="badge bg-success status-badge">Approved</span>';
                                break;
                            case 'disabled':
                                statusBadge = '<span class="badge bg-danger status-badge">Disabled</span>';
                                break;
                            case 'pending':
                                statusBadge = '<span class="badge bg-warning status-badge">Pending</span>';
                                break;
                            default:
                                statusBadge = `<span class="badge bg-secondary status-badge">${status}</span>`;
                        }
                        
                        document.getElementById('adminDetails').innerHTML = `
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <img src="${profile}" class="admin-avatar-large mb-3">
                                    <h4>${name}</h4>
                                    <p class="text-muted">Admin ID: ${id}</p>
                                    ${statusBadge}
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
                                            <p class="mb-0 fw-semibold">Mobile</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <p class="text-muted mb-0">${mobile}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <p class="mb-0 fw-semibold">Account Created</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <p class="text-muted mb-0">${created}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <p class="mb-0 fw-semibold">Last Login</p>
                                        </div>
                                        <div class="col-sm-8">
                                            <p class="text-muted mb-0">${login}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }, 500);
                });
            });
        });
    </script>

</body>

</html>

<?php
$conn->close();
?>