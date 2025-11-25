<?php
class UserController extends Controller
{
    public function index()
    {
        $db = new Database();

        // Join users with role, department, and transit location
        $sql = "
            SELECT 
                u.id,
                u.username,
                u.email,
                u.full_name,
                u.role_id,
                r.role_name,
                u.dept_id,
                d.department_name AS dept_name,
                u.location_id,
                t.main_location_name,
                u.display,
                u.profile_image,
                u.created_at,
                u.updated_at
            FROM users_t u
            LEFT JOIN role_master_t r ON u.role_id = r.id
            LEFT JOIN department_master_t d ON u.dept_id = d.id
            LEFT JOIN main_office_master_t t ON u.location_id = t.id
            WHERE u.display = 'Y'
            ORDER BY u.id DESC
        ";

        $result = $db->customQuery($sql);
        $roles = $db->selectData('role_master_t', '*', ['display' => 'Y']);
        $departments = $db->selectData('department_master_t', '*', ['display' => 'Y']);
        $locations = $db->selectData('main_office_master_t', '*', ['display' => 'Y']); // new dropdown source

        $data = [
            'title' => 'User Master',
            'result' => $result,
            'roles' => $roles,
            'departments' => $departments,
            'locations' => $locations
        ];

        $this->viewWithLayout('masters/user', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'users_t';

        function sanitize($val)
        {
            return htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
        }

        // ✅ INSERT
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => sanitize($_POST['username'] ?? ''),
                'password' => password_hash($_POST['password'] ?? '', PASSWORD_BCRYPT),
                'email' => sanitize($_POST['email'] ?? ''),
                'full_name' => sanitize($_POST['full_name'] ?? ''),
                'role_id' => (int)($_POST['role_id'] ?? 0),
                'dept_id' => (int)($_POST['dept_id'] ?? 0),
                'location_id' => (int)($_POST['location_id'] ?? 0),
                'display' => $_POST['display'] ?? 'Y',
                'created_by' => 1,
                'updated_by' => 1
            ];

            if (empty($data['username']) || empty($_POST['password'])) {
                echo json_encode(['success' => false, 'message' => '❌ Username and Password are required']);
                exit;
            }

            $insert = $db->insertData($table, $data);
            echo json_encode($insert ? ['success' => true, 'message' => '✅ User added successfully'] : ['success' => false, 'message' => 'Failed to add user']);
            exit;
        }

        // ✅ UPDATE
        if ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID']);
                exit;
            }

            $data = [
                'username' => sanitize($_POST['username'] ?? ''),
                'email' => sanitize($_POST['email'] ?? ''),
                'full_name' => sanitize($_POST['full_name'] ?? ''),
                'role_id' => (int)($_POST['role_id'] ?? 0),
                'dept_id' => (int)($_POST['dept_id'] ?? 0),
                'location_id' => (int)($_POST['location_id'] ?? 0),
                'display' => $_POST['display'] ?? 'Y',
                'updated_by' => 1
            ];

            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            }

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode($update ? ['success' => true, 'message' => '✅ User updated successfully'] : ['success' => false, 'message' => '❌ Failed to update']);
            exit;
        }

        // ✅ DELETE
        if ($action === 'deletion') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID']);
                exit;
            }

            $delete = $db->deleteData($table, ['id' => $id]);
            echo json_encode($delete ? ['success' => true, 'message' => '✅ User deleted successfully'] : ['success' => false, 'message' => '❌ Failed to delete']);
            exit;
        }
    }

    // ✅ GET SINGLE RECORD
    public function getUserById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            exit;
        }

        $data = $db->selectData('users_t', '*', ['id' => $id]);
        echo json_encode(!empty($data)
            ? ['success' => true, 'data' => $data[0]]
            : ['success' => false, 'message' => 'Record not found']);
        exit;
    }
}
?>
