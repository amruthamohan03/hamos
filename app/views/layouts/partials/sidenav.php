<!-- Sidenav Menu Start -->
<div class="sidenav-menu">

    <!-- Brand Logo -->
    <a href="index.php" class="logo">
        <span class="logo-light">
            <span class="logo-lg"><img src="<?php echo BASE_URL;?>/assets/images/logo.png" alt="logo"></span>
            <span class="logo-sm"><img src="<?php echo BASE_URL;?>/assets/images/logo-sm.png" alt="small logo"></span>
        </span>

        <span class="logo-dark">
            <span class="logo-lg"><img src="<?php echo BASE_URL;?>/assets/images/logo-dark.png" alt="dark logo"></span>
            <span class="logo-sm"><img src="<?php echo BASE_URL;?>/assets/images/logo-sm.png" alt="small logo"></span>
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

        <!--- Sidenav Menu -->
        <?php $menuItems = $this->getMenu();?>
        <ul class="side-nav">
            <?php foreach ($menuItems as $item): ?>
                <li class="side-nav-item">
                    <?php if (!empty($item->submenu)): ?>
                        <a data-bs-toggle="collapse" href="#submenu<?= $item->id; ?>" aria-expanded="false"
                        aria-controls="submenu<?= $item->id; ?>" class="side-nav-link">
                            <span class="menu-icon"><i class="<?= $item->icon; ?>"></i></span>
                            <span class="menu-text"><?= $item->menu_name; ?></span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="submenu<?= $item->id; ?>">
                            <ul class="sub-menu">
                                <?php foreach ($item->submenu as $sub): ?>
                                    <li class="side-nav-item">
                                        <a href="<?= APP_URL.$sub->url; ?>" class="side-nav-link">
                                            <span class="menu-text"><?= $sub->menu_name; ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?= APP_URL.$item->url; ?>" class="side-nav-link">
                            <span class="menu-icon"><i class="<?= $item->icon; ?>"></i></span>
                            <span class="menu-text"><?= $item->menu_name; ?></span>
                            <?php if (!empty($item->badge)): ?>
                                <span class="badge bg-danger rounded-pill"><?= $item->badge; ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>   
        <div class="clearfix"></div>
    </div>
</div>
<!-- Sidenav Menu End -->