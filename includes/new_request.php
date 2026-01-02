<?php
session_start();

// Check if walker connected
if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.php");
    exit;
}

//Get walker name from session
$walkerName = $_SESSION['walker_name'] ?? '';
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>בחירת סוג ליווי - SafeWalk</title>
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/new_request.css">
</head>
<body>

<!--Loading header-->
<div id="header-container">
    <?php include 'header_walker.php'; ?>
</div>

<main class="support-options">

  <p class="subtitle">בחרי את אופן הליווי המתאים לך</p>

  <div class="options-grid">

  <a href="whatsapp_escort.php" class="option-card">
    <img src="../images/whatsapp.png" alt="WhatsApp">
    <span>ליווי בווטסאפ + וידאו</span>
  </a>

  <a href="phone_call_escort.php" class="option-card">
    <img src="../images/phone.png" alt="שיחת טלפון">
    <span>ליווי בשיחת טלפון</span>
  </a>

    <a href="walker_share_location.php" class="option-card">
    <img src="../images/location.png" alt="שיתוף מיקום">
    <span>שיתוף מיקום</span>
  </a>

  <a href="ai_escort.php" class="option-card">
    <img src="../images/ai.jfif" alt="בינה מלאכותית">
    <span>ליווי מבוסס בינה מלאכותית</span>
  </a>

</div>

   <div class="sos-wrap">
    <a href="tel:100" class="sos-btn">S.O.S</a>
   </div>

</main>

 <footer class="footer">
      <p>© 2025 SafeWalk</p>
  </footer>

</body>
</html>