<?php
session_start();
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
          <div class="badge">8 transactions today</div>
          <button class="btn-add" type="button" id="openRecordSaleModal">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Record Sale
          </button>
          <button class="btn-add btn-secondary" type="button" id="openReceiptModal">Generate Receipt</button>
          <button class="btn-add btn-secondary" type="button" id="openPrescriptionModal">Add Prescription</button>
          <button class="btn-add btn-secondary" type="button" id="openPatientModal">Add Patient</button>
          <button class="btn-add btn-secondary" type="button" id="openProfileModalTop">Profile</button>
          <button class="mode-btn" id="modeBtn" type="button" onclick="toggleMode()">🌙 Dark</button>
        </div>
      </div>

      <div class="scroll">
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
              <div class="stat-val">34</div>
              <div class="stat-sub" style="color:var(--color-text-success);">↑ 6 from yesterday</div>
            </div>
            <div class="stat-card info">
              <div class="stat-label">Receipts Generated</div>
              <div class="stat-val">31</div>
              <div class="stat-sub" style="color:var(--color-text-info);">3 pending</div>
            </div>
            <div class="stat-card warn">
              <div class="stat-label">Pending Prescriptions</div>
              <div class="stat-val">5</div>
              <div class="stat-sub" style="color:var(--color-text-warning);">Need attention</div>
            </div>
            <div class="stat-card danger">
              <div class="stat-label">Voided Sales</div>
              <div class="stat-val">2</div>
              <div class="stat-sub" style="color:var(--color-text-danger);">Check audit trail</div>
            </div>
          </div>
        </div>

        <div class="rx-grid">
          <div class="rx-panel">
            <div class="card-head">
              <div class="card-title">Recent Sales</div>
              <span class="card-link">View all</span>
            </div>
            <div class="rx-list">
              <div class="sale-row" data-patient-name="Fatima Abubakar" data-prescription="Paracetamol 500mg" data-prescription-note="2 tablets after meals">
                <div class="sale-av" style="background:#e6f1fb;color:#0c447c;">FA</div>
                <div>
                  <div class="sale-name">Fatima Abubakar</div>
                  <div class="sale-meta">Paracetamol 500mg · ₦2,500</div>
                </div>
                <span class="chip success">Paid</span>
                <div class="rx-actions">
                  <button class="chip-btn open-sale-btn" type="button">Record Again</button>
                  <button class="chip-btn" type="button" id="openReceiptModalRow">Receipt</button>
                </div>
              </div>
              <div class="sale-row" data-patient-name="Emeka Okonkwo" data-prescription="Amoxicillin 250mg" data-prescription-note="1 capsule three times daily">
                <div class="sale-av" style="background:#faeeda;color:#854f0b;">EO</div>
                <div>
                  <div class="sale-name">Emeka Okonkwo</div>
                  <div class="sale-meta">Amoxicillin 250mg · ₦6,400</div>
                </div>
                <span class="chip warning">Pending</span>
                <div class="rx-actions">
                  <button class="chip-btn open-sale-btn" type="button">Record Again</button>
                  <button class="chip-btn" type="button" id="openReceiptModalRow2">Receipt</button>
                </div>
              </div>
              <div class="sale-row" data-patient-name="Ngozi Kalu" data-prescription="Metformin 850mg" data-prescription-note="1 tablet daily after breakfast">
                <div class="sale-av" style="background:#eaf3de;color:#3b6d11;">NK</div>
                <div>
                  <div class="sale-name">Ngozi Kalu</div>
                  <div class="sale-meta">Metformin 850mg · ₦4,800</div>
                </div>
                <span class="chip info">Receipt Ready</span>
                <div class="rx-actions">
                  <button class="chip-btn open-sale-btn" type="button">Record Again</button>
                  <button class="chip-btn" type="button" id="openReceiptModalRow3">Receipt</button>
                </div>
              </div>
            </div>
          </div>

          <div class="rx-panel">
            <div class="card-head">
              <div class="card-title">Daily Transactions</div>
              <span class="card-link">Export</span>
            </div>
            <div class="tx-row">
              <div><div class="rx-name">Cash Sale</div><div class="rx-meta">Paracetamol</div></div>
              <span class="chip success">₦2,500</span>
            </div>
            <div class="tx-row">
              <div><div class="rx-name">POS Sale</div><div class="rx-meta">Amoxicillin</div></div>
              <span class="chip success">₦6,400</span>
            </div>
            <div class="tx-row">
              <div><div class="rx-name">Prescription Hold</div><div class="rx-meta">Metformin</div></div>
              <span class="chip warning">Pending</span>
            </div>
            <div class="tx-row">
              <div><div class="rx-name">Refund</div><div class="rx-meta">Ibuprofen return</div></div>
              <span class="chip danger">₦1,200</span>
            </div>
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
            <tbody>
              <tr data-patient-name="Fatima Abubakar" data-prescription="Paracetamol 500mg" data-prescription-note="2 tablets after meals">
                <td>
                  <div class="sale-name">Fatima Abubakar</div>
                  <div class="sale-meta">0703 555 1122</div>
                </td>
                <td>Paracetamol 500mg</td>
                <td>Prescription</td>
                <td><span class="chip success">Completed</span></td>
                <td>
                  <div class="rx-actions">
                    <button class="chip-btn open-sale-btn" type="button">Record Sale</button>
                    <button class="chip-btn view-prescription-btn" type="button">Prescription</button>
                  </div>
                </td>
              </tr>
              <tr data-patient-name="Emeka Okonkwo" data-prescription="Amoxicillin 250mg" data-prescription-note="1 capsule three times daily">
                <td>
                  <div class="sale-name">Emeka Okonkwo</div>
                  <div class="sale-meta">0802 444 7788</div>
                </td>
                <td>Amoxicillin 250mg</td>
                <td>Prescription</td>
                <td><span class="chip warning">Awaiting Pickup</span></td>
                <td>
                  <div class="rx-actions">
                    <button class="chip-btn open-sale-btn" type="button">Record Sale</button>
                    <button class="chip-btn view-prescription-btn" type="button">Prescription</button>
                  </div>
                </td>
              </tr>
              <tr data-patient-name="Ngozi Kalu" data-prescription="Metformin 850mg" data-prescription-note="1 tablet daily after breakfast">
                <td>
                  <div class="sale-name">Ngozi Kalu</div>
                  <div class="sale-meta">0809 222 3344</div>
                </td>
                <td>Metformin 850mg</td>
                <td>OTC</td>
                <td><span class="chip info">Receipt Ready</span></td>
                <td>
                  <div class="rx-actions">
                    <button class="chip-btn open-sale-btn" type="button">Record Sale</button>
                    <button class="chip-btn view-prescription-btn" type="button">Prescription</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="note-card">
          <div class="card-title">Prescription & sales workflow</div>
          <p class="inv-note" style="margin-top:10px;">Use Record Sale to capture a transaction, Generate Receipt for the printed copy, and Prescription to store the patient medication details that are linked to sales.</p>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/js/patients.js"></script>
  <?php include '../includes/patients_modals.php'; ?>
</body>
</html>
