<?php
class AccountController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . 'auth/login');
            exit;
        }
        $db = new Database();
        $userId = $_SESSION['user_id'];
        // Get user details with department and role information
        $sql = "
            SELECT 
                u.id,
                u.username,
                u.full_name,
                u.email,
                u.mobile,
                u.profile_image,
                u.signature_image,
                u.dept_id,
                u.role_id,
                d.department_name,
                r.role_name
            FROM users_t u
            LEFT JOIN department_master_t d ON u.dept_id = d.id
            LEFT JOIN role_master_t r ON u.role_id = r.id
            WHERE u.id = :user_id
        ";
        
        $result = $db->customQuery($sql, [':user_id' => $userId]);
        $user = !empty($result) ? $result[0] : [];

        if (empty($user)) {
            // User not found, logout
            session_destroy();
            header('Location: ' . APP_URL . 'auth/login');
            exit;
        }

        $data = [
            'title' => 'Account Settings',
            'user' => $user
        ];

        $this->viewWithLayout('account/settings', $data);
    }

    /**
     * Update Profile Information
     */
    public function updateProfile()
    { 
        ob_clean();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => '❌ Invalid request method']);
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => '❌ Unauthorized access']);
            exit;
        }

        $db = new Database();
        $userId = $_SESSION['user_id'];
        // Sanitize inputs
        $full_name = htmlspecialchars(trim($_POST['full_name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $mobile = preg_replace('/[^0-9]/', '', $_POST['mobile'] ?? '');

        // Validate required fields
        if (empty($full_name)) {
            echo json_encode(['success' => false, 'message' => '❌ Full name is required']);
            exit;
        }

        if (empty($mobile) || strlen($mobile) !== 10) {
            echo json_encode(['success' => false, 'message' => '❌ Valid 10-digit mobile number is required']);
            exit;
        }


        if (!empty($existingEmail)) {
            echo json_encode(['success' => false, 'message' => '❌ Email already exists']);
            exit;
        }

        // Check if mobile already exists for another user
        $existingMobile = $db->customQuery(
            "SELECT id FROM users_t WHERE mobile = :mobile AND id != :user_id",
            [':mobile' => $mobile, ':user_id' => $userId]
        );

        if (!empty($existingMobile)) {
            echo json_encode(['success' => false, 'message' => '❌ Mobile number already exists']);
            exit;
        }

        // Update data
        $data = [
            'full_name' => $full_name,
            'mobile' => $mobile,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $userId
        ];
        $update = $db->updateData('users_t', $data, ['id' => $userId]);
        if ($update) {
            // Update session data if needed
            $_SESSION['full_name'] = $full_name;
            
            echo json_encode(['success' => true, 'message' => '✅ Profile updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Failed to update profile']);
        }
        exit;
    }

    /**
     * Change Password
     */
    public function changePassword()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => '❌ Invalid request method']);
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => '❌ Unauthorized access']);
            exit;
        }

        $db = new Database();
        $userId = $_SESSION['user_id'];

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate inputs
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            echo json_encode(['success' => false, 'message' => '❌ All fields are required']);
            exit;
        }

        if (strlen($newPassword) < 6) {
            echo json_encode(['success' => false, 'message' => '❌ Password must be at least 6 characters long']);
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            echo json_encode(['success' => false, 'message' => '❌ New password and confirm password do not match']);
            exit;
        }

        // Get current password from database
        $user = $db->selectData('users_t', 'id, password', ['id' => $userId]);

        if (empty($user)) {
            echo json_encode(['success' => false, 'message' => '❌ User not found']);
            exit;
        }

        // Verify current password
        if (!password_verify($currentPassword, $user[0]['password'])) {
            echo json_encode(['success' => false, 'message' => '❌ Current password is incorrect']);
            exit;
        }

        // Hash new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password
        $data = [
            'password' => $hashedPassword,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $userId
        ];

        $update = $db->updateData('users_t', $data, ['id' => $userId]);

        if ($update) {
            echo json_encode(['success' => true, 'message' => '✅ Password changed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Failed to change password']);
        }
        exit;
    }

    /**
     * Upload Profile Photo
     */
    public function uploadPhoto()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => '❌ Invalid request method']);
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => '❌ Unauthorized access']);
            exit;
        }

        $db = new Database();
        $userId = $_SESSION['user_id'];

        // Check if file was uploaded
        if (!isset($_FILES['profile_photo']) || $_FILES['profile_photo']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => '❌ Please select a valid image file']);
            exit;
        }

        $file = $_FILES['profile_photo'];
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        // Validate file type
        if (!in_array($file['type'], $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => '❌ Only JPG, JPEG, and PNG files are allowed']);
            exit;
        }

        // Validate file size
        if ($file['size'] > $maxSize) {
            echo json_encode(['success' => false, 'message' => '❌ File size must be less than 2MB']);
            exit;
        }

        // Create upload directory if it doesn't exist
        $uploadDir = UPLOAD_PATH. '/profiles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $userId . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $filename;

        // Get old photo to delete
        $oldPhoto = $db->selectData('users_t', 'profile_image', ['id' => $userId]);
        $oldPhotoFile = !empty($oldPhoto[0]['profile_photo']) ? $oldPhoto[0]['profile_photo'] : null;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Update database
            $data = [
                'profile_image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $userId
            ];

            $update = $db->updateData('users_t', $data, ['id' => $userId]);

            if ($update) {
                // Delete old photo if exists
                if ($oldPhotoFile && file_exists($uploadDir . $oldPhotoFile)) {
                    unlink($uploadDir . $oldPhotoFile);
                }

                // Update session
                $_SESSION['profile_photo'] = $filename;

                echo json_encode(['success' => true, 'message' => '✅ Profile photo uploaded successfully']);
            } else {
                // Delete uploaded file if database update fails
                unlink($uploadPath);
                echo json_encode(['success' => false, 'message' => '❌ Failed to update database']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Failed to upload file']);
        }
        exit;
    }

    /**
     * Remove Profile Photo
     */
    public function removePhoto()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => '❌ Invalid request method']);
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => '❌ Unauthorized access']);
            exit;
        }

        $db = new Database();
        $userId = $_SESSION['user_id'];

        // Get current photo
        $user = $db->selectData('users_t', 'profile_image', ['id' => $userId]);

        if (empty($user) || empty($user[0]['profile_photo'])) {
            echo json_encode(['success' => false, 'message' => '❌ No profile photo to remove']);
            exit;
        }

        $photoFile = $user[0]['profile_photo'];
        $uploadDir = UPLOAD_PATH. '/profiles/';

        // Update database
        $data = [
            'profile_image' => null,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $userId
        ];

        $update = $db->updateData('users_t', $data, ['id' => $userId]);

        if ($update) {
            // Delete physical file
            if (file_exists($uploadDir . $photoFile)) {
                unlink($uploadDir . $photoFile);
            }

            // Update session
            unset($_SESSION['profile_photo']);

            echo json_encode(['success' => true, 'message' => '✅ Profile photo removed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Failed to remove photo']);
        }
        exit;
    }
    /**
 * Upload Signature
 */
    public function uploadSignature()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => '❌ Invalid request method']);
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => '❌ Unauthorized access']);
            exit;
        }

        $db = new Database();
        $userId = $_SESSION['user_id'];

        // Check if file was uploaded
        if (!isset($_FILES['signature_image']) || $_FILES['signature_image']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => '❌ Please select a valid image file']);
            exit;
        }

        $file = $_FILES['signature_image'];
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        $maxSize = 1 * 1024 * 1024; // 1MB

        // Validate file type
        if (!in_array($file['type'], $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => '❌ Only JPG, JPEG, and PNG files are allowed']);
            exit;
        }

        // Validate file size
        if ($file['size'] > $maxSize) {
            echo json_encode(['success' => false, 'message' => '❌ File size must be less than 1MB']);
            exit;
        }

        // Create upload directory if it doesn't exist
        $uploadDir = UPLOAD_PATH . '/signatures/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'signature_' . $userId . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $filename;

        // Get old signature to delete
        $oldSignature = $db->selectData('users_t', 'signature_image', ['id' => $userId]);
        $oldSignatureFile = !empty($oldSignature[0]['signature_image']) ? $oldSignature[0]['signature_image'] : null;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Update database
            $data = [
                'signature_image' => $filename,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $userId
            ];

            $update = $db->updateData('users_t', $data, ['id' => $userId]);

            if ($update) {
                // Delete old signature if exists
                if ($oldSignatureFile && file_exists($uploadDir . $oldSignatureFile)) {
                    unlink($uploadDir . $oldSignatureFile);
                }

                // Update session if you're storing it
                $_SESSION['user_data']['signature_image'] = $filename;

                echo json_encode(['success' => true, 'message' => '✅ Signature uploaded successfully']);
            } else {
                // Delete uploaded file if database update fails
                unlink($uploadPath);
                echo json_encode(['success' => false, 'message' => '❌ Failed to update database']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Failed to upload file']);
        }
        exit;
    }

    /**
     * Remove Signature
     */
    public function removeSignature()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => '❌ Invalid request method']);
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => '❌ Unauthorized access']);
            exit;
        }

        $db = new Database();
        $userId = $_SESSION['user_id'];

        // Get current signature
        $user = $db->selectData('users_t', 'signature_image', ['id' => $userId]);

        if (empty($user) || empty($user[0]['signature_image'])) {
            echo json_encode(['success' => false, 'message' => '❌ No signature to remove']);
            exit;
        }

        $signatureFile = $user[0]['signature_image'];
        $uploadDir = UPLOAD_PATH . '/signatures/';

        // Update database
        $data = [
            'signature_image' => null,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $userId
        ];

        $update = $db->updateData('users_t', $data, ['id' => $userId]);

        if ($update) {
            // Delete physical file
            if (file_exists($uploadDir . $signatureFile)) {
                unlink($uploadDir . $signatureFile);
            }

            // Update session
            if (isset($_SESSION['user_data']['signature_image'])) {
                unset($_SESSION['user_data']['signature_image']);
            }

            echo json_encode(['success' => true, 'message' => '✅ Signature removed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Failed to remove signature']);
        }
        exit;
    }
}
?>