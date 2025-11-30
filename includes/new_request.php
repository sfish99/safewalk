<?php
session_start();

// Check if walker connected
if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.html");
    exit;
}
?>


<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>בחירת סוג ליווי - SafeWalk</title>
  <link rel="stylesheet" href="../css/new_request.css">
</head>
<body>

<header class="header">
  <img src="../images/logo.png" alt="SafeWalk Logo" class="logo">
  <h1>בחירת סוג ליווי</h1>
</header>

<main class="support-options">

  <p class="subtitle">בחרי את אופן הליווי המתאים לך</p>

  <div class="options-grid">

  <a href="whatsapp-escort.html" class="option-card">
    <img src="../images/whatsapp.png" alt="WhatsApp">
    <span>ליווי בווטסאפ</span>
  </a>

  <a href="phone-escort.html" class="option-card">
    <img src="../images/phone.png" alt="שיחת טלפון">
    <span>ליווי בשיחת טלפון</span>
  </a>

    <a href="../includes/Location-based-escort.html" class="option-card">
    <img src="../images/location.png" alt="שיתוף מיקום">
    <span>שיתוף מיקום</span>
  </a>

  <a href="video-escort.html" class="option-card">
    <img src="../images/video call.png" alt="וידאו">
    <span>ליווי בשיחת וידאו</span>
  </a>

</div>

   <div class="sos-wrap">
     <button class="sos-btn">S.O.S</button>
   </div>

</main>

 <footer class="footer">
      <p>© 2025 SafeWalk</p>
  </footer>

<script src="new_request.js"></script>
</body>
</html>