<?php
session_start();
require_once '../includes/db-conn.php'; // DB connection

// -------------------------
// Helper: Calculate age
// -------------------------
function calculate_age($birthday) {
    $birthDate = new DateTime($birthday);
    $today = new DateTime("today");
    return $birthDate->diff($today)->y;
}

// -------------------------
// Today birthdays
// -------------------------
$today = date("m-d");
$sql_today = "SELECT * FROM students WHERE DATE_FORMAT(birthday, '%m-%d') = ?";
$stmt = $conn->prepare($sql_today);
$stmt->bind_param("s", $today);
$stmt->execute();
$result_today = $stmt->get_result();
$todays_birthdays = $result_today->fetch_all(MYSQLI_ASSOC);
$today_count = count($todays_birthdays);

// -------------------------
// Upcoming birthdays (next 3 days)
// -------------------------
$sql_upcoming = "
    SELECT * FROM students 
    WHERE DATE_FORMAT(birthday, '%m-%d') > DATE_FORMAT(NOW(), '%m-%d') 
    ORDER BY DATE_FORMAT(birthday, '%m-%d') ASC
    LIMIT 3";
$result_upcoming = $conn->query($sql_upcoming);
$upcoming_birthdays = $result_upcoming->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upcoming Birthdays</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; padding: 20px; }
        .section { margin-bottom: 30px; }
        .student-card {
            background: #fff; padding: 20px; border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 15px; display: flex; align-items: center;
            width: 50%;
        }
        .student-card img {
            width: 120px; height: 120px; border-radius: 50%;
            margin-right: 20px; object-fit: cover; border: 3px solid #eee;
        }
        h1 { font-size: 1.5rem; margin: 0; }
        .highlight { color: #e63946; font-weight: bold; }
    </style>
</head>
<body>

    <div class="section">
        <h1>🎂 Today’s Birthdays (<?= $today_count ?>)</h1>
        <?php if ($today_count > 0): ?>
            <?php foreach ($todays_birthdays as $student): ?>
                <div class="student-card" style="border:2px solid #ff4081; background:#fff3f3;">
                    <img src="<?= htmlspecialchars($student['profile_picture']) ?>" alt="Profile">
                    <div>
                        <h1><?= htmlspecialchars($student['name']) ?></h1>
                        <p><b>Batch:</b> <?= $student['batch_year'] ?></p>
                        <p><b>Reg No:</b> <?= $student['regno'] ?></p>
                        <p><b>Birthday:</b> <?= date("F d", strtotime($student['birthday'])) ?></p>
                        <p><b>Birth Year:</b> <?= date("Y", strtotime($student['birthday'])) ?></p>
                        <p><b>Age:</b> <?= calculate_age($student['birthday']) ?> years</p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No birthdays today 🎉</p>
        <?php endif; ?>
    </div>

    <div class="section">
        <h1>📅 Upcoming Birthdays (Next 3 Days)</h1>
        <?php if (count($upcoming_birthdays) > 0): ?>
            <?php foreach ($upcoming_birthdays as $student): ?>
                <div class="student-card">
                    <img src="<?= htmlspecialchars($student['profile_picture']) ?>" alt="Profile">
                    <div>
                        <h1><?= htmlspecialchars($student['name']) ?></h1>
                        <p><b>Batch:</b> <?= $student['batch_year'] ?></p>
                        <p><b>Reg No:</b> <?= $student['regno'] ?></p>
                        <p><b>Birthday:</b> <?= date("F d", strtotime($student['birthday'])) ?></p>
                        <p><b>Birth Year:</b> <?= date("Y", strtotime($student['birthday'])) ?></p>
                        <p><b>Age:</b> <?= calculate_age($student['birthday']) ?> years</p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No upcoming birthdays in the next 3 days 🎂</p>
        <?php endif; ?>
    </div>

    <?php if ($today_count > 0): ?>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Request notification permission
                if (Notification.permission !== "granted") {
                    Notification.requestPermission();
                }

                const birthdays = <?php echo json_encode($todays_birthdays); ?>;

                birthdays.forEach(student => {
                    if (Notification.permission === "granted") {
                        new Notification("🎉 Happy Birthday!", {
                            body: `Happy Birthday ${student.name}! 🎂 - Edulk . Malitha Tishamal Admin`,
                            icon: student.profile_picture || "cake.png"
                        });
                    } else {
                        alert("🎉 Happy Birthday " + student.name + "! 🎂\nFrom Edulk. (Malitha Tishamal Admin)");
                    }
                });
            });
        </script>
    <?php endif; ?>

</body>
</html>
<?php $conn->close(); ?>
