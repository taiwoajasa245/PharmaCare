document.addEventListener('DOMContentLoaded', function () {
  const openBtn = document.getElementById('openLoginModal');
  const modal = document.getElementById('loginModal');
  const backdrop = modal && modal.querySelector('.modal-backdrop');
  const closeBtns = modal && modal.querySelectorAll('[data-close]');
  const modalEmail = document.getElementById('modalLoginEmail');
  const query = new URLSearchParams(window.location.search);

  function openModal() {
    if (!modal) return;
    modal.setAttribute('aria-hidden', 'false');
    modal.classList.add('open');

    // Copy email from signup step if user already typed it there.
    const mainEmail = document.getElementById('emailInput');
    if (modalEmail && mainEmail && mainEmail.value) {
      modalEmail.value = mainEmail.value;
    }

    document.body.style.overflow = 'hidden';
    setTimeout(() => {
      const firstField = modal.querySelector('input, button');
      if (firstField) firstField.focus();
    }, 80);
  }

  function closeModal() {
    if (!modal) return;
    modal.setAttribute('aria-hidden', 'true');
    modal.classList.remove('open');
    document.body.style.overflow = '';
  }

  if (openBtn) openBtn.addEventListener('click', openModal);
  if (backdrop) backdrop.addEventListener('click', closeModal);
  if (closeBtns) closeBtns.forEach(b => b.addEventListener('click', closeModal));

  if (query.get('auth') === 'login' || query.has('login_error')) {
    openModal();
  }

  // close on ESC
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeModal();
  });
});
