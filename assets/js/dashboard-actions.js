document.addEventListener('DOMContentLoaded', function () {
  const openIds = {
    openSaleModal: 'saleModal',
    openDrugModal: 'drugModal',
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

  Object.keys(openIds).forEach(function (triggerId) {
    const trigger = document.getElementById(triggerId);
    if (trigger) {
      trigger.addEventListener('click', function () {
        openModal(openIds[triggerId]);
      });
      trigger.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' || event.key === ' ') {
          event.preventDefault();
          openModal(openIds[triggerId]);
        }
      });
    }
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

  const drugForm = document.getElementById('drugForm');
  if (drugForm) {
    drugForm.addEventListener('submit', function (event) {
      event.preventDefault();
      closeModal('drugModal');
    });
  }

  const saveProfileBtn = document.getElementById('saveProfileBtn');
  if (saveProfileBtn) {
    saveProfileBtn.addEventListener('click', function () {
      closeModal('profileModal');
    });
  }

  const logoutBtn = document.getElementById('logoutBtn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', function () {
      window.location.href = '../auth/logout.php';
    });
  }
});
