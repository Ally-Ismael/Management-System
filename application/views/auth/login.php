<?php
use App\Lib\Helpers\CsrfHelper;
?>
<h2>Login</h2>
<?php if (!empty($error)): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post">
  <?= CsrfHelper::inputField() ?>
  <div>
    <label>Email</label>
    <input type="email" name="email" required>
  </div>
  <div>
    <label>Password</label>
    <input type="password" name="password" required>
  </div>
  <button class="primary" type="submit">Login</button>
</form>
<p>
  <a href="/index.php?r=auth/register">Create an account</a>
</p>