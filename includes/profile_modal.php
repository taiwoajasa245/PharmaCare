<?php
$currentUserName = trim((string) ($_SESSION['user_name'] ?? 'Ade Oke'));
$currentUserEmail = trim((string) ($_SESSION['user_email'] ?? 'ade@pharmacare.com'));
$currentUserRole = trim((string) ($_SESSION['user_role'] ?? 'Pharmacist'));
$profileInitials = '';

foreach (array_slice(preg_split('/\s+/', $currentUserName), 0, 2) as $part) {
	$profileInitials .= strtoupper(substr($part, 0, 1));
}

if ($profileInitials === '') {
	$profileInitials = 'AO';
}
?>
<div class="modal-overlay" id="profileModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="profileModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="profileModalTitle">Profile</div>
      <button class="modal-close" type="button" data-close="profileModal" aria-label="Close">×</button>
    </div>

    <form id="profileForm" action="../api/profile.php" method="POST">
      <div class="modal-profile">
        <div class="profile-badge"><?php echo htmlspecialchars($profileInitials); ?></div>
        <div>
          <div class="modal-field">
            <label for="profileName">Full Name</label>
            <input id="profileName" name="full_name" type="text" value="<?php echo htmlspecialchars($currentUserName); ?>" required>
          </div>
          <div class="modal-field">
            <label for="profileRole">Role</label>
            <input id="profileRole" name="role" type="text" value="<?php echo htmlspecialchars($currentUserRole); ?>" required>
          </div>
          <div class="modal-field">
            <label for="profileEmail">Email</label>
            <input id="profileEmail" name="email" type="email" value="<?php echo htmlspecialchars($currentUserEmail); ?>" required>
          </div>
        </div>
      </div>

      <div class="modal-actions" style="justify-content: space-between; flex-wrap: wrap;">
        <button class="modal-link-btn modal-danger" type="button" id="logoutBtn">Logout</button>
        <button class="btn-add" type="submit" data-loading-text="Saving profile...">
          <span class="btn-label">Update Profile</span>
          <span class="btn-spinner" aria-hidden="true"></span>
        </button>
      </div>
    </form>
  </div>
</div>
