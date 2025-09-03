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

// Fetch lectures
$sql = "SELECT * FROM lectures";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Lectures Manage - Edulk</title>
    <?php include_once("../includes/css-links-inc.php"); ?>
</head>

<body>

<?php include_once("../includes/header.php") ?>
<?php include_once("../includes/sadmin-sidebar.php") ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Lectures</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Pages</li>
                <li class="breadcrumb-item active">Manage Lectures</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Lectures Management</h5>
                        <p>Manage Lectures here.</p>

                        <table class="table datatable">
                            <thead class="align-middle text-center">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Profile Picture</th>
                                    <th>NIC</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Created at</th>
                                    <th>Last Login</th>
                                    <th>Status</th>
                                    <th></th>
                                    <th>Action</th>
                                    <th></th>
                                    <th>Edit</th>
                                </tr>
                                <tr>
                                    <th colspan="9" class="text-center"></th>
                                    <th class="text-center">Approve</th>
                                    <th class="text-center">Disable</th>
                                    <th class="text-center">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $status = strtolower($row['status']);
                                        echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td><img src='../lectures/" . $row["profile_picture"] . "' alt='Profile' width='150px' height='150px'></td>";
                                        echo "<td>" . $row['nic'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>" . $row['mobile'] . "</td>";
                                        echo "<td>" . $row['created_at'] . "</td>";
                                        echo "<td>" . $row['last_login'] . "</td>";

                                        // Status Display
                                        echo "<td>";
                                        if ($status === 'active' || $status === 'approved') {
                                            echo "<span class='btn btn-success btn-sm w-100 text-center'>Approved</span>";
                                        } elseif ($status === 'disabled') {
                                            echo "<span class='btn btn-danger btn-sm w-100 text-center'>Disabled</span>";
                                        } elseif ($status === 'pending') {
                                            echo "<span class='btn btn-warning btn-sm w-100 text-center'>Pending</span>";
                                        } else {
                                            echo "<span class='btn btn-secondary btn-sm w-100 text-center'>" . ucfirst($row['status']) . "</span>";
                                        }
                                        echo "</td>";

                                        // Approve Button
                                        $approveDisabled = ($status === 'active' || $status === 'approved') ? "style='opacity: 0.5; pointer-events: none;' disabled" : "";
                                        echo "<td class='text-center'>
                                                <button class='btn btn-success btn-sm w-100 approve-btn' data-id='" . $row['id'] . "' $approveDisabled>Approve</button>
                                              </td>";

                                        // Disable Button
                                        $disableDisabled = ($status === 'disabled') ? "style='opacity: 0.5; pointer-events: none;' disabled" : "";
                                        echo "<td class='text-center'>
                                                <button class='btn btn-warning btn-sm w-100 disable-btn' data-id='" . $row['id'] . "' $disableDisabled>Disable</button>
                                              </td>";

                                        // Delete Button
                                        echo "<td class='text-center'>
                                                <button class='btn btn-danger btn-sm w-100 delete-btn' data-id='" . $row['id'] . "'>Delete</button>
                                              </td>";

                                        // Edit Button
                                        echo "<td class='text-center'>
                                                <a href='edit-lecture.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm w-100'>Edit</a>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='13' class='text-center'>No users found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include_once("../includes/footer.php") ?>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<?php include_once("../includes/js-links-inc.php") ?>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    const approveButtons = document.querySelectorAll('.approve-btn');
    const disableButtons = document.querySelectorAll('.disable-btn');
    const deleteButtons = document.querySelectorAll('.delete-btn');

    approveButtons.forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            window.location.href = `process-lectures.php?approve_id=${userId}`;
        });
    });

    disableButtons.forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            window.location.href = `process-lectures.php?disable_id=${userId}`;
        });
    });

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = `process-lectures.php?delete_id=${userId}`;
            }
        });
    });
});
</script>

</body>
</html>

<?php $conn->close(); ?>
