<!-- Horizontal Menu Start -->
<header class="topnav">
    <nav class="navbar navbar-expand-lg">
        <nav class="page-container">
            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown hover-dropdown">
                        <a class="nav-link dropdown-toggle drop-arrow-none" href="index.php">
                            <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                            <span class="menu-text"> Dashboards </span>
                        </a>
                    </li>

                    <li class="nav-item dropdown hover-dropdown">
                        <a class="nav-link dropdown-toggle drop-arrow-none" href="#" id="topnav-apps" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="menu-icon"><i class="ti ti-apps"></i></span>
                            <span class="menu-text">Apps</span>
                            <div class="menu-arrow"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-apps">
                            <a href="apps-calendar.php" class="dropdown-item">Calendar</a>
                            <a href="apps-chat.php" class="dropdown-item">Chat</a>
                            <a href="apps-email.php" class="dropdown-item">Email</a>
                            <a href="apps-file-manager.php" class="dropdown-item">File Manager</a>
                            <a href="apps-projects.php" class="dropdown-item">Projects</a>
                            <div class="dropdown hover-dropdown">
                                <a class="dropdown-item dropdown-toggle drop-arrow-none" href="#" id="topnav-user"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    User
                                    <div class="menu-arrow"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-user">
                                    <a href="apps-user-contacts.php" class="dropdown-item">Contacts</a>
                                    <a href="apps-user-profile.php" class="dropdown-item">Profile</a>
                                </div>
                            </div>

                            <div class="dropdown hover-dropdown">
                                <a class="dropdown-item dropdown-toggle drop-arrow-none" href="#" id="topnav-tasks"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Tasks
                                    <div class="menu-arrow"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-tasks">
                                    <a href="apps-kanban.php" class="dropdown-item">Kanban</a>
                                    <a href="apps-task-details.php" class="dropdown-item">View Details</a>
                                </div>
                            </div>

                            <div class="dropdown hover-dropdown">
                                <a class="dropdown-item dropdown-toggle drop-arrow-none" href="#" id="topnav-invoices"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Invoice
                                    <div class="menu-arrow"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-invoices">
                                    <a href="apps-invoices.php" class="dropdown-item">Invoices</a>
                                    <a href="apps-invoice-details.php" class="dropdown-item">View Invoice</a>
                                    <a href="apps-invoice-create.php" class="dropdown-item">Create Invoice</a>
                                </div>
                            </div>

                        </div>
                    </li>

                    <li class="nav-item dropdown hover-dropdown">
                        <a class="nav-link dropdown-toggle drop-arrow-none" href="#" id="topnav-pages" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="menu-icon"><i class="ti ti-file-description"></i></span>
                            <span class="menu-text">Pages</span>
                            <div class="menu-arrow"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-pages">
                            <div class="dropdown hover-dropdown">
                                <a class="dropdown-item dropdown-toggle drop-arrow-none" href="#" id="topnav-auth"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Authentication <div class="menu-arrow"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="auth-login.php" class="dropdown-item">Login</a>
                                    <a href="auth-register.php" class="dropdown-item">Register</a>
                                    <a href="auth-logout.php" class="dropdown-item">Logout</a>
                                    <a href="auth-recoverpw.php" class="dropdown-item">Recover Password</a>
                                    <a href="auth-createpw.php" class="dropdown-item">Create Password</a>
                                    <a href="auth-lock-screen.php" class="dropdown-item">Lock Screen</a>
                                    <a href="auth-confirm-mail.php" class="dropdown-item">Confirm Mail</a>
                                    <a href="auth-login-pin.php" class="dropdown-item">Login with PIN</a>
                                </div>
                            </div>
                            <div class="dropdown hover-dropdown">
                                <a class="dropdown-item dropdown-toggle drop-arrow-none" href="#" id="topnav-error"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Error <div class="menu-arrow"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-error">
                                    <a href="error-401.php" class="dropdown-item">401 Unauthorized</a>
                                    <a href="error-400.php" class="dropdown-item">400 Bad Request</a>
                                    <a href="error-403.php" class="dropdown-item">403 Forbidden</a>
                                    <a href="error-404.php" class="dropdown-item">404 Not Found</a>
                                    <a href="error-500.php" class="dropdown-item">500 Internal Server</a>
                                    <a href="error-service-unavailable.php" class="dropdown-item">Service
                                        Unavailable</a>
                                    <a href="error-404-alt.php" class="dropdown-item">Error 404 Alt</a>
                                </div>
                            </div>
                            <a href="pages-starter.php" class="dropdown-item">Starter Page</a>
                            <a href="pages-faq.php" class="dropdown-item">FAQ</a>
                            <a href="pages-pricing.php" class="dropdown-item">Pricing</a>
                            <a href="pages-maintenance.php" class="dropdown-item">Maintenance</a>
                            <a href="pages-timeline.php" class="dropdown-item">Timeline</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown hover-dropdown">
                        <a class="nav-link dropdown-toggle drop-arrow-none" href="#" id="topnav-components"
                            role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="menu-icon"><i class="ti ti-components"></i></span>
                            <span class="menu-text">Components</span>
                            <div class="menu-arrow"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <div class="dropdown hover-dropdown">
                                <a class="dropdown-item dropdown-toggle drop-arrow-none" href="#" id="topnav-ui-kit"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Base UI 1
                                    <div class="menu-arrow"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-ui-kit">
                                    <a href="ui-accordions.php" class="dropdown-item">Accordions</a>
                                    <a href="ui-alerts.php" class="dropdown-item">Alerts</a>
                                    <a href="ui-avatars.php" class="dropdown-item">Avatars</a>
                                    <a href="ui-badges.php" class="dropdown-item">Badges</a>
                                    <a href="ui-breadcrumb.php" class="dropdown-item">Breadcrumb</a>
                                    <a href="ui-buttons.php" class="dropdown-item">Buttons</a>
                                    <a href="ui-cards.php" class="dropdown-item">Cards</a>
                                    <a href="ui-carousel.php" class="dropdown-item">Carousel</a>
                                    <a href="ui-dropdowns.php" class="dropdown-item">Dropdowns</a>
                                    <a href="ui-embed-video.php" class="dropdown-item">Embed Video</a>
                                    <a href="ui-grid.php" class="dropdown-item">Grid</a>
                                    <a href="ui-list-group.php" class="dropdown-item">List Group</a>
                                </div>
                            </div>
                            <div class="dropdown hover-dropdown">
                                <a class="dropdown-item dropdown-toggle drop-arrow-none" href="#" id="topnav-ui-kit2"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Base UI 2
                                    <div class="menu-arrow"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-ui-kit2">
                                    <a href="ui-modals.php" class="dropdown-item">Modals</a>
                                    <a href="ui-notifications.php" class="dropdown-item">Notifications</a>
                                    <a href="ui-offcanvas.php" class="dropdown-item">Offcanvas</a>
                                    <a href="ui-placeholders.php" class="dropdown-item">Placeholders</a>
                                    <a href="ui-pagination.php" class="dropdown-item">Pagination</a>
                                    <a href="ui-popovers.php" class="dropdown-item">Popovers</a>
                                    <a href="ui-progress.php" class="dropdown-item">Progress</a>
                                    <a href="ui-ribbons.php" class="dropdown-item">Ribbons</a>
                                    <a href="ui-spinners.php" class="dropdown-item">Spinners</a>
                                    <a href="ui-tabs.php" class="dropdown-item">Tabs</a>
                                    <a href="ui-tooltips.php" class="dropdown-item">Tooltips</a>
                                    <a href="ui-links.php" class="dropdown-item">Links</a>
                                    <a href="ui-typography.php" class="dropdown-item">Typography</a>
                                    <a href="ui-utilities.php" class="dropdown-item">Utilities</a>
                                </div>
                            </div>
                            <div class="dropdown hover-dropdown">
                                <a class="dropdown-item dropdown-toggle drop-arrow-none" href="#"
                                    id="topnav-extended-ui" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Extended UI
                                    <div class="menu-arrow"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-extended-ui">
                                    <a href="extended-dragula.php" class="dropdown-item">Dragula</a>
                                    <a href="extended-sweetalerts.php" class="dropdown-item">Sweet Alerts</a>
                                    <a href="extended-ratings.php" class="dropdown-item">Ratings</a>
                                    <a href="extended-scrollbar.php" class="dropdown-item">Scrollbar</a>
                                </div>
                            </div>
                            <div class="dropdown hover-dropdown">
                                <a class="dropdown-item dropdown-toggle drop-arrow-none" href="#" id="topnav-forms"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Forms
                                    <div class="menu-arrow"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-forms">
                                    <a href="form-elements.php" class="dropdown-item">Basic Elements</a>
                                    <a href="form-inputmask.php" class="dropdown-item">Inputmask</a>
                                    <a href="form-picker.php" class="dropdown-item">Picker</a>
                                    <a href="form-select.php" class="dropdown-item">Select</a>
                                    <a href="form-range-slider.php" class="dropdown-item">Range Slider</a>
                                    <a href="form-validation.php" class="dropdown-item">Validation</a>
                                    <a href="form-wizard.php" class="dropdown-item">Wizard</a>
                                    <a href="form-fileuploads.php" class="dropdown-item">File Uploads</a>
                                    <a href="form-editors.php" class="dropdown-item">Editors</a>
                                    <a href="form-layouts.php" class="dropdown-item">Layouts</a>
                                </div>
                            </div>
                            <div class="dropdown hover-dropdown">
                                <a class="dropdown-item dropdown-toggle drop-arrow-none" href="#" id="topnav-charts"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Charts
                                    <div class="menu-arrow"></div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-lg" aria-labelledby="topnav-charts">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="charts-apex-area.php" class="dropdown-item">Area</a>
                                            <a href="charts-apex-bar.php" class="dropdown-item">Bar</a>
                                            <a href="charts-apex-bubble.php" class="dropdown-item">Bubble</a>
                                            <a href="charts-apex-candlestick.php" class="dropdown-item">Candlestick</a>
                                            <a href="charts-apex-column.php" class="dropdown-item">Column</a>
                                            <a href="charts-apex-heatmap.php" class="dropdown-item">Heatmap</a>
                                            <a href="charts-apex-line.php" class="dropdown-item">Line</a>
                                            <a href="charts-apex-mixed.php" class="dropdown-item">Mixed</a>
                                            <a href="charts-apex-timeline.php" class="dropdown-item">Timeline</a>
                                            <a href="charts-apex-boxplot.php" class="dropdown-item">Boxplot</a>
                                        </div>

                                        <div class="col-md-6">
                                            <a href="charts-apex-treemap.php" class="dropdown-item">Treemap</a>
                                            <a href="charts-apex-pie.php" class="dropdown-item">Pie</a>
                                            <a href="charts-apex-radar.php" class="dropdown-item">Radar</a>
                                            <a href="charts-apex-radialbar.php" class="dropdown-item">RadialBar</a>
                                            <a href="charts-apex-scatter.php" class="dropdown-item">Scatter</a>
                                            <a href="charts-apex-polar-area.php" class="dropdown-item">Polar Area</a>
                                            <a href="charts-apex-sparklines.php" class="dropdown-item">Sparklines</a>
                                            <a href="charts-apex-slope.php" class="dropdown-item">Slope</a>
                                            <a href="charts-apex-funnel.php" class="dropdown-item">Funnel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown hover-dropdown">
                                <a class="dropdown-item dropdown-toggle drop-arrow-none" href="#" id="topnav-tables"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Tables
                                    <div class="menu-arrow"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-tables">
                                    <a href="tables-basic.php" class="dropdown-item">Basic Tables</a>
                                    <a href="tables-gridjs.php" class="dropdown-item">Gridjs Tables</a>
                                    <a href=".tables-datatable.php" class="dropdown-item">Datatable Tables</a>
                                </div>
                            </div>
                            <div class="dropdown hover-dropdown">
                                <a class="dropdown-item dropdown-toggle drop-arrow-none" href="#" id="topnav-icons"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Icons
                                    <div class="menu-arrow"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-icons">
                                    <a href="icons-tabler.php" class="dropdown-item">Tabler Icons</a>
                                    <a href="icons-remix.php" class="dropdown-item">Remixicon Icons</a>
                                    <a href="icons-solar.php" class="dropdown-item">Solar Design</a>
                                </div>
                            </div>
                            <div class="dropdown hover-dropdown">
                                <a class="dropdown-item dropdown-toggle drop-arrow-none" href="#" id="topnav-maps"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Maps
                                    <div class="menu-arrow"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-maps">
                                    <a href="maps-google.php" class="dropdown-item">Google Maps</a>
                                    <a href="maps-vector.php" class="dropdown-item">Vector Maps</a>
                                    <a href="maps-leaflet.php" class="dropdown-item">Leaflet Maps</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown hover-dropdown">
                        <a class="nav-link dropdown-toggle drop-arrow-none" href="#" id="topnav-layouts" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="menu-icon"><i class="ti ti-layout"></i></span>
                            <span class="menu-text">Layouts</span>
                            <div class="menu-arrow"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-layouts">
                            <a href="index.php" class="dropdown-item" target="_blank">Vertical</a>
                            <a href="layouts-horizontal.php" class="dropdown-item" target="_blank">Horizontal</a>
                            <a href="layouts-detached.php" class="dropdown-item" target="_blank">Detached</a>
                            <a href="layouts-full.php" class="dropdown-item" target="_blank">Full</a>
                            <a href="layouts-fullscreen.php" class="dropdown-item" target="_blank">Fullscreen</a>
                            <a href="layouts-hover.php" class="dropdown-item" target="_blank">Hover Menu</a>
                            <a href="layouts-compact.php" class="dropdown-item" target="_blank">Compact Menu</a>
                            <a href="layouts-icon-view.php" class="dropdown-item" target="_blank">Icon View</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </nav>
</header>
<!-- Horizontal Menu End -->