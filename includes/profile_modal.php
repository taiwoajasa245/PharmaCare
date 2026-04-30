<div class="modal-overlay" id="profileModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="profileModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="profileModalTitle">Profile</div>
      <button class="modal-close" type="button" data-close="profileModal" aria-label="Close">×</button>
    </div>

    <div class="modal-profile">
      <div class="profile-badge">AO</div>
      <div>
        <div class="modal-field">
          <label for="profileName">Full Name</label>
          <input id="profileName" type="text" value="Ade Oke">
        </div>
        <div class="modal-field">
          <label for="profileRole">Role</label>
          <input id="profileRole" type="text" value="Pharmacist">
        </div>
        <div class="modal-field">
          <label for="profileEmail">Email</label>
          <input id="profileEmail" type="email" value="ade@pharmacare.com">
        </div>
      </div>
    </div>

    <div class="modal-actions" style="justify-content: space-between; flex-wrap: wrap;">
      <button class="modal-link-btn modal-danger" type="button" id="logoutBtn">Logout</button>
      <button class="btn-add" type="button" id="saveProfileBtn">Update Profile</button>
    </div>
  </div>
</div>
