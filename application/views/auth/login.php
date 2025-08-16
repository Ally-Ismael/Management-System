<?php
use App\Lib\Helpers\CsrfHelper;
?>
<h2>Login</h2>
<?php if (!empty($error)): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post">
  <?= CsrfHelper::inputField() ?>
  <div class="row">
    <div>
      <label>Email</label>
      <input name="email" type="email" required>
    </div>
    <div>
      <label>Password</label>
      <input name="password" type="password" required>
    </div>
  </div>
  <button class="primary" type="submit">Login</button>
</form>
<?php if (!empty($allowRegister)): ?>
  <a href="/index.php?r=auth/register">Create an account</a>
<?php endif; ?>