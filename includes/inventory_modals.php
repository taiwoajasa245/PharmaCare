<div class="modal-overlay" id="addDrugModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="addDrugTitle">
    <div class="modal-head">
      <div class="modal-title" id="addDrugTitle">Add Drug</div>
      <button class="modal-close" type="button" data-close="addDrugModal" aria-label="Close">×</button>
    </div>

    <form id="addDrugForm" action="../api/inventory.php" method="POST">
      <input type="hidden" name="action" value="add">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="drugName">Drug Name</label>
          <input id="drugName" name="name" type="text" placeholder="Medicine name" required>
        </div>
        <div class="modal-field">
          <label for="drugCategory">Category</label>
          <select id="drugCategory" name="category" required>
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
          <label for="drugStock">Initial Stock</label>
          <input id="drugStock" name="stock_qty" type="number" min="0" placeholder="0" required>
        </div>
        <div class="modal-field">
          <label for="drugExpiry">Expiry Date</label>
          <input id="drugExpiry" name="expiry_date" type="date" required>
        </div>
      </div>

      <input type="hidden" name="reorder_level" value="10">

      <div class="modal-msg is-error" id="addDrugModalError" hidden></div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="addDrugModal">Cancel</button>
        <button class="btn-add" type="submit" data-loading-text="Saving drug...">
          <span class="btn-label">Save Drug</span>
          <span class="btn-spinner" aria-hidden="true"></span>
        </button>
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

    <form id="editDrugForm" action="../api/inventory.php" method="POST">
      <input type="hidden" name="action" value="update">
      <input type="hidden" id="editDrugId" name="id">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="editDrugName">Drug Name</label>
          <input id="editDrugName" name="name" type="text" required>
        </div>
        <div class="modal-field">
          <label for="editDrugCategory">Category</label>
          <select id="editDrugCategory" name="category" required>
            <option value="" disabled>Select category</option>
            <option>Tablets</option>
            <option>Syrups</option>
            <option>Capsules</option>
            <option>Injection</option>
          </select>
        </div>
      </div>

      <div class="modal-grid">
        <div class="modal-field">
          <label for="editDrugQty">Stock Qty</label>
          <input id="editDrugQty" name="stock_qty" type="number" min="0" required>
        </div>
        <div class="modal-field">
          <label for="editDrugExpiry">Expiry Date</label>
          <input id="editDrugExpiry" name="expiry_date" type="date" required>
        </div>
      </div>

      <input type="hidden" name="reorder_level" value="10">

      <div class="modal-msg is-error" id="editDrugModalError" hidden></div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="editDrugModal">Cancel</button>
        <button class="btn-add" type="submit" data-loading-text="Updating drug...">
          <span class="btn-label">Update Drug</span>
          <span class="btn-spinner" aria-hidden="true"></span>
        </button>
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

    <form id="stockInForm" action="../api/inventory.php" method="POST">
      <input type="hidden" name="action" value="stock_in">
      <input type="hidden" id="stockInDrugId" name="id">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="stockInDrug">Drug</label>
          <input id="stockInDrug" type="text" placeholder="Medicine name" required readonly>
        </div>
        <div class="modal-field">
          <label for="stockInQty">Quantity Added</label>
          <input id="stockInQty" name="quantity" type="number" min="1" required>
        </div>
      </div>

      <div class="modal-field">
        <label for="stockInNote">Notes</label>
        <textarea id="stockInNote" placeholder="Delivery note or batch number"></textarea>
      </div>

      <div class="modal-msg is-error" id="stockInModalError" hidden></div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="stockInModal">Cancel</button>
        <button class="btn-add" type="submit" data-loading-text="Saving stock in...">
          <span class="btn-label">Save Stock In</span>
          <span class="btn-spinner" aria-hidden="true"></span>
        </button>
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

    <form id="stockOutForm" action="../api/inventory.php" method="POST">
      <input type="hidden" name="action" value="stock_out">
      <input type="hidden" id="stockOutDrugId" name="id">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="stockOutDrug">Drug</label>
          <input id="stockOutDrug" type="text" placeholder="Medicine name" required readonly>
        </div>
        <div class="modal-field">
          <label for="stockOutQty">Quantity Removed</label>
          <input id="stockOutQty" name="quantity" type="number" min="1" required>
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

      <div class="modal-msg is-error" id="stockOutModalError" hidden></div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="stockOutModal">Cancel</button>
        <button class="btn-add" type="submit" data-loading-text="Saving stock out...">
          <span class="btn-label">Save Stock Out</span>
          <span class="btn-spinner" aria-hidden="true"></span>
        </button>
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
    <input type="hidden" id="deleteDrugId">

    <div class="modal-msg is-error" id="deleteDrugModalError" hidden></div>

    <div class="modal-actions">
      <button class="modal-link-btn" type="button" data-close="deleteDrugModal">Cancel</button>
      <button class="modal-link-btn modal-danger" type="button" id="confirmDeleteDrugBtn">Delete</button>
    </div>
  </div>
</div>


