<?php
session_start();

// if there is no session - send to log in page
if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.html");
    exit;
}

$walkerName = $_SESSION['walker_name'];
?>


<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeWalk - דף ראשי מתנדבת</title>
    <link rel="stylesheet" href="../css/Home_walker.css">
</head>
<body>

    <header class="header-dashboard">
        <div class="dashboard-content-wrapper">
                <img src="../images/logo.png" alt="SafeWalk Logo" class="logo">
          
            
                       <div class="role-switch" role="tablist">
                <button class="role-btn-toggle walker active">הולכת רגל</button>
                <button class="role-btn-toggle volunteer">מתנדבת</button>
            </div>
        </div>
    </header>

    <div class="shell">
        <main class="main-dashboard-content">
            <div class="grid-2x2">
                <button class="card" onclick="window.location.href='../includes/new_request.php'">
                    <div class="card-icon">✋</div>
                    <p>פתיחת קריאה</p>
                    <small>התחל קריאה חדשה</small>
                </button>               
                <button class="card"><div class="card-icon">📋</div><p>היסטוריית קריאות</p><small>צפה בקריאות קודמות</small></button>
                <button class="card"><div class="card-icon">🤝</div><p>תמיכה </p><small>קבלת מידע ומענה על שאלות נפוצות</small></button>
                <button class="card"><div class="card-icon">👤</div><p>פרופיל אישי</p><small>ניהול חשבון</small></button>
            </div>

            <div class="sos-wrap">
                <a href="tel:100" class="sos-btn">S.O.S</a>
            </div>
        </main>

    </div>
    
    <footer class="footer">
      <p>© 2025 SafeWalk</p>
      </footer>

    <script src="../js/switch-role.js"></script>

</body>
</html>