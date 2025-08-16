<h2>Dashboard</h2>
<p>Welcome, <?= htmlspecialchars($_SESSION['user']['name'] ?? '') ?>.</p>
<ul>
  <li><a href="/index.php?r=users/index">User Management</a></li>
  <li><a href="/index.php?r=assets/laptops">Manage Laptops</a></li>
  <li><a href="/index.php?r=assets/cars">Manage Cars</a></li>
  <li><a href="/index.php?r=scans/laptops">Laptop Scans</a></li>
  <li><a href="/index.php?r=scans/cars">Car Scans</a></li>
  <li><a href="/index.php?r=reports/index">Reports</a></li>
  <li><a href="/index.php?r=attendance/index">Attendance</a></li>
</ul>