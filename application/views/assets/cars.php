<?php
use App\Lib\Helpers\CsrfHelper;
?>
<h2>Cars</h2>
<form method="post">
  <?= CsrfHelper::inputField() ?>
  <div class="row">
    <div>
      <label>Registration Number</label>
      <input name="registration_number" required>
    </div>
    <div>
      <label>Make</label>
      <input name="make" required>
    </div>
    <div>
      <label>Model</label>
      <input name="model" required>
    </div>
    <div>
      <label>Assigned To</label>
      <input name="assigned_to">
    </div>
  </div>
  <button class="primary" type="submit">Add Car</button>
</form>

<table class="table">
  <thead><tr><th>ID</th><th>Reg</th><th>Make</th><th>Model</th><th>Assigned To</th><th>Created</th></tr></thead>
  <tbody>
    <?php foreach ($items as $it): ?>
    <tr>
      <td><?= (int)$it['id'] ?></td>
      <td><?= htmlspecialchars($it['registration_number']) ?></td>
      <td><?= htmlspecialchars($it['make']) ?></td>
      <td><?= htmlspecialchars($it['model']) ?></td>
      <td><?= htmlspecialchars((string)$it['assigned_to']) ?></td>
      <td><?= htmlspecialchars($it['created_at']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>