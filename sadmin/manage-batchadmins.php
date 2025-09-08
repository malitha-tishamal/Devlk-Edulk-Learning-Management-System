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

// Fetch users from the database
$sql = "SELECT * FROM admins";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>Batch Representers Manage - Edulk</title>

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
        
        .table th {
            background: linear-gradient(120deg, var(--primary), var(--secondary));
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
            font-weight: 500;
            padding: 0.75rem;
            vertical-align: middle;
        }
        
        .table td {
            padding: 0.75rem;
            vertical-align: middle;
        }
        
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .badge {
            font-weight: 500;
            padding: 0.5em 0.8em;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .action-buttons .btn {
            border-radius: 6px;
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
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
        
        .stats-card {
            text-align: center;
            padding: 1rem;
            border-radius: 12px;
            background: linear-gradient(120deg, #f8f9fa, #e9ecef);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
        }
        
        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .stats-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .stats-label {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 500;
        }
        
        /* Modal Styles */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }
        
        .modal-header {
            background: linear-gradient(120deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 1.5rem;
            border-bottom: none;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .modal-profile-img {
            width: 180px;
            height: 180px;
            border-radius: 10%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin: -80px auto 1rem;
            background: white;
        }
        
        .detail-item {
            display: flex;
            margin-bottom: 1rem;
            padding: 0.75rem;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
        
        .detail-label {
            min-width: 120px;
            font-weight: 600;
            color: #495057;
        }
        
        .detail-value {
            color: #6c757d;
        }
        
        .status-display {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            margin-top: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .stats-number {
                font-size: 1.5rem;
            }
            
            .modal-profile-img {
                width: 100px;
                height: 100px;
                margin: -70px auto 1rem;
            }
            
            .detail-item {
                flex-direction: column;
            }
            
            .detail-label {
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>

<body>

<?php include_once("../includes/header.php") ?>
<?php include_once("../includes/sadmin-sidebar.php") ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Representers</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Manage Representers</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="feature-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <h5 class="card-title mb-0">Representers Management</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>Manage representers and their status here.</p>
                        
                        <!-- Stats Overview -->
                        <?php
                        $total_admins = $result->num_rows;
                        $active_admins = $conn->query("SELECT COUNT(*) as count FROM admins WHERE status IN ('active', 'approved')")->fetch_assoc()['count'];
                        $pending_admins = $conn->query("SELECT COUNT(*) as count FROM admins WHERE status = 'pending'")->fetch_assoc()['count'];
                        $disabled_admins = $conn->query("SELECT COUNT(*) as count FROM admins WHERE status IN ('disabled', 'rejected')")->fetch_assoc()['count'];
                        ?>
                        
                        <div class="row mb-4">
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="stats-card">
                                    <div class="stats-number"><?php echo $total_admins; ?></div>
                                    <div class="stats-label">Total Representers</div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="stats-card">
                                    <div class="stats-number"><?php echo $active_admins; ?></div>
                                    <div class="stats-label">Active Representers</div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="stats-card">
                                    <div class="stats-number"><?php echo $pending_admins; ?></div>
                                    <div class="stats-label">Pending Approval</div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="stats-card">
                                    <div class="stats-number"><?php echo $disabled_admins; ?></div>
                                    <div class="stats-label">Disabled/Rejected</div>
                                </div>
                            </div>
                        </div>

                        <!-- Table with user data -->
                        <div class="table-responsive">
                            <table class="table table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Profile</th>
                                        <th>Name</th>
                                        <th>NIC</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Created at</th>
                                        <th>Last Login</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td><img src='../admin/" . $row["profile_picture"] . "' alt='Profile' class='profile-img'></td>";
                                            echo "<td>" . $row['name'] . "</td>";
                                            echo "<td>" . $row['nic'] . "</td>";
                                            echo "<td>" . $row['email'] . "</td>";
                                            echo "<td>" . $row['mobile'] . "</td>";
                                            echo "<td>" . $row['created_at'] . "</td>";
                                            echo "<td>" . $row['last_login'] . "</td>";

                                            // Status Column with Color
                                            $status = strtolower($row['status']); // case insensitive
                                            echo "<td>";
                                            if ($status === 'active' || $status === 'approved') {
                                                echo "<span class='badge bg-success'>Approved</span>";
                                            } elseif ($status === 'disabled' || $status === 'rejected') {
                                                echo "<span class='badge bg-danger'>Rejected</span>";
                                            } elseif ($status === 'pending') {
                                                echo "<span class='badge bg-warning'>Pending</span>";
                                            } else {
                                                echo "<span class='badge bg-secondary'>" . ucfirst($row['status']) . "</span>";
                                            }
                                            echo "</td>";

                                            // Button disable conditions
                                            $approve_disabled = ($status === 'active' || $status === 'approved')
                                                ? "disabled"
                                                : "";

                                            $disable_disabled = ($status === 'disabled' || $status === 'rejected')
                                                ? "disabled"
                                                : "";

                                            echo "<td class='action-buttons'>";
                                            // View button
                                            echo "<button class='btn btn-info btn-sm view-btn' data-id='" . $row['id'] . "' data-name='" . $row['name'] . "' data-email='" . $row['email'] . "' data-nic='" . $row['nic'] . "' data-mobile='" . $row['mobile'] . "' data-created='" . $row['created_at'] . "' data-login='" . $row['last_login'] . "' data-status='" . $row['status'] . "' data-profile='../admin/" . $row['profile_picture'] . "' title='View Details'>
                                                    <i class='bi bi-eye'></i>
                                                  </button>";
                                            // Approve button
                                            echo "<button class='btn btn-success btn-sm approve-btn' data-id='" . $row['id'] . "' $approve_disabled title='Approve'>
                                                    <i class='bi bi-check-lg'></i>
                                                  </button>";
                                            // Disable button
                                            echo "<button class='btn btn-warning btn-sm disable-btn' data-id='" . $row['id'] . "' $disable_disabled title='Disable'>
                                                    <i class='bi bi-slash-circle'></i>
                                                  </button>";
                                            // Delete button
                                            echo "<button class='btn btn-danger btn-sm delete-btn' data-id='" . $row['id'] . "' title='Delete'>
                                                    <i class='bi bi-trash'></i>
                                                  </button>";
                                            // Edit button
                                            echo "<a href='edit-representer.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm' title='Edit'>
                                                    <i class='bi bi-pencil'></i>
                                                  </a>";
                                            echo "</td>";

                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='10' class='text-center py-4'>No representers found.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- End Table with user data -->

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- View Representer Modal -->
<div class="modal fade" id="viewRepresenterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white">Representer Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img id="modalProfileImg" src="" class="modal-profile-img" alt="Profile Image">
                    <h3 id="modalName" class="mt-2 mb-0"></h3>
                    <div id="modalStatus" class="status-display"></div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <span class="detail-label">ID:</span>
                            <span class="detail-value" id="modalId"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <span class="detail-label">NIC:</span>
                            <span class="detail-value" id="modalNic"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value" id="modalEmail"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <span class="detail-label">Mobile:</span>
                            <span class="detail-value" id="modalMobile"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <span class="detail-label">Created At:</span>
                            <span class="detail-value" id="modalCreated"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <span class="detail-label">Last Login:</span>
                            <span class="detail-value" id="modalLogin"></span>
                        </div>
                    </div>
                </div>
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

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // View representer details
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const email = this.getAttribute('data-email');
            const nic = this.getAttribute('data-nic');
            const mobile = this.getAttribute('data-mobile');
            const created = this.getAttribute('data-created');
            const login = this.getAttribute('data-login');
            const status = this.getAttribute('data-status');
            const profile = this.getAttribute('data-profile');
            
            // Populate modal with data
            document.getElementById('modalProfileImg').src = profile;
            document.getElementById('modalName').textContent = name;
            document.getElementById('modalId').textContent = id;
            document.getElementById('modalNic').textContent = nic;
            document.getElementById('modalEmail').textContent = email;
            document.getElementById('modalMobile').textContent = mobile;
            document.getElementById('modalCreated').textContent = created;
            document.getElementById('modalLogin').textContent = login;
            
            // Set status with appropriate color
            const statusElement = document.getElementById('modalStatus');
            statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            
            if (status === 'active' || status === 'approved') {
                statusElement.className = 'status-display badge bg-success';
            } else if (status === 'disabled' || status === 'rejected') {
                statusElement.className = 'status-display badge bg-danger';
            } else if (status === 'pending') {
                statusElement.className = 'status-display badge bg-warning';
            } else {
                statusElement.className = 'status-display badge bg-secondary';
            }
            
            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('viewRepresenterModal'));
            modal.show();
        });
    });
    
    // Approve representer
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            if (confirm("Are you sure you want to approve this representer?")) {
                window.location.href = `process-batchadmins.php?approve_id=${id}`;
            }
        });
    });
    
    // Disable representer
    document.querySelectorAll('.disable-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            if (confirm("Are you sure you want to disable this representer?")) {
                window.location.href = `process-batchadmins.php?disable_id=${id}`;
            }
        });
    });
    
    // Delete representer
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            if (confirm("Are you sure you want to delete this representer?")) {
                window.location.href = `process-batchadmins.php?delete_id=${id}`;
            }
        });
    });
});
</script>

</body>

</html>

<?php
// Close database connection
$conn->close();
?>