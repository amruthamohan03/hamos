<style>
    /* CSS Variables for Light and Dark Mode */
    :root, [data-bs-theme="light"] {
        --bg-primary: #f8fafc;
        --bg-card: #ffffff;
        --text-title: #64748b;
        --text-number: #1e293b;
        --text-subtitle: #10b981;
        --border-color: rgba(0, 0, 0, 0.08);
        --shadow-color: rgba(0, 0, 0, 0.08);
        --card-hover-shadow: rgba(0, 0, 0, 0.12);
    }

    [data-bs-theme="dark"] {
        --bg-primary: #0f172a;
        --bg-card: #1e293b;
        --text-title: #94a3b8;
        --text-number: #f1f5f9;
        --text-subtitle: #34d399;
        --border-color: rgba(255, 255, 255, 0.1);
        --shadow-color: rgba(0, 0, 0, 0.3);
        --card-hover-shadow: rgba(0, 0, 0, 0.5);
    }

    /* Premium Card Styling */
    .page-content {
        background: var(--bg-primary);
        padding: 20px;
        min-height: 100vh;
    }

    .page-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: 0 4px 20px var(--shadow-color);
        overflow: hidden;
        height: 100%;
        position: relative;
        padding: 24px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px var(--card-hover-shadow);
    }

    .card-header {
        padding: 0;
        background: transparent;
        border: none;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .header-title {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-title);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #ffffff;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Different icon colors for each card - vibrant gradients */
    .col:nth-child(1) .icon-wrapper {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    }
    .col:nth-child(2) .icon-wrapper {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    }
    .col:nth-child(3) .icon-wrapper {
        background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
    }
    .col:nth-child(4) .icon-wrapper {
        background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
    }
    .col:nth-child(5) .icon-wrapper {
        background: linear-gradient(135deg, #65a30d 0%, #84cc16 100%);
    }
    .col:nth-child(6) .icon-wrapper {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
    }
    .col:nth-child(7) .icon-wrapper {
        background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
    }
    .col:nth-child(8) .icon-wrapper {
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
    }
    .col:nth-child(9) .icon-wrapper {
        background: linear-gradient(135deg, #db2777 0%, #ec4899 100%);
    }
    .col:nth-child(10) .icon-wrapper {
        background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
    }
    .col:nth-child(11) .icon-wrapper {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    }
    .col:nth-child(12) .icon-wrapper {
        background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
    }

    .card-body {
        padding: 0;
    }

    .card-number {
        font-size: 48px;
        font-weight: 700;
        color: var(--text-number);
        line-height: 1;
        margin-bottom: 8px;
        letter-spacing: -1px;
    }

    .card-subtitle {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-subtitle);
        margin: 0;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .page-content {
            padding: 10px;
        }
        
        .card {
            padding: 20px;
        }

        .card-number {
            font-size: 36px;
        }

        .icon-wrapper {
            width: 48px;
            height: 48px;
            font-size: 20px;
        }

        .header-title {
            font-size: 12px;
        }
    }
</style>

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="page-content">
    <div class="page-container">

        <!-- 12 Cards Grid -->
        <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1 g-4">
            <div class="col">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="header-title">Total Users</h4>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="card-number">40</div>
                        <p class="card-subtitle">Active: 40</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="header-title">Total Clients</h4>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="card-number">63</div>
                        <p class="card-subtitle">Active: 63</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="header-title">Total Licenses</h4>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="card-number">11</div>
                        <p class="card-subtitle">Active: 11</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="header-title">Import Logistics</h4>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-truck"></i>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="card-number">12</div>
                        <p class="card-subtitle">Active: 12</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="header-title">Import Overview</h4>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="card-number">15</div>
                        <p class="card-subtitle">Total Files: 15</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="header-title">Import KPI</h4>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="card-number">15</div>
                        <p class="card-subtitle">Performance Metrics</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="header-title">Import Delay KPI</h4>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="card-number">0</div>
                        <p class="card-subtitle">Delay Analysis</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="header-title">Export Logistics</h4>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-truck-loading"></i>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="card-number">0</div>
                        <p class="card-subtitle">Definitive: 0</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="header-title">Export KPI</h4>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="card-number">$0K</div>
                        <p class="card-subtitle">Temporary: 0</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="header-title">Fiche De Calcul</h4>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-calculator"></i>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="card-number">0</div>
                        <p class="card-subtitle">Active: 0</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="header-title">Import Invoices</h4>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="card-number">0</div>
                        <p class="card-subtitle">Processed: 0</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="header-title">Card Title 12</h4>
                        </div>
                        <div class="icon-wrapper">
                            <i class="fas fa-cog"></i>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="card-number">25</div>
                        <p class="card-subtitle">Status: Active</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <?php include(VIEW_PATH . 'layouts/partials/footer.php'); ?>

</div>