<?php
use const App\Config\BASE_URL;
use App\Lib\Helpers\CsrfHelper;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Management-System</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>css/styles.css">
  <style>
    .button { background:#0b5ed7; color:#fff; padding:6px 10px; border-radius:6px; text-decoration:none; display:inline-block; }
    .button:hover { opacity:.9; }
    .actions { display:flex; gap:8px; flex-wrap:wrap; margin:8px 0 12px 0; }
    section { border:1px solid #eee; border-radius:8px; padding:12px; }
  </style>
</head>
<body>
  <header class="topbar">
    <div class="brand">Laptop & Car Gate Scan System</div>
    <nav>
      <?php if (!empty($_SESSION['user'])): ?>
        <a href="<?= BASE_URL ?>index.php?r=dashboard/index">Dashboard</a>
        <a href="<?= BASE_URL ?>index.php?r=users/index">Users</a>
        <a href="<?= BASE_URL ?>index.php?r=assets/laptops">Laptops</a>
        <a href="<?= BASE_URL ?>index.php?r=assets/cars">Cars</a>
        <a href="<?= BASE_URL ?>index.php?r=scans/laptops">Laptop Scans</a>
        <a href="<?= BASE_URL ?>index.php?r=scans/cars">Car Scans</a>
        <a href="<?= BASE_URL ?>index.php?r=reports/index">Reports</a>
        <a href="<?= BASE_URL ?>index.php?r=attendance/index">Attendance</a>
        <form method="post" action="<?= BASE_URL ?>index.php?r=auth/logout" class="inline-form">
          <?= CsrfHelper::inputField() ?>
          <button type="submit">Logout (<?= htmlspecialchars($_SESSION['user']['name'] ?? '', ENT_QUOTES) ?>)</button>
        </form>
      <?php else: ?>
        <a href="<?= BASE_URL ?>index.php?r=auth/login">Login</a>
        <a href="<?= BASE_URL ?>index.php?r=auth/register">Register</a>
      <?php endif; ?>
    </nav>
  </header>
  <main class="container">
    <?= $content ?>
  </main>
  <script src="<?= BASE_URL ?>js/app.js"></script>
</body>
</html>