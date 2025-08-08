<h2>Reports</h2>
<h3>Laptop Scans <a href="/index.php?r=reports/exportLaptopCsv">Export CSV</a></h3>
<table class="table">
  <thead><tr><th>ID</th><th>Laptop</th><th>Direction</th><th>Time</th><th>User</th></tr></thead>
  <tbody>
    <?php foreach ($laptopScans as $r): ?>
    <tr>
      <td><?= (int)$r['id'] ?></td>
      <td><?= htmlspecialchars($r['laptop_number']) ?></td>
      <td><?= htmlspecialchars($r['direction']) ?></td>
      <td><?= htmlspecialchars($r['scanned_at']) ?></td>
      <td><?= htmlspecialchars((string)$r['scanned_by']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<h3>Car Scans <a href="/index.php?r=reports/exportCarCsv">Export CSV</a></h3>
<table class="table">
  <thead><tr><th>ID</th><th>Reg</th><th>Direction</th><th>Time</th><th>User</th></tr></thead>
  <tbody>
    <?php foreach ($carScans as $r): ?>
    <tr>
      <td><?= (int)$r['id'] ?></td>
      <td><?= htmlspecialchars($r['registration_number']) ?></td>
      <td><?= htmlspecialchars($r['direction']) ?></td>
      <td><?= htmlspecialchars($r['scanned_at']) ?></td>
      <td><?= htmlspecialchars((string)$r['scanned_by']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<h3>Attendance <a href="/index.php?r=reports/exportAttendanceCsv">Export CSV</a></h3>
<table class="table">
  <thead><tr><th>ID</th><th>Employee No</th><th>Name</th><th>Status</th><th>Time</th></tr></thead>
  <tbody>
    <?php foreach ($attendance as $r): ?>
    <tr>
      <td><?= (int)$r['id'] ?></td>
      <td><?= htmlspecialchars($r['employee_number']) ?></td>
      <td><?= htmlspecialchars($r['full_name']) ?></td>
      <td><?= htmlspecialchars($r['status']) ?></td>
      <td><?= htmlspecialchars($r['occurred_at']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>