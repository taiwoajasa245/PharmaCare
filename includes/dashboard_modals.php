<div class="modal-overlay" id="saleModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="saleModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="saleModalTitle">New Sale</div>
      <button class="modal-close" type="button" data-close="saleModal" aria-label="Close">×</button>
    </div>

    <form id="saleForm" action="../api/sales.php" method="POST">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="salePatient">Patient</label>
          <input id="salePatient" name="patient_name" type="text" placeholder="Patient name" required>
        </div>
        <div class="modal-field">
          <label for="saleType">Sale Type</label>
          <select id="saleType" name="sale_type" required>
            <option>Prescription</option>
            <option>OTC</option>
          </select>
        </div>
      </div>

      <div class="modal-grid">
        <div class="modal-field">
          <label for="saleDrug">Drug</label>
          <input id="saleDrug" name="drug_name" type="text" placeholder="Drug name" required>
        </div>
        <div class="modal-field">
          <label for="saleQty">Quantity</label>
          <input id="saleQty" name="quantity" type="number" min="1" placeholder="1" required>
        </div>
      </div>

      <div class="modal-field">
        <label for="saleNotes">Notes</label>
        <textarea id="saleNotes" name="notes" placeholder="Optional notes"></textarea>
      </div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="saleModal">Cancel</button>
        <button class="btn-add" type="submit" data-loading-text="Saving sale...">
          <span class="btn-label">Save Sale</span>
          <span class="btn-spinner" aria-hidden="true"></span>
        </button>
      </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="drugModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="drugModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="drugModalTitle">Add Drug</div>
      <button class="modal-close" type="button" data-close="drugModal" aria-label="Close">×</button>
    </div>

    <form id="drugForm" action="../api/drugs.php" method="POST">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="drugName">Drug Name</label>
          <input id="drugName" name="name" type="text" placeholder="Medicine name" required>
        </div>
        <div class="modal-field">
          <label for="drugCategory">Category</label>
          <input id="drugCategory" name="category" type="text" placeholder="Tablets, syrup, etc." required>
        </div>
      </div>

      <div class="modal-grid">
        <div class="modal-field">
          <label for="drugStock">Stock Qty</label>
          <input id="drugStock" name="stock_qty" type="number" min="0" placeholder="0" required>
        </div>
        <div class="modal-field">
          <label for="drugExpiry">Expiry Date</label>
          <input id="drugExpiry" name="expiry_date" type="date" required>
        </div>
      </div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="drugModal">Cancel</button>
        <button class="btn-add" type="submit" data-loading-text="Saving drug...">
          <span class="btn-label">Save Drug</span>
          <span class="btn-spinner" aria-hidden="true"></span>
        </button>
      </div>
    </form>
  </div>
</div>

