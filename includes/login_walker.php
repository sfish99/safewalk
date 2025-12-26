<?php
session_start();
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>התחברות הולכת רגל - SafeWalk</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

  <header class="header">
    <a href="../index.html">
        <img src="../images/logo.png" alt="SafeWalk Logo" class="logo">
    </a>
    </header>

<div class="login-box">
    <h2>התחברות הולכת רגל</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-msg">
            <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']); 
            ?>
        </div>
    <?php endif; ?>

    <form action="login_walker_form.php" method="POST">

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
