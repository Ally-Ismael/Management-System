<h2>Reports</h2>
<section style="margin-bottom:20px;">
  <h3>Booking Analytics</h3>
  <p>View booking trends and performance metrics.</p>
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
  <div class="actions"><a class="button" href="/index.php?r=reports/exportLaptopCsv">Export CSV</a></div>
</section>

<section style="margin-bottom:20px;">
  <h3>Vehicle Movements</h3>
  <p>In/Out movements for company vehicles.</p>
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
  <div class="actions"><a class="button" href="/index.php?r=reports/exportCarCsv">Export CSV</a></div>
</section>

<section style="margin-bottom:20px;">
  <h3>Revenue Report</h3>
  <p>Generate revenue reports for selected date ranges.</p>
  <form method="get" action="/index.php">
    <input type="hidden" name="r" value="reports/index">
    <div class="row">
      <div>
        <label>Start Date</label>
        <input type="date" name="start_date">
      </div>
      <div>
        <label>End Date</label>
        <input type="date" name="end_date">
      </div>
      <div style="align-self:flex-end;">
        <button class="primary" type="submit">Apply</button>
      </div>
    </div>
  </form>
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
  <div class="actions"><a class="button" href="/index.php?r=reports/exportAttendanceCsv">Export CSV</a></div>
</section>