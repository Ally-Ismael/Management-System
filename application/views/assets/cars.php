<?php
use App\Lib\Helpers\CsrfHelper;
use const App\Config\BASE_URL;
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
  <thead><tr><th>ID</th><th>Reg</th><th>Make</th><th>Model</th><th>Assigned To</th><th>Created</th><th>Actions</th></tr></thead>
  <tbody>
    <?php foreach ($items as $it): ?>
    <tr>
      <td><?= (int)$it['id'] ?></td>
      <td><?= htmlspecialchars($it['registration_number']) ?></td>
      <td><?= htmlspecialchars($it['make']) ?></td>
      <td><?= htmlspecialchars($it['model']) ?></td>
      <td><?= htmlspecialchars((string)$it['assigned_to']) ?></td>
      <td><?= htmlspecialchars($it['created_at']) ?></td>
      <td class="actions">
        <form method="post" action="<?= BASE_URL ?>index.php?r=assets/deleteCar" style="display:inline;">
          <?= CsrfHelper::inputField() ?>
          <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
          <button type="submit" data-confirm="Delete this car?">Delete</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>