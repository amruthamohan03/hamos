<div class="page-content">
    <div class="page-container">
        <div class="row">
            <div class="col-12">

                <!-- Account Settings Card -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="ti ti-settings me-2"></i>Account Settings</h4>
                    </div>
                    <div class="card-body">
                        
                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" 
                                    data-bs-target="#profile" type="button" role="tab">
                                    <i class="ti ti-user me-1"></i> Profile Information
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="password-tab" data-bs-toggle="tab" 
                                    data-bs-target="#password" type="button" role="tab">
                                    <i class="ti ti-lock me-1"></i> Change Password
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="photo-tab" data-bs-toggle="tab" 
                                    data-bs-target="#photo" type="button" role="tab">
                                    <i class="ti ti-camera me-1"></i> Profile Photo
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="signature-tab" data-bs-toggle="tab" 
                                    data-bs-target="#signature" type="button" role="tab">
                                    <i class="ti ti-writing me-1"></i> Signature
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="settingsTabContent">
                            
                            <!-- Profile Information Tab -->
                            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                                <form id="profileUpdateForm" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" name="full_name" id="full_name" 
                                                class="form-control" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" required disabled>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email" 
                                                class="form-control" disabled value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                            <input type="text" name="mobile" id="mobile" 
                                                class="form-control" value="<?= htmlspecialchars($user['mobile'] ?? '') ?>" 
                                                pattern="[0-9]{10}" maxlength="10" required disabled>
                                            <small class="text-muted">Enter 10-digit mobile number</small>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" class="form-control" 
                                                value="<?= htmlspecialchars($user['username'] ?? '') ?>" disabled>
                                            <small class="text-muted">Username cannot be changed</small>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Approval Level</label>
                                            <input type="text" class="form-control" 
                                                value="<?= htmlspecialchars($user['department_name'] ?? 'N/A') ?>" disabled>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Role</label>
                                            <input type="text" class="form-control" 
                                                value="<?= htmlspecialchars($user['role_name'] ?? 'N/A') ?>" disabled>
                                        </div>

                                        <!-- <div class="col-md-12 mb-3">
                                            <label class="form-label">Address</label>
                                            <textarea name="address" id="address" class="form-control" 
                                                rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                                        </div> -->
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary" disabled>
                                            <i class="ti ti-device-floppy me-1"></i> Update Profile
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Change Password Tab -->
                            <div class="tab-pane fade" id="password" role="tabpanel">
                                <form id="passwordChangeForm">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Current Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" name="current_password" id="current_password" 
                                                    class="form-control" required>
                                                <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="togglePassword('current_password')">
                                                    <i class="ti ti-eye" id="current_password_icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">New Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" name="new_password" id="new_password" 
                                                    class="form-control" minlength="6" required>
                                                <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="togglePassword('new_password')">
                                                    <i class="ti ti-eye" id="new_password_icon"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted">Minimum 6 characters</small>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" name="confirm_password" id="confirm_password" 
                                                    class="form-control" minlength="6" required>
                                                <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="togglePassword('confirm_password')">
                                                    <i class="ti ti-eye" id="confirm_password_icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="ti ti-info-circle me-2"></i>
                                        <strong>Password Requirements:</strong>
                                        <ul class="mb-0 mt-2">
                                            <li>Minimum 6 characters long</li>
                                            <li>Mix of uppercase and lowercase letters recommended</li>
                                            <li>Include numbers and special characters for stronger security</li>
                                        </ul>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-lock me-1"></i> Change Password
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Profile Photo Tab -->
                            <div class="tab-pane fade" id="photo" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <div class="mb-3">
                                            <img id="profile_photo_preview" 
                                                src="<?= !empty($user['profile_image']) ? BASE_URL . '/uploads/profiles/' . $user['profile_image'] : APP_URL . 'assets/images/default-avatar.png' ?>" 
                                                alt="Profile Photo" 
                                                class="img-thumbnail rounded-circle" 
                                                style="width: 200px; height: 200px; object-fit: cover;">
                                        </div>
                                        <p class="text-muted">Current Profile Photo</p>
                                    </div>

                                    <div class="col-md-8">
                                        <form id="photoUploadForm" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label class="form-label">Upload New Photo</label>
                                                <input type="file" name="profile_photo" id="profile_photo" 
                                                    class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                                                <small class="text-muted">
                                                    Allowed formats: JPG, JPEG, PNG | Max size: 2MB
                                                </small>
                                            </div>

                                            <div class="mb-3">
                                                <img id="new_photo_preview" src="" alt="Preview" 
                                                    class="img-thumbnail d-none" style="max-width: 200px;">
                                            </div>

                                            <div class="alert alert-warning">
                                                <i class="ti ti-alert-triangle me-2"></i>
                                                <strong>Photo Guidelines:</strong>
                                                <ul class="mb-0 mt-2">
                                                    <li>Use a clear, professional photo</li>
                                                    <li>Face should be clearly visible</li>
                                                    <li>Recommended size: 500x500 pixels</li>
                                                    <li>Background should be plain</li>
                                                </ul>
                                            </div>

                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="ti ti-upload me-1"></i> Upload Photo
                                                </button>
                                                <?php if (!empty($user['profile_photo'])): ?>
                                                <button type="button" class="btn btn-danger" id="removePhotoBtn">
                                                    <i class="ti ti-trash me-1"></i> Remove Photo
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Signature Tab (Add after Profile Photo Tab) -->
                            <div class="tab-pane fade" id="signature" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <div class="mb-3">
                                            <img id="signature_preview" 
                                                src="<?= !empty($user['signature_image']) ? BASE_URL . '/uploads/signatures/' . $user['signature_image'] : BASE_URL . '/assets/images/default-signature.png' ?>" 
                                                alt="Signature" 
                                                class="img-thumbnail" 
                                                style="width: 300px; height: 150px; object-fit: contain; background: #f8f9fa;">
                                        </div>
                                        <p class="text-muted">Current Signature</p>
                                    </div>

                                    <div class="col-md-8">
                                        <form id="signatureUploadForm" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label class="form-label">Upload New Signature</label>
                                                <input type="file" name="signature_image" id="signature_image" 
                                                    class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                                                <small class="text-muted">
                                                    Allowed formats: JPG, JPEG, PNG | Max size: 1MB
                                                </small>
                                            </div>

                                            <div class="mb-3">
                                                <img id="new_signature_preview" src="" alt="Preview" 
                                                    class="img-thumbnail d-none" style="max-width: 300px;">
                                            </div>

                                            <div class="alert alert-info">
                                                <i class="ti ti-info-circle me-2"></i>
                                                <strong>Signature Guidelines:</strong>
                                                <ul class="mb-0 mt-2">
                                                    <li>Use a clear signature on white background</li>
                                                    <li>Signature should be clearly visible</li>
                                                    <li>Recommended size: 300x150 pixels</li>
                                                    <li>Use black or dark blue ink for scanning</li>
                                                    <li>Save as PNG for transparent background (optional)</li>
                                                </ul>
                                            </div>

                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="ti ti-upload me-1"></i> Upload Signature
                                                </button>
                                                <?php if (!empty($user['sugnature_image'])): ?>
                                                <button type="button" class="btn btn-danger" id="removeSignatureBtn">
                                                    <i class="ti ti-trash me-1"></i> Remove Signature
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php include(VIEW_PATH . 'layouts/partials/footer.php'); ?>

</div>

<!-- JavaScript -->
<script>
    $(function () {
        
        // ✅ Update Profile Information
        $('#profileUpdateForm').submit(function (e) {
            
            e.preventDefault(); 
            // Validate mobile number
            let mobile = $('#mobile').val();
            if (!/^\d{10}$/.test(mobile)) {
                alert('❌ Please enter a valid 10-digit mobile number');
                return;
            }

            $.post('<?php echo APP_URL; ?>account/updateProfile', $(this).serialize(), function (res) {
                if (res.success) {
                    alert(res.message);
                   location.reload();
                } else {
                    alert(res.message);
                }
            }, 'json').fail(function() {
                alert('❌ Error updating profile. Please try again.');
            });
        });

        // ✅ Change Password
        $('#passwordChangeForm').submit(function (e) {
            e.preventDefault();
            
            let newPassword = $('#new_password').val();
            let confirmPassword = $('#confirm_password').val();

            // Validate password match
            if (newPassword !== confirmPassword) {
                alert('❌ New password and confirm password do not match');
                return;
            }

            // Validate password strength
            if (newPassword.length < 6) {
                alert('❌ Password must be at least 6 characters long');
                return;
            }

            $.post('<?php echo APP_URL; ?>account/changePassword', $(this).serialize(), function (res) {
                if (res.success) {
                    alert(res.message);
                    $('#passwordChangeForm')[0].reset();
                } else {
                    alert(res.message);
                }
            }, 'json').fail(function() {
                alert('❌ Error changing password. Please try again.');
            });
        });

        // ✅ Upload Profile Photo
        $('#photoUploadForm').submit(function (e) {
            e.preventDefault();
            
            let fileInput = $('#profile_photo')[0];
            if (!fileInput.files[0]) {
                alert('❌ Please select a photo to upload');
                return;
            }

            // Validate file size (2MB)
            if (fileInput.files[0].size > 2 * 1024 * 1024) {
                alert('❌ File size must be less than 2MB');
                return;
            }

            let formData = new FormData(this);

            $.ajax({
                url: '<?php echo APP_URL; ?>account/uploadPhoto',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        alert(res.message);
                        location.reload();
                    } else {
                        alert(res.message);
                    }
                },
                error: function() {
                    alert('❌ Error uploading photo. Please try again.');
                }
            });
        });

        // ✅ Remove Profile Photo
        $('#removePhotoBtn').click(function () {
            if (!confirm('Are you sure you want to remove your profile photo?')) return;

            $.post('<?php echo APP_URL; ?>account/removePhoto', {}, function (res) {
                if (res.success) {
                    alert(res.message);
                    location.reload();
                } else {
                    alert(res.message);
                }
            }, 'json').fail(function() {
                alert('❌ Error removing photo. Please try again.');
            });
        });

        // Preview new photo before upload
        $('#profile_photo').change(function () {
            let file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#new_photo_preview').attr('src', e.target.result).removeClass('d-none');
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Toggle Password Visibility
    function togglePassword(fieldId) {
        let field = document.getElementById(fieldId);
        let icon = document.getElementById(fieldId + '_icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('ti-eye');
            icon.classList.add('ti-eye-off');
        } else {
            field.type = 'password';
            icon.classList.remove('ti-eye-off');
            icon.classList.add('ti-eye');
        }
    }
    // ✅ Upload Signature
    $('#signatureUploadForm').submit(function (e) {
        e.preventDefault();
        
        let fileInput = $('#signature_image')[0];
        if (!fileInput.files[0]) {
            alert('❌ Please select a signature to upload');
            return;
        }

        // Validate file size (1MB)
        if (fileInput.files[0].size > 1 * 1024 * 1024) {
            alert('❌ File size must be less than 1MB');
            return;
        }

        let formData = new FormData(this);

        $.ajax({
            url: '<?php echo APP_URL; ?>account/uploadSignature',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert(res.message);
                    location.reload();
                } else {
                    alert(res.message);
                }
            },
            error: function() {
                alert('❌ Error uploading signature. Please try again.');
            }
        });
    });

    // ✅ Remove Signature
    $('#removeSignatureBtn').click(function () {
        if (!confirm('Are you sure you want to remove your signature?')) return;

        $.post('<?php echo APP_URL; ?>account/removeSignature', {}, function (res) {
            if (res.success) {
                alert(res.message);
                location.reload();
            } else {
                alert(res.message);
            }
        }, 'json').fail(function() {
            alert('❌ Error removing signature. Please try again.');
        });
    });

    // Preview new signature before upload
    $('#signature_image').change(function () {
        let file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $('#new_signature_preview').attr('src', e.target.result).removeClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });
</script>