<?php
// Login modal include.
$loginError = $loginError ?? '';
?>

<div class="modal" id="loginModal" aria-hidden="true">
  <div class="modal-backdrop" data-close></div>
  <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="loginModalTitle">
    <button class="modal-close" data-close aria-label="Close">×</button>
    <div class="modal-body">
      <div class="modal-logo">
        <div class="logo">⚕️ PharmaCare</div>
      </div>
      <h2 id="loginModalTitle">Sign in to PharmaCare</h2>
      <p class="subtitle">Enter your credentials to access the dashboard.</p>

      <?php if ($loginError): ?>
        <div class="auth-error"><?php echo htmlspecialchars($loginError); ?></div>
      <?php endif; ?>

      <div class="auth-success" id="loginSuccess" hidden></div>

      <form action="auth/login.php" method="POST" class="modal-form" onsubmit="return handleLoginSubmit(event)">
        <label class="field-label" for="modalLoginEmail">Email</label>
        <input type="email" name="email" id="modalLoginEmail" autocomplete="email" required />

        <label class="field-label" for="modalLoginPassword">Password</label>
        <input type="password" name="password" id="modalLoginPassword" autocomplete="current-password" required />

        <input type="hidden" name="role" value="pharmacist" />

        <button type="submit" class="btn-submit" data-loading-text="Signing in...">
          <span class="btn-label">Sign in</span>
          <span class="btn-spinner" aria-hidden="true"></span>
        </button>
      </form>
    </div>
  </div>
</div>
