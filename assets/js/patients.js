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
    openRecordSaleModal: 'saleModal',
    openRecordSaleModalHero: 'saleModal',
    openReceiptModal: 'receiptModal',
    openReceiptModalRow: 'receiptModal',
    openReceiptModalRow2: 'receiptModal',
    openReceiptModalRow3: 'receiptModal',
    openPrescriptionModal: 'prescriptionModal',
    openPrescriptionModalHero: 'prescriptionModal',
    openPatientModal: 'patientModal',
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

  const saleForm = document.getElementById('saleForm');
  if (saleForm) {
    saleForm.addEventListener('submit', function (event) {
      event.preventDefault();
      closeModal('saleModal');
    });
  }

  const receiptForm = document.getElementById('receiptForm');
  if (receiptForm) {
    receiptForm.addEventListener('submit', function (event) {
      event.preventDefault();
      closeModal('receiptModal');
    });
  }

  const prescriptionForm = document.getElementById('prescriptionForm');
  if (prescriptionForm) {
    prescriptionForm.addEventListener('submit', function (event) {
      event.preventDefault();
      closeModal('prescriptionModal');
    });
  }

  const patientForm = document.getElementById('patientForm');
  if (patientForm) {
    patientForm.addEventListener('submit', function (event) {
      event.preventDefault();
      closeModal('patientModal');
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

  document.querySelectorAll('.open-sale-btn').forEach(function (button) {
    button.addEventListener('click', function () {
      const row = button.closest('tr');
      const patientField = document.getElementById('salePatient');
      const prescriptionField = document.getElementById('salePrescription');
      if (row && patientField) patientField.value = row.getAttribute('data-patient-name') || '';
      if (row && prescriptionField) prescriptionField.value = row.getAttribute('data-prescription') || '';
      openModal('saleModal');
    });
  });

  document.querySelectorAll('.view-prescription-btn').forEach(function (button) {
    button.addEventListener('click', function () {
      const row = button.closest('tr');
      const patientField = document.getElementById('prescriptionPatient');
      const drugField = document.getElementById('prescriptionDrug');
      const noteField = document.getElementById('prescriptionNote');
      if (row && patientField) patientField.value = row.getAttribute('data-patient-name') || '';
      if (row && drugField) drugField.value = row.getAttribute('data-prescription') || '';
      if (row && noteField) noteField.value = row.getAttribute('data-prescription-note') || '';
      openModal('prescriptionModal');
    });
  });
});
