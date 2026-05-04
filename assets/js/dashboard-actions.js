document.addEventListener('DOMContentLoaded', function () {
  const openIds = {
    openSaleModal: 'saleModal',
    openSaleModalQuick: 'saleModal',
    openSaleModalLink: 'saleModal',
    openDrugModal: 'drugModal',
    openProfileModal: 'profileModal',
    openProfileModalSidebar: 'profileModal',
    openProfileModalTop: 'profileModal'
  };
  const feedbackEl = document.getElementById('dashboardFeedback');

  function showFeedback(message, type) {
    if (!feedbackEl) return;
    feedbackEl.hidden = false;
    feedbackEl.classList.remove('is-error', 'is-success');
    feedbackEl.classList.add(type === 'error' ? 'is-error' : 'is-success');
    feedbackEl.textContent = message;
  }

  function setButtonLoading(button, loading) {
    if (!button) return;
    const label = button.querySelector('.btn-label');
    const spinner = button.querySelector('.btn-spinner');

    if (loading) {
      button.disabled = true;
      button.classList.add('is-loading');
      if (label) {
        button.dataset.originalText = label.textContent;
        label.textContent = button.dataset.loadingText || 'Saving...';
      }
      if (spinner) spinner.hidden = false;
      return;
    }

    button.disabled = false;
    button.classList.remove('is-loading');
    if (label && button.dataset.originalText) {
      label.textContent = button.dataset.originalText;
    }
    if (spinner) spinner.hidden = true;
  }

  async function submitForm(form, modalId) {
    const submitButton = form.querySelector('button[type="submit"]');
    setButtonLoading(submitButton, true);

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          Accept: 'application/json'
        },
        body: new FormData(form)
      });

      const payload = await response.json().catch(function () { return null; });

      if (!response.ok || !payload || !payload.success) {
        const message = payload && payload.message ? payload.message : 'Something went wrong. Please try again.';
        throw new Error(message);
      }

      showFeedback(payload.message || 'Saved successfully.', 'success');
      closeModal(modalId);
      form.reset();

      if (typeof window.refreshDashboard === 'function') {
        window.refreshDashboard();
      }

      if (payload.user) {
        if (payload.user.name) {
          const firstName = String(payload.user.name).split(' ')[0];
          const title = document.querySelector('.page-title');
          const userNameEl = document.querySelector('.u-name');
          if (title) title.textContent = 'Good morning, ' + firstName + ' 👋';
          if (userNameEl) userNameEl.textContent = payload.user.name;
        }
        if (payload.user.role) {
          const userRoleEl = document.querySelector('.u-role');
          if (userRoleEl) userRoleEl.textContent = payload.user.role;
        }
      }
    } catch (error) {
      showFeedback(error.message || 'Unable to save right now.', 'error');
    } finally {
      setButtonLoading(submitButton, false);
    }
  }

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
    const spinner = saleForm.querySelector('.btn-spinner');
    if (spinner) spinner.hidden = true;
    saleForm.addEventListener('submit', function (event) {
      event.preventDefault();
      submitForm(saleForm, 'saleModal');
    });
  }

  const drugForm = document.getElementById('drugForm');
  if (drugForm) {
    const spinner = drugForm.querySelector('.btn-spinner');
    if (spinner) spinner.hidden = true;
    drugForm.addEventListener('submit', function (event) {
      event.preventDefault();
      submitForm(drugForm, 'drugModal');
    });
  }

  const profileForm = document.getElementById('profileForm');
  if (profileForm) {
    const spinner = profileForm.querySelector('.btn-spinner');
    if (spinner) spinner.hidden = true;
    profileForm.addEventListener('submit', function (event) {
      event.preventDefault();
      submitForm(profileForm, 'profileModal');
    });
  }

  const logoutBtn = document.getElementById('logoutBtn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', function () {
      window.location.href = '../auth/logout.php';
    });
  }
});
