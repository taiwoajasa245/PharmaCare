
document.addEventListener('DOMContentLoaded', function () {

  let currentStep = 0;
  let selectedRole = 'pharmacist';

  const screens   = document.querySelectorAll('.screen');
  const sideSteps = document.querySelectorAll('.side-step');

  window.goTo = function (next) {

    if (next === 3) {
      const email = document.getElementById('emailInput').value;
      document.getElementById('successSub').textContent =
        'Signed in as ' + cap(selectedRole) + ' · ' + email;
    }

    if (next === 2) {
      document.getElementById('passSub').textContent =
        'Signing in as ' + cap(selectedRole);
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

  // ── Helper: capitalise first letter ──
  function cap(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

});