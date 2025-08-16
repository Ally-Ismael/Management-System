<?php
use App\Lib\Helpers\CsrfHelper;
use const App\Config\BASE_URL;
?>
<h2>User Management</h2>
<?php if (!empty($msg)): ?><div class="alert success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<div class="actions">
  <a class="button" href="<?= BASE_URL ?>index.php?r=users/index">Load All Users</a>
  <a class="button" href="<?= BASE_URL ?>index.php?r=users/pending">Pending Verification</a>
  <a class="button" href="<?= BASE_URL ?>index.php?r=users/exportCsv">Export Data</a>
  <button class="primary" id="openAddDriver">Add Driver</button>
</div>

<table class="table">
  <thead>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Verified</th><th>Actions</th></tr>
  </thead>
  <tbody>
    <?php foreach ($items as $u): ?>
    <tr>
      <td><?= (int)$u['id'] ?></td>
      <td><?= htmlspecialchars($u['name']) ?></td>
      <td><?= htmlspecialchars($u['email']) ?></td>
      <td><?= htmlspecialchars($u['role']) ?></td>
      <td><?= ((int)($u['verified'] ?? 0) === 1) ? 'Yes' : 'No' ?></td>
      <td class="actions">
        <form method="post" action="<?= BASE_URL ?>index.php?r=users/verify" style="display:inline">
          <?= CsrfHelper::inputField() ?>
          <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
          <input type="hidden" name="verified" value="<?= ((int)($u['verified'] ?? 0) === 1) ? 0 : 1 ?>">
          <button type="submit"><?= ((int)($u['verified'] ?? 0) === 1) ? 'Unverify' : 'Verify' ?></button>
        </form>
        <form method="post" action="<?= BASE_URL ?>index.php?r=users/delete" style="display:inline">
          <?= CsrfHelper::inputField() ?>
          <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
          <button type="submit" data-confirm="Delete this user?">Delete</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div id="driverModal" class="modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); align-items:center; justify-content:center;">
  <div class="modal-card" style="background:#fff; padding:16px; border-radius:8px; width:400px; max-width:90vw;">
    <h3>Add Driver</h3>
    <form method="post" action="<?= BASE_URL ?>index.php?r=drivers/create">
      <?= CsrfHelper::inputField() ?>
      <div>
        <label>Driver ID</label>
        <input name="driver_id" required>
      </div>
      <div>
        <label>Full Name</label>
        <input name="full_name" required>
      </div>
      <div>
        <label>Gender</label>
        <select name="gender" required>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>
      <div>
        <label>Car Color</label>
        <input name="car_color" required>
      </div>
      <div class="actions" style="margin-top:12px;">
        <button class="primary" type="submit">Save</button>
        <button type="button" id="closeAddDriver">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const openBtn = document.getElementById('openAddDriver');
    const closeBtn = document.getElementById('closeAddDriver');
    const modal = document.getElementById('driverModal');
    if (openBtn && modal) { openBtn.addEventListener('click', () => { modal.style.display = 'flex'; }); }
    if (closeBtn && modal) { closeBtn.addEventListener('click', () => { modal.style.display = 'none'; }); }
    modal?.addEventListener('click', (e) => { if (e.target === modal) { modal.style.display = 'none'; } });
  });
</script>