<div class="modal-overlay" id="dashboardSaleModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="dashboardSaleModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="dashboardSaleModalTitle">New Sale</div>
      <button class="modal-close" type="button" data-close="dashboardSaleModal" aria-label="Close">×</button>
    </div>

    <form id="dashboardSaleForm" action="../api/sales.php" method="POST">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="dashboardSalePatient">Patient</label>
          <input id="dashboardSalePatient" name="patient_name" type="text" placeholder="Patient name" required>
        </div>
        <div class="modal-field">
          <label for="dashboardSaleType">Sale Type</label>
          <select id="dashboardSaleType" name="sale_type" required>
            <option>Prescription</option>
            <option>OTC</option>
          </select>
        </div>
      </div>

      <div class="modal-grid">
        <div class="modal-field">
          <label for="dashboardSaleDrug">Drug</label>
          <select id="dashboardSaleDrug" name="drug_name" required>
            <option value="" disabled selected>Select drug</option>
          </select>
        </div>
        <div class="modal-field">
          <label for="dashboardSaleQty">Quantity</label>
          <input id="dashboardSaleQty" name="quantity" type="number" min="1" placeholder="1" required>
        </div>
      </div>

      <div class="modal-field">
        <label for="dashboardSaleNotes">Notes</label>
        <textarea id="dashboardSaleNotes" name="notes" placeholder="Optional notes"></textarea>
      </div>

      <div class="modal-msg is-error" id="dashboardSaleModalError" hidden></div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="dashboardSaleModal">Cancel</button>
        <button class="btn-add" type="submit" data-loading-text="Saving sale...">
          <span class="btn-label">Save Sale</span>
          <span class="btn-spinner" aria-hidden="true"></span>
        </button>
      </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="dashboardDrugModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="dashboardDrugModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="dashboardDrugModalTitle">Add Drug</div>
      <button class="modal-close" type="button" data-close="dashboardDrugModal" aria-label="Close">×</button>
    </div>

    <form id="dashboardDrugForm" action="../api/drugs.php" method="POST">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="dashboardDrugName">Drug Name</label>
          <input id="dashboardDrugName" name="name" type="text" placeholder="Medicine name" required>
        </div>
        <div class="modal-field">
          <label for="dashboardDrugCategory">Category</label>
          <select id="dashboardDrugCategory" name="category" required>
            <option value="" disabled selected>Select category</option>
            <option>Tablets</option>
            <option>Syrups</option>
            <option>Capsules</option>
            <option>Injection</option>
          </select>
        </div>
      </div>

      <div class="modal-grid">
        <div class="modal-field">
          <label for="dashboardDrugStock">Stock Qty</label>
          <input id="dashboardDrugStock" name="stock_qty" type="number" min="0" placeholder="0" required>
        </div>
        <div class="modal-field">
          <label for="dashboardDrugExpiry">Expiry Date</label>
          <input id="dashboardDrugExpiry" name="expiry_date" type="date" required>
        </div>
      </div>

      <div class="modal-msg is-error" id="dashboardDrugModalError" hidden></div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="dashboardDrugModal">Cancel</button>
        <button class="btn-add" type="submit" data-loading-text="Saving drug...">
          <span class="btn-label">Save Drug</span>
          <span class="btn-spinner" aria-hidden="true"></span>
        </button>
      </div>
    </form>
  </div>
</div>

