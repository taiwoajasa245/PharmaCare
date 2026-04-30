<?php
// Login modal include
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

      <form action="auth/login.php" method="POST" class="modal-form">
        <label class="field-label">Email</label>
        <input type="email" name="email" id="modalEmail" required />

        <label class="field-label">Password</label>
        <input type="password" name="password" required />

        <input type="hidden" name="role" value="pharmacist" />

        <button type="submit" class="btn-submit">Sign In</button>
      </form>
    </div>
  </div>
</div>
