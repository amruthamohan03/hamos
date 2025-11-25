<link href="<?= BASE_URL ?>/assets/pages/css/license_styles.css" rel="stylesheet" type="text/css">

<style>
  /* Premium Gradient Background Cards */
  .stats-card {
    border: none;
    border-radius: 15px;
    transition: transform 0.3s, box-shadow 0.3s;
    overflow: hidden;
    cursor: pointer;
    min-height: 120px;
  }
  
  .stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
  }
  
  .stats-card-annulation {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
  }
  
  .stats-card-modification {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
  }
  
  .stats-card-prorogation {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
  }
  
  .stats-card .card-body {
    padding: 20px 15px;
    position: relative;
  }
  
  .stats-icon {
    font-size: 2.5rem;
    opacity: 0.3;
    position: absolute;
    right: 15px;
    top: 15px;
  }
  
  .stats-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 5px;
  }
  
  .stats-label {
    font-size: 0.75rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  /* Action Selection Cards */
  .action-card {
    background: white;
    border: 3px solid transparent;
    border-radius: 20px;
    padding: 2rem;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
    min-height: 200px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
  
  .action-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
  }
  
  .action-card.active {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
  }
  
  .action-card-annulation {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
  }
  
  .action-card-annulation.active {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    border-color: #dc2626;
  }
  
  .action-card-modification {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
  }
  
  .action-card-modification.active {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    border-color: #2563eb;
  }
  
  .action-card-prorogation {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
  }
  
  .action-card-prorogation.active {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
    border-color: #059669;
  }
  
  .action-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2.5rem;
  }
  
  .action-card-annulation .action-icon {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
  }
  
  .action-card-annulation.active .action-icon {
    background: rgba(255, 255, 255, 0.2);
    color: white;
  }
  
  .action-card-modification .action-icon {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
  }
  
  .action-card-modification.active .action-icon {
    background: rgba(255, 255, 255, 0.2);
    color: white;
  }
  
  .action-card-prorogation .action-icon {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
  }
  
  .action-card-prorogation.active .action-icon {
    background: rgba(255, 255, 255, 0.2);
    color: white;
  }
  
  /* License Types Section */
  .license-types-section {
    background: rgba(102, 126, 234, 0.05);
    border-radius: 16px;
    padding: 25px;
    margin: 30px 0;
    border: 2px dashed rgba(102, 126, 234, 0.2);
  }
  
  .license-types-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 50px;
    flex-wrap: wrap;
  }
  
  .model-title {
    font-size: 1.3rem;
    font-weight: bold;
    color: #667eea;
    text-transform: uppercase;
    letter-spacing: 2px;
  }
  
  .license-box-container {
    display: flex;
    gap: 20px;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
  }
  
  .license-box {
    width: 80px;
    height: 50px;
    border: 3px solid #667eea;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    cursor: pointer;
    background: white;
    position: relative;
    color: #667eea;
    font-size: 1rem;
    transition: all 0.3s;
  }
  
  .license-box input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
  }
  
  .license-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    border-color: #764ba2;
  }
  
  .license-box.checked {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
  }
  
  /* Modification Table */
  .modification-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }
  
  .modification-table th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem;
    font-weight: 700;
    text-align: center;
    border: none;
  }
  
  .modification-table td {
    padding: 0.75rem;
    border-bottom: 1px solid #e2e8f0;
  }
  
  .modification-table tbody tr:last-child td {
    border-bottom: none;
  }
  
  .modification-table input {
    width: 100%;
    padding: 0.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    transition: all 0.3s;
  }
  
  .modification-table input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
  }
  
  .modification-table input[readonly] {
    background-color: #f9fafb;
    color: #6b7280;
  }
  
  /* Modal Styling */
  .modal-content {
    border: none;
    border-radius: 15px;
    overflow: hidden;
  }
  
  .modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 20px 30px;
  }
  
  .modal-header .btn-close {
    filter: brightness(0) invert(1);
  }
  
  .detail-row {
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s;
  }
  
  .detail-row:hover {
    background: #f8f9fa;
  }
  
  .detail-row:last-child {
    border-bottom: none;
  }
  
  .detail-label {
    font-weight: 600;
    color: #667eea;
    font-size: 0.9rem;
    margin-bottom: 5px;
  }
  
  .detail-value {
    color: #2d3748;
    font-size: 1rem;
    font-weight: 500;
  }
  
  .detail-icon {
    color: #667eea;
    margin-right: 8px;
  }
  
  /* Premium Tabs */
  .premium-tabs {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 10px;
  }
  
  .premium-tab {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    color: #64748b;
  }
  
  .premium-tab:hover {
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-2px);
  }
  
  .premium-tab.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
  }
  
  /* Export Button Styling */
  .btn-export-all {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
  }
  
  .btn-export-all:hover {
    background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    color: white;
  }
  
  /* Form Section Styling */
  .form-section {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 25px;
    margin-bottom: 25px;
  }
  
  .form-section:last-child {
    border-bottom: none;
  }
  
  .section-header {
    position: relative;
    padding-bottom: 10px;
    margin-bottom: 20px;
  }
  
  .section-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 2px;
  }
  
  .section-header h5 {
    color: #667eea;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0;
  }
  
  /* DataTable Styling */
  table.dataTable thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
    border: none;
    padding: 12px 8px;
  }
  
  table.dataTable tbody tr {
    transition: background 0.2s;
  }
  
  table.dataTable tbody tr:hover {
    background: rgba(102, 126, 234, 0.05);
  }
  
  /* Card Shadows */
  .card {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: none;
    border-radius: 15px;
  }
  
  /* Accordion Styling */
  .accordion-button {
    font-weight: 600;
    background-color: #f8f9fa;
  }
  
  .accordion-button:not(.collapsed) {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }
  
  .accordion-button:not(.collapsed)::after {
    filter: brightness(0) invert(1);
  }
  
  .accordion-item {
    border: none;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 15px;
  }
  
  /* Button Styles */
  .btn-view {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
  }
  
  .btn-view:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
  }
  
  .btn-export {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
  }
  
  .btn-export:hover {
    background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
  }
  
  /* Print Button */
  .btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    color: white;
    border: none;
  }
  
  .btn-warning:hover {
    background: linear-gradient(135deg, #ff9800 0%, #ffc107 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255, 193, 7, 0.4);
  }
  
  /* Responsive Design */
  @media (max-width: 768px) {
    .stats-value {
      font-size: 1.5rem;
    }
    
    .stats-icon {
      font-size: 2rem;
    }
    
    .action-card {
      min-height: 150px;
      padding: 1.5rem;
    }
    
    .action-icon {
      width: 60px;
      height: 60px;
      font-size: 2rem;
    }
    
    .license-types-container {
      flex-direction: column;
      gap: 20px;
    }
  }
</style>

<div class="page-content">
  <div class="page-container">
    <div class="row">
      <div class="col-12">
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
          <div class="col-lg-4 col-md-6 mb-3">
            <div class="card stats-card stats-card-annulation shadow-sm">
              <div class="card-body position-relative">
                <i class="ti ti-ban stats-icon"></i>
                <div class="stats-value" id="totalAnnulations">0</div>
                <div class="stats-label">Total Annulations</div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-4 col-md-6 mb-3">
            <div class="card stats-card stats-card-modification shadow-sm">
              <div class="card-body position-relative">
                <i class="ti ti-edit stats-icon"></i>
                <div class="stats-value" id="totalModifications">0</div>
                <div class="stats-label">Total Modifications</div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-4 col-md-6 mb-3">
            <div class="card stats-card stats-card-prorogation shadow-sm">
              <div class="card-body position-relative">
                <i class="ti ti-clock stats-icon"></i>
                <div class="stats-value" id="totalProrogations">0</div>
                <div class="stats-label">Total Prorogations</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Selection Card -->
        <div class="card shadow-sm mb-4">
          <div class="card-header border-bottom border-dashed">
            <h4 class="header-title mb-0">
              <i class="ti ti-settings me-2"></i>Select Operation Type
            </h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4 mb-3">
                <div class="action-card action-card-annulation" data-type="annulation">
                  <div class="action-icon">
                    <i class="ti ti-ban"></i>
                  </div>
                  <h4 class="h5 font-weight-bold mb-2">ANNULATION</h4>
                  <p class="small mb-0">Cancel license permanently</p>
                </div>
              </div>
              
              <div class="col-md-4 mb-3">
                <div class="action-card action-card-modification" data-type="modification">
                  <div class="action-icon">
                    <i class="ti ti-edit"></i>
                  </div>
                  <h4 class="h5 font-weight-bold mb-2">MODIFICATION</h4>
                  <p class="small mb-0">Modify license details</p>
                </div>
              </div>
              
              <div class="col-md-4 mb-3">
                <div class="action-card action-card-prorogation" data-type="prorogation">
                  <div class="action-icon">
                    <i class="ti ti-clock"></i>
                  </div>
                  <h4 class="h5 font-weight-bold mb-2">PROROGATION</h4>
                  <p class="small mb-0">Extend license validity</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Container -->
        <div id="formContainer" class="card shadow-sm mb-4" style="display:none;">
          <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
            <h4 class="header-title mb-0" id="formTitle">Operation Form</h4>
            <button type="button" class="btn btn-secondary btn-sm" id="cancelFormBtn">
              <i class="ti ti-x me-1"></i>Cancel
            </button>
          </div>

          <div class="card-body">
            <form id="operationForm" novalidate>
              
              <!-- ANNULATION FORM -->
              <div id="annulationFormContent" style="display:none;">
                <div class="accordion" id="annulationAccordion">
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#annulationDetails">
                        <i class="ti ti-ban me-2"></i>Annulation Form
                      </button>
                    </h2>
                    <div id="annulationDetails" class="accordion-collapse collapse show" data-bs-parent="#annulationAccordion">
                      <div class="accordion-body">
                        
                        <!-- Bank and License Selection -->
                        <div class="form-section">
                          <div class="section-header">
                            <h5><i class="ti ti-building-bank me-2"></i>Bank & License Selection</h5>
                          </div>
                          <div class="row">
                            <div class="col-md-4 mb-3">
                              <label class="form-label">Select Bank <span class="text-danger">*</span></label>
                              <select id="annul_bank_id" class="form-control">
                                <option value="">-- Select Bank --</option>
                                <?php if (!empty($banks)): ?>
                                  <?php foreach ($banks as $bank): ?>
                                    <option value="<?= $bank['id'] ?>" 
                                            data-code="<?= htmlspecialchars($bank['bank_code'] ?? '') ?>"
                                            data-name="<?= htmlspecialchars($bank['bank_name'] ?? '') ?>">
                                      <?= htmlspecialchars($bank['bank_name'] ?? '') ?>
                                    </option>
                                  <?php endforeach; ?>
                                <?php endif; ?>
                              </select>
                            </div>

                            <div class="col-md-4 mb-3">
                              <label class="form-label">Bank Code <span class="text-danger">*</span></label>
                              <input type="text" id="annul_bank_code" class="form-control" readonly>
                            </div>

                            <div class="col-md-4 mb-3">
                              <label class="form-label">Select License <span class="text-danger">*</span></label>
                              <select id="annul_license_id" class="form-control" disabled>
                                <option value="">-- Select License --</option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <!-- Agent and Processing Details -->
                        <div class="form-section">
                          <div class="section-header">
                            <h5><i class="ti ti-user me-2"></i>Agent & Processing Details</h5>
                          </div>
                          <div class="row">
                            <div class="col-md-4 mb-3">
                              <label class="form-label">Transmission Number <span class="text-danger">*</span></label>
                              <input type="text" id="annul_transmission_number" class="form-control" maxlength="100">
                            </div>

                            <div class="col-md-4 mb-3">
                              <label class="form-label">Processing Fee ($) <span class="text-danger">*</span></label>
                              <input type="number" step="0.01" min="0" id="annul_processing_fee" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                              <label class="form-label">Agent Name <span class="text-danger">*</span></label>
                              <input type="text" id="annul_agent_name" class="form-control" readonly>
                            </div>

                            <div class="col-md-4 mb-3">
                              <label class="form-label">ID NAT Number <span class="text-danger">*</span></label>
                              <input type="text" id="annul_national_id" class="form-control" readonly>
                            </div>
                          </div>
                        </div>

                        <!-- License Types -->
                        <div class="license-types-section">
                          <div class="license-types-container">
                            <h3 class="model-title">MODÈLE</h3>
                            <div class="license-box-container">
                              <?php foreach(['EB', 'IB', 'ES', 'IS', 'RC'] as $type): ?>
                                <label class="license-box" data-form="annulation">
                                  <input type="checkbox" value="<?= $type ?>" class="annul-license-type">
                                  <?= $type ?>
                                </label>
                              <?php endforeach; ?>
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MODIFICATION FORM -->
              <div id="modificationFormContent" style="display:none;">
                <div class="accordion" id="modificationAccordion">
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#modificationDetails">
                        <i class="ti ti-edit me-2"></i>Modification Form
                      </button>
                    </h2>
                    <div id="modificationDetails" class="accordion-collapse collapse show" data-bs-parent="#modificationAccordion">
                      <div class="accordion-body">
                        
                        <!-- Bank and License Selection -->
                        <div class="form-section">
                          <div class="section-header">
                            <h5><i class="ti ti-building-bank me-2"></i>Bank & License Selection</h5>
                          </div>
                          <div class="row">
                            <div class="col-md-4 mb-3">
                              <label class="form-label">Select Bank <span class="text-danger">*</span></label>
                              <select id="mod_bank_id" class="form-control">
                                <option value="">-- Select Bank --</option>
                                <?php if (!empty($banks)): ?>
                                  <?php foreach ($banks as $bank): ?>
                                    <option value="<?= $bank['id'] ?>" 
                                            data-code="<?= htmlspecialchars($bank['bank_code'] ?? '') ?>"
                                            data-name="<?= htmlspecialchars($bank['bank_name'] ?? '') ?>">
                                      <?= htmlspecialchars($bank['bank_name'] ?? '') ?>
                                    </option>
                                  <?php endforeach; ?>
                                <?php endif; ?>
                              </select>
                            </div>

                            <div class="col-md-4 mb-3">
                              <label class="form-label">Bank Code <span class="text-danger">*</span></label>
                              <input type="text" id="mod_bank_code" class="form-control" readonly>
                            </div>

                            <div class="col-md-4 mb-3">
                              <label class="form-label">Select License <span class="text-danger">*</span></label>
                              <select id="mod_license_id" class="form-control" disabled>
                                <option value="">-- Select License --</option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <!-- Agent and Processing Details -->
                        <div class="form-section">
                          <div class="section-header">
                            <h5><i class="ti ti-user me-2"></i>Agent & Processing Details</h5>
                          </div>
                          <div class="row">
                            <div class="col-md-4 mb-3">
                              <label class="form-label">Transmission Number <span class="text-danger">*</span></label>
                              <input type="text" id="mod_transmission_number" class="form-control" maxlength="100">
                            </div>

                            <div class="col-md-4 mb-3">
                              <label class="form-label">Processing Fee ($) <span class="text-danger">*</span></label>
                              <input type="number" step="0.01" min="0" id="mod_processing_fee" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                              <label class="form-label">Agent Name <span class="text-danger">*</span></label>
                              <input type="text" id="mod_agent_name" class="form-control" readonly>
                            </div>

                            <div class="col-md-4 mb-3">
                              <label class="form-label">ID NAT Number <span class="text-danger">*</span></label>
                              <input type="text" id="mod_national_id" class="form-control" readonly>
                            </div>
                          </div>
                        </div>

                        <!-- License Types -->
                        <div class="license-types-section">
                          <div class="license-types-container">
                            <h3 class="model-title">MODÈLE</h3>
                            <div class="license-box-container">
                              <?php foreach(['EB', 'IB', 'ES', 'IS', 'RC'] as $type): ?>
                                <label class="license-box" data-form="modification">
                                  <input type="checkbox" value="<?= $type ?>" class="mod-license-type">
                                  <?= $type ?>
                                </label>
                              <?php endforeach; ?>
                            </div>
                          </div>
                        </div>

                        <!-- Modification Table -->
                        <div class="form-section">
                          <div class="section-header">
                            <h5><i class="ti ti-edit me-2"></i>Modification Details</h5>
                          </div>
                          <div class="table-responsive">
                            <table class="modification-table">
                              <thead>
                                <tr>
                                  <th>Field to Modify</th>
                                  <th>Before</th>
                                  <th>After</th>
                                  <th style="width: 80px;">Action</th>
                                </tr>
                              </thead>
                              <tbody id="modificationTableBody">
                                <!-- Rows will be added dynamically -->
                              </tbody>
                            </table>
                          </div>
                          <button type="button" class="btn btn-success btn-sm" id="addModRowBtn">
                            <i class="ti ti-plus me-1"></i>Add Row
                          </button>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- PROROGATION FORM -->
              <div id="prorogationFormContent" style="display:none;">
                <div class="accordion" id="prorogationAccordion">
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#prorogationDetails">
                        <i class="ti ti-clock me-2"></i>Prorogation Form
                      </button>
                    </h2>
                    <div id="prorogationDetails" class="accordion-collapse collapse show" data-bs-parent="#prorogationAccordion">
                      <div class="accordion-body">
                        
                        <!-- Bank and License Selection -->
                        <div class="form-section">
                          <div class="section-header">
                            <h5><i class="ti ti-building-bank me-2"></i>Bank & License Selection</h5>
                          </div>
                          <div class="row">
                            <div class="col-md-4 mb-3">
                              <label class="form-label">Select Bank <span class="text-danger">*</span></label>
                              <select id="pror_bank_id" class="form-control">
                                <option value="">-- Select Bank --</option>
                                <?php if (!empty($banks)): ?>
                                  <?php foreach ($banks as $bank): ?>
                                    <option value="<?= $bank['id'] ?>" 
                                            data-code="<?= htmlspecialchars($bank['bank_code'] ?? '') ?>"
                                            data-name="<?= htmlspecialchars($bank['bank_name'] ?? '') ?>">
                                      <?= htmlspecialchars($bank['bank_name'] ?? '') ?>
                                    </option>
                                  <?php endforeach; ?>
                                <?php endif; ?>
                              </select>
                            </div>

                            <div class="col-md-4 mb-3">
                              <label class="form-label">Bank Code <span class="text-danger">*</span></label>
                              <input type="text" id="pror_bank_code" class="form-control" readonly>
                            </div>

                            <div class="col-md-4 mb-3">
                              <label class="form-label">Select License <span class="text-danger">*</span></label>
                              <select id="pror_license_id" class="form-control" disabled>
                                <option value="">-- Select License --</option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <!-- Agent and Processing Details -->
                        <div class="form-section">
                          <div class="section-header">
                            <h5><i class="ti ti-user me-2"></i>Agent & Processing Details</h5>
                          </div>
                          <div class="row">
                            <div class="col-md-4 mb-3">
                              <label class="form-label">Transmission Number <span class="text-danger">*</span></label>
                              <input type="text" id="pror_transmission_number" class="form-control" maxlength="100">
                            </div>

                            <div class="col-md-4 mb-3">
                              <label class="form-label">Processing Fee ($) <span class="text-danger">*</span></label>
                              <input type="number" step="0.01" min="0" id="pror_processing_fee" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                              <label class="form-label">Agent Name <span class="text-danger">*</span></label>
                              <input type="text" id="pror_agent_name" class="form-control" readonly>
                            </div>

                            <div class="col-md-4 mb-3">
                              <label class="form-label">ID NAT Number <span class="text-danger">*</span></label>
                              <input type="text" id="pror_national_id" class="form-control" readonly>
                            </div>
                          </div>
                        </div>

                        <!-- License Types -->
                        <div class="license-types-section">
                          <div class="license-types-container">
                            <h3 class="model-title">MODÈLE</h3>
                            <div class="license-box-container">
                              <?php foreach(['EB', 'IB', 'ES', 'IS', 'RC'] as $type): ?>
                                <label class="license-box" data-form="prorogation">
                                  <input type="checkbox" value="<?= $type ?>" class="pror-license-type">
                                  <?= $type ?>
                                </label>
                              <?php endforeach; ?>
                            </div>
                          </div>
                        </div>

                        <!-- Prorogation Dates -->
                        <div class="form-section">
                          <div class="section-header">
                            <h5><i class="ti ti-calendar me-2"></i>Prorogation Details</h5>
                          </div>
                          <div class="row">
                            <div class="col-md-6 mb-3">
                              <label class="form-label">Initial Expiry Date</label>
                              <input type="date" id="pror_initial_expiry_date" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                              <label class="form-label">New Expiry Date <span class="text-danger">*</span></label>
                              <input type="date" id="pror_new_expiry_date" class="form-control">
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Form Buttons -->
              <div class="row mt-4">
                <div class="col-12 text-end">
                  <button type="button" class="btn btn-secondary" id="cancelBtn">
                    <i class="ti ti-x me-1"></i>Cancel
                  </button>
                  <button type="submit" class="btn btn-primary ms-2" id="submitBtn">
                    <i class="ti ti-check me-1"></i><span id="submitBtnText">Save Operation</span>
                  </button>
                </div>
              </div>

            </form>
          </div>
        </div>

        <!-- Records List Card -->
        <div class="card shadow-sm">
          <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
            <h4 class="header-title mb-0"><i class="ti ti-list me-2"></i>Records</h4>
            <button type="button" class="btn btn-export-all btn-sm" id="exportAllBtn">
              <i class="ti ti-file-spreadsheet me-1"></i>Export All to Excel
            </button>
          </div>

          <div class="card-body">
            <!-- Tabs -->
            <div class="premium-tabs">
              <button class="premium-tab active" data-tab="annulation">
                <i class="ti ti-ban me-2"></i>Annulations
              </button>
              <button class="premium-tab" data-tab="modification">
                <i class="ti ti-edit me-2"></i>Modifications
              </button>
              <button class="premium-tab" data-tab="prorogation">
                <i class="ti ti-clock me-2"></i>Prorogations
              </button>
            </div>

            <!-- DataTable -->
            <div class="table-responsive">
              <table id="recordsTable" class="table table-striped table-bordered dt-responsive nowrap w-100">
                <thead>
                  <tr id="tableHeaders">
                    <!-- Headers will be dynamically updated -->
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <?php include(VIEW_PATH . 'layouts/partials/footer.php'); ?>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewRecordModal" tabindex="-1" aria-labelledby="viewRecordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewRecordModalLabel">
          <i class="ti ti-eye me-2"></i>Record Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div id="modalDetailsContent">
          <!-- Details will be loaded here -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x me-1"></i>Close
        </button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  let currentType = 'annulation';
  let currentTab = 'annulation';
  let recordsTable = null;
  const APP_URL = '<?= APP_URL ?>';

  // ===== UPDATE STATISTICS =====
  function updateStatistics() {
    $.ajax({
      url: APP_URL + '/licensemodification/crudData/statistics',
      method: 'GET',
      dataType: 'json',
      success: function(res) {
        if (res.success && res.data) {
          $('#totalAnnulations').text(res.data.total_annulations || 0);
          $('#totalModifications').text(res.data.total_modifications || 0);
          $('#totalProrogations').text(res.data.total_prorogations || 0);
        }
      },
      error: function() {
        console.error('Failed to load statistics');
      }
    });
  }

  // ===== ACTION CARD SELECTION =====
  $('.action-card').on('click', function() {
    $('.action-card').removeClass('active');
    $(this).addClass('active');
    
    currentType = $(this).data('type');
    showForm(currentType);
  });

  // ===== SHOW FORM =====
  function showForm(type) {
    $('#formContainer').slideDown(300);
    $('#formTitle').html('<i class="ti ti-' + getTypeIcon(type) + ' me-2"></i>' + type.toUpperCase() + ' Form');
    
    // Reset all forms
    resetForms();
    
    // Hide all form contents
    $('#annulationFormContent, #modificationFormContent, #prorogationFormContent').hide();
    
    // Show the appropriate form
    if (type === 'annulation') {
      $('#annulationFormContent').show();
    } else if (type === 'modification') {
      $('#modificationFormContent').show();
      initModificationRows();
    } else if (type === 'prorogation') {
      $('#prorogationFormContent').show();
    }
    
    // Update submit button
    $('#submitBtnText').text('Save ' + type.charAt(0).toUpperCase() + type.slice(1));
    
    // Scroll to form
    $('html, body').animate({
      scrollTop: $('#formContainer').offset().top - 100
    }, 500);
  }

  // ===== RESET FORMS =====
  function resetForms() {
    $('#annul_bank_id, #mod_bank_id, #pror_bank_id').val('');
    $('#annul_bank_code, #mod_bank_code, #pror_bank_code').val('');
    $('#annul_license_id, #mod_license_id, #pror_license_id').html('<option value="">-- Select License --</option>').prop('disabled', true);
    $('#annul_transmission_number, #mod_transmission_number, #pror_transmission_number').val('');
    $('#annul_processing_fee, #mod_processing_fee, #pror_processing_fee').val('');
    $('#annul_agent_name, #mod_agent_name, #pror_agent_name').val('');
    $('#annul_national_id, #mod_national_id, #pror_national_id').val('');
    $('#pror_initial_expiry_date, #pror_new_expiry_date').val('');
    $('.license-box').removeClass('checked').find('input').prop('checked', false);
    $('#modificationTableBody').empty();
  }

  // ===== GET TYPE ICON =====
  function getTypeIcon(type) {
    const icons = {
      'annulation': 'ban',
      'modification': 'edit',
      'prorogation': 'clock'
    };
    return icons[type] || 'file';
  }

  // ===== CANCEL FORM =====
  $('#cancelFormBtn, #cancelBtn').on('click', function() {
    $('#formContainer').slideUp(300);
    $('.action-card').removeClass('active');
    resetForms();
  });

  // ===== BANK SELECTION CHANGE =====
  $(document).on('change', '#annul_bank_id, #mod_bank_id, #pror_bank_id', function() {
    const id = $(this).attr('id');
    const bankId = $(this).val();
    const $option = $(this).find('option:selected');
    const bankCode = $option.data('code');
    
    let prefix = id.split('_')[0];
    
    $('#' + prefix + '_bank_code').val(bankCode || '');
    $('#' + prefix + '_license_id').html('<option value="">-- Select License --</option>').prop('disabled', !bankId);
    
    if (bankId) {
      $.ajax({
        url: APP_URL + '/licensemodification/crudData/getLicenses',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ bank_id: bankId }),
        success: function(res) {
          if (res.success && res.data) {
            const $select = $('#' + prefix + '_license_id');
            res.data.forEach(license => {
              $select.append(
                $('<option></option>')
                  .val(license.id)
                  .text(license.license_number)
                  .data('license', license)
              );
            });
            $select.prop('disabled', false);
          }
        },
        error: function() {
          Swal.fire('Error', 'Failed to load licenses', 'error');
        }
      });
    }
  });

  // ===== LICENSE SELECTION CHANGE =====
  $(document).on('change', '#annul_license_id, #mod_license_id, #pror_license_id', function() {
    const license = $(this).find('option:selected').data('license');
    const id = $(this).attr('id');
    const prefix = id.split('_')[0];
    
    if (!license) {
      $('#' + prefix + '_agent_name').val('');
      $('#' + prefix + '_national_id').val('');
      if (prefix === 'pror') {
        $('#pror_initial_expiry_date').val('');
      }
      return;
    }

    $('#' + prefix + '_agent_name').val(license.client_name || '');
    $('#' + prefix + '_national_id').val(license.id_nat_number || '');
    
    if (prefix === 'pror') {
      $('#pror_initial_expiry_date').val(license.license_expiry_date || '');
    }
    
    // For modification form - populate before values
    if (prefix === 'mod') {
      $('#modificationTableBody tr').each(function() {
        const fieldName = $(this).find('.field-name').val().toLowerCase();
        const beforeInput = $(this).find('.before-value');
        
        const fieldMap = {
          'freight': license.freight,
          'fret': license.freight,
          'weight': license.weight,
          'poids': license.weight,
          'fob': license.fob_declared,
          'fob declared': license.fob_declared,
          'insurance': license.insurance,
          'assurance': license.insurance,
          'other costs': license.other_costs,
          'autres frais': license.other_costs
        };
        
        if (fieldMap[fieldName] !== undefined) {
          beforeInput.val(fieldMap[fieldName] || '');
        }
      });
    }
  });

  // ===== INITIALIZE MODIFICATION ROWS =====
  function initModificationRows() {
    const tbody = $('#modificationTableBody');
    tbody.empty();
    
    const fields = ['Freight', 'Weight', 'FOB Declared'];
    fields.forEach(field => {
      addModificationRow(field, '', '');
    });
  }

  // ===== ADD MODIFICATION ROW =====
  $('#addModRowBtn').on('click', function() {
    addModificationRow('', '', '');
  });

  function addModificationRow(field = '', before = '', after = '') {
    const row = `
      <tr>
        <td>
          <input type="text" class="form-control field-name" value="${field}" placeholder="e.g., Freight">
        </td>
        <td>
          <input type="text" class="form-control before-value" value="${before}" readonly>
        </td>
        <td>
          <input type="text" class="form-control after-value" value="${after}">
        </td>
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-danger remove-mod-row">
            <i class="ti ti-trash"></i>
          </button>
        </td>
      </tr>
    `;
    
    $('#modificationTableBody').append(row);
  }

  // ===== REMOVE MODIFICATION ROW =====
  $(document).on('click', '.remove-mod-row', function() {
    if ($('#modificationTableBody tr').length > 1) {
      $(this).closest('tr').remove();
    } else {
      Swal.fire({
        icon: 'warning',
        title: 'Warning',
        text: 'At least one modification row is required'
      });
    }
  });

  // ===== LICENSE TYPE CHECKBOXES =====
  $('.license-box').on('click', function(e) {
    e.preventDefault();
    const checkbox = $(this).find('input[type="checkbox"]');
    checkbox.prop('checked', !checkbox.prop('checked'));
    $(this).toggleClass('checked', checkbox.prop('checked'));
  });

  // ===== FORM SUBMISSION =====
  $('#operationForm').on('submit', function(e) {
    e.preventDefault();
    
    let prefix = '';
    let licenseTypesClass = '';
    
    if (currentType === 'annulation') {
      prefix = 'annul';
      licenseTypesClass = '.annul-license-type';
    } else if (currentType === 'modification') {
      prefix = 'mod';
      licenseTypesClass = '.mod-license-type';
    } else if (currentType === 'prorogation') {
      prefix = 'pror';
      licenseTypesClass = '.pror-license-type';
    }
    
    // Get form data
    const bankId = $('#' + prefix + '_bank_id').val();
    const licenseId = $('#' + prefix + '_license_id').val();
    const transmissionNumber = $('#' + prefix + '_transmission_number').val();
    const processingFee = $('#' + prefix + '_processing_fee').val();
    const agentName = $('#' + prefix + '_agent_name').val();
    const nationalId = $('#' + prefix + '_national_id').val();
    
    // Validate required fields
    if (!bankId) {
      Swal.fire('Error', 'Please select a bank', 'error');
      return;
    }
    
    if (!licenseId) {
      Swal.fire('Error', 'Please select a license', 'error');
      return;
    }
    
    if (!transmissionNumber) {
      Swal.fire('Error', 'Please enter transmission number', 'error');
      return;
    }
    
    if (!processingFee || parseFloat(processingFee) <= 0) {
      Swal.fire('Error', 'Please enter a valid processing fee', 'error');
      return;
    }
    
    if (!agentName || !nationalId) {
      Swal.fire('Error', 'Agent name and ID NAT number are required', 'error');
      return;
    }
    
    // Get license types
    const licenseTypes = [];
    $(licenseTypesClass + ':checked').each(function() {
      licenseTypes.push($(this).val());
    });
    
    // Get bank and license info
    const $bankOption = $('#' + prefix + '_bank_id option:selected');
    const $licenseOption = $('#' + prefix + '_license_id option:selected');
    
    const data = {
      license_id: licenseId,
      license_number: $licenseOption.text(),
      bank_name: $bankOption.data('name'),
      bank_code: $('#' + prefix + '_bank_code').val(),
      transmission_number: transmissionNumber,
      processing_fee: processingFee,
      agent_name: agentName,
      national_id: nationalId,
      license_types: licenseTypes
    };
    
    // Add type-specific data
    if (currentType === 'modification') {
      const beforeData = [];
      const afterData = [];
      
      let hasValidRow = false;
      $('#modificationTableBody tr').each(function() {
        const field = $(this).find('.field-name').val();
        const before = $(this).find('.before-value').val();
        const after = $(this).find('.after-value').val();
        
        if (field && after) {
          beforeData.push(field + ': ' + before);
          afterData.push(field + ': ' + after);
          hasValidRow = true;
        }
      });
      
      if (!hasValidRow) {
        Swal.fire('Error', 'Please fill in at least one modification row', 'error');
        return;
      }
      
      data.before_modification = beforeData.join('\n');
      data.after_modification = afterData.join('\n');
      
    } else if (currentType === 'prorogation') {
      const initialExpiryDate = $('#pror_initial_expiry_date').val();
      const newExpiryDate = $('#pror_new_expiry_date').val();
      
      if (!newExpiryDate) {
        Swal.fire('Error', 'Please enter the new expiry date', 'error');
        return;
      }
      
      if (initialExpiryDate && new Date(newExpiryDate) <= new Date(initialExpiryDate)) {
        Swal.fire('Error', 'New expiry date must be after the initial expiry date', 'error');
        return;
      }
      
      data.initial_expiry_date = initialExpiryDate;
      data.new_expiry_date = newExpiryDate;
    }
    
    const submitBtn = $('#submitBtn');
    const originalText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Saving...');
    
    $.ajax({
      url: APP_URL + '/licensemodification/crudData/' + currentType,
      method: 'POST',
      data: JSON.stringify(data),
      contentType: 'application/json',
      success: function(res) {
        submitBtn.prop('disabled', false).html(originalText);
        
        if (res.success) {
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            html: res.message + '<br><br>Would you like to generate a PDF?',
            showCancelButton: true,
            confirmButtonText: '<i class="ti ti-printer me-1"></i>Generate PDF',
            cancelButtonText: 'Close',
            confirmButtonColor: '#667eea'
          }).then((result) => {
            if (result.isConfirmed && res.id) {
              window.open(APP_URL + '/licensemodification/crudData/generatePDF?id=' + res.id + '&type=' + currentType, '_blank');
            }
            
            $('#formContainer').slideUp(300);
            $('.action-card').removeClass('active');
            resetForms();
            
            if (recordsTable) {
              recordsTable.ajax.reload(null, false);
            }
            updateStatistics();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: res.message
          });
        }
      },
      error: function(xhr) {
        submitBtn.prop('disabled', false).html(originalText);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to save record. Please try again.'
        });
      }
    });
  });

  // ===== TAB SWITCHING =====
  $('.premium-tab').on('click', function() {
    $('.premium-tab').removeClass('active');
    $(this).addClass('active');
    
    currentTab = $(this).data('tab');
    
    // Destroy existing table
    if (recordsTable) {
      recordsTable.destroy();
      recordsTable = null;
    }
    
    // Reinitialize with new tab
    initDataTable();
  });

  // ===== GET COLUMN CONFIGURATION =====
  function getColumnConfig() {
    const config = {
      annulation: {
        headers: [
          'License Number',
          'Bank',
          'Processing Fee',
          'Date',
          'Actions'
        ],
        columns: [
          { data: 'license_number', width: '20%' },
          { data: 'bank_name', width: '25%' },
          { 
            data: 'processing_fee',
            width: '15%',
            render: function(data) {
              return data ? '$' + parseFloat(data).toFixed(2) : '-';
            }
          },
          { 
            data: 'created_at',
            width: '20%',
            render: function(data) {
              return data ? new Date(data).toLocaleDateString() : '-';
            }
          },
          {
            data: null,
            width: '20%',
            orderable: false,
            className: 'text-center',
            render: function(data) {
              return `
                <button class="btn btn-sm btn-view viewBtn" data-id="${data.id}" data-type="${currentTab}" title="View">
                  <i class="ti ti-eye"></i>
                </button>
                <button class="btn btn-sm btn-warning printBtn" data-id="${data.id}" data-type="${currentTab}" title="Print PDF">
                  <i class="ti ti-printer"></i>
                </button>
                <button class="btn btn-sm btn-export exportBtn" data-id="${data.id}" data-type="${currentTab}" title="Export Excel">
                  <i class="ti ti-file-spreadsheet"></i>
                </button>
                <button class="btn btn-sm btn-danger deleteBtn" data-id="${data.id}" data-type="${currentTab}" title="Delete">
                  <i class="ti ti-trash"></i>
                </button>
              `;
            }
          }
        ]
      },
      modification: {
        headers: [
          'License Number',
          'Bank',
          'Before',
          'After',
          'Date',
          'Actions'
        ],
        columns: [
          { data: 'license_number', width: '15%' },
          { data: 'bank_name', width: '20%' },
          { 
            data: 'before_modification',
            width: '15%',
            render: function(data) {
              return data ? '<small>' + data.substring(0, 30) + '...</small>' : '-';
            }
          },
          { 
            data: 'after_modification',
            width: '15%',
            render: function(data) {
              return data ? '<small>' + data.substring(0, 30) + '...</small>' : '-';
            }
          },
          { 
            data: 'created_at',
            width: '15%',
            render: function(data) {
              return data ? new Date(data).toLocaleDateString() : '-';
            }
          },
          {
            data: null,
            width: '20%',
            orderable: false,
            className: 'text-center',
            render: function(data) {
              return `
                <button class="btn btn-sm btn-view viewBtn" data-id="${data.id}" data-type="${currentTab}" title="View">
                  <i class="ti ti-eye"></i>
                </button>
                <button class="btn btn-sm btn-warning printBtn" data-id="${data.id}" data-type="${currentTab}" title="Print PDF">
                  <i class="ti ti-printer"></i>
                </button>
                <button class="btn btn-sm btn-export exportBtn" data-id="${data.id}" data-type="${currentTab}" title="Export Excel">
                  <i class="ti ti-file-spreadsheet"></i>
                </button>
                <button class="btn btn-sm btn-danger deleteBtn" data-id="${data.id}" data-type="${currentTab}" title="Delete">
                  <i class="ti ti-trash"></i>
                </button>
              `;
            }
          }
        ]
      },
      prorogation: {
        headers: [
          'License Number',
          'Bank',
          'Initial Expiry',
          'New Expiry',
          'Date',
          'Actions'
        ],
        columns: [
          { data: 'license_number', width: '15%' },
          { data: 'bank_name', width: '20%' },
          { 
            data: 'initial_expiry_date',
            width: '15%',
            render: function(data) {
              return data ? new Date(data).toLocaleDateString() : '-';
            }
          },
          { 
            data: 'new_expiry_date',
            width: '15%',
            render: function(data) {
              return data ? new Date(data).toLocaleDateString() : '-';
            }
          },
          { 
            data: 'created_at',
            width: '15%',
            render: function(data) {
              return data ? new Date(data).toLocaleDateString() : '-';
            }
          },
          {
            data: null,
            width: '20%',
            orderable: false,
            className: 'text-center',
            render: function(data) {
              return `
                <button class="btn btn-sm btn-view viewBtn" data-id="${data.id}" data-type="${currentTab}" title="View">
                  <i class="ti ti-eye"></i>
                </button>
                <button class="btn btn-sm btn-warning printBtn" data-id="${data.id}" data-type="${currentTab}" title="Print PDF">
                  <i class="ti ti-printer"></i>
                </button>
                <button class="btn btn-sm btn-export exportBtn" data-id="${data.id}" data-type="${currentTab}" title="Export Excel">
                  <i class="ti ti-file-spreadsheet"></i>
                </button>
                <button class="btn btn-sm btn-danger deleteBtn" data-id="${data.id}" data-type="${currentTab}" title="Delete">
                  <i class="ti ti-trash"></i>
                </button>
              `;
            }
          }
        ]
      }
    };
    
    return config[currentTab];
  }

  // ===== INITIALIZE DATATABLE =====
  function initDataTable() {
    const columnConfig = getColumnConfig();
    
    // Update headers
    let headerHtml = '';
    columnConfig.headers.forEach(header => {
      headerHtml += '<th>' + header + '</th>';
    });
    $('#tableHeaders').html(headerHtml);
    
    // Initialize DataTable
    recordsTable = $('#recordsTable').DataTable({
      processing: true,
      serverSide: true,
      destroy: true,
      ajax: {
        url: APP_URL + '/licensemodification/crudData/listing',
        type: 'GET',
        data: function(d) {
          d.type = currentTab;
        },
        error: function(xhr, error, code) {
          console.error('DataTable Error:', error);
        }
      },
      columns: columnConfig.columns,
      order: [[columnConfig.columns.length - 2, 'desc']],
      pageLength: 25,
      lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
      language: {
        processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
        emptyTable: 'No records found',
        zeroRecords: 'No matching records found'
      },
      drawCallback: function() {
        // Add tooltips to buttons
        $('[title]').tooltip();
      }
    });
  }

  // ===== VIEW RECORD =====
  $(document).on('click', '.viewBtn', function() {
    const id = $(this).data('id');
    const type = $(this).data('type');
    
    $.ajax({
      url: APP_URL + '/licensemodification/crudData/getRecord',
      method: 'GET',
      data: { id: id, type: type },
      dataType: 'json',
      success: function(res) {
        if (res.success && res.data) {
          const record = res.data;
          let detailsHtml = `
            <div class="detail-row">
              <div class="row">
                <div class="col-md-6">
                  <div class="detail-label">
                    <i class="ti ti-file-text detail-icon"></i>License Number
                  </div>
                  <div class="detail-value">${record.license_number || 'N/A'}</div>
                </div>
                <div class="col-md-6">
                  <div class="detail-label">
                    <i class="ti ti-building-bank detail-icon"></i>Bank
                  </div>
                  <div class="detail-value">${record.bank_name || 'N/A'}</div>
                </div>
              </div>
            </div>
            
            <div class="detail-row">
              <div class="row">
                <div class="col-md-6">
                  <div class="detail-label">
                    <i class="ti ti-code detail-icon"></i>Bank Code
                  </div>
                  <div class="detail-value">${record.bank_code || 'N/A'}</div>
                </div>
                <div class="col-md-6">
                  <div class="detail-label">
                    <i class="ti ti-hash detail-icon"></i>Transmission Number
                  </div>
                  <div class="detail-value">${record.transmission_number || 'N/A'}</div>
                </div>
              </div>
            </div>
            
            <div class="detail-row">
              <div class="row">
                <div class="col-md-6">
                  <div class="detail-label">
                    <i class="ti ti-currency-dollar detail-icon"></i>Processing Fee
                  </div>
                  <div class="detail-value">$${parseFloat(record.processing_fee || 0).toFixed(2)}</div>
                </div>
                <div class="col-md-6">
                  <div class="detail-label">
                    <i class="ti ti-user detail-icon"></i>Agent Name
                  </div>
                  <div class="detail-value">${record.agent_name || 'N/A'}</div>
                </div>
              </div>
            </div>
            
            <div class="detail-row">
              <div class="row">
                <div class="col-md-6">
                  <div class="detail-label">
                    <i class="ti ti-id detail-icon"></i>ID NAT Number
                  </div>
                  <div class="detail-value">${record.id_nat_number || 'N/A'}</div>
                </div>
                <div class="col-md-6">
                  <div class="detail-label">
                    <i class="ti ti-calendar detail-icon"></i>Created Date
                  </div>
                  <div class="detail-value">${record.created_at ? new Date(record.created_at).toLocaleDateString() : 'N/A'}</div>
                </div>
              </div>
            </div>
          `;
          
          if (type === 'modification') {
            detailsHtml += `
              <div class="detail-row">
                <div class="detail-label">
                  <i class="ti ti-arrow-back detail-icon"></i>Before Modification
                </div>
                <div class="detail-value"><pre style="white-space: pre-wrap;">${record.before_modification || 'N/A'}</pre></div>
              </div>
              
              <div class="detail-row">
                <div class="detail-label">
                  <i class="ti ti-arrow-forward detail-icon"></i>After Modification
                </div>
                <div class="detail-value"><pre style="white-space: pre-wrap;">${record.after_modification || 'N/A'}</pre></div>
              </div>
            `;
          } else if (type === 'prorogation') {
            detailsHtml += `
              <div class="detail-row">
                <div class="row">
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-calendar-x detail-icon"></i>Initial Expiry Date
                    </div>
                    <div class="detail-value">${record.initial_expiry_date ? new Date(record.initial_expiry_date).toLocaleDateString() : 'N/A'}</div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-label">
                      <i class="ti ti-calendar-check detail-icon"></i>New Expiry Date
                    </div>
                    <div class="detail-value">${record.new_expiry_date ? new Date(record.new_expiry_date).toLocaleDateString() : 'N/A'}</div>
                  </div>
                </div>
              </div>
            `;
          }
          
          $('#modalDetailsContent').html(detailsHtml);
          $('#viewRecordModal').modal('show');
        } else {
          Swal.fire('Error', res.message || 'Failed to load record', 'error');
        }
      },
      error: function() {
        Swal.fire('Error', 'Failed to load record', 'error');
      }
    });
  });

  // ===== EXPORT RECORD =====
  $(document).on('click', '.exportBtn', function() {
    const id = $(this).data('id');
    const type = $(this).data('type');
    
    Swal.fire({
      title: 'Generating Excel...',
      text: 'Please wait',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    window.location.href = APP_URL + '/licensemodification/crudData/exportRecord?id=' + id + '&type=' + type;
    
    setTimeout(function() {
      Swal.close();
    }, 1000);
  });

  // ===== PRINT PDF =====
  $(document).on('click', '.printBtn', function() {
    const id = $(this).data('id');
    const type = $(this).data('type');
    
    window.open(APP_URL + '/licensemodification/crudData/generatePDF?id=' + id + '&type=' + type, '_blank');
  });

  // ===== EXPORT ALL =====
  $('#exportAllBtn').on('click', function() {
    Swal.fire({
      title: 'Generating Excel...',
      text: 'Please wait while we export all ' + currentTab + ' records',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    window.location.href = APP_URL + '/licensemodification/crudData/exportAll?type=' + currentTab;
    
    setTimeout(function() {
      Swal.close();
    }, 1500);
  });

  // ===== DELETE RECORD =====
  $(document).on('click', '.deleteBtn', function() {
    const id = $(this).data('id');
    const type = $(this).data('type');
    
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: APP_URL + '/licensemodification/crudData/delete',
          method: 'POST',
          data: { id: id, type: type },
          success: function(res) {
            if (res.success) {
              Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: res.message,
                timer: 1500,
                showConfirmButton: false
              });
              if (recordsTable) {
                recordsTable.ajax.reload(null, false);
              }
              updateStatistics();
            } else {
              Swal.fire('Error', res.message, 'error');
            }
          },
          error: function() {
            Swal.fire('Error', 'Failed to delete record', 'error');
          }
        });
      }
    });
  });

  // ===== INITIALIZE ON PAGE LOAD =====
  initDataTable();
  updateStatistics();
});
</script>