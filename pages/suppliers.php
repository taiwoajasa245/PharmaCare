<?php
require_once __DIR__ . '/../auth/guard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PharmaCare — Suppliers</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/suppliers.css">
</head>
<body class="light-mode">
  <div class="shell">
    <?php include '../includes/sidebar.php'; ?>

    <div class="body">
      <div class="topbar">
        <div class="page-title">Suppliers & Restocking</div>
        <div class="topbar-right">
          <div class="badge">2 suppliers need follow-up</div>
          <button class="btn-add" type="button" id="openAddSupplierModal">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Add Supplier
          </button>
          <button class="btn-add btn-secondary" type="button" id="openPurchaseOrderModal">Purchase Order</button>
          <button class="btn-add btn-secondary" type="button" id="openLogDeliveryModal">Log Delivery</button>
          <button class="btn-add btn-secondary" type="button" id="openProfileModalTop">Profile</button>
          <button class="mode-btn" id="modeBtn" type="button" onclick="toggleMode()">🌙 Dark</button>
        </div>
      </div>

      <div class="scroll">
        <div class="hero-card">
          <div class="hero-copy">
            <h1>Manage supplier relationships and restocking</h1>
            <p>Keep drug supply flowing with supplier profiles, purchase orders, delivery logs, and a simple audit trail for restocking.</p>
          </div>
          <div class="hero-actions">
            <button class="btn-add btn-secondary" type="button" id="openAddSupplierModalHero">Add Supplier</button>
            <button class="btn-add" type="button" id="openPurchaseOrderModalHero">New Order ↗</button>
          </div>
        </div>

        <div>
          <div class="section-title">Supplier Overview</div>
          <div class="stat-grid">
            <div class="stat-card">
              <div class="stat-label">Active Suppliers</div>
              <div class="stat-val">18</div>
              <div class="stat-sub" style="color:var(--color-text-success);">↑ 2 added this month</div>
            </div>
            <div class="stat-card info">
              <div class="stat-label">Open Orders</div>
              <div class="stat-val">6</div>
              <div class="stat-sub" style="color:var(--color-text-info);">3 waiting for delivery</div>
            </div>
            <div class="stat-card warn">
              <div class="stat-label">Needs Follow-up</div>
              <div class="stat-val">2</div>
              <div class="stat-sub" style="color:var(--color-text-warning);">Call suppliers today</div>
            </div>
            <div class="stat-card danger">
              <div class="stat-label">Delayed Deliveries</div>
              <div class="stat-val">1</div>
              <div class="stat-sub" style="color:var(--color-text-danger);">Review pending shipment</div>
            </div>
          </div>
        </div>

        <div class="supplier-grid">
          <div class="supplier-panel">
            <div class="card-head">
              <div class="card-title">Supplier List</div>
              <span class="card-link">View all</span>
            </div>
            <div class="supplier-list">
              <div class="sup-row" data-supplier-name="MedPlus Ltd" data-supplier-phone="0803 112 2233" data-supplier-category="Tablets" data-supplier-status="Active">
                <div class="sup-av" style="background:#e6f1fb;color:#0c447c;">MP</div>
                <div>
                  <div class="sup-name">MedPlus Ltd</div>
                  <div class="sup-meta">Tablets · 0803 112 2233</div>
                </div>
                <span class="chip success">Active</span>
              </div>
              <div class="sup-row" data-supplier-name="HealthSource" data-supplier-phone="0805 445 6677" data-supplier-category="Capsules" data-supplier-status="Active">
                <div class="sup-av" style="background:#faeeda;color:#854f0b;">HS</div>
                <div>
                  <div class="sup-name">HealthSource</div>
                  <div class="sup-meta">Capsules · 0805 445 6677</div>
                </div>
                <span class="chip success">Active</span>
              </div>
              <div class="sup-row" data-supplier-name="Prime Care" data-supplier-phone="0809 991 0022" data-supplier-category="Syrups" data-supplier-status="Pending">
                <div class="sup-av" style="background:#eaf3de;color:#3b6d11;">PC</div>
                <div>
                  <div class="sup-name">Prime Care</div>
                  <div class="sup-meta">Syrups · 0809 991 0022</div>
                </div>
                <span class="chip warning">Pending</span>
              </div>
              <div class="sup-row" data-supplier-name="Metro Pharma" data-supplier-phone="0812 313 8899" data-supplier-category="Tablets" data-supplier-status="Paused">
                <div class="sup-av" style="background:#fcebeb;color:#a32d2d;">MF</div>
                <div>
                  <div class="sup-name">Metro Pharma</div>
                  <div class="sup-meta">Tablets · 0812 313 8899</div>
                </div>
                <span class="chip danger">Paused</span>
              </div>
            </div>
          </div>

          <div class="supplier-panel">
            <div class="card-head">
              <div class="card-title">Recent Orders</div>
              <span class="card-link">Export</span>
            </div>
            <div class="order-row">
              <div>
                <div class="lead-name">Purchase order #1024</div>
                <div class="lead-meta">MedPlus Ltd · 24 boxes</div>
              </div>
              <span class="chip info">Pending</span>
            </div>
            <div class="order-row">
              <div>
                <div class="lead-name">Delivery log #1020</div>
                <div class="lead-meta">HealthSource · received</div>
              </div>
              <span class="chip success">Complete</span>
            </div>
            <div class="order-row">
              <div>
                <div class="lead-name">Purchase order #1019</div>
                <div class="lead-meta">Prime Care · awaiting dispatch</div>
              </div>
              <span class="chip warning">Awaiting</span>
            </div>
          </div>
        </div>

        <div class="toolbar">
          <div>
            <div class="field-label">Search</div>
            <input type="search" placeholder="Search suppliers, phone, or category">
          </div>
          <div>
            <div class="field-label">Status</div>
            <select>
              <option>All Status</option>
              <option>Active</option>
              <option>Pending</option>
              <option>Paused</option>
            </select>
          </div>
          <div>
            <div class="field-label">Category</div>
            <select>
              <option>All Categories</option>
              <option>Tablets</option>
              <option>Capsules</option>
              <option>Syrups</option>
            </select>
          </div>
          <div>
            <div class="field-label">Quick Action</div>
            <button class="btn-add btn-secondary" type="button" id="openAddSupplierModalToolbar">Open Add Modal</button>
          </div>
          <div>
            <div class="field-label">Restock</div>
            <button class="btn-add" type="button" id="openPurchaseOrderModalToolbar">Create Order</button>
          </div>
        </div>

        <div class="table-card">
          <table class="sup-table">
            <thead>
              <tr>
                <th>Supplier</th>
                <th>Category</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Orders</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr data-supplier-name="MedPlus Ltd" data-supplier-phone="0803 112 2233" data-supplier-category="Tablets" data-supplier-status="Active">
                <td>
                  <div class="sup-name">MedPlus Ltd</div>
                  <div class="sup-meta">Lagos office · medplus@example.com</div>
                </td>
                <td>Tablets</td>
                <td>0803 112 2233</td>
                <td><span class="chip success">Active</span></td>
                <td>12</td>
                <td>
                  <div class="sup-actions">
                    <button class="chip-btn edit-supplier-btn" type="button">Edit</button>
                    <button class="chip-btn order-supplier-btn" type="button">Order</button>
                    <button class="chip-btn delivery-btn" type="button">Delivery</button>
                  </div>
                </td>
              </tr>
              <tr data-supplier-name="HealthSource" data-supplier-phone="0805 445 6677" data-supplier-category="Capsules" data-supplier-status="Active">
                <td>
                  <div class="sup-name">HealthSource</div>
                  <div class="sup-meta">Abuja office · healthsource@example.com</div>
                </td>
                <td>Capsules</td>
                <td>0805 445 6677</td>
                <td><span class="chip success">Active</span></td>
                <td>8</td>
                <td>
                  <div class="sup-actions">
                    <button class="chip-btn edit-supplier-btn" type="button">Edit</button>
                    <button class="chip-btn order-supplier-btn" type="button">Order</button>
                    <button class="chip-btn delivery-btn" type="button">Delivery</button>
                  </div>
                </td>
              </tr>
              <tr data-supplier-name="Prime Care" data-supplier-phone="0809 991 0022" data-supplier-category="Syrups" data-supplier-status="Pending">
                <td>
                  <div class="sup-name">Prime Care</div>
                  <div class="sup-meta">Port Harcourt · primecare@example.com</div>
                </td>
                <td>Syrups</td>
                <td>0809 991 0022</td>
                <td><span class="chip warning">Pending</span></td>
                <td>4</td>
                <td>
                  <div class="sup-actions">
                    <button class="chip-btn edit-supplier-btn" type="button">Edit</button>
                    <button class="chip-btn order-supplier-btn" type="button">Order</button>
                    <button class="chip-btn delivery-btn" type="button">Delivery</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="note-card">
          <div class="card-title">Restocking workflow</div>
          <p class="inv-note" style="margin-top:10px;">Add suppliers, create purchase orders, and log deliveries from the modal actions. Everything is separated for easier database wiring later.</p>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/js/suppliers.js"></script>
  <?php include '../includes/suppliers_modals.php'; ?>
</body>
</html>
