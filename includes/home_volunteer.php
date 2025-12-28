<?php
session_start();

// if there is no session - send to log in page
if (!isset($_SESSION['volunteer_id'])) {
    header("Location: login_volunteer.php");
    exit;
}

$volName = $_SESSION['volunteer_name']; 
?>


<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeWalk - דף ראשי מתנדבת</title>
    <link rel="stylesheet" href="../css/new_request.css">

</head>
<body>

    <header class="header-dashboard">
       
        <div class="dashboard-content-wrapper">
                <img src="../images/logo.png" alt="SafeWalk Logo" class="logo">
        
        </div>
    </header>

    <header class="header">
        <a href="logout_volunteer.php" class="logout-btn">התנתקות</a>
        <div class="dashboard-content-wrapper">
        <img src="../images/logo.png" alt="SafeWalk Logo" class="logo">
        </div>
    </header>

    <main class="support-options">

              <div class="options-grid">
                    <a href="online_walkers.php" class="option-card">
                    <div class="card-icon">📍</div>
                    <span>מעקב מיקום</span>
                    <small>צפייה במיקום הולכות רגל</small>
                </a>

                   <div class="options-grid">
                    <a href="support_volunteer..php" class="option-card">
                    <div class="card-icon">🤝</div>
                    <span> תמיכה</span>
                    <small>קבלת מידע ומענה על שאלות נפוצות</small>
                </a>
            
                   <div class="options-grid">
                    <a href="" class="option-card">
                    <div class="card-icon">📋</div>
                    <span> היסטוריית קריאות</span>
                    <small>צפייה בקריאות קודמות</small>
                </a>

                <div class="options-grid">
                    <a href=" " class="option-card">
                    <div class="card-icon">👤</div>
                    <span> פרופיל אישי </span>
                    <small>ניהול חשבון</small>
                </a>

            </div>

            <div class="sos-wrap">
                <a href="tel:100" class="sos-btn">S.O.S</a>
            </div>
        </main>

    </div>
    
    <footer class="footer">
      <p>© 2025 SafeWalk</p>
      </footer>

    
</body>
</html>
