<?php
use App\Lib\Helpers\CsrfHelper;
?>
<h2>Attendance Register</h2>
<?php if (!empty($msg)): ?><div class="alert success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

<h3>Add Employee</h3>
<form method="post">
  <?= CsrfHelper::inputField() ?>
  <input type="hidden" name="create_employee" value="1">
  <div class="row">
    <div>
      <label>Employee Number</label>
      <input name="employee_number" required>
    </div>
    <div>
      <label>Full Name</label>
      <input name="full_name" required>
    </div>
    <div>
      <label>Department</label>
      <input name="department" required>
    </div>
  </div>
  <button class="primary" type="submit">Add Employee</button>
</form>

<h3>Log Attendance</h3>
<form method="post">
  <?= CsrfHelper::inputField() ?>
  <div class="row">
    <div>
      <label>Employee</label>
      <select name="employee_id">
        <?php foreach ($employees as $e): ?>
          <option value="<?= (int)$e['id'] ?>"><?= htmlspecialchars($e['employee_number'] . ' - ' . $e['full_name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label>Status</label>
      <select name="status">
        <option value="in">In</option>
        <option value="out">Out</option>
      </select>
    </div>
  </div>
  <button class="primary" type="submit">Log</button>
</form>

<h3>Recent Attendance</h3>
<table class="table">
  <thead><tr><th>ID</th><th>Employee No</th><th>Name</th><th>Status</th><th>Time</th></tr></thead>
  <tbody>
    <?php foreach ($recent as $r): ?>
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