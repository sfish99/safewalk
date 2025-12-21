<?php
session_start();
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>התחברות מתנדבת - SafeWalk</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

<header class="header">
  <img src="../images/logo.png" alt="SafeWalk Logo" class="logo">

</header>

<div class="login-box">
    <h2>התחברות מתנדבת</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-msg">
            <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']); 
            ?>
        </div>
    <?php endif; ?>

    <form action="login_volunteer_form.php" method="POST">

        <label>אימייל:</label>
        <input type="email" name="email" required>

        <label>סיסמה:</label>
        <input type="password" name="password" required>

        <button type="submit">התחברי</button>

    </form>
</div>

</body>
<footer class="footer">
      <p>© 2025 SafeWalk</p>
</footer>

</html>
