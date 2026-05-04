document.addEventListener('DOMContentLoaded', function () {
  const modeBtn = document.getElementById('modeBtn');
  const themeKey = 'pharmacare-theme';
  const dashboardRoot = document.getElementById('dashboardRoot');
  const feedbackEl = document.getElementById('dashboardFeedback');
  const badgeEl = document.getElementById('dashboardBadge');
  const listPatientsEl = document.getElementById('recentPatientsList');
  const listSalesEl = document.getElementById('recentSalesList');
  const listLowStockEl = document.getElementById('lowStockList');
  const statEls = {
    totalMedicines: document.getElementById('statTotalMedicines'),
    lowStockAlerts: document.getElementById('statLowStockAlerts'),
    expiredMedicines: document.getElementById('statExpiredMedicines'),
    totalPatients: document.getElementById('statTotalPatients'),
    totalSales: document.getElementById('statTotalSales')
  };
  const statSubMedicines = document.getElementById('statTotalMedicinesSub');

  let stockChart = null;

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

  function escapeHtml(value) {
    return String(value || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function initials(name) {
    const parts = String(name || '').trim().split(/\s+/).filter(Boolean);
    const first = parts[0] ? parts[0][0] : '';
    const second = parts[1] ? parts[1][0] : '';
    return (first + second).toUpperCase() || 'NA';
  }

  function setLoading(loading) {
    if (!dashboardRoot) return;
    dashboardRoot.classList.toggle('dashboard-loading', loading);
  }

  function showFeedback(message, type) {
    if (!feedbackEl) return;
    feedbackEl.hidden = false;
    feedbackEl.classList.remove('is-error', 'is-success');
    feedbackEl.classList.add(type === 'error' ? 'is-error' : 'is-success');
    feedbackEl.textContent = message;
  }

  function clearFeedback() {
    if (!feedbackEl) return;
    feedbackEl.hidden = true;
    feedbackEl.textContent = '';
    feedbackEl.classList.remove('is-error', 'is-success');
  }

  function renderPlaceholderRows(target, count) {
    if (!target) return;
    let html = '';
    for (let i = 0; i < count; i += 1) {
      html += '<div class="pt-row"><div class="pt-av skeleton-circle"></div><div class="pt-text-group"><div class="skeleton-line"></div><div class="skeleton-line short"></div></div><span class="pt-badge skeleton-pill"></span></div>';
    }
    target.innerHTML = html;
  }

  function renderPatients(rows) {
    if (!listPatientsEl) return;
    if (!rows || rows.length === 0) {
      listPatientsEl.innerHTML = '<div class="empty-state">No recent patients yet.</div>';
      return;
    }

    const html = rows.map(function (item) {
      const displayName = escapeHtml(item.full_name);
      const drug = escapeHtml(item.last_drug || 'N/A');
      const visitAt = escapeHtml(item.last_visit_at || 'N/A');
      const saleType = escapeHtml(item.last_sale_type || 'N/A');

      return '<div class="pt-row">'
        + '<div class="pt-av">' + initials(displayName) + '</div>'
        + '<div><div class="pt-name">' + displayName + '</div><div class="pt-meta">' + drug + ' · ' + visitAt + '</div></div>'
        + '<span class="pt-badge">' + saleType + '</span>'
        + '</div>';
    }).join('');

    listPatientsEl.innerHTML = html;
  }

  function renderSales(rows) {
    if (!listSalesEl) return;
    if (!rows || rows.length === 0) {
      listSalesEl.innerHTML = '<div class="empty-state">No sales recorded yet.</div>';
      return;
    }

    const html = rows.map(function (item) {
      const patient = escapeHtml(item.patient_name);
      const drug = escapeHtml(item.drug_name);
      const createdAt = escapeHtml(item.created_at || 'N/A');
      const saleType = escapeHtml(item.sale_type || 'N/A');

      return '<div class="pt-row">'
        + '<div class="pt-av">' + initials(patient) + '</div>'
        + '<div><div class="pt-name">' + patient + '</div><div class="pt-meta">' + drug + ' · Qty ' + Number(item.quantity || 0) + ' · ' + createdAt + '</div></div>'
        + '<span class="pt-badge">' + saleType + '</span>'
        + '</div>';
    }).join('');

    listSalesEl.innerHTML = html;
  }

  function renderLowStock(rows) {
    if (!listLowStockEl) return;
    if (!rows || rows.length === 0) {
      listLowStockEl.innerHTML = '<div class="empty-state">No low stock medicines right now.</div>';
      return;
    }

    const html = rows.map(function (item) {
      const name = escapeHtml(item.name);
      const category = escapeHtml(item.category);
      const expiry = escapeHtml(item.expiry_date || 'N/A');

      return '<div class="pt-row">'
        + '<div class="pt-av">' + initials(name) + '</div>'
        + '<div><div class="pt-name">' + name + '</div><div class="pt-meta">' + category + ' · Expires ' + expiry + '</div></div>'
        + '<span class="pt-badge">' + Number(item.stock_qty || 0) + '/' + Number(item.reorder_level || 0) + '</span>'
        + '</div>';
    }).join('');

    listLowStockEl.innerHTML = html;
  }

  function renderSummary(summary) {
    const values = summary || {};
    if (statEls.totalMedicines) statEls.totalMedicines.textContent = String(values.totalMedicines || 0);
    if (statEls.lowStockAlerts) statEls.lowStockAlerts.textContent = String(values.lowStockAlerts || 0);
    if (statEls.expiredMedicines) statEls.expiredMedicines.textContent = String(values.expiredMedicines || 0);
    if (statEls.totalPatients) statEls.totalPatients.textContent = String(values.totalPatients || 0);
    if (statEls.totalSales) statEls.totalSales.textContent = String(values.totalSales || 0);

    if (statSubMedicines) {
      statSubMedicines.textContent = (values.totalMedicines || 0) > 0
        ? 'Stock count across all medicines'
        : 'No medicines added yet';
    }

    if (badgeEl) {
      badgeEl.textContent = String(values.lowStockAlerts || 0) + ' low stock alerts';
    }
  }

  function renderChart(chartData) {
    const chartEl = document.getElementById('stockChart');
    if (!chartEl || !window.Chart) return;

    const labels = chartData && Array.isArray(chartData.labels) ? chartData.labels : [];
    const values = chartData && Array.isArray(chartData.values) ? chartData.values : [];

    if (stockChart) {
      stockChart.destroy();
    }

    stockChart = new Chart(chartEl, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Stock count',
          data: values,
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
          y: { grid: { color: 'rgba(128,128,128,0.1)' }, ticks: { font: { size: 11 }, color: '#888' }, beginAtZero: true }
        }
      }
    });
  }

  async function fetchDashboardData() {
    clearFeedback();
    setLoading(true);
    renderPlaceholderRows(listPatientsEl, 4);
    renderPlaceholderRows(listSalesEl, 4);
    renderPlaceholderRows(listLowStockEl, 4);

    try {
      const response = await fetch('../api/dashboard.php', {
        headers: { Accept: 'application/json' }
      });

      const payload = await response.json().catch(function () { return null; });

      if (!response.ok || !payload || !payload.success) {
        throw new Error(payload && payload.message ? payload.message : 'Unable to load dashboard data.');
      }

      renderSummary(payload.summary || {});
      renderPatients(payload.recentPatients || []);
      renderSales(payload.recentSales || []);
      renderLowStock(payload.lowStockDrugs || []);
      renderChart(payload.chart || {});
      window.dispatchEvent(new CustomEvent('dashboard:loaded'));
    } catch (error) {
      showFeedback(error.message || 'Unable to load dashboard data.', 'error');
      renderPatients([]);
      renderSales([]);
      renderLowStock([]);
    } finally {
      setLoading(false);
    }
  }

  window.refreshDashboard = fetchDashboardData;

  fetchDashboardData();
});
