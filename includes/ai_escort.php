<?php
session_start();

/*
  התאימי את המפתח לפי מה שאת שומרת אצלך ב-session.
  דוגמאות נפוצות: walkerName / walker_name / username / full_name
*/
$walkerName = $_SESSION['walkerName'] ?? $_SESSION['walker_name'] ?? $_SESSION['username'] ?? 'שרון';
?>
<!doctype html>
<html lang="he" dir="rtl">
<head>
  <meta charset="utf-8" />
  <title>SafeWalk – ליווי AI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- CSS file for designing AI escorting page -->
  <link rel="stylesheet" href="../css/ai.css"/>
</head>

<body>

  <!-- Header -->
  <header class="header-dashboard">
    <a href="../home_walker.php" class="back-btn">חזרה לדף הבית</a>
    <a href="../logout_walker.php" class="logout-btn">התנתקות</a>

    <div class="dashboard-content-wrapper">
      <img src="../images/logo.png" alt="SafeWalk Logo" class="logo">
      <h2 class="welcome-text">✨ שלום <?php echo htmlspecialchars($walkerName); ?></h2>
    </div>
  </header>

  <div class="shell">
    <main>
      <!-- כרטיס: זיהוי מילות מצוקה -->
      <section class="card">
        <h2>זיהוי מצוקה אוטומטי</h2>
        <p>
          הבינה המלאכותית מאזינה למילים שמורות כמו:
          <strong>"עזרה"</strong>, <strong>"מפחיד"</strong>, <strong>"תתקשרו למישהו"</strong>.
          בעת זיהוי מילים כאלה, היא יכולה להמליץ על פנייה למוקד חירום או לגורם אנושי.
        </p>
        <ul class="list">
          <li>תגובה קולית מרגיעה (למשל: "אני איתך, הכול בסדר").</li>
          <li>הצעה ליצור קשר עם מוקד חירום.</li>
          <li>פתיחת אפשרות לחיוג/שליחת הודעה לגורם חירום שנבחר.</li>
        </ul>

        <div class="hint">
          להדגמה, לחיצה על הכפתור למטה יכולה לייצג מצב שבו זוהתה מילת מצוקה.
        </div>

        <div class="center">
          <button id="simulateKeywordBtn" type="button">
            סימולציית זיהוי "עזרה"
          </button>
        </div>
      </section>

      <section class="card">
        <h2>הפעלת ליווי AI</h2>
        <p>
          המלווה מבוססת הבינה המלאכותית מדברת איתך בטון רגוע, מחזקת ומעודדת בזמן ההליכה.
        </p>
        <p>
          לצורך התחלת הליווי, אנא לחצי על כפתור ״התחלי ליווי AI״.
        </p>

        <div class="buttons">
          <button id="startAiBtn" type="button">התחלי ליווי AI</button>
          <button id="stopAiBtn" type="button" disabled>עצורי ליווי</button>
          <button id="muteAi
