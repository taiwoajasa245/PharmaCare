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
    openAddSupplierModal: 'supplierModal',
    openAddSupplierModalHero: 'supplierModal',
    openAddSupplierModalToolbar: 'supplierModal',
    openPurchaseOrderModal: 'orderModal',
    openPurchaseOrderModalHero: 'orderModal',
    openPurchaseOrderModalToolbar: 'orderModal',
    openLogDeliveryModal: 'deliveryModal',
    openEditSupplierModal: 'editSupplierModal',
    openProfileModalTop: 'profileModal',
    openProfileModalSidebar: 'profileModal'
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

  ['supplierForm', 'editSupplierForm', 'orderForm', 'deliveryForm', 'profileForm'].forEach(function (formId) {
    const form = document.getElementById(formId);
    if (form) {
      form.addEventListener('submit', function (event) {
        event.preventDefault();
        closeModal(formId.replace('Form', 'Modal'));
      });
    }
  });

  const logoutBtn = document.getElementById('logoutBtn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', function () {
      window.location.href = '../auth/logout.php';
    });
  }

  document.querySelectorAll('.edit-supplier-btn').forEach(function (button) {
    button.addEventListener('click', function () {
      const row = button.closest('tr');
      if (!row) return;
      const name = row.getAttribute('data-supplier-name') || '';
      const phone = row.getAttribute('data-supplier-phone') || '';
      const category = row.getAttribute('data-supplier-category') || '';
      const status = row.getAttribute('data-supplier-status') || '';

      const nameField = document.getElementById('editSupplierName');
      const phoneField = document.getElementById('editSupplierPhone');
      const categoryField = document.getElementById('editSupplierCategory');
      const statusField = document.getElementById('editSupplierStatus');

      if (nameField) nameField.value = name;
      if (phoneField) phoneField.value = phone;
      if (categoryField) categoryField.value = category;
      if (statusField) statusField.value = status;

      openModal('editSupplierModal');
    });
  });

  document.querySelectorAll('.order-supplier-btn').forEach(function (button) {
    button.addEventListener('click', function () {
      const row = button.closest('tr');
      const supplierField = document.getElementById('orderSupplier');
      const itemField = document.getElementById('orderItem');
      if (row && supplierField) supplierField.value = row.getAttribute('data-supplier-name') || '';
      if (row && itemField) itemField.value = row.getAttribute('data-supplier-category') || '';
      openModal('orderModal');
    });
  });

  document.querySelectorAll('.delivery-btn').forEach(function (button) {
    button.addEventListener('click', function () {
      const row = button.closest('tr');
      const supplierField = document.getElementById('deliverySupplier');
      const refField = document.getElementById('deliveryReference');
      if (row && supplierField) supplierField.value = row.getAttribute('data-supplier-name') || '';
      if (row && refField) refField.value = row.getAttribute('data-supplier-phone') || '';
      openModal('deliveryModal');
    });
  });
});
