<?php
use App\Lib\Helpers\CsrfHelper;
?>
<h2>Register</h2>
<?php if (!empty($error)): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if (!empty($success)): ?><div class="alert success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<form method="post">
  <?= CsrfHelper::inputField() ?>
  <div class="row">
    <div>
      <label>Name</label>
      <input type="text" name="name" required>
    </div>
    <div>
      <label>Email</label>
      <input type="email" name="email" required>
    </div>
  </div>
  <div class="row">
    <div>
      <label>Password</label>
      <input type="password" name="password" required>
    </div>
    <div>
      <label>Confirm Password</label>
      <input type="password" name="password2" required>
    </div>
  </div>
  <button class="primary" type="submit">Register</button>
</form>