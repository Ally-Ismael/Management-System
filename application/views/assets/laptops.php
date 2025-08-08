<?php
use App\Lib\Helpers\CsrfHelper;
?>
<h2>Laptops</h2>
<form method="post">
  <?= CsrfHelper::inputField() ?>
  <div class="row">
    <div>
      <label>Laptop Number</label>
      <input name="laptop_number" required>
    </div>
    <div>
      <label>Brand</label>
      <input name="brand" required>
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
  <button class="primary" type="submit">Add Laptop</button>
</form>

<table class="table">
  <thead><tr><th>ID</th><th>Number</th><th>Brand</th><th>Model</th><th>Assigned To</th><th>Created</th></tr></thead>
  <tbody>
    <?php foreach ($items as $it): ?>
    <tr>
      <td><?= (int)$it['id'] ?></td>
      <td><?= htmlspecialchars($it['laptop_number']) ?></td>
      <td><?= htmlspecialchars($it['brand']) ?></td>
      <td><?= htmlspecialchars($it['model']) ?></td>
      <td><?= htmlspecialchars((string)$it['assigned_to']) ?></td>
      <td><?= htmlspecialchars($it['created_at']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>