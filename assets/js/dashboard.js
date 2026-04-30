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
  }

  const chartEl = document.getElementById('stockChart');
  if (chartEl && window.Chart) {
    new Chart(chartEl, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
          label: 'Stock count',
          data: [980, 1050, 1020, 1180, 1240, 1284],
          borderColor: '#185fa5',
          backgroundColor: 'rgba(24,95,165,0.08)',
          borderWidth: 2,
          pointRadius: 3,
          pointBackgroundColor: '#185fa5',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { color: 'rgba(128,128,128,0.1)' }, ticks: { font: { size: 11 }, color: '#888' } },
          y: { grid: { color: 'rgba(128,128,128,0.1)' }, ticks: { font: { size: 11 }, color: '#888' }, beginAtZero: false }
        }
      }
    });
  }
});
