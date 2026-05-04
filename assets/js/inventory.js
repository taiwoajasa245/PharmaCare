document.addEventListener('DOMContentLoaded', function () {
  const modeBtn = document.getElementById('modeBtn');
  const themeKey = 'pharmacare-theme';
  const feedbackEl = document.getElementById('inventoryFeedback');
  const tableBody = document.getElementById('inventoryTableBody');
  const badgeEl = document.getElementById('inventoryBadge');
  const searchInput = document.getElementById('inventorySearch');
  const categoryFilter = document.getElementById('categoryFilter');
  const statusFilter = document.getElementById('statusFilter');
  const sortFilter = document.getElementById('sortFilter');

  const statTotalDrugs = document.getElementById('invStatTotalDrugs');
  const statLowStock = document.getElementById('invStatLowStock');
  const statExpiringSoon = document.getElementById('invStatExpiringSoon');
  const statStockInToday = document.getElementById('invStatStockInToday');

  let selectedDrug = null;

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

  function statusChip(status) {
    if (status === 'low') {
      return '<span class="inv-chip warning">Low Stock</span>';
    }
    if (status === 'expiring') {
      return '<span class="inv-chip danger">Expiring</span>';
    }
    return '<span class="inv-chip success">In Stock</span>';
  }

  function renderInventory(items) {
    if (!tableBody) return;

    if (!items || items.length === 0) {
      tableBody.innerHTML = '<tr><td colspan="7" class="inv-note">No drugs found for this filter.</td></tr>';
      return;
    }

    tableBody.innerHTML = items.map(function (item) {
      return '<tr data-drug-id="' + Number(item.id) + '" data-drug-name="' + escapeHtml(item.name) + '" data-drug-category="' + escapeHtml(item.category) + '" data-drug-qty="' + Number(item.stock_qty) + '" data-drug-expiry="' + escapeHtml(item.expiry_date) + '">'
        + '<td><div class="inv-row-title">' + escapeHtml(item.name) + '</div><div class="inv-row-meta">Updated ' + escapeHtml(item.updated_label) + '</div></td>'
        + '<td>' + escapeHtml(item.category) + '</td>'
        + '<td>' + Number(item.stock_qty) + '</td>'
        + '<td>' + escapeHtml(item.expiry_label) + '</td>'
        + '<td>Main Store</td>'
        + '<td>' + statusChip(item.status) + '</td>'
        + '<td><div class="inv-actions">'
        + '<button class="edit-drug-btn" type="button">Edit</button>'
        + '<button class="modal-link-btn stock-in-row-btn" type="button">Stock In</button>'
        + '<button class="btn-add btn-secondary stock-out-row-btn" type="button">Stock Out</button>'
        + '<button class="delete-drug-btn" type="button">Delete</button>'
        + '</div></td>'
        + '</tr>';
    }).join('');
  }

  function renderSummary(summary) {
    if (statTotalDrugs) statTotalDrugs.textContent = String(summary.totalDrugs || 0);
    if (statLowStock) statLowStock.textContent = String(summary.lowStock || 0);
    if (statExpiringSoon) statExpiringSoon.textContent = String(summary.expiringSoon || 0);
    if (statStockInToday) statStockInToday.textContent = String(summary.stockInToday || 0);
  }

  async function loadInventory() {
    const params = new URLSearchParams();
    if (searchInput && searchInput.value.trim() !== '') params.set('search', searchInput.value.trim());
    if (categoryFilter && categoryFilter.value) params.set('category', categoryFilter.value);
    if (statusFilter && statusFilter.value) params.set('status', statusFilter.value);
    if (sortFilter && sortFilter.value) params.set('sort', sortFilter.value);

    const response = await fetch('../api/inventory.php?' + params.toString(), { headers: { Accept: 'application/json' } });
    const payload = await response.json().catch(function () { return null; });

    if (!response.ok || !payload || !payload.success) {
      throw new Error(payload && payload.message ? payload.message : 'Unable to load inventory data.');
    }

    renderSummary(payload.summary || {});
    renderInventory(payload.items || []);
  }

  async function submitActionForm(form, modalId) {
    const button = form.querySelector('button[type="submit"]');
    setButtonLoading(button, true);

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
        throw new Error(payload && payload.message ? payload.message : 'Unable to save this action.');
      }

      showFeedback(payload.message || 'Saved successfully.', 'success');
      closeModal(modalId);
      form.reset();
      selectedDrug = null;
      await loadInventory();
    } catch (error) {
      showFeedback(error.message || 'Unable to save this action.', 'error');
    } finally {
      setButtonLoading(button, false);
    }
  }

  async function deleteSelectedDrug() {
    const deleteDrugIdEl = document.getElementById('deleteDrugId');
    const id = Number(deleteDrugIdEl ? deleteDrugIdEl.value : 0);
    if (!id) {
      showFeedback('Select a drug to delete.', 'error');
      return;
    }

    const response = await fetch('../api/inventory.php', {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json'
      },
      body: new URLSearchParams({ action: 'delete', id: String(id) })
    });

    const payload = await response.json().catch(function () { return null; });
    if (!response.ok || !payload || !payload.success) {
      showFeedback(payload && payload.message ? payload.message : 'Unable to delete drug.', 'error');
      return;
    }

    showFeedback(payload.message || 'Drug deleted successfully.', 'success');
    closeModal('deleteDrugModal');
    selectedDrug = null;
    await loadInventory();
  }

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
      if (triggerId === 'openEditDrugModal' && !selectedDrug) {
        showFeedback('Select a drug row using an Edit button first.', 'error');
        return;
      }
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
    const spinner = addDrugForm.querySelector('.btn-spinner');
    if (spinner) spinner.hidden = true;
    addDrugForm.addEventListener('submit', function (event) {
      event.preventDefault();
      submitActionForm(addDrugForm, 'addDrugModal');
    });
  }

  const editDrugForm = document.getElementById('editDrugForm');
  if (editDrugForm) {
    const spinner = editDrugForm.querySelector('.btn-spinner');
    if (spinner) spinner.hidden = true;
    editDrugForm.addEventListener('submit', function (event) {
      event.preventDefault();
      submitActionForm(editDrugForm, 'editDrugModal');
    });
  }

  const stockInForm = document.getElementById('stockInForm');
  if (stockInForm) {
    const spinner = stockInForm.querySelector('.btn-spinner');
    if (spinner) spinner.hidden = true;
    stockInForm.addEventListener('submit', function (event) {
      event.preventDefault();
      submitActionForm(stockInForm, 'stockInModal');
    });
  }

  const stockOutForm = document.getElementById('stockOutForm');
  if (stockOutForm) {
    const spinner = stockOutForm.querySelector('.btn-spinner');
    if (spinner) spinner.hidden = true;
    stockOutForm.addEventListener('submit', function (event) {
      event.preventDefault();
      submitActionForm(stockOutForm, 'stockOutModal');
    });
  }

  const profileForm = document.getElementById('profileForm');
  if (profileForm) {
    const spinner = profileForm.querySelector('.btn-spinner');
    if (spinner) spinner.hidden = true;
    profileForm.addEventListener('submit', function (event) {
      event.preventDefault();
      const button = profileForm.querySelector('button[type="submit"]');
      setButtonLoading(button, true);
      fetch(profileForm.action, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          Accept: 'application/json'
        },
        body: new FormData(profileForm)
      }).then(function (response) {
        return response.json().then(function (payload) {
          return { ok: response.ok, payload: payload };
        });
      }).then(function (result) {
        if (!result.ok || !result.payload || !result.payload.success) {
          throw new Error(result.payload && result.payload.message ? result.payload.message : 'Unable to update profile.');
        }

        showFeedback(result.payload.message || 'Profile updated.', 'success');
        closeModal('profileModal');
      }).catch(function (error) {
        showFeedback(error.message || 'Unable to update profile.', 'error');
      }).finally(function () {
        setButtonLoading(button, false);
      });
    });
  }

  const logoutBtn = document.getElementById('logoutBtn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', function () {
      window.location.href = '../auth/logout.php';
    });
  }

  if (tableBody) {
    tableBody.addEventListener('click', function (event) {
      const row = event.target.closest('tr');
      if (!row) return;

      const payload = {
        id: Number(row.getAttribute('data-drug-id') || 0),
        name: row.getAttribute('data-drug-name') || '',
        category: row.getAttribute('data-drug-category') || '',
        qty: Number(row.getAttribute('data-drug-qty') || 0),
        expiry: row.getAttribute('data-drug-expiry') || ''
      };

      if (event.target.closest('.edit-drug-btn')) {
        selectedDrug = payload;
        const editDrugId = document.getElementById('editDrugId');
        const nameField = document.getElementById('editDrugName');
        const qtyField = document.getElementById('editDrugQty');
        const expiryField = document.getElementById('editDrugExpiry');
        const categoryField = document.getElementById('editDrugCategory');

        if (editDrugId) editDrugId.value = String(payload.id);
        if (nameField) nameField.value = payload.name;
        if (qtyField) qtyField.value = String(payload.qty);
        if (expiryField) expiryField.value = payload.expiry;
        if (categoryField) categoryField.value = payload.category;
        openModal('editDrugModal');
        return;
      }

      if (event.target.closest('.stock-in-row-btn')) {
        selectedDrug = payload;
        const idField = document.getElementById('stockInDrugId');
        const drugField = document.getElementById('stockInDrug');
        if (idField) idField.value = String(payload.id);
        if (drugField) drugField.value = payload.name;
        openModal('stockInModal');
        return;
      }

      if (event.target.closest('.stock-out-row-btn')) {
        selectedDrug = payload;
        const idField = document.getElementById('stockOutDrugId');
        const drugField = document.getElementById('stockOutDrug');
        if (idField) idField.value = String(payload.id);
        if (drugField) drugField.value = payload.name;
        openModal('stockOutModal');
        return;
      }

      if (event.target.closest('.delete-drug-btn')) {
        selectedDrug = payload;
        const label = document.getElementById('deleteDrugLabel');
        const idField = document.getElementById('deleteDrugId');
        if (label) label.textContent = payload.name || 'this item';
        if (idField) idField.value = String(payload.id);
        openModal('deleteDrugModal');
      }
    });
  }

  const confirmDeleteDrugBtn = document.getElementById('confirmDeleteDrugBtn');
  if (confirmDeleteDrugBtn) {
    confirmDeleteDrugBtn.addEventListener('click', function () {
      deleteSelectedDrug().catch(function (error) {
        showFeedback(error.message || 'Unable to delete drug.', 'error');
      });
    });
  }

  [searchInput, categoryFilter, statusFilter, sortFilter].forEach(function (el) {
    if (!el) return;
    el.addEventListener('change', function () {
      loadInventory().catch(function (error) {
        showFeedback(error.message || 'Unable to load inventory.', 'error');
      });
    });
  });

  if (searchInput) {
    searchInput.addEventListener('input', function () {
      loadInventory().catch(function (error) {
        showFeedback(error.message || 'Unable to load inventory.', 'error');
      });
    });
  }

  loadInventory().catch(function (error) {
    showFeedback(error.message || 'Unable to load inventory.', 'error');
  });
});
