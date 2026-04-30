<div class="modal-overlay" id="saleModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="saleModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="saleModalTitle">New Sale</div>
      <button class="modal-close" type="button" data-close="saleModal" aria-label="Close">×</button>
    </div>

    <form id="saleForm">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="salePatient">Patient</label>
          <input id="salePatient" type="text" placeholder="Patient name" required>
        </div>
        <div class="modal-field">
          <label for="saleType">Sale Type</label>
          <select id="saleType" required>
            <option>Prescription</option>
            <option>OTC</option>
          </select>
        </div>
      </div>

      <div class="modal-grid">
        <div class="modal-field">
          <label for="saleDrug">Drug</label>
          <input id="saleDrug" type="text" placeholder="Drug name" required>
        </div>
        <div class="modal-field">
          <label for="saleQty">Quantity</label>
          <input id="saleQty" type="number" min="1" placeholder="1" required>
        </div>
      </div>

      <div class="modal-field">
        <label for="saleNotes">Notes</label>
        <textarea id="saleNotes" placeholder="Optional notes"></textarea>
      </div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="saleModal">Cancel</button>
        <button class="btn-add" type="submit">Save Sale</button>
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

    <form id="drugForm">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="drugName">Drug Name</label>
          <input id="drugName" type="text" placeholder="Medicine name" required>
        </div>
        <div class="modal-field">
          <label for="drugCategory">Category</label>
          <input id="drugCategory" type="text" placeholder="Tablets, syrup, etc." required>
        </div>
      </div>

      <div class="modal-grid">
        <div class="modal-field">
          <label for="drugStock">Stock Qty</label>
          <input id="drugStock" type="number" min="0" placeholder="0" required>
        </div>
        <div class="modal-field">
          <label for="drugExpiry">Expiry Date</label>
          <input id="drugExpiry" type="date" required>
        </div>
      </div>

      <div class="modal-field">
        <label for="drugSupplier">Supplier</label>
        <input id="drugSupplier" type="text" placeholder="Supplier name">
      </div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="drugModal">Cancel</button>
        <button class="btn-add" type="submit">Save Drug</button>
      </div>
    </form>
  </div>
</div>

