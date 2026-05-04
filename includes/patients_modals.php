<div class="modal-overlay" id="saleModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="saleModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="saleModalTitle">Record Drug Sale</div>
      <button class="modal-close" type="button" data-close="saleModal" aria-label="Close">×</button>
    </div>

    <form id="saleForm" action="../api/patients.php" method="POST">
      <input type="hidden" name="action" value="record_sale">
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
        <label for="salePrescription">Linked Prescription</label>
        <input id="salePrescription" name="notes" type="text" placeholder="Prescription ID or note">
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

<div class="modal-overlay" id="prescriptionModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="prescriptionModalTitle">
    <div class="modal-head">
      <div class="modal-title" id="prescriptionModalTitle">Prescription</div>
      <button class="modal-close" type="button" data-close="prescriptionModal" aria-label="Close">×</button>
    </div>

    <form id="prescriptionForm" action="../api/patients.php" method="POST">
      <input type="hidden" name="action" value="add_prescription">
      <div class="modal-grid">
        <div class="modal-field">
          <label for="prescriptionPatient">Patient</label>
          <input id="prescriptionPatient" name="patient_name" type="text" placeholder="Patient name" required>
        </div>
        <div class="modal-field">
          <label for="prescriptionDrug">Drug / Item</label>
          <input id="prescriptionDrug" name="drug_name" type="text" placeholder="Prescription items" required>
        </div>
      </div>

      <div class="modal-field">
        <label for="prescriptionNote">Notes</label>
        <textarea id="prescriptionNote" name="notes" placeholder="Dosage, directions, notes"></textarea>
      </div>

      <div class="modal-actions">
        <button class="modal-link-btn" type="button" data-close="prescriptionModal">Cancel</button>
        <button class="btn-add" type="submit" data-loading-text="Saving prescription...">
          <span class="btn-label">Save Prescription</span>
          <span class="btn-spinner" aria-hidden="true"></span>
        </button>
      </div>
    </form>
  </div>
</div>

<div class="modal-overlay" id="deletePatientModal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="deletePatientTitle">
    <div class="modal-head">
      <div class="modal-title" id="deletePatientTitle">Delete Patient</div>
      <button class="modal-close" type="button" data-close="deletePatientModal" aria-label="Close">×</button>
    </div>

    <p class="inv-note">Delete <strong id="deletePatientLabel">this patient</strong> from records?</p>
    <input type="hidden" id="deletePatientId">

    <div class="modal-actions">
      <button class="modal-link-btn" type="button" data-close="deletePatientModal">Cancel</button>
      <button class="modal-link-btn modal-danger" type="button" id="confirmDeletePatientBtn">Delete</button>
    </div>
  </div>
</div>


