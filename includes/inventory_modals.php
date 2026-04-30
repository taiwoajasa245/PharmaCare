<div class="modal-overlay" id="addDrugModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="addDrugTitle">
    <div class="modal-head">
      <div class="modal-title" id="addDrugTitle">Add Drug</div>
      <button class="modal-close" type="button" data-close="addDrugModal" aria-label="Close">×</button>
    </div>

    <form id="addDrugForm">
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
          <label for="drugStock">Initial Stock</label>
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
        <button class="modal-link-btn" type="button" data-close="addDrugModal">Cancel</button>
        <button class="btn-add" type="submit">Save Drug</button>
      </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="editDrugModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="editDrugTitle">
    <div class="modal-head">
      <div class="modal-title" id="editDrugTitle">Edit Drug</div>
      <button class="modal-close" type="button" data-close="editDrugModal" aria-label="Close">×</button>
    </div>

    <form id="editDrugForm">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="editDrugName">Drug Name</label>
          <input id="editDrugName" type="text" required>
        </div>
        <div class="modal-field">
          <label for="editDrugCategory">Category</label>
          <input id="editDrugCategory" type="text" required>
        </div>
      </div>

      <div class="modal-grid">
        <div class="modal-field">
          <label for="editDrugQty">Stock Qty</label>
          <input id="editDrugQty" type="number" min="0" required>
        </div>
        <div class="modal-field">
          <label for="editDrugExpiry">Expiry Date</label>
          <input id="editDrugExpiry" type="date" required>
        </div>
      </div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="editDrugModal">Cancel</button>
        <button class="btn-add" type="submit">Update Drug</button>
      </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="stockInModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="stockInTitle">
    <div class="modal-head">
      <div class="modal-title" id="stockInTitle">Stock In</div>
      <button class="modal-close" type="button" data-close="stockInModal" aria-label="Close">×</button>
    </div>

    <form id="stockInForm">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="stockInDrug">Drug</label>
          <input id="stockInDrug" type="text" placeholder="Medicine name" required>
        </div>
        <div class="modal-field">
          <label for="stockInQty">Quantity Added</label>
          <input id="stockInQty" type="number" min="1" required>
        </div>
      </div>

      <div class="modal-field">
        <label for="stockInNote">Notes</label>
        <textarea id="stockInNote" placeholder="Delivery note, supplier batch, etc."></textarea>
      </div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="stockInModal">Cancel</button>
        <button class="btn-add" type="submit">Save Stock In</button>
      </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="stockOutModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="stockOutTitle">
    <div class="modal-head">
      <div class="modal-title" id="stockOutTitle">Stock Out</div>
      <button class="modal-close" type="button" data-close="stockOutModal" aria-label="Close">×</button>
    </div>

    <form id="stockOutForm">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="stockOutDrug">Drug</label>
          <input id="stockOutDrug" type="text" placeholder="Medicine name" required>
        </div>
        <div class="modal-field">
          <label for="stockOutQty">Quantity Removed</label>
          <input id="stockOutQty" type="number" min="1" required>
        </div>
      </div>

      <div class="modal-field">
        <label for="stockOutReason">Reason</label>
        <select id="stockOutReason" required>
          <option>Sale</option>
          <option>Expired</option>
          <option>Damaged</option>
          <option>Adjustment</option>
        </select>
      </div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="stockOutModal">Cancel</button>
        <button class="btn-add" type="submit">Save Stock Out</button>
      </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="deleteDrugModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="deleteDrugTitle">
    <div class="modal-head">
      <div class="modal-title" id="deleteDrugTitle">Delete Drug</div>
      <button class="modal-close" type="button" data-close="deleteDrugModal" aria-label="Close">×</button>
    </div>

    <p class="inv-note">Delete <strong id="deleteDrugLabel">this item</strong> from inventory? This action can be connected to a database delete later.</p>

    <div class="modal-actions">
      <button class="modal-link-btn" type="button" data-close="deleteDrugModal">Cancel</button>
      <button class="modal-link-btn modal-danger" type="button" data-close="deleteDrugModal">Delete</button>
    </div>
  </div>
</div>


