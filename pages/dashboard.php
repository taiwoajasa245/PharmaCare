<?php
require_once __DIR__ . '/../auth/guard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PharmaCare — Dashboard</title>
  <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body class="light-mode">
  <div class="shell">
    <?php include '../includes/sidebar.php'; ?>

    <div class="body">
      <?php include '../includes/header.php'; ?>

      <div class="scroll dashboard-loading" id="dashboardRoot">
        <div class="quick-add">
          <div class="qa-icon">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 1v14M1 8h14" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
          </div>
          <div class="qa-text">
            <div class="qa-title">Quick Add Sale</div>
            <div class="qa-sub">Record a new prescription or OTC sale in seconds</div>
          </div>
          <button class="qa-btn" type="button" id="openSaleModalQuick">Add Sale ↗</button>
        </div>

        <div class="stat-grid">
          <div class="stat-card">
            <div class="stat-label">Total Medicines</div>
            <div class="stat-val" id="statTotalMedicines"><span class="skeleton-line"></span></div>
            <div class="stat-sub" id="statTotalMedicinesSub" style="color:var(--color-text-success, #15803d);">Loading...</div>
          </div>
          <div class="stat-card warn">
            <div class="stat-label">Low Stock Alerts</div>
            <div class="stat-val" id="statLowStockAlerts"><span class="skeleton-line"></span></div>
            <div class="stat-sub" style="color:var(--color-text-warning, #b45309);">Needs restocking</div>
          </div>
          <div class="stat-card danger">
            <div class="stat-label">Expired Medicines</div>
            <div class="stat-val" id="statExpiredMedicines"><span class="skeleton-line"></span></div>
            <div class="stat-sub" style="color:var(--color-text-danger, #b91c1c);">Remove from shelf</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Total Patients</div>
            <div class="stat-val" id="statTotalPatients"><span class="skeleton-line"></span></div>
            <div class="stat-sub" style="color:var(--color-text-tertiary, #6b7280);">Registered patients</div>
          </div>
          <div class="stat-card info">
            <div class="stat-label">Total Sales</div>
            <div class="stat-val" id="statTotalSales"><span class="skeleton-line"></span></div>
            <div class="stat-sub" style="color:var(--color-text-info, #2563eb);">Recorded transactions</div>
          </div>
        </div>

        <div class="row2">
          <div class="card">
            <div class="card-head">
              <div class="card-title">Stock levels — last 6 months</div>
            </div>
            <div class="chart-wrap">
              <canvas id="stockChart" role="img" aria-label="Line chart showing stock levels over the last 6 months">Stock data: Jan 980, Feb 1050, Mar 1020, Apr 1180, May 1240, Jun 1284.</canvas>
            </div>
          </div>

          <div class="card">
            <div class="card-head">
              <div class="card-title">Recent patients</div>
              <a class="card-link" href="patients.php">View all</a>
            </div>
            <div id="recentPatientsList"></div>
          </div>
        </div>

        <div class="row2">
          <div class="card">
            <div class="card-head">
              <div class="card-title">Recent sales</div>
              <span class="card-link" id="openSaleModalLink" role="button" tabindex="0">Add sale</span>
            </div>
            <div id="recentSalesList"></div>
          </div>

          <div class="card">
            <div class="card-head">
              <div class="card-title">Low stock medicines</div>
              <a class="card-link" href="inventory.php">Inventory</a>
            </div>
            <div id="lowStockList"></div>
          </div>
        </div>

        <div class="inline-feedback" id="dashboardFeedback" aria-live="polite" hidden></div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
  <script src="../assets/js/dashboard.js"></script>
  <script src="../assets/js/dashboard-actions.js"></script>
  <?php include '../includes/dashboard_modals.php'; ?>
  <?php include '../includes/profile_modal.php'; ?>
</body>
</html>
