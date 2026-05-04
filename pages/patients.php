<?php
require_once __DIR__ . '/../auth/guard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PharmaCare — Patients</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/patients.css">
</head>
<body class="light-mode">
  <div class="shell">
    <?php include '../includes/sidebar.php'; ?>

    <div class="body">
      <div class="topbar">
        <div class="page-title">Sales & Prescription Management</div>
        <div class="topbar-right">
          <div class="badge" id="patientsBadge">Loading transactions...</div>
          <button class="mode-btn" id="modeBtn" type="button" onclick="toggleMode()">🌙 Dark</button>
        </div>
      </div>

      <div class="scroll" id="patientsRoot">
        <div class="hero-card">
          <div class="hero-copy">
            <h1>Record sales and link prescriptions</h1>
            <p>Use one page to record drug sales, generate receipts, track daily transactions, and store patient prescriptions tied to sale activity.</p>
          </div>
          <div class="hero-actions">
            <button class="btn-add btn-secondary" type="button" id="openRecordSaleModalHero">Record Sale</button>
            <button class="btn-add" type="button" id="openPrescriptionModalHero">Prescription ↗</button>
          </div>
        </div>

        <div>
          <div class="section-title">Today at a glance</div>
          <div class="stat-grid">
            <div class="stat-card">
              <div class="stat-label">Sales Today</div>
              <div class="stat-val" id="ptStatSalesToday">0</div>
              <div class="stat-sub" style="color:var(--color-text-success);">Transactions recorded today</div>
            </div>
            <div class="stat-card info">
              <div class="stat-label">Prescriptions Today</div>
              <div class="stat-val" id="ptStatPrescriptionsToday">0</div>
              <div class="stat-sub" style="color:var(--color-text-info);">Added today</div>
            </div>
            <div class="stat-card warn">
              <div class="stat-label">Pending Prescriptions</div>
              <div class="stat-val" id="ptStatPendingPrescriptions">0</div>
              <div class="stat-sub" style="color:var(--color-text-warning);">Need attention</div>
            </div>
            <div class="stat-card danger">
              <div class="stat-label">Voided Sales</div>
              <div class="stat-val" id="ptStatVoided">0</div>
              <div class="stat-sub" style="color:var(--color-text-danger);">Check audit trail</div>
            </div>
          </div>
        </div>

        <div class="rx-grid">
          <div class="rx-panel">
            <div class="card-head">
              <div class="card-title">Recent Sales</div>
              <span class="card-link" id="openRecordSaleModalList" role="button" tabindex="0">Record sale</span>
            </div>
            <div class="rx-list" id="recentSalesList"></div>
          </div>

          <div class="rx-panel">
            <div class="card-head">
              <div class="card-title">Daily Transactions</div>
              <span class="card-link">Export</span>
            </div>
            <div id="transactionsList"></div>
          </div>
        </div>

        <div class="table-card">
          <table class="rx-table">
            <thead>
              <tr>
                <th>Patient</th>
                <th>Prescription</th>
                <th>Sale Type</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="patientsTableBody"></tbody>
          </table>
        </div>

        <div class="inline-feedback" id="patientsFeedback" aria-live="polite" hidden></div>

        <div class="note-card">
          <div class="card-title">Prescription & sales workflow</div>
          <p class="inv-note" style="margin-top:10px;">Every patient action now saves to DB and updates this page instantly.</p>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/js/patients.js"></script>
  <?php include '../includes/patients_modals.php'; ?>
  <?php include '../includes/profile_modal.php'; ?>
</body>
</html>
