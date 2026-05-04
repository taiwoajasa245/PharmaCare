<?php
require_once __DIR__ . '/../auth/guard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PharmaCare — Inventory</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/inventory.css">
</head>
<body class="light-mode">
  <div class="shell">
    <?php include '../includes/sidebar.php'; ?>

    <div class="body">
      <div class="topbar">
        <div class="page-title">Inventory Management</div>
        <div class="topbar-right">
          <button class="mode-btn" id="modeBtn" type="button" onclick="toggleMode()">🌙 Dark</button>
        </div>
      </div>

      <div class="scroll" id="inventoryRoot">
        <div class="hero-card">
          <div class="hero-copy">
            <h1>Drug Management</h1>
            <p>Track stock, monitor expiration dates, and keep the pharmacy balanced with add, edit, stock in, stock out, and delete actions that are ready for database wiring.</p>
          </div>
          <div class="hero-actions">
            <!-- <button class="btn-add btn-secondary" type="button" id="openEditDrugModal">Edit Selected</button> -->
            <button class="btn-add" type="button" id="openAddDrugModalHero">Add Drug ↗</button>
          </div>
        </div>

        <div>
          <div class="section-title">Inventory Overview</div>
          <div class="stat-grid">
            <div class="stat-card">
              <div class="stat-label">Total Drugs</div>
              <div class="stat-val" id="invStatTotalDrugs">0</div>
              <div class="stat-sub" style="color:var(--color-text-success);">Current medicine count</div>
            </div>
            <div class="stat-card warn">
              <div class="stat-label">Low Stock</div>
              <div class="stat-val" id="invStatLowStock">0</div>
              <div class="stat-sub" style="color:var(--color-text-warning);">Needs attention</div>
            </div>
            <div class="stat-card danger">
              <div class="stat-label">Expiring Soon</div>
              <div class="stat-val" id="invStatExpiringSoon">0</div>
              <div class="stat-sub" style="color:var(--color-text-danger);">Within 30 days</div>
            </div>
            <div class="stat-card info">
              <div class="stat-label">Stock In Today</div>
              <div class="stat-val" id="invStatStockInToday">0</div>
              <div class="stat-sub" style="color:var(--color-text-info);">Updated today</div>
            </div>
          </div>
        </div>

        <div class="inv-toolbar" aria-label="Inventory filters">
          <div>
            <div class="field-label">Search</div>
            <input type="search" id="inventorySearch" placeholder="Search drugs or category">
          </div>
          <div>
            <div class="field-label">Category</div>
            <select id="categoryFilter">
              <option value="">All Categories</option>
              <option>Tablets</option>
              <option>Syrups</option>
              <option>Capsules</option>
              <option>Injection</option>
            </select>
          </div>
          <div>
            <div class="field-label">Status</div>
            <select id="statusFilter">
              <option value="">All Status</option>
              <option value="in stock">In Stock</option>
              <option value="low stock">Low Stock</option>
              <option value="expiring">Expiring</option>
            </select>
          </div>
          <div>
            <div class="field-label">Sort</div>
            <select id="sortFilter">
              <option value="updated_desc">Latest Updated</option>
              <option value="name_asc">Name</option>
              <option value="stock_desc">Stock Qty</option>
              <option value="expiry_asc">Expiry Date</option>
            </select>
          </div>
          <div>
            <div class="field-label">Quick Action</div>
            <button class="btn-add btn-secondary" type="button" id="openAddDrugModalToolbar">Open Add Modal</button>
          </div>
        </div>

        <div class="inv-table-card">
          <table class="inv-table">
            <thead>
              <tr>
                <th>Drug</th>
                <th>Category</th>
                <th>Stock</th>
                <th>Expiry</th>
                <th>Source</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="inventoryTableBody"></tbody>
          </table>
        </div>

        <div class="inline-feedback" id="inventoryFeedback" aria-live="polite" hidden></div>
<!-- 
        <div class="card">
          <div class="card-title">Inventory notes</div>
          <p class="inv-note" style="margin-top: 10px;">Inventory actions now save to the database and update this page immediately.</p>
        </div> -->
      </div>
    </div>
  </div>

  <script src="../assets/js/inventory.js"></script>
  <?php include '../includes/inventory_modals.php'; ?>
  <?php include '../includes/profile_modal.php'; ?>
</body>
</html>
