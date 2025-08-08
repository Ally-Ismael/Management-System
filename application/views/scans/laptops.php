<?php
use App\Lib\Helpers\CsrfHelper;
?>
<h2>Laptop Scans</h2>
<?php if (!empty($msg)): ?><div class="alert success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<form method="post">
  <?= CsrfHelper::inputField() ?>
  <div class="row">
    <div>
      <label>Laptop Number</label>
      <input name="laptop_number" required>
    </div>
    <div>
      <label>Direction</label>
      <select name="direction">
        <option value="in">In</option>
        <option value="out">Out</option>
      </select>
    </div>
  </div>
  <button class="primary" type="submit">Log Scan</button>
</form>

<h3>Recent</h3>
<table class="table">
  <thead><tr><th>ID</th><th>Laptop</th><th>Direction</th><th>Time</th><th>User</th></tr></thead>
  <tbody>
    <?php foreach ($recent as $r): ?>
    <tr>
      <td><?= (int)$r['id'] ?></td>
      <td><?= htmlspecialchars($r['laptop_number']) ?></td>
      <td><span class="badge <?= $r['direction'] ?>"><?= htmlspecialchars($r['direction']) ?></span></td>
      <td><?= htmlspecialchars($r['scanned_at']) ?></td>
      <td><?= htmlspecialchars((string)$r['scanned_by']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>