<?php
session_start();
require_once '../includes/db-conn.php'; // DB connection

if (!isset($_SESSION['sadmin_id'])) {
    header("Location: ../index.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['sadmin_id'];
$sql = "SELECT * FROM sadmins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

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
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Upcoming Students Birthdays - Edulk</title>
  <?php include_once("../includes/css-links-inc.php"); ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <style>
    :root {
      --primary: #4361ee;
      --primary-light: #4895ef;
      --secondary: #3f37c9;
      --success: #4cc9f0;
      --light: #f8f9fa;
      --dark: #212529;
      --gray: #6c757d;
      --blue-gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
      --blue-gradient-light: linear-gradient(135deg, #4895ef 0%, #4361ee 100%);
      --pink: #f72585;
      --card-shadow: 0 8px 24px rgba(67, 97, 238, 0.15);
      --card-shadow-hover: 0 12px 30px rgba(67, 97, 238, 0.25);
      --transition: all 0.3s ease;
    }
    
    body {
      font-family: 'Inter', 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #f5f7fb 0%, #e6f0ff 100%);
      color: #495057;
      min-height: 100vh;
    }
    
    .main {
      padding: 10px;
    }
    
    .pagetitle h1 {
      font-weight: 800;
      color: var(--dark);
      margin-bottom: 0.5rem;
      background: var(--blue-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-size: 1.8rem;
    }
    
    .breadcrumb {
      background: transparent;
      padding: 0;
      font-size: 0.9rem;
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
    
    .section {
      margin-top: 1.5rem;
    }
    
    .card {
      border-radius: 16px;
      border: none;
      box-shadow: var(--card-shadow);
      overflow: hidden;
      background: #fff;
      transition: var(--transition);
      width: 100%;
      max-width: 100%;
    }
    
    .card:hover {
      box-shadow: var(--card-shadow-hover);
    }
    
    .card-header {
      background: var(--blue-gradient);
      border-bottom: none;
      padding: 1rem;
      color: white;
    }
    
    .card-title {
      font-weight: 700;
      color: white;
      margin-bottom: 0;
      font-size: 1.3rem;
    }
    
    .card-body {
      padding: 1.5rem;
      width: 100%;
      max-width: 100%;
      overflow-x: hidden;
    }
    
    .birthday-section {
      margin-bottom: 2.5rem;
      position: relative;
    }
    
    .birthday-section:last-child {
      margin-bottom: 0;
    }
    
    .birthday-section::before {
      content: '';
      position: absolute;
      top: 30px;
      left: -10px;
      height: calc(100% - 60px);
      width: 3px;
      background: var(--blue-gradient);
      border-radius: 10px;
    }
    
    .section-title {
      font-size: 1.3rem;
      font-weight: 800;
      color: var(--dark);
      margin-bottom: 1.2rem;
      display: flex;
      align-items: center;
      gap: 0.6rem;
      padding: 0.5rem 0;
      border-bottom: 2px solid #e6f0ff;
    }
    
    .section-title i {
      background: var(--blue-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-size: 1.5rem;
    }
    
    .students-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 1.5rem;
    }
    
    .student-card {
      background: white;
      border-radius: 16px;
      overflow: hidden;
      transition: var(--transition);
      box-shadow: var(--card-shadow);
      cursor: pointer;
      position: relative;
      border: none;
      margin: 0 auto;
      max-width: 100px;
    }
    
    .student-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--card-shadow-hover);
    }
    
    .card-decoration {
      height: 8px;
      background: var(--blue-gradient);
      width: 100%;
    }
    
    .card-content {
      padding: 1.5rem;
      display: flex;
      flex-direction: column;
      gap: 1.2rem;
      align-items: center;
      text-align: center;
    }
    
    .student-image-container {
      position: relative;
      flex-shrink: 0;
    }
    
    .student-image {
      width: 140px;
      height: 140px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #fff;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
      transition: var(--transition);
    }
    
    .student-card:hover .student-image {
      transform: scale(1.05);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    
    .student-image-container::after {
      content: '';
      position: absolute;
      top: -5px;
      left: -5px;
      right: -5px;
      bottom: -5px;
      border-radius: 50%;
      background: var(--blue-gradient);
      z-index: -1;
      opacity: 0.7;
    }
    
    .student-info {
      width: 100%;
    }
    
    .student-name {
      font-size: 1.2rem;
      font-weight: 800;
      color: var(--dark);
      margin-bottom: 0.75rem;
      position: relative;
      display: inline-block;
    }
    
    .student-name::after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 50%;
      transform: translateX(-50%);
      width: 40px;
      height: 3px;
      background: var(--blue-gradient);
      border-radius: 3px;
    }
    
    .student-details {
      margin-bottom: 1rem;
    }
    
    .student-detail {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      font-size: 0.85rem;
      color: var(--gray);
      margin-bottom: 0.4rem;
    }
    
    .student-detail i {
      width: 16px;
      color: var(--primary);
      transition: var(--transition);
    }
    
    .student-card:hover .student-detail i {
      transform: scale(1.1);
      color: var(--secondary);
    }
    
    .birthday-message {
      background: linear-gradient(135deg, #e6f0ff 0%, #d9e7ff 100%);
      border-radius: 12px;
      padding: 1rem;
      margin-top: 1rem;
      border-left: 4px solid var(--primary);
      transition: var(--transition);
    }
    
    .student-card:hover .birthday-message {
      background: linear-gradient(135deg, #d9e7ff 0%, #cadaff 100%);
      transform: translateX(3px);
    }
    
    .birthday-text {
      color: var(--primary);
      font-weight: 600;
      font-size: 0.9rem;
      line-height: 1.4;
      margin: 0;
    }
    
    .birthday-icon {
      position: absolute;
      top: 15px;
      right: 15px;
      width: 40px;
      height: 40px;
      background: var(--blue-gradient);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.2rem;
      box-shadow: 0 6px 15px rgba(67, 97, 238, 0.3);
      transition: var(--transition);
    }
    
    .student-card:hover .birthday-icon {
      transform: rotate(15deg) scale(1.1);
      box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
    }
    
    .no-birthdays {
      text-align: center;
      padding: 2.5rem 1.5rem;
      background: white;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
      grid-column: 1 / -1;
      transition: var(--transition);
      max-width: 400px;
      margin: 0 auto;
    }
    
    .no-birthdays:hover {
      transform: translateY(-3px);
      box-shadow: var(--card-shadow-hover);
    }
    
    .no-birthdays i {
      font-size: 3rem;
      background: var(--blue-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 1rem;
    }
    
    .no-birthdays p {
      color: var(--gray);
      font-size: 1rem;
      margin-bottom: 0;
      font-weight: 600;
    }
    
    .count-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: var(--blue-gradient);
      color: white;
      font-size: 0.8rem;
      font-weight: 700;
      border-radius: 50px;
      padding: 0.3rem 0.8rem;
      margin-left: 0.5rem;
      box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
    }
    
    .download-indicator {
      position: absolute;
      bottom: 10px;
      right: 10px;
      background: rgba(255, 255, 255, 0.9);
      padding: 0.3rem 0.6rem;
      border-radius: 16px;
      font-size: 0.7rem;
      color: var(--primary);
      font-weight: 600;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      opacity: 0;
      transition: var(--transition);
      display: flex;
      align-items: center;
      gap: 0.3rem;
    }
    
    .student-card:hover .download-indicator {
      opacity: 1;
      transform: translateY(-3px);
    }
    
    .logos-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      margin-bottom: 1rem;
    }
    
    .logo {
      height: 40px;
      width: auto;
      object-fit: contain;
    }
    
    .logo.edulk {
      height: 70px;
    }
    
    .logo.hnd {
      height: 80px;
    }
    
    /* Animation for birthday cards */
    @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-5px); }
      100% { transform: translateY(0px); }
    }
    
    .student-card {
      animation: float 4s ease-in-out infinite;
    }
    
    .student-card:nth-child(2n) {
      animation-delay: 0.5s;
    }
    
    .student-card:nth-child(3n) {
      animation-delay: 1s;
    }
    
    /* Media Queries for Larger Screens */
    @media (min-width: 576px) {
      .pagetitle h1 {
        font-size: 2rem;
      }
      
      .card-title {
        font-size: 1.5rem;
      }
      
      .student-card {
        max-width: 500px;
      }
      
      .student-image {
        width: 160px;
        height: 160px;
      }
    }
    
    @media (min-width: 768px) {
      .main {
        padding: 20px;
      }
      
      .card-body {
        padding: 2rem;
      }
      
      .students-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
      }
      
      .section-title {
        font-size: 1.5rem;
      }
      
      .student-card {
        max-width: 100%;
      }
      
      .card-content {
        flex-direction: row;
        text-align: left;
        padding: 2rem;
      }
      
      .student-name::after {
        left: 0;
        transform: none;
      }
      
      .student-detail {
        justify-content: flex-start;
      }
    }
    
    @media (min-width: 992px) {
      .students-grid {
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
      }
      
      .student-image {
        width: 180px;
        height: 180px;
      }
    }
    
    @media (min-width: 1200px) {
      .students-grid {
        grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
      }
      
      .student-image {
        width: 200px;
        height: 200px;
      }
    }
  </style>
</head>
<body>
  <?php include_once("../includes/header.php"); ?>
  <?php include_once("../includes/sadmin-sidebar.php") ?>

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Students Birthdays</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <li class="breadcrumb-item"><a href="students.php">Students</a></li>
          <li class="breadcrumb-item active">Birthdays</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="card shadow-sm">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="fas fa-calendar-alt me-2"></i>Upcoming Students Birthdays</h5>
        </div>
        <div class="card-body">

          <!-- Today Birthdays -->
          <div class="birthday-section">
            <h2 class="section-title">
              <i class="fas fa-birthday-cake"></i>
              Today's Birthdays
              <span class="count-badge"><?= $today_count ?></span>
            </h2>
            
            <?php if ($today_count > 0): ?>
              <div class="students-grid">
                <?php foreach ($todays_birthdays as $student): ?>
                  <div class="student-card" onclick="downloadCard(this)">
                    <div class="card-decoration"></div>
                    <div class="birthday-icon">
                      <i class="fas fa-gift"></i>
                    </div>
                    <div class="logos-container">
                        <img src="../assets/images/logos/edulk-logo.png" class="logo edulk" alt="Edulk Logo">
                        <img src="../assets/images/logos/hnd-logo.png" class="logo hnd" alt="HND Logo">
                      </div>
                    <div class="card-content">
                      
                      
                      <div class="student-image-container">
                        <img src="../<?= htmlspecialchars($student['profile_picture']) ?>" alt="Profile" class="student-image">
                      </div>
                      
                      <div class="student-info">
                        <h3 class="student-name"><?= htmlspecialchars($student['name']) ?></h3>
                        
                        <div class="student-details">
                          <div class="student-detail">
                            <i class="fas fa-users"></i>
                            <span>Batch: <?= $student['batch_year'] ?></span>
                          </div>
                          <div class="student-detail">
                            <i class="fas fa-id-card"></i>
                            <span>Reg No: <?= $student['regno'] ?></span>
                          </div>
                          <div class="student-detail">
                            <i class="fas fa-calendar-day"></i>
                            <span>Birthday: <?= date("F d", strtotime($student['birthday'])) ?></span>
                          </div>
                          <div class="student-detail birthyear-info">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Birth Year: <?= date("Y", strtotime($student['birthday'])) ?></span>
                          </div>
                          <div class="student-detail">
                            <i class="fas fa-birthday-cake"></i>
                            <span>Age: <?= calculate_age($student['birthday']) ?> years</span>
                          </div>
                        </div>
                        
                        <div class="birthday-message">
                          <p class="birthday-text">
                            🎉 Happy Birthday <?= htmlspecialchars($student['name']) ?>! 🎂<br>
                            From Edulk (Admin Malitha Tishamal)
                          </p>
                        </div>
                         <div class="auto-generated-badge">
  <i class="fas fa-robot"></i>
  <span>Auto-Generated by <strong>Edulk</strong></span>
</div>
                      </div>
                    </div>
                    <div class="download-indicator">
                      <i class="fas fa-download me-1"></i> Click to download
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="no-birthdays">
                <i class="fas fa-birthday-cake"></i>
                <p>No birthdays today 🎉</p>
              </div>
            <?php endif; ?>
          </div>

          <!-- Upcoming Birthdays -->
          <div class="birthday-section">
            <h2 class="section-title">
              <i class="fas fa-calendar-alt"></i>
              Upcoming Birthdays
            </h2>
            
            <?php if (count($upcoming_birthdays) > 0): ?>
              <div class="students-grid">
                <?php foreach ($upcoming_birthdays as $student): ?>
                  <div class="student-card" onclick="downloadCard(this)">
                    <div class="card-decoration"></div>
                    <div class="birthday-icon">
                      <i class="fas fa-clock"></i>
                    </div>
                    <div class="logos-container">
                        <img src="../assets/images/logos/edulk-logo.png" class="logo edulk" alt="Edulk Logo">
                        <img src="../assets/images/logos/hnd-logo.png" class="logo hnd" alt="HND Logo">
                      </div>
                    <div class="card-content">
                      
                      
                      <div class="student-image-container">
                        <img src="../<?= htmlspecialchars($student['profile_picture']) ?>" alt="Profile" class="student-image">
                      </div>
                      
                      <div class="student-info">
                        <h3 class="student-name"><?= htmlspecialchars($student['name']) ?></h3>
                        
                        <div class="student-details">
                          <div class="student-detail">
                            <i class="fas fa-users"></i>
                            <span>Batch: <?= $student['batch_year'] ?></span>
                          </div>
                          <div class="student-detail">
                            <i class="fas fa-id-card"></i>
                            <span>Reg No: <?= $student['regno'] ?></span>
                          </div>
                          <div class="student-detail">
                            <i class="fas fa-calendar-day"></i>
                            <span>Birthday: <?= date("F d", strtotime($student['birthday'])) ?></span>
                          </div>
                          <div class="student-detail birthyear-info">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Birth Year: <?= date("Y", strtotime($student['birthday'])) ?></span>
                          </div>
                          <div class="student-detail">
                            <i class="fas fa-birthday-cake"></i>
                            <span>Age: <?= calculate_age($student['birthday']) ?> years</span>
                          </div>
                        </div>
                        
                        <div class="birthday-message">
                          <p class="birthday-text">
                            🎉 Happy Birthday <?= htmlspecialchars($student['name']) ?>! 🎂<br>
                            From Edulk (Admin Malitha Tishamal)
                          </p>
                        </div>
                        <div class="auto-generated-badge">
  <i class="fas fa-robot"></i>
  <span>Auto-Generated by <strong>Edulk</strong></span>
</div>
<style>
.auto-generated-badge {
  margin-top: 12px;
  font-size: 0.8rem;
  color: #666;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 20px;
  background: #f4f4f4;
  border: 1px solid #ddd;
  font-style: italic;
  opacity: 0.9;
}

.auto-generated-badge i {
  color: #888;
  font-size: 0.9rem;
}

</style>
                      </div>
                    </div>
                    <div class="download-indicator">
                      <i class="fas fa-download me-1"></i> Click to download
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="no-birthdays">
                <i class="fas fa-calendar-times"></i>
                <p>No upcoming birthdays in the next 3 days 🎂</p>
              </div>
            <?php endif; ?>
          </div>

        </div>
      </div>
    </section>
  </main>

  <?php include_once("../includes/footer.php") ?>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <?php include_once("../includes/js-links-inc.php") ?>

  <script>
    function downloadCard(cardElement) {
      // Create a clone of the card to avoid affecting the original
      const clone = cardElement.cloneNode(true);
      
      // Remove the birth year information from the clone
      const birthYearElements = clone.querySelectorAll('.birthyear-info');
      birthYearElements.forEach(el => el.remove());
      
      // Remove the download indicator
      const downloadIndicator = clone.querySelector('.download-indicator');
      if (downloadIndicator) {
        downloadIndicator.remove();
      }
      
      // Remove the birthday icon from the clone
      const birthdayIcon = clone.querySelector('.birthday-icon');
      if (birthdayIcon) {
        birthdayIcon.remove();
      }
      
      // Temporarily append to body for rendering
      clone.style.position = 'absolute';
      clone.style.left = '-9999px';
      document.body.appendChild(clone);
      
      html2canvas(clone, { 
        scale: 2,
        backgroundColor: '#ffffff',
        onclone: function(clonedDoc, element) {
          // Additional styling for the cloned element
          element.style.boxShadow = 'none';
          element.style.transform = 'none';
          element.style.animation = 'none';
          
          // Remove any hover effects
          const birthdayMessage = element.querySelector('.birthday-message');
          if (birthdayMessage) {
            birthdayMessage.style.transform = 'none';
          }
        }
      }).then(canvas => {
        let studentName = clone.querySelector(".student-name").innerText;
        let link = document.createElement("a");
        link.download = studentName.replace(/\s+/g, "_") + "_birthday_card.png";
        link.href = canvas.toDataURL("image/png");
        link.click();
        
        // Remove the clone after capturing
        document.body.removeChild(clone);
      });
    }
  </script>

</body>
</html>
<?php $conn->close(); ?>