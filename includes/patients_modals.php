<div class="modal-overlay" id="saleModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="saleModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="saleModalTitle">Record Drug Sale</div>
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
        <label for="salePrescription">Linked Prescription</label>
        <input id="salePrescription" type="text" placeholder="Prescription ID or note">
      </div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="saleModal">Cancel</button>
        <button class="btn-add" type="submit">Save Sale</button>
      </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="receiptModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="receiptModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="receiptModalTitle">Generate Receipt</div>
      <button class="modal-close" type="button" data-close="receiptModal" aria-label="Close">×</button>
    </div>

    <form id="receiptForm">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="receiptPatient">Patient</label>
          <input id="receiptPatient" type="text" required>
        </div>
        <div class="modal-field">
          <label for="receiptSale">Sale Reference</label>
          <input id="receiptSale" type="text" required>
        </div>
      </div>

      <div class="modal-field">
        <label for="receiptNote">Receipt Note</label>
        <textarea id="receiptNote" placeholder="Optional message for receipt"></textarea>
      </div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="receiptModal">Cancel</button>
        <button class="btn-add" type="submit">Create Receipt</button>
      </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="prescriptionModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="prescriptionModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="prescriptionModalTitle">Prescription</div>
      <button class="modal-close" type="button" data-close="prescriptionModal" aria-label="Close">×</button>
    </div>

    <form id="prescriptionForm">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="prescriptionPatient">Patient</label>
          <input id="prescriptionPatient" type="text" placeholder="Patient name" required>
        </div>
        <div class="modal-field">
          <label for="prescriptionDrug">Drug / Item</label>
          <input id="prescriptionDrug" type="text" placeholder="Prescription items" required>
        </div>
      </div>

      <div class="modal-field">
        <label for="prescriptionNote">Notes</label>
        <textarea id="prescriptionNote" placeholder="Dosage, directions, notes"></textarea>
      </div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="prescriptionModal">Cancel</button>
        <button class="btn-add" type="submit">Save Prescription</button>
      </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="patientModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="patientModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="patientModalTitle">Add Patient</div>
      <button class="modal-close" type="button" data-close="patientModal" aria-label="Close">×</button>
    </div>

    <form id="patientForm">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="patientName">Full Name</label>
          <input id="patientName" type="text" required>
        </div>
        <div class="modal-field">
          <label for="patientPhone">Phone</label>
          <input id="patientPhone" type="tel" required>
        </div>
      </div>

      <div class="modal-field">
        <label for="patientNotes">Notes</label>
        <textarea id="patientNotes" placeholder="Allergies, history, or other notes"></textarea>
      </div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="patientModal">Cancel</button>
        <button class="btn-add" type="submit">Save Patient</button>
      </div>
    </form>
  </div>
</div>


