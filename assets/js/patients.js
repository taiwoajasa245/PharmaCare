document.addEventListener('DOMContentLoaded', function () {
  const modeBtn = document.getElementById('modeBtn');
  const themeKey = 'pharmacare-theme';
  const feedbackEl = document.getElementById('patientsFeedback');
  const badgeEl = document.getElementById('patientsBadge');
  const recentSalesListEl = document.getElementById('recentSalesList');
  const transactionsListEl = document.getElementById('transactionsList');
  const patientsTableBodyEl = document.getElementById('patientsTableBody');

  const statSalesToday = document.getElementById('ptStatSalesToday');
  const statPrescriptionsToday = document.getElementById('ptStatPrescriptionsToday');
  const statPendingPrescriptions = document.getElementById('ptStatPendingPrescriptions');
  const statVoided = document.getElementById('ptStatVoided');

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

  function ensureDrugOption(select, value) {
    if (!select || !value) return;
    const exists = Array.from(select.options).some(function (option) {
      return option.value === value;
    });
    if (!exists) {
      const option = document.createElement('option');
      option.value = value;
      option.textContent = value;
      select.appendChild(option);
    }
    select.value = value;
  }

  function updateDrugSelect(select, items) {
    if (!select) return;
    const options = (items || []).map(function (item) {
      const name = String(item.name || '');
      return '<option value="' + name.replace(/"/g, '&quot;') + '">' + name + '</option>';
    }).join('');

    select.innerHTML = '<option value="" disabled selected>Select drug</option>'
      + (options || '<option value="" disabled>No drugs available</option>');
  }

  async function loadDrugOptions() {
    const saleSelect = document.getElementById('saleDrug');
    const prescriptionSelect = document.getElementById('prescriptionDrug');
    if (!saleSelect && !prescriptionSelect) return;

    try {
      const response = await fetch('../api/inventory.php?sort=name_asc', { headers: { Accept: 'application/json' } });
      const payload = await response.json().catch(function () { return null; });

      if (!response.ok || !payload || !payload.success) {
        throw new Error(payload && payload.message ? payload.message : 'Unable to load drugs.');
      }

      updateDrugSelect(saleSelect, payload.items || []);
      updateDrugSelect(prescriptionSelect, payload.items || []);
    } catch (error) {
      showFeedback(error.message || 'Unable to load drugs.', 'error');
    }
  }

  function showFeedback(message, type, modalId) {
    if (modalId) {
      const errorEl = document.getElementById(modalId + 'Error');
      if (errorEl) {
        errorEl.hidden = false;
        errorEl.classList.remove('is-error', 'is-success');
        errorEl.classList.add(type === 'error' ? 'is-error' : 'is-success');
        errorEl.textContent = message;
        return;
      }
    }
    if (!feedbackEl) return;
    feedbackEl.hidden = false;
    feedbackEl.classList.remove('is-error', 'is-success');
    feedbackEl.classList.add(type === 'error' ? 'is-error' : 'is-success');
    feedbackEl.textContent = message;
  }

  function statusClass(status) {
    const value = String(status || '').toLowerCase();
    if (value.includes('awaiting') || value.includes('pending')) return 'warning';
    if (value.includes('void') || value.includes('failed')) return 'danger';
    if (value.includes('receipt')) return 'info';
    return 'success';
  }

  function renderSummary(summary) {
    if (statSalesToday) statSalesToday.textContent = String(summary.salesToday || 0);
    if (statPrescriptionsToday) statPrescriptionsToday.textContent = String(summary.prescriptionsToday || 0);
    if (statPendingPrescriptions) statPendingPrescriptions.textContent = String(summary.pendingPrescriptions || 0);
    if (statVoided) statVoided.textContent = String(summary.voidedSales || 0);
    if (badgeEl) badgeEl.textContent = String(summary.salesToday || 0) + ' transactions today';
  }

  function renderRecentSales(rows) {
    if (!recentSalesListEl) return;
    if (!rows || rows.length === 0) {
      recentSalesListEl.innerHTML = '<div class="inv-note">No sales yet.</div>';
      return;
    }

    recentSalesListEl.innerHTML = rows.slice(0, 6).map(function (item) {
      const status = escapeHtml(item.status || 'Completed');
      const cls = statusClass(status);
      return '<div class="sale-row">'
        + '<div class="sale-av">' + initials(item.patient_name) + '</div>'
        + '<div><div class="sale-name">' + escapeHtml(item.patient_name) + '</div><div class="sale-meta">' + escapeHtml(item.drug_name) + ' · Qty ' + Number(item.quantity || 0) + '</div></div>'
        + '<span class="chip ' + cls + '">' + status + '</span>'
        + '<div class="rx-actions">'
        + '<button class="chip-btn open-sale-btn" type="button" data-patient-name="' + escapeHtml(item.patient_name) + '" data-drug-name="' + escapeHtml(item.drug_name) + '" data-notes="' + escapeHtml(item.notes || '') + '">Record Again</button>'
        + '</div></div>';
    }).join('');
  }

  function renderTransactions(rows) {
    if (!transactionsListEl) return;
    if (!rows || rows.length === 0) {
      transactionsListEl.innerHTML = '<div class="inv-note">No transactions yet.</div>';
      return;
    }

    transactionsListEl.innerHTML = rows.slice(0, 5).map(function (item) {
      const status = escapeHtml(item.status || 'Completed');
      const cls = statusClass(status);
      return '<div class="tx-row"><div><div class="rx-name">' + escapeHtml(item.sale_type) + ' Sale</div><div class="rx-meta">' + escapeHtml(item.drug_name) + ' · ' + escapeHtml(item.created_at) + '</div></div><span class="chip ' + cls + '">' + status + '</span></div>';
    }).join('');
  }

  function renderPatientsTable(rows) {
    if (!patientsTableBodyEl) return;
    if (!rows || rows.length === 0) {
      patientsTableBodyEl.innerHTML = '<tr><td colspan="5" class="inv-note">No patients available.</td></tr>';
      return;
    }

    patientsTableBodyEl.innerHTML = rows.map(function (item) {
      const status = escapeHtml(item.status || 'Completed');
      const cls = statusClass(status);

      return '<tr data-patient-id="' + Number(item.id) + '" data-patient-name="' + escapeHtml(item.full_name) + '" data-prescription="' + escapeHtml(item.last_drug || '') + '" data-prescription-note="' + escapeHtml(item.prescription_note || item.notes || '') + '">'
        + '<td><div class="sale-name">' + escapeHtml(item.full_name) + '</div><div class="sale-meta">' + escapeHtml(item.phone || '') + '</div></td>'
        + '<td>' + escapeHtml(item.last_drug || 'N/A') + '</td>'
        + '<td>' + escapeHtml(item.last_sale_type || 'N/A') + '</td>'
        + '<td><span class="chip ' + cls + '">' + status + '</span></td>'
        + '<td><div class="rx-actions">'
        + '<button class="chip-btn open-sale-btn" type="button" data-patient-name="' + escapeHtml(item.full_name) + '" data-drug-name="' + escapeHtml(item.last_drug || '') + '" data-notes="' + escapeHtml(item.notes || '') + '">Record Sale</button>'
        + '<button class="chip-btn view-prescription-btn" type="button" data-patient-name="' + escapeHtml(item.full_name) + '" data-drug-name="' + escapeHtml(item.last_drug || '') + '" data-notes="' + escapeHtml(item.prescription_note || item.notes || '') + '">Prescription</button>'
        + '<button class="chip-btn delete-patient-btn" type="button" data-patient-id="' + Number(item.id) + '" data-patient-name="' + escapeHtml(item.full_name) + '">Delete</button>'
        + '</div></td></tr>';
    }).join('');
  }

  async function loadPatientsPage() {
    const response = await fetch('../api/patients.php', { headers: { Accept: 'application/json' } });
    const payload = await response.json().catch(function () { return null; });

    if (!response.ok || !payload || !payload.success) {
      throw new Error(payload && payload.message ? payload.message : 'Unable to load patient data.');
    }

    renderSummary(payload.summary || {});
    renderRecentSales(payload.recentSales || []);
    renderTransactions(payload.recentSales || []);
    renderPatientsTable(payload.patients || []);
  }

  async function submitModalForm(form, modalId) {
    const button = form.querySelector('button[type="submit"]');
    setButtonLoading(button, true);

    try {
      const actionUrl = form.getAttribute('action') || form.action;
      const response = await fetch(actionUrl, {
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

      showFeedback(payload.message || 'Saved successfully.', 'success', modalId);
      form.reset();
      
      // Force modal close after brief delay to ensure form is reset first
      setTimeout(function() {
        closeModal(modalId);
        loadPatientsPage().catch(function(error) {
          console.error('Failed to reload patients:', error.message);
        });
      }, 300);
      if (typeof window.refreshDashboard === 'function') {
        window.refreshDashboard();
      }
    } catch (error) {
      showFeedback(error.message || 'Unable to save this action.', 'error', modalId);
    } finally {
      setButtonLoading(button, false);
    }
  }

  const modalMap = {
    openRecordSaleModal: 'saleModal',
    openRecordSaleModalHero: 'saleModal',
    openRecordSaleModalList: 'saleModal',
    openPrescriptionModal: 'prescriptionModal',
    openPrescriptionModalHero: 'prescriptionModal',
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
    const errorEl = document.getElementById(modalId + 'Error');
    if (errorEl) errorEl.hidden = true;
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
    const spinner = saleForm.querySelector('.btn-spinner');
    if (spinner) spinner.hidden = true;
    saleForm.addEventListener('submit', function (event) {
      event.preventDefault();
      submitModalForm(saleForm, 'saleModal');
    });
  }

  const prescriptionForm = document.getElementById('prescriptionForm');
  if (prescriptionForm) {
    const spinner = prescriptionForm.querySelector('.btn-spinner');
    if (spinner) spinner.hidden = true;
    prescriptionForm.addEventListener('submit', function (event) {
      event.preventDefault();
      submitModalForm(prescriptionForm, 'prescriptionModal');
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
        showFeedback(result.payload.message || 'Profile updated.', 'success', 'profileModal');
        closeModal('profileModal');
      }).catch(function (error) {
        showFeedback(error.message || 'Unable to update profile.', 'error', 'profileModal');
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

  document.addEventListener('click', function (event) {
    const saleBtn = event.target.closest('.open-sale-btn');
    if (saleBtn) {
      const patientField = document.getElementById('salePatient');
      const drugField = document.getElementById('saleDrug');
      const notesField = document.getElementById('salePrescription');
      if (patientField) patientField.value = saleBtn.getAttribute('data-patient-name') || '';
        if (drugField) ensureDrugOption(drugField, saleBtn.getAttribute('data-drug-name') || '');
      if (notesField) notesField.value = saleBtn.getAttribute('data-notes') || '';
      openModal('saleModal');
      return;
    }

    const rxBtn = event.target.closest('.view-prescription-btn');
    if (rxBtn) {
      const patientField = document.getElementById('prescriptionPatient');
      const drugField = document.getElementById('prescriptionDrug');
      const noteField = document.getElementById('prescriptionNote');
      if (patientField) patientField.value = rxBtn.getAttribute('data-patient-name') || '';
        if (drugField) ensureDrugOption(drugField, rxBtn.getAttribute('data-drug-name') || '');
      if (noteField) noteField.value = rxBtn.getAttribute('data-notes') || '';
      openModal('prescriptionModal');
      return;
    }

    const deleteBtn = event.target.closest('.delete-patient-btn');
    if (deleteBtn) {
      const idField = document.getElementById('deletePatientId');
      const label = document.getElementById('deletePatientLabel');
      if (idField) idField.value = deleteBtn.getAttribute('data-patient-id') || '';
      if (label) label.textContent = deleteBtn.getAttribute('data-patient-name') || 'this patient';
      openModal('deletePatientModal');
    }
  });

  const confirmDeletePatientBtn = document.getElementById('confirmDeletePatientBtn');
  if (confirmDeletePatientBtn) {
    confirmDeletePatientBtn.addEventListener('click', function () {
      const idField = document.getElementById('deletePatientId');
      const id = Number(idField ? idField.value : 0);
      if (!id) {
        showFeedback('Select a patient to delete.', 'error', 'deletePatientModal');
        return;
      }

      fetch('../api/patients.php', {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          Accept: 'application/json'
        },
        body: new URLSearchParams({ action: 'delete_patient', id: String(id) })
      }).then(function (response) {
        return response.json().then(function (payload) {
          return { ok: response.ok, payload: payload };
        });
      }).then(function (result) {
        if (!result.ok || !result.payload || !result.payload.success) {
          throw new Error(result.payload && result.payload.message ? result.payload.message : 'Unable to delete patient.');
        }

        showFeedback(result.payload.message || 'Patient deleted.', 'success', 'deletePatientModal');
        setTimeout(function () {
          closeModal('deletePatientModal');
          loadPatientsPage().catch(function (error) {
            console.error('Failed to reload patients:', error.message);
          });
        }, 300);
      }).catch(function (error) {
        showFeedback(error.message || 'Unable to delete patient.', 'error', 'deletePatientModal');
      });
    });
  }

  loadPatientsPage().catch(function (error) {
    showFeedback(error.message || 'Unable to load patient page.', 'error');
  });

  loadDrugOptions();
});
