<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: pages/dashboard.php");
    exit();
}
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PharmaCare — Sign In</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

<div class="layout">

  <!-- ── Sidebar ── -->
  <aside class="sidebar">
    <div class="logo">⚕️ PharmaCare</div>
    <div class="step-label">Sign in to your account</div>

    <div class="side-step active" id="si-0">
      <div class="step-num">1</div>
      <span class="step-name">Email</span>
    </div>
    <div class="side-step" id="si-1">
      <div class="step-num">2</div>
      <span class="step-name">Role</span>
    </div>
    <div class="side-step" id="si-2">
      <div class="step-num">3</div>
      <span class="step-name">Password</span>
    </div>
    <div class="side-step" id="si-3">
      <div class="step-num">4</div>
      <span class="step-name">Access</span>
    </div>
  </aside>

  <!-- ── Main ── -->
    <div class="main">

    <div class="topbar">
      <span>Have an account?</span>
      <button class="btn-outline" id="openLoginModal">Login</button>
      <button class="btn-toggle" id="modeToggle">☀️ Light mode</button>
    </div>
    <div class="screens">
  <!-- Screen 1: Email -->
  <div class="screen active" id="sc-0">
    <div class="screen-container">
      <h1>What's your email address?</h1>
      <p class="subtitle">We'll use this to find your account.</p>
      <div class="field-label">Email address</div>
      <input type="email" id="emailInput" class="field-input" placeholder="you@pharmacare.com"/>
      <button class="btn-submit" onclick="goTo(1)">Continue</button>
    </div>
  </div>

  <!-- Screen 2: Role -->
  <div class="screen" id="sc-1">
    <div class="screen-container">
      <h1>What's your role at the pharmacy?</h1>
      <p class="subtitle">You can update this from settings later.</p>
      
      <div class="role-card selected" data-role="pharmacist">
        <div>
          <div class="role-name">Pharmacist</div>
          <div class="role-desc">Manage inventory, dispense medicine, serve patients</div>
        </div>
        <div class="check">
          <svg width="10" height="10" viewBox="0 0 12 12" fill="none">
            <path d="M2 6l3 3 5-5" stroke="#000" stroke-width="1.8" stroke-linecap="round"/>
          </svg>
        </div>
      </div>
      
      <!-- Add other role cards... -->
      
      <button class="btn-submit" onclick="goTo(2)">Continue</button>
      <button class="btn-back" onclick="goTo(0)">← Back</button>
    </div>
  </div>

  <!-- Screen 3: Password -->
  <div class="screen" id="sc-2">
    <div class="screen-container">
      <h1>Enter your password</h1>
      <p class="subtitle" id="passSub">Signing in as Pharmacist</p>
      
      <form action="#" method="POST" onsubmit="event.preventDefault(); syncHiddenFields(); goTo(3);">
        <input type="hidden" name="email" id="emailField"/>
        <input type="hidden" name="role" id="roleField" value="pharmacist"/>
        
        <div class="field-label">Password</div>
        <input type="password" name="password" class="field-input" placeholder="••••••••" required/>
        
        <?php if ($error): ?>
          <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <button type="submit" class="btn-submit">Sign In</button>
      </form>
      
      <button class="btn-back" onclick="goTo(1)">← Back</button>
    </div>
  </div>

  <!-- Screen 4: Success -->
  <div class="screen" id="sc-3">
    <div class="screen-container" style="text-align: center;">
      <div class="success-icon" style="margin: 0 auto 20px auto;">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
          <path d="M5 12l4 4L19 7" stroke="#000" stroke-width="2.5" stroke-linecap="round"/>
        </svg>
      </div>
      <h1>Welcome to PharmaCare</h1>
      <p class="subtitle" id="successSub">Loading your dashboard...</p>
      <button
        type="button"
        class="btn-submit"
        style="margin-top: 8px;"
        onclick="window.location.href='pages/dashboard.php'"
      >
        Go to dashboard ↗
      </button>
    </div>
  </div>
</div>
 <div class="footer">
      <span>PharmaCare v1.0</span>
      <div class="footer-links">
        <a href="#">Privacy policy</a>
        <a href="#">Terms of service</a>
      </div>
    </div>
  </div>
</div>

<script src="assets/js/login.js"></script>
<script src="assets/js/modal.js"></script>
<?php include 'includes/login_modal.php'; ?>
</body>
</html>