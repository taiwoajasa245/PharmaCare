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
          <div class="badge">4 low stock alerts</div>
          <button class="btn-add" type="button" id="openAddDrugModal">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Add Drug
          </button>
          <button class="btn-add btn-secondary" type="button" id="openStockInModal">Stock In</button>
          <button class="btn-add btn-secondary" type="button" id="openStockOutModal">Stock Out</button>
          <button class="btn-add btn-secondary" type="button" id="openProfileModalTop">Profile</button>
          <button class="mode-btn" id="modeBtn" type="button" onclick="toggleMode()">🌙 Dark</button>
        </div>
      </div>

      <div class="scroll">
        <div class="hero-card">
          <div class="hero-copy">
            <h1>Drug Management</h1>
            <p>Track stock, monitor expiration dates, and keep the pharmacy balanced with add, edit, stock in, stock out, and delete actions that are ready for database wiring.</p>
          </div>
          <div class="hero-actions">
            <button class="btn-add btn-secondary" type="button" id="openEditDrugModal">Edit Selected</button>
            <button class="btn-add" type="button" id="openAddDrugModalHero">Add Drug ↗</button>
          </div>
        </div>

        <div>
          <div class="section-title">Inventory Overview</div>
          <div class="stat-grid">
            <div class="stat-card">
              <div class="stat-label">Total Drugs</div>
              <div class="stat-val">1,284</div>
              <div class="stat-sub" style="color:var(--color-text-success);">↑ 24 added this week</div>
            </div>
            <div class="stat-card warn">
              <div class="stat-label">Low Stock</div>
              <div class="stat-val">12</div>
              <div class="stat-sub" style="color:var(--color-text-warning);">Needs attention</div>
            </div>
            <div class="stat-card danger">
              <div class="stat-label">Expiring Soon</div>
              <div class="stat-val">7</div>
              <div class="stat-sub" style="color:var(--color-text-danger);">Within 30 days</div>
            </div>
            <div class="stat-card info">
              <div class="stat-label">Stock In Today</div>
              <div class="stat-val">34</div>
              <div class="stat-sub" style="color:var(--color-text-info);">5 deliveries logged</div>
            </div>
          </div>
        </div>

        <div class="inv-toolbar" aria-label="Inventory filters">
          <div>
            <div class="field-label">Search</div>
            <input type="search" id="inventorySearch" placeholder="Search drugs, category, supplier">
          </div>
          <div>
            <div class="field-label">Category</div>
            <select id="categoryFilter">
              <option>All Categories</option>
              <option>Tablets</option>
              <option>Syrups</option>
              <option>Capsules</option>
              <option>Injection</option>
            </select>
          </div>
          <div>
            <div class="field-label">Status</div>
            <select id="statusFilter">
              <option>All Status</option>
              <option>In Stock</option>
              <option>Low Stock</option>
              <option>Expiring</option>
            </select>
          </div>
          <div>
            <div class="field-label">Sort</div>
            <select id="sortFilter">
              <option>Latest Updated</option>
              <option>Name</option>
              <option>Stock Qty</option>
              <option>Expiry Date</option>
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
                <th>Supplier</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr data-drug-name="Paracetamol 500mg" data-drug-category="Tablets" data-drug-qty="24" data-drug-expiry="2026-06-14">
                <td>
                  <div class="inv-row-title">Paracetamol 500mg</div>
                  <div class="inv-row-meta">Updated 2 hrs ago</div>
                </td>
                <td>Tablets</td>
                <td>24</td>
                <td>14 Jun 2026</td>
                <td>MedPlus Ltd</td>
                <td><span class="inv-chip warning">Low Stock</span></td>
                <td>
                  <div class="inv-actions">
                    <button class="edit-drug-btn" type="button">Edit</button>
                    <button class="modal-link-btn" type="button" id="openStockInModalRow">Stock In</button>
                    <button class="delete-drug-btn" type="button">Delete</button>
                  </div>
                </td>
              </tr>
              <tr data-drug-name="Amoxicillin 250mg" data-drug-category="Capsules" data-drug-qty="120" data-drug-expiry="2027-01-28">
                <td>
                  <div class="inv-row-title">Amoxicillin 250mg</div>
                  <div class="inv-row-meta">Updated yesterday</div>
                </td>
                <td>Capsules</td>
                <td>120</td>
                <td>28 Jan 2027</td>
                <td>HealthSource</td>
                <td><span class="inv-chip success">In Stock</span></td>
                <td>
                  <div class="inv-actions">
                    <button class="edit-drug-btn" type="button">Edit</button>
                    <button class="btn-add btn-secondary" type="button" id="openStockOutModalRow">Stock Out</button>
                    <button class="delete-drug-btn" type="button">Delete</button>
                  </div>
                </td>
              </tr>
              <tr data-drug-name="ORS Sachets" data-drug-category="Syrups" data-drug-qty="10" data-drug-expiry="2026-05-03">
                <td>
                  <div class="inv-row-title">ORS Sachets</div>
                  <div class="inv-row-meta">Updated 5 hrs ago</div>
                </td>
                <td>Syrups</td>
                <td>10</td>
                <td>03 May 2026</td>
                <td>Prime Care</td>
                <td><span class="inv-chip danger">Expiring</span></td>
                <td>
                  <div class="inv-actions">
                    <button class="edit-drug-btn" type="button">Edit</button>
                    <button class="modal-link-btn" type="button" id="openStockInModalRow2">Stock In</button>
                    <button class="delete-drug-btn" type="button">Delete</button>
                  </div>
                </td>
              </tr>
              <tr data-drug-name="Metformin 850mg" data-drug-category="Tablets" data-drug-qty="68" data-drug-expiry="2026-11-11">
                <td>
                  <div class="inv-row-title">Metformin 850mg</div>
                  <div class="inv-row-meta">Updated today</div>
                </td>
                <td>Tablets</td>
                <td>68</td>
                <td>11 Nov 2026</td>
                <td>Metro Pharma</td>
                <td><span class="inv-chip neutral">Stable</span></td>
                <td>
                  <div class="inv-actions">
                    <button class="edit-drug-btn" type="button">Edit</button>
                    <button class="btn-add btn-secondary" type="button" id="openStockOutModalRow2">Stock Out</button>
                    <button class="delete-drug-btn" type="button">Delete</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="card">
          <div class="card-title">Inventory notes</div>
          <p class="inv-note" style="margin-top: 10px;">Use the buttons above to test the modal flow. The forms are separated and ready for database integration later.</p>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/js/inventory.js"></script>
  <?php include '../includes/inventory_modals.php'; ?>
</body>
</html>
