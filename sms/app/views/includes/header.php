<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>HAMOS | Dashboard</title>

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance." />
    <meta name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant" />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="<?php echo BASE_URL; ?>/dist/css/adminlte.css" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
        onload="this.media='all'" />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/dist/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->

    <!-- apexcharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />

    <!-- jsvectormap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Start Navbar Links-->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="#" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="#" class="nav-link">Contact</a>
                    </li>
                </ul>
                <!--end::Start Navbar Links-->

                <!--begin::End Navbar Links-->
                <ul class="navbar-nav ms-auto">
                    <!--begin::Navbar Search-->
                    <li class="nav-item">
                        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                            <i class="bi bi-search"></i>
                        </a>
                    </li>
                    <!--end::Navbar Search-->

                    <!--begin::Messages Dropdown Menu-->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-chat-text"></i>
                            <span class="navbar-badge badge text-bg-danger">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <a href="#" class="dropdown-item">
                                <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="./assets/img/user1-128x128.jpg" alt="User Avatar"
                                            class="img-size-50 rounded-circle me-3" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="dropdown-item-title">
                                            Brad Diesel
                                            <span class="float-end fs-7 text-danger"><i
                                                    class="bi bi-star-fill"></i></span>
                                        </h3>
                                        <p class="fs-7">Call me whenever you can...</p>
                                        <p class="fs-7 text-secondary">
                                            <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                <!--end::Message-->
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="./assets/img/user8-128x128.jpg" alt="User Avatar"
                                            class="img-size-50 rounded-circle me-3" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="dropdown-item-title">
                                            John Pierce
                                            <span class="float-end fs-7 text-secondary">
                                                <i class="bi bi-star-fill"></i>
                                            </span>
                                        </h3>
                                        <p class="fs-7">I got your message bro</p>
                                        <p class="fs-7 text-secondary">
                                            <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                <!--end::Message-->
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="./assets/img/user3-128x128.jpg" alt="User Avatar"
                                            class="img-size-50 rounded-circle me-3" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="dropdown-item-title">
                                            Nora Silvester
                                            <span class="float-end fs-7 text-warning">
                                                <i class="bi bi-star-fill"></i>
                                            </span>
                                        </h3>
                                        <p class="fs-7">The subject goes here</p>
                                        <p class="fs-7 text-secondary">
                                            <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                <!--end::Message-->
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                        </div>
                    </li>
                    <!--end::Messages Dropdown Menu-->

                    <!--begin::Notifications Dropdown Menu-->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-bell-fill"></i>
                            <span class="navbar-badge badge text-bg-warning">15</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <span class="dropdown-item dropdown-header">15 Notifications</span>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-envelope me-2"></i> 4 new messages
                                <span class="float-end text-secondary fs-7">3 mins</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-people-fill me-2"></i> 8 friend requests
                                <span class="float-end text-secondary fs-7">12 hours</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                                <span class="float-end text-secondary fs-7">2 days</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
                        </div>
                    </li>
                    <!--end::Notifications Dropdown Menu-->

                    <!--begin::Fullscreen Toggle-->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                        </a>
                    </li>
                    <!--end::Fullscreen Toggle-->

                    <!--begin::User Menu Dropdown-->
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="./assets/img/user2-160x160.jpg" class="user-image rounded-circle shadow"
                                alt="User Image" />
                            <span class="d-none d-md-inline">Alexander Pierce</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <!--begin::User Image-->
                            <li class="user-header text-bg-primary">
                                <img src="./assets/img/user2-160x160.jpg" class="rounded-circle shadow"
                                    alt="User Image" />
                                <p>
                                    Alexander Pierce - Web Developer
                                    <small>Member since Nov. 2023</small>
                                </p>
                            </li>
                            <!--end::User Image-->
                            <!--begin::Menu Body-->
                            <li class="user-body">
                                <!--begin::Row-->
                                <div class="row">
                                    <div class="col-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </div>
                                <!--end::Row-->
                            </li>
                            <!--end::Menu Body-->
                            <!--begin::Menu Footer-->
                            <li class="user-footer">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                                <a href="#" class="btn btn-default btn-flat float-end">Sign out</a>
                            </li>
                            <!--end::Menu Footer-->
                        </ul>
                    </li>
                    <!--end::User Menu Dropdown-->
                </ul>
                <!--end::End Navbar Links-->
            </div>
            <!--end::Container-->
        </nav>
        <!--end::Header-->
        <!--begin::Sidebar-->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
                <!--begin::Brand Link-->
                <a href="./index.html" class="brand-link">
                    <!--begin::Brand Image-->
                    <!-- <img src="./assets/img/AdminLTELogo.png" alt="AdminLTE Logo"
                        class="brand-image opacity-75 shadow" /> -->
                    <!--end::Brand Image-->
                    <!--begin::Brand Text-->
                    <span class="brand-text fw-light">HAMOS</span>
                    <!--end::Brand Text-->
                </a>
                <!--end::Brand Link-->
            </div>
            <!--end::Sidebar Brand-->
            <!--begin::Sidebar Wrapper-->


            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <?php
                    require_once __DIR__ . '/../../core/Database.php';

                    function getMenuData()
                    {
                        $db = new Database();
                        return $db->selectData('menu_master_t', '*', []); // display='Y' already handled
                    }

                    /**
                     * Find all root-level menus (where menu_id = id or menu_id = 0)
                     */
                    function getRootMenus($menuItems)
                    {
                        $roots = [];
                        foreach ($menuItems as $item) {
                            if ((int) $item['menu_id'] === 0 || (int) $item['menu_id'] === (int) $item['id']) {
                                $roots[] = $item;
                            }
                        }
                        return $roots;
                    }

                    /**
                     * Recursively build child menus
                     */
                    function buildMenuTree($menuItems, $parentId)
                    {
                        $branch = [];
                        foreach ($menuItems as $item) {
                            if ((int) $item['menu_id'] === (int) $parentId && (int) $item['id'] !== (int) $parentId) {
                                $children = buildMenuTree($menuItems, $item['id']);
                                if (!empty($children)) {
                                    $item['children'] = $children;
                                }
                                $branch[] = $item;
                            }
                        }
                        return $branch;
                    }

                    /**
                     * Render menu HTML
                     */
                    function renderMenu($menuTree)
                    {
                        $html = '<ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">';
                        foreach ($menuTree as $menu) {
                            $hasChildren = isset($menu['children']) && !empty($menu['children']);

                            $html .= '<li class="nav-item">';
                            $html .= '<a href="' .APP_ROOT. htmlspecialchars($menu['url'] ?: '#') . '" class="nav-link">';
                            if (!empty($menu['icon'])) {
                                $html .= '<i class="' . htmlspecialchars($menu['icon']) . '"></i>';
                            }
                            $html .= '<p>' . htmlspecialchars($menu['menu_name']);
                            if ($hasChildren) {
                                $html .= '<i class="nav-arrow bi bi-chevron-right"></i>';
                            }
                            $html .= '</p></a>';

                            if ($hasChildren) {
                                $html .= '<ul class="nav nav-treeview">';
                                foreach ($menu['children'] as $child) {
                                    $html .= '<li class="nav-item">';
                                    $html .= '<a href="' . htmlspecialchars($child['url'] ?: '#') . '" class="nav-link">';
                                    $html .= '<i class="nav-icon bi bi-circle"></i>';
                                    $html .= '<p>' . htmlspecialchars($child['menu_name']) . '</p>';
                                    $html .= '</a>';
                                    $html .= '</li>';
                                }
                                $html .= '</ul>';
                            }

                            $html .= '</li>';
                        }
                        $html .= '</ul>';
                        return $html;
                    }

                    // --- MAIN EXECUTION ---
                    $menuItems = getMenuData();

                    // Get only roots (menu_id = id or 0)
                    $rootMenus = getRootMenus($menuItems);

                    // Build tree for each root
                    $menuTree = [];
                    foreach ($rootMenus as $root) {
                        $root['children'] = buildMenuTree($menuItems, $root['id']);
                        $menuTree[] = $root;
                    }

                    echo renderMenu($menuTree);
                    ?>



                    <!--begin::Sidebar Menu-->
                    <!-- <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                        aria-label="Main navigation" data-accordion="false" id="navigation">
                        <li class="nav-item menu-open">
                            <a href="dashboard" class="nav-link active">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>
                                    Dashboard
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-box-seam-fill"></i>
                                <p>
                                    Widgets
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./widgets/small-box.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Small Box</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./widgets/info-box.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>info Box</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./widgets/cards.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Cards</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul> -->
                    <!--end::Sidebar Menu-->
                </nav>
            </div>
            <!--end::Sidebar Wrapper-->
        </aside>