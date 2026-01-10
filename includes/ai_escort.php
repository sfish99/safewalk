<?php
// Start the session to access logged-in user data
    session_start();

// If the volunteer is not logged in, redirect to login page
    if (!isset($_SESSION['walker_id'])) {
    header("Location: login_walker.php");
    exit;
    }

    $walkerName = $_SESSION['walker_name'] ?? '';
?>

<!doctype html>
<html lang="he" dir="rtl">
<head>
    <meta charset="utf-8" />
    <title>SafeWalk – ליווי AI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!-- CSS file for designing AI escorting page -->
    <link rel="stylesheet" href="../css/header.css"/>
    <link rel="stylesheet" href="../css/ai.css"/>
</head>

<body>
    <!--Loading header-->
    <div id="header-container">
        <?php include 'header_walker.php'; ?>
    </div>

    <div class="shell">
        <main>
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
                לצורך התחלת הליווי, אנא לחצי על כפתור ״התחלי ליווי AI".
                </p>

                <div class="buttons">
                    <button id="startAiBtn" type="button">התחלי ליווי AI</button>
                    <button id="stopAiBtn" type="button" disabled>עצורי ליווי</button>
                    <button id="muteAiBtn" type="button" disabled>השתקה</button>
                </div>

                <div id="aiStatus" class="status">
                ליווי AI כבוי כרגע.
                </div>

                <!--Messages in the chat -->
                <div id="aiMessages" class="log">
                    <div class="msg msg-ai">
                        <strong>המלווה:</strong> שלום, אני איתך. נתחיל ליווי עכשיו 💗
                    </div>
                </div>

                <!-- Input message -->
                <form id="chatForm" class="chat-form">
                    <input
                    id="userMessage"
                    type="text"
                    placeholder="ספרי לי איך את מרגישה עכשיו…"
                    autocomplete="off"
                    />
                        <button type="submit">שלחי</button>
                        <button type="button" id="voiceInputBtn" title="דיבור למלל">🎤</button>
                </form>

            </section>

            <!-- SOS button -->
            <div class="sos-wrap">
                <a href="tel:100" class="sos-btn">S.O.S</a>
            </div>
        </main>
    </div>

    <!-- JS for AI escort -->
    <script src="../js/ai_escort.js"></script>

</body>
</html>
