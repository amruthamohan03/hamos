<!-- Theme Settings -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="theme-settings-offcanvas">
    <div class="d-flex align-items-center gap-2 px-3 py-3 offcanvas-header border-bottom border-dashed">
        <h5 class="flex-grow-1 fs-16 fw-bold mb-0">Theme Settings</h5>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-0 h-100" data-simplebar>
        <div class="p-3 border-bottom border-dashed">
            <h5 class="mb-3 fs-13 text-uppercase fw-bold">Color Scheme</h5>

            <div class="row">
                <div class="col-6">
                    <div class="form-check card-radio">
                        <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-color-light"
                            value="light">
                        <label class="form-check-label p-3 w-100 d-flex justify-content-center align-items-center"
                            for="layout-color-light">
                            <iconify-icon icon="solar:sun-bold-duotone" class="fs-32 text-muted"></iconify-icon>
                        </label>
                    </div>
                    <h5 class="fs-14 text-center text-muted mt-2">Light</h5>
                </div>

                <div class="col-6">
                    <div class="form-check card-radio">
                        <input class="form-check-input" type="radio" name="data-bs-theme" id="layout-color-dark"
                            value="dark">
                        <label class="form-check-label p-3 w-100 d-flex justify-content-center align-items-center"
                            for="layout-color-dark">
                            <iconify-icon icon="solar:cloud-sun-2-bold-duotone" class="fs-32 text-muted"></iconify-icon>
                        </label>
                    </div>
                    <h5 class="fs-14 text-center text-muted mt-2">Dark</h5>
                </div>
            </div>
        </div>





        
    </div>

   

</div>

<!-- Add this JavaScript to sync theme colors -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Listen for color scheme changes
    const colorSchemeRadios = document.querySelectorAll('input[name="data-bs-theme"]');
    
    colorSchemeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                const theme = this.value;
                
                // Sync topbar color
                const topbarRadio = document.getElementById('topbar-color-' + theme);
                if (topbarRadio) {
                    topbarRadio.checked = true;
                    // Trigger change event if needed
                    topbarRadio.dispatchEvent(new Event('change'));
                }
                
                // Sync menu color
                const menuRadio = document.getElementById('sidenav-color-' + theme);
                if (menuRadio) {
                    menuRadio.checked = true;
                    // Trigger change event if needed
                    menuRadio.dispatchEvent(new Event('change'));
                }
                
                // Apply theme to body or root element
                document.documentElement.setAttribute('data-bs-theme', theme);
                document.documentElement.setAttribute('data-topbar-color', theme);
                document.documentElement.setAttribute('data-menu-color', theme);
            }
        });
    });
    
    // Set default sidebar size
    const defaultSidebarSize = document.getElementById('sidenav-size-default');
    if (defaultSidebarSize) {
        defaultSidebarSize.checked = true;
        document.documentElement.setAttribute('data-sidenav-size', 'default');
    }
});
</script>