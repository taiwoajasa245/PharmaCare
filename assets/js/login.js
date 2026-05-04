
document.addEventListener('DOMContentLoaded', function () {

  let currentStep = 0;
  let selectedRole = 'pharmacist';
  const startStep = Number(document.body.dataset.startStep || 0);

  const screens   = document.querySelectorAll('.screen');
  const sideSteps = document.querySelectorAll('.side-step');
  const authForms = document.querySelectorAll('form[action="auth/login.php"], form[action="auth/register.php"]');

  function setButtonLoading(button, isLoading, loadingText) {
    if (!button) return;
    const label = button.querySelector('.btn-label');
    const spinner = button.querySelector('.btn-spinner');

    if (isLoading) {
      button.disabled = true;
      button.classList.add('is-loading');
      if (label) {
        button.dataset.originalText = label.textContent;
        label.textContent = loadingText || button.dataset.loadingText || 'Loading...';
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

  function showInlineMessage(form, type, message) {
    if (!form) return;
    const target = form.closest('.screen-container, .modal-body');
    if (!target) return;

    let box = target.querySelector('.form-inline-message');
    if (!box) {
      box = document.createElement('div');
      box.className = 'form-inline-message';
      target.insertBefore(box, form);
    }

    box.classList.remove('is-error', 'is-success', 'is-loading');
    if (type === 'success') {
      box.classList.add('is-success');
    } else if (type === 'loading') {
      box.classList.add('is-loading');
    } else {
      box.classList.add('is-error');
    }
    box.textContent = message;
    box.hidden = false;
  }

  async function submitAuthForm(form, successMessage, successRedirect) {
    const button = form.querySelector('button[type="submit"]');
    const loadingText = button && button.dataset.loadingText ? button.dataset.loadingText : 'Loading...';

    setButtonLoading(button, true, loadingText);
    showInlineMessage(form, 'loading', loadingText);

    const response = await fetch(form.action, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      body: new FormData(form)
    });

    const payload = await response.json().catch(() => null);

    if (!response.ok || !payload || !payload.success) {
      const errorMessage = payload && payload.message ? payload.message : 'Something went wrong. Please try again.';
      showInlineMessage(form, 'error', errorMessage);
      setButtonLoading(button, false);
      return;
    }

    showInlineMessage(form, 'success', payload.message || successMessage);
    setButtonLoading(button, false);

    if (successRedirect) {
      window.location.href = payload.redirect || successRedirect;
    }
  }

  window.handleSignupSubmit = function (event) {
    event.preventDefault();
    const form = event.target;
    syncHiddenFields();
    submitAuthForm(form, 'Account created successfully.', '../pages/dashboard.php');
    return false;
  };

  window.handleLoginSubmit = function (event) {
    event.preventDefault();
    const form = event.target;
    submitAuthForm(form, 'Signed in successfully.', '../pages/dashboard.php');
    return false;
  };

  window.syncHiddenFields = function () {
    const emailInput = document.getElementById('emailInput');
    const emailField = document.getElementById('emailField');
    const roleField = document.getElementById('roleField');

    if (emailInput && emailField) {
      emailField.value = emailInput.value;
    }

    if (roleField) {
      roleField.value = selectedRole;
    }
  };

  window.goTo = function (next) {

    if (next === 3) {
      const email = document.getElementById('emailInput').value;
      document.getElementById('successSub').textContent =
        'Signed in as ' + cap(selectedRole) + ' · ' + email;
    }

    if (next === 2) {
      document.getElementById('passSub').textContent =
        'Signing up as ' + cap(selectedRole);

      syncHiddenFields();
    }

    screens[currentStep].classList.remove('active');
    screens[currentStep].classList.add('exit');
    setTimeout(() => screens[currentStep].classList.remove('exit'), 350);

    if (next > currentStep) {
      sideSteps[currentStep].classList.remove('active');
      sideSteps[currentStep].classList.add('done');
    } else {
      sideSteps[currentStep].classList.remove('active', 'done');
      sideSteps[next].classList.remove('done');
    }


    currentStep = next;
    screens[currentStep].classList.add('active');
    sideSteps[currentStep].classList.add('active');
    sideSteps[currentStep].classList.remove('done');

    // Keep step numbers visible as numbers until completed.
    sideSteps.forEach(function (step, index) {
      const num = step.querySelector('.step-num');
      if (!num) return;
      if (step.classList.contains('done')) {
        num.innerHTML = '✓';
      } else {
        num.textContent = String(index + 1);
      }
    });
  };

  // ── Role card selection ──
  document.querySelectorAll('.role-card').forEach(function (card) {
    card.addEventListener('click', function () {
      document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
      card.classList.add('selected');
      selectedRole = card.dataset.role;
      // Sync the hidden form field
      document.getElementById('roleField').value = selectedRole;
    });
  });

  // ── Dark / Light toggle ──
  const modeBtn = document.getElementById('modeToggle');
  modeBtn.addEventListener('click', function () {
    const isLight = document.body.classList.toggle('light');
    modeBtn.textContent = isLight ? '🌙 Dark mode' : '☀️ Light mode';
    localStorage.setItem('theme', isLight ? 'light' : 'dark');
  });

  // Restore saved theme
  if (localStorage.getItem('theme') === 'light') {
    document.body.classList.add('light');
    modeBtn.textContent = '🌙 Dark mode';
  }

  if (startStep > 0) {
    goTo(startStep);
  } else {
    syncHiddenFields();
  }

  authForms.forEach(function (form) {
    const button = form.querySelector('button[type="submit"]');
    if (button) {
      const spinner = button.querySelector('.btn-spinner');
      if (spinner) spinner.hidden = true;
    }
  });

  // ── Helper: capitalise first letter ──
  function cap(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

});