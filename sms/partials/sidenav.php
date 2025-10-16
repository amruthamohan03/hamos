<!-- Sidenav Menu Start -->
<div class="sidenav-menu">

    <!-- Brand Logo -->
    <a href="index.php" class="logo">
        <span class="logo-light">
            <span class="logo-lg"><img src="assets/images/logo.png" alt="logo"></span>
            <span class="logo-sm"><img src="assets/images/logo-sm.png" alt="small logo"></span>
        </span>

        <span class="logo-dark">
            <span class="logo-lg"><img src="assets/images/logo-dark.png" alt="dark logo"></span>
            <span class="logo-sm"><img src="assets/images/logo-sm.png" alt="small logo"></span>
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-sm-hover">
        <i class="ri-circle-line align-middle"></i>
    </button>

    <!-- Sidebar Menu Toggle Button -->
    <button class="sidenav-toggle-button">
        <i class="ri-menu-5-line fs-20"></i>
    </button>

    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-fullsidebar">
        <i class="ti ti-x align-middle"></i>
    </button>

    <div data-simplebar>

        <!-- User -->
        <div class="sidenav-user">
            <div class="dropdown-center text-center">
                <a class="topbar-link dropdown-toggle text-reset drop-arrow-none px-2" data-bs-toggle="dropdown"
                    type="button" aria-haspopup="false" aria-expanded="false">
                    <img src="assets/images/users/avatar-1.jpg" width="46" class="rounded-circle" alt="user-image">
                    <span class="d-flex gap-1 sidenav-user-name my-2">
                        <span>
                            <span class="mb-0 fw-semibold lh-base fs-15">Nowak Helme</span>
                            <p class="my-0 fs-13 text-muted">Admin Head</p>
                        </span>

                        <i class="ri-arrow-down-s-line d-block sidenav-user-arrow align-middle"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <i class="ri-account-circle-line me-1 fs-16 align-middle"></i>
                        <span class="align-middle">My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <i class="ri-wallet-3-line me-1 fs-16 align-middle"></i>
                        <span class="align-middle">Wallet : <span class="fw-semibold">$89.25k</span></span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <i class="ri-settings-2-line me-1 fs-16 align-middle"></i>
                        <span class="align-middle">Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <i class="ri-question-line me-1 fs-16 align-middle"></i>
                        <span class="align-middle">Support</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <i class="ri-lock-line me-1 fs-16 align-middle"></i>
                        <span class="align-middle">Lock Screen</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item active fw-semibold text-danger">
                        <i class="ri-logout-box-line me-1 fs-16 align-middle"></i>
                        <span class="align-middle">Sign Out</span>
                    </a>
                </div>
            </div>
        </div>

        <!--- Sidenav Menu -->
        <ul class="side-nav">
            <li class="side-nav-item">
                <a href="index.php" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                    <span class="menu-text"> Dashboard </span>
                    <span class="badge bg-danger rounded-pill">9+</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="apps-chat.php" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-message"></i></span>
                    <span class="menu-text"> Chat </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="apps-calendar.php" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-calendar"></i></span>
                    <span class="menu-text"> Calendar </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarContacts" aria-expanded="false"
                    aria-controls="sidebarContacts" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-user-square-rounded"></i></span>
                    <span class="menu-text"> Users</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarContacts">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="apps-user-contacts.php" class="side-nav-link">
                                <span class="menu-text">Contacts</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="apps-user-profile.php" class="side-nav-link">
                                <span class="menu-text">Profile</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="apps-email.php" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-mailbox"></i></span>
                    <span class="menu-text"> Email </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="apps-file-manager.php" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-folders"></i></span>
                    <span class="menu-text"> File Manager </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="apps-projects.php" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-briefcase"></i></span>
                    <span class="menu-text"> Projects </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarTasks" aria-expanded="false" aria-controls="sidebarTasks"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-layout-kanban"></i></span>
                    <span class="menu-text"> Tasks</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarTasks">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="apps-kanban.php" class="side-nav-link">
                                <span class="menu-text">Kanban</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="apps-task-details.php" class="side-nav-link">
                                <span class="menu-text">View Details</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarInvoice" aria-expanded="false" aria-controls="sidebarInvoice"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-invoice"></i></span>
                    <span class="menu-text"> Invoice</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarInvoice">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="apps-invoices.php" class="side-nav-link">
                                <span class="menu-text">Invoices</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="apps-invoice-details.php" class="side-nav-link">
                                <span class="menu-text">View Invoice</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="apps-invoice-create.php" class="side-nav-link">
                                <span class="menu-text">Create Invoice</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-title mt-2">
                Custom
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPages" aria-expanded="false" aria-controls="sidebarPages"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-package"></i></span>
                    <span class="menu-text"> Pages </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarPages">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="pages-starter.php" class="side-nav-link">
                                <span class="menu-text">Starter Page</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="pages-pricing.php" class="side-nav-link">
                                <span class="menu-text">Pricing</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="pages-faq.php" class="side-nav-link">
                                <span class="menu-text">FAQ</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="pages-maintenance.php" class="side-nav-link">
                                <span class="menu-text">Maintenance</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="pages-timeline.php" class="side-nav-link">
                                <span class="menu-text">Timeline</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="pages-coming-soon.php" class="side-nav-link">
                                <span class="menu-text">Coming Soon</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPagesAuth" aria-expanded="false"
                    aria-controls="sidebarPagesAuth" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-user-shield"></i></span>
                    <span class="menu-text"> Authentication </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarPagesAuth">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="auth-login.php" class="side-nav-link">
                                <span class="menu-text">Login</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="auth-register.php" class="side-nav-link">
                                <span class="menu-text">Register</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="auth-logout.php" class="side-nav-link">
                                <span class="menu-text">Logout</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="auth-recoverpw.php" class="side-nav-link">
                                <span class="menu-text">Recover Password</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="auth-createpw.php" class="side-nav-link">
                                <span class="menu-text">Create Password</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="auth-lock-screen.php" class="side-nav-link">
                                <span class="menu-text">Lock Screen</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="auth-confirm-mail.php" class="side-nav-link">
                                <span class="menu-text">Confirm Mail</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="auth-login-pin.php" class="side-nav-link">
                                <span class="menu-text">Login with PIN</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPagesError" aria-expanded="false"
                    aria-controls="sidebarPagesError" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-exclamation-circle"></i></span>
                    <span class="menu-text"> Error Pages </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarPagesError">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="error-401.php" class="side-nav-link">
                                <span class="menu-text">401 Unauthorized</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="error-400.php" class="side-nav-link">
                                <span class="menu-text">400 Bad Request</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="error-403.php" class="side-nav-link">
                                <span class="menu-text">403 Forbidden</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="error-404.php" class="side-nav-link">
                                <span class="menu-text">404 Not Found</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="error-500.php" class="side-nav-link">
                                <span class="menu-text">500 Internal Server</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="error-service-unavailable.php" class="side-nav-link">
                                <span class="menu-text">Service Unavailable</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="error-404-alt.php" class="side-nav-link">
                                <span class="menu-text">Error 404 Alt</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="widgets.php" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-layout-dashboard"></i></span>
                    <span class="menu-text"> Widgets </span>
                </a>
            </li>

            <li class="side-nav-title mt-2">Components</li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarBaseUI" aria-expanded="false" aria-controls="sidebarBaseUI"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-aperture"></i></span>
                    <span class="menu-text"> Base UI </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarBaseUI">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="ui-accordions.php" class="side-nav-link">
                                <span class="menu-text">Accordions</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-alerts.php" class="side-nav-link">
                                <span class="menu-text">Alerts</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-avatars.php" class="side-nav-link">
                                <span class="menu-text">Avatars</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-badges.php" class="side-nav-link">
                                <span class="menu-text">Badges</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-breadcrumb.php" class="side-nav-link">
                                <span class="menu-text">Breadcrumb</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-buttons.php" class="side-nav-link">
                                <span class="menu-text">Buttons</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-cards.php" class="side-nav-link">
                                <span class="menu-text">Cards</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-carousel.php" class="side-nav-link">
                                <span class="menu-text">Carousel</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-collapse.php" class="side-nav-link">
                                <span class="menu-text">Collapse</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-dropdowns.php" class="side-nav-link">
                                <span class="menu-text">Dropdowns</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-ratios.php" class="side-nav-link">
                                <span class="menu-text">Ratios</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-grid.php" class="side-nav-link">
                                <span class="menu-text">Grid</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-links.php" class="side-nav-link">
                                <span class="menu-text">Links</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-list-group.php" class="side-nav-link">
                                <span class="menu-text">List Group</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-modals.php" class="side-nav-link">
                                <span class="menu-text">Modals</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-notifications.php" class="side-nav-link">
                                <span class="menu-text">Notifications</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-offcanvas.php" class="side-nav-link">
                                <span class="menu-text">Offcanvas</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-placeholders.php" class="side-nav-link">
                                <span class="menu-text">Placeholders</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-pagination.php" class="side-nav-link">
                                <span class="menu-text">Pagination</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-popovers.php" class="side-nav-link">
                                <span class="menu-text">Popovers</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-progress.php" class="side-nav-link">
                                <span class="menu-text">Progress</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-scrollspy.php" class="side-nav-link">
                                <span class="menu-text">Scrollspy</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-spinners.php" class="side-nav-link">
                                <span class="menu-text">Spinners</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-tabs.php" class="side-nav-link">
                                <span class="menu-text">Tabs</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-tooltips.php" class="side-nav-link">
                                <span class="menu-text">Tooltips</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-typography.php" class="side-nav-link">
                                <span class="menu-text">Typography</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="ui-utilities.php" class="side-nav-link">
                                <span class="menu-text">Utilities</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarExtendedUI" aria-expanded="false"
                    aria-controls="sidebarExtendedUI" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-macro"></i></span>
                    <span class="menu-text"> Extended UI </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarExtendedUI">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="extended-dragula.php" class="side-nav-link">
                                <span class="menu-text">Dragula</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="extended-sweetalerts.php" class="side-nav-link">
                                <span class="menu-text">Sweet Alerts</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="extended-ratings.php" class="side-nav-link">
                                <span class="menu-text">Ratings</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="extended-scrollbar.php" class="side-nav-link">
                                <span class="menu-text">Scrollbar</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarIcons" aria-expanded="false" aria-controls="sidebarIcons"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-icons"></i></span>
                    <span class="menu-text"> Icons </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarIcons">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="icons-remix.php" class="side-nav-link">
                                <span class="menu-text">Remix</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="icons-tabler.php" class="side-nav-link">
                                <span class="menu-text">Tabler</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="icons-solar.php" class="side-nav-link">
                                <span class="menu-text">Solar</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarCharts" aria-expanded="false" aria-controls="sidebarCharts"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-chart-infographic"></i></span>
                    <span class="menu-text"> Charts </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarCharts">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="charts-apex-area.php" class="side-nav-link">
                                <span class="menu-text">Area</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-bar.php" class="side-nav-link">
                                <span class="menu-text">Bar</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-bubble.php" class="side-nav-link">
                                <span class="menu-text">Bubble</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-candlestick.php" class="side-nav-link">
                                <span class="menu-text">Candlestick</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-column.php" class="side-nav-link">
                                <span class="menu-text">Column</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-heatmap.php" class="side-nav-link">
                                <span class="menu-text">Heatmap</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-line.php" class="side-nav-link">
                                <span class="menu-text">Line</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-mixed.php" class="side-nav-link">
                                <span class="menu-text">Mixed</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-timeline.php" class="side-nav-link">
                                <span class="menu-text">Timeline</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-boxplot.php" class="side-nav-link">
                                <span class="menu-text">Boxplot</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-treemap.php" class="side-nav-link">
                                <span class="menu-text">Treemap</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-pie.php" class="side-nav-link">
                                <span class="menu-text">Pie</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-radar.php" class="side-nav-link">
                                <span class="menu-text">Radar</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-radialbar.php" class="side-nav-link">
                                <span class="menu-text">RadialBar</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-scatter.php" class="side-nav-link">
                                <span class="menu-text">Scatter</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-polar-area.php" class="side-nav-link">
                                <span class="menu-text">Polar Area</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-sparklines.php" class="side-nav-link">
                                <span class="menu-text">Sparklines</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-slope.php" class="side-nav-link">
                                <span class="menu-text">Slope</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="charts-apex-funnel.php" class="side-nav-link">
                                <span class="menu-text">Funnel</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarForms" aria-expanded="false" aria-controls="sidebarForms"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-list-details"></i></span>
                    <span class="menu-text"> Forms </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarForms">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="form-elements.php" class="side-nav-link">
                                <span class="menu-text">Basic Elements</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="form-inputmask.php" class="side-nav-link">
                                <span class="menu-text">Inputmask</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="form-picker.php" class="side-nav-link">
                                <span class="menu-text">Picker</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="form-select.php" class="side-nav-link">
                                <span class="menu-text">Select</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="form-range-slider.php" class="side-nav-link">
                                <span class="menu-text">Range Slider</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="form-validation.php" class="side-nav-link">
                                <span class="menu-text">Validation</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="form-wizard.php" class="side-nav-link">
                                <span class="menu-text">Wizard</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="form-fileuploads.php" class="side-nav-link">
                                <span class="menu-text">File Uploads</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="form-editors.php" class="side-nav-link">
                                <span class="menu-text">Editors</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="form-layouts.php" class="side-nav-link">
                                <span class="menu-text">Layouts</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarTables" aria-expanded="false" aria-controls="sidebarTables"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-table-row"></i></span>
                    <span class="menu-text"> Tables </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarTables">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="tables-basic.php" class="side-nav-link">
                                <span class="menu-text">Basic Tables</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="tables-gridjs.php" class="side-nav-link">
                                <span class="menu-text">Gridjs Tables</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="tables-datatable.php" class="side-nav-link">
                                <span class="menu-text">Datatable Tables</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarMaps" aria-expanded="false" aria-controls="sidebarMaps"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-map-2"></i></span>
                    <span class="menu-text"> Maps </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarMaps">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="maps-google.php" class="side-nav-link">
                                <span class="menu-text">Google Maps</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="maps-vector.php" class="side-nav-link">
                                <span class="menu-text">Vector Maps</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="maps-leaflet.php" class="side-nav-link">
                                <span class="menu-text">Leaflet Maps</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-title mt-2">
                More
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarLayouts" aria-expanded="false" aria-controls="sidebarLayouts"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-layout"></i></span>
                    <span class="menu-text"> Layouts </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarLayouts">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="layouts-horizontal.php" target="_blank" class="side-nav-link">Horizontal</a>
                        </li>
                        <li class="side-nav-item">
                            <a href="layouts-detached.php" target="_blank" class="side-nav-link">Detached</a>
                        </li>
                        <li class="side-nav-item">
                            <a href="layouts-full.php" target="_blank" class="side-nav-link">Full View</a>
                        </li>
                        <li class="side-nav-item">
                            <a href="layouts-fullscreen.php" target="_blank" class="side-nav-link">Fullscreen View</a>
                        </li>
                        <li class="side-nav-item">
                            <a href="layouts-hover.php" target="_blank" class="side-nav-link">Hover Menu</a>
                        </li>
                        <li class="side-nav-item">
                            <a href="layouts-compact.php" target="_blank" class="side-nav-link">Compact</a>
                        </li>
                        <li class="side-nav-item">
                            <a href="layouts-icon-view.php" target="_blank" class="side-nav-link">Icon View</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarMultiLevel" aria-expanded="false"
                    aria-controls="sidebarMultiLevel" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-share"></i></span>
                    <span class="menu-text"> Multi Level </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarMultiLevel">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarSecondLevel" aria-expanded="false"
                                aria-controls="sidebarSecondLevel" class="side-nav-link">
                                <span class="menu-text"> Second Level </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarSecondLevel">
                                <ul class="sub-menu">
                                    <li class="side-nav-item">
                                        <a href="javascript: void(0);" class="side-nav-link">
                                            <span class="menu-text">Item 1</span>
                                        </a>
                                    </li>
                                    <li class="side-nav-item">
                                        <a href="javascript: void(0);" class="side-nav-link">
                                            <span class="menu-text">Item 2</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarThirdLevel" aria-expanded="false"
                                aria-controls="sidebarThirdLevel" class="side-nav-link">
                                <span class="menu-text"> Third Level </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarThirdLevel">
                                <ul class="sub-menu">
                                    <li class="side-nav-item">
                                        <a href="javascript: void(0);" class="side-nav-link">Item 1</a>
                                    </li>
                                    <li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarFourthLevel" aria-expanded="false"
                                            aria-controls="sidebarFourthLevel" class="side-nav-link">
                                            <span class="menu-text"> Item 2 </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarFourthLevel">
                                            <ul class="sub-menu">
                                                <li class="side-nav-item">
                                                    <a href="javascript: void(0);" class="side-nav-link">
                                                        <span class="menu-text">Item 2.1</span>
                                                    </a>
                                                </li>
                                                <li class="side-nav-item">
                                                    <a href="javascript: void(0);" class="side-nav-link">
                                                        <span class="menu-text">Item 2.2</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>

        <!-- Help Box -->
        <div class="help-box text-center">
            <h5 class="fw-semibold fs-16">Unlimited Access</h5>
            <p class="mb-3 text-muted">Upgrade to plan to get access to unlimited reports</p>
            <a href="javascript: void(0);" class="btn btn-danger btn-sm">Upgrade</a>
        </div>

        <div class="clearfix"></div>
    </div>
</div>
<!-- Sidenav Menu End -->