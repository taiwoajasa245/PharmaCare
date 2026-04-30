<div class="modal-overlay" id="supplierModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="supplierModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="supplierModalTitle">Add Supplier</div>
      <button class="modal-close" type="button" data-close="supplierModal" aria-label="Close">×</button>
    </div>

    <form id="supplierForm">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="supplierName">Supplier Name</label>
          <input id="supplierName" type="text" placeholder="Company or contact name" required>
        </div>
        <div class="modal-field">
          <label for="supplierPhone">Phone</label>
          <input id="supplierPhone" type="tel" placeholder="Phone number" required>
        </div>
      </div>

      <div class="modal-grid">
        <div class="modal-field">
          <label for="supplierCategory">Category</label>
          <input id="supplierCategory" type="text" placeholder="Tablets, syrup, etc." required>
        </div>
        <div class="modal-field">
          <label for="supplierStatus">Status</label>
          <select id="supplierStatus" required>
            <option>Active</option>
            <option>Pending</option>
            <option>Paused</option>
          </select>
        </div>
      </div>

      <div class="modal-field">
        <label for="supplierNotes">Notes</label>
        <textarea id="supplierNotes" placeholder="Address, email, or extra notes"></textarea>
      </div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="supplierModal">Cancel</button>
        <button class="btn-add" type="submit">Save Supplier</button>
      </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="editSupplierModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="editSupplierModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="editSupplierModalTitle">Edit Supplier</div>
      <button class="modal-close" type="button" data-close="editSupplierModal" aria-label="Close">×</button>
    </div>

    <form id="editSupplierForm">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="editSupplierName">Supplier Name</label>
          <input id="editSupplierName" type="text" required>
        </div>
        <div class="modal-field">
          <label for="editSupplierPhone">Phone</label>
          <input id="editSupplierPhone" type="tel" required>
        </div>
      </div>

      <div class="modal-grid">
        <div class="modal-field">
          <label for="editSupplierCategory">Category</label>
          <input id="editSupplierCategory" type="text" required>
        </div>
        <div class="modal-field">
          <label for="editSupplierStatus">Status</label>
          <select id="editSupplierStatus" required>
            <option>Active</option>
            <option>Pending</option>
            <option>Paused</option>
          </select>
        </div>
      </div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="editSupplierModal">Cancel</button>
        <button class="btn-add" type="submit">Update Supplier</button>
      </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="orderModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="orderModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="orderModalTitle">Purchase Order</div>
      <button class="modal-close" type="button" data-close="orderModal" aria-label="Close">×</button>
    </div>

    <form id="orderForm">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="orderSupplier">Supplier</label>
          <input id="orderSupplier" type="text" required>
        </div>
        <div class="modal-field">
          <label for="orderItem">Item</label>
          <input id="orderItem" type="text" required>
        </div>
      </div>

      <div class="modal-grid">
        <div class="modal-field">
          <label for="orderQty">Quantity</label>
          <input id="orderQty" type="number" min="1" required>
        </div>
        <div class="modal-field">
          <label for="orderDate">Expected Delivery</label>
          <input id="orderDate" type="date" required>
        </div>
      </div>

      <div class="modal-field">
        <label for="orderNotes">Notes</label>
        <textarea id="orderNotes" placeholder="Order notes"></textarea>
      </div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="orderModal">Cancel</button>
        <button class="btn-add" type="submit">Create Order</button>
      </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="deliveryModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="deliveryModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="deliveryModalTitle">Log Delivery</div>
      <button class="modal-close" type="button" data-close="deliveryModal" aria-label="Close">×</button>
    </div>

    <form id="deliveryForm">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="deliverySupplier">Supplier</label>
          <input id="deliverySupplier" type="text" required>
        </div>
        <div class="modal-field">
          <label for="deliveryReference">Reference</label>
          <input id="deliveryReference" type="text" required>
        </div>
      </div>

      <div class="modal-field">
        <label for="deliveryNotes">Delivery Notes</label>
        <textarea id="deliveryNotes" placeholder="What was delivered?"></textarea>
      </div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="deliveryModal">Cancel</button>
        <button class="btn-add" type="submit">Save Delivery</button>
      </div>
    </form>
  </div>
</div>


