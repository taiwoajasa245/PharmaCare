document.addEventListener('DOMContentLoaded', function () {
  const modeBtn = document.getElementById('modeBtn');
  const themeKey = 'pharmacare-theme';

  function syncModeButton() {
    if (!modeBtn) return;
    modeBtn.textContent = document.body.classList.contains('light-mode') ? '🌙 Dark' : '☀ Light';
  }

  function applyTheme(theme) {
    document.body.classList.toggle('light-mode', theme === 'light');
  }

  function getPreferredTheme() {
    const savedTheme = localStorage.getItem(themeKey);
    if (savedTheme === 'light' || savedTheme === 'dark') {
      return savedTheme;
    }

    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  applyTheme(getPreferredTheme());
  syncModeButton();

  window.toggleMode = function () {
    const nextTheme = document.body.classList.contains('light-mode') ? 'dark' : 'light';
    localStorage.setItem(themeKey, nextTheme);
    applyTheme(nextTheme);
    syncModeButton();
  };

  const modalMap = {
    openAddDrugModal: 'addDrugModal',
    openAddDrugModalHero: 'addDrugModal',
    openAddDrugModalToolbar: 'addDrugModal',
    openEditDrugModal: 'editDrugModal',
    openStockInModal: 'stockInModal',
    openStockInModalRow: 'stockInModal',
    openStockInModalRow2: 'stockInModal',
    openStockOutModal: 'stockOutModal',
    openStockOutModalRow: 'stockOutModal',
    openStockOutModalRow2: 'stockOutModal',
    openProfileModal: 'profileModal',
    openProfileModalSidebar: 'profileModal',
    openProfileModalTop: 'profileModal'
  };

  function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  Object.keys(modalMap).forEach(function (triggerId) {
    const trigger = document.getElementById(triggerId);
    if (!trigger) return;
    trigger.addEventListener('click', function () {
      openModal(modalMap[triggerId]);
    });
  });

  document.querySelectorAll('[data-close]').forEach(function (button) {
    button.addEventListener('click', function () {
      closeModal(button.getAttribute('data-close'));
    });
  });

  document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
    overlay.addEventListener('click', function (event) {
      if (event.target === overlay) {
        closeModal(overlay.id);
      }
    });
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
      document.querySelectorAll('.modal-overlay.open').forEach(function (modal) {
        closeModal(modal.id);
      });
    }
  });

  const addDrugForm = document.getElementById('addDrugForm');
  if (addDrugForm) {
    addDrugForm.addEventListener('submit', function (event) {
      event.preventDefault();
      closeModal('addDrugModal');
    });
  }

  const editDrugForm = document.getElementById('editDrugForm');
  if (editDrugForm) {
    editDrugForm.addEventListener('submit', function (event) {
      event.preventDefault();
      closeModal('editDrugModal');
    });
  }

  const stockInForm = document.getElementById('stockInForm');
  if (stockInForm) {
    stockInForm.addEventListener('submit', function (event) {
      event.preventDefault();
      closeModal('stockInModal');
    });
  }

  const stockOutForm = document.getElementById('stockOutForm');
  if (stockOutForm) {
    stockOutForm.addEventListener('submit', function (event) {
      event.preventDefault();
      closeModal('stockOutModal');
    });
  }

  const profileForm = document.getElementById('profileForm');
  if (profileForm) {
    profileForm.addEventListener('submit', function (event) {
      event.preventDefault();
      closeModal('profileModal');
    });
  }

  const logoutBtn = document.getElementById('logoutBtn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', function () {
      window.location.href = '../auth/logout.php';
    });
  }

  document.querySelectorAll('.edit-drug-btn').forEach(function (button) {
    button.addEventListener('click', function () {
      const row = button.closest('tr');
      if (!row) return;
      const name = row.getAttribute('data-drug-name') || '';
      const qty = row.getAttribute('data-drug-qty') || '';
      const expiry = row.getAttribute('data-drug-expiry') || '';
      const category = row.getAttribute('data-drug-category') || '';

      const nameField = document.getElementById('editDrugName');
      const qtyField = document.getElementById('editDrugQty');
      const expiryField = document.getElementById('editDrugExpiry');
      const categoryField = document.getElementById('editDrugCategory');

      if (nameField) nameField.value = name;
      if (qtyField) qtyField.value = qty;
      if (expiryField) expiryField.value = expiry;
      if (categoryField) categoryField.value = category;

      openModal('editDrugModal');
    });
  });

  document.querySelectorAll('.delete-drug-btn').forEach(function (button) {
    button.addEventListener('click', function () {
      const row = button.closest('tr');
      const drugName = row ? row.getAttribute('data-drug-name') || 'this item' : 'this item';
      const label = document.getElementById('deleteDrugLabel');
      if (label) label.textContent = drugName;
      openModal('deleteDrugModal');
    });
  });
});
