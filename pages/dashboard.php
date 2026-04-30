<?php
session_start();
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

      <div class="scroll">
        <div class="quick-add">
          <div class="qa-icon">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 1v14M1 8h14" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
          </div>
          <div class="qa-text">
            <div class="qa-title">Quick Add Sale</div>
            <div class="qa-sub">Record a new prescription or OTC sale in seconds</div>
          </div>
          <button class="qa-btn" type="button">Add Sale ↗</button>
        </div>

        <div class="stat-grid">
          <div class="stat-card">
            <div class="stat-label">Total Medicines</div>
            <div class="stat-val">1,284</div>
            <div class="stat-sub" style="color:var(--color-text-success, #15803d);">↑ 24 added this week</div>
          </div>
          <div class="stat-card warn">
            <div class="stat-label">Low Stock Alerts</div>
            <div class="stat-val">3</div>
            <div class="stat-sub" style="color:var(--color-text-warning, #b45309);">Needs restocking</div>
          </div>
          <div class="stat-card danger">
            <div class="stat-label">Expired Medicines</div>
            <div class="stat-val">7</div>
            <div class="stat-sub" style="color:var(--color-text-danger, #b91c1c);">Remove from shelf</div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Total Patients</div>
            <div class="stat-val">542</div>
            <div class="stat-sub" style="color:var(--color-text-tertiary, #6b7280);">↑ 12 this month</div>
          </div>
          <div class="stat-card info">
            <div class="stat-label">Total Suppliers</div>
            <div class="stat-val">18</div>
            <div class="stat-sub" style="color:var(--color-text-info, #2563eb);">4 orders pending</div>
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
              <span class="card-link">View all</span>
            </div>
            <div class="pt-row">
              <div class="pt-av" style="background:#e6f1fb;color:#0c447c;">FA</div>
              <div><div class="pt-name">Fatima Abubakar</div><div class="pt-meta">Paracetamol · 2 hrs ago</div></div>
              <span class="pt-badge" style="background:var(--color-background-success, #dcfce7);color:var(--color-text-success, #15803d);">Dispensed</span>
            </div>
            <div class="pt-row">
              <div class="pt-av" style="background:#faeeda;color:#854f0b;">EO</div>
              <div><div class="pt-name">Emeka Okonkwo</div><div class="pt-meta">Amoxicillin · 4 hrs ago</div></div>
              <span class="pt-badge" style="background:var(--color-background-warning, #fef3c7);color:var(--color-text-warning, #b45309);">Pending</span>
            </div>
            <div class="pt-row">
              <div class="pt-av" style="background:#eaf3de;color:#3b6d11;">NK</div>
              <div><div class="pt-name">Ngozi Kalu</div><div class="pt-meta">Metformin · 6 hrs ago</div></div>
              <span class="pt-badge" style="background:var(--color-background-success, #dcfce7);color:var(--color-text-success, #15803d);">Dispensed</span>
            </div>
            <div class="pt-row">
              <div class="pt-av" style="background:#fcebeb;color:#a32d2d;">BM</div>
              <div><div class="pt-name">Bola Mustapha</div><div class="pt-meta">Ibuprofen · Yesterday</div></div>
              <span class="pt-badge" style="background:var(--color-background-danger, #fee2e2);color:var(--color-text-danger, #b91c1c);">Returned</span>
            </div>
            <div class="pt-row">
              <div class="pt-av" style="background:#eeedfe;color:#3c3489;">CI</div>
              <div><div class="pt-name">Chinwe Ibe</div><div class="pt-meta">Vitamins · Yesterday</div></div>
              <span class="pt-badge" style="background:var(--color-background-success, #dcfce7);color:var(--color-text-success, #15803d);">Dispensed</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
  <script src="../assets/js/dashboard.js"></script>
</body>
</html>
