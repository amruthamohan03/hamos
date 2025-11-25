<?php
class RoleController extends Controller
{
    public function index()
    {
        $db = new Database();
        $sql = "
            SELECT 
                r.id,
                r.role_name,
                r.department_id,
                r.office_location_id,
                r.parent_role_id,
                r.approval_level,
                r.department,
                r.management,
                r.finance,
                r.display,
                r.created_at,
                r.updated_at,
                d.department_name AS department_name,
                m.main_location_name AS office_name,
                pr.role_name AS parent_role_name
            FROM role_master_t r
            LEFT JOIN department_master_t d ON r.department_id = d.id
            LEFT JOIN main_office_master_t m ON r.office_location_id = m.id
            LEFT JOIN role_master_t pr ON r.parent_role_id = pr.id
            ORDER BY r.id DESC
        ";
        $result = $db->customQuery($sql);
        $departments = $db->selectData('department_master_t', '*', []);
        $offices = $db->selectData('main_office_master_t', '*', ['display' => 'Y']);

        $data = [
            'title' => 'Role Master',
            'result' => $result,
            'departments' => $departments,
            'offices' => $offices
        ];

        $this->viewWithLayout('masters/role', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'role_master_t';

        function sanitize($val)
        {
            return htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
        }

        // ✅ INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'role_name' => sanitize($_POST['role_name'] ?? ''),
                'department_id' => !empty($_POST['department_id']) ? (int)$_POST['department_id'] : null,
                'office_location_id' => !empty($_POST['office_location_id']) ? (int)$_POST['office_location_id'] : null,
                'parent_role_id' => !empty($_POST['parent_role_id']) ? (int)$_POST['parent_role_id'] : 0,
                'approval_level' => (int)($_POST['approval_level'] ?? 0),
                'department' => isset($_POST['department']) ? 1 : 0,
                'management' => isset($_POST['management']) ? 1 : 0,
                'finance' => isset($_POST['finance']) ? 1 : 0,
                'display' => $_POST['display'] ?? 'Y',
                'created_by' => $_SESSION['user_id'] ?? 1,
                'updated_by' => $_SESSION['user_id'] ?? 1
            ];

            if (empty($data['role_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ Role Name is required']);
                exit;
            }

            $insert = $db->insertData($table, $data);
            echo json_encode($insert ? ['success' => true, 'message' => '✅ Role added successfully'] : ['success' => false, 'message' => '❌ Failed to add role']);
            exit;
        }

        // ✅ UPDATION
        if ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => '❌ Invalid ID']);
                exit;
            }

            $data = [
                'role_name' => sanitize($_POST['role_name'] ?? ''),
                'department_id' => !empty($_POST['department_id']) ? (int)$_POST['department_id'] : null,
                'office_location_id' => !empty($_POST['office_location_id']) ? (int)$_POST['office_location_id'] : null,
                'parent_role_id' => !empty($_POST['parent_role_id']) ? (int)$_POST['parent_role_id'] : 0,
                'approval_level' => (int)($_POST['approval_level'] ?? 0),
                'department' => isset($_POST['department']) ? 1 : 0,
                'management' => isset($_POST['management']) ? 1 : 0,
                'finance' => isset($_POST['finance']) ? 1 : 0,
                'display' => $_POST['display'] ?? 'Y',
                'updated_by' => $_SESSION['user_id'] ?? 1
            ];

            if (empty($data['role_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ Role Name is required']);
                exit;
            }

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode($update ? ['success' => true, 'message' => '✅ Role updated successfully'] : ['success' => false, 'message' => '❌ Failed to update']);
            exit;
        }

        // ✅ DELETION
        if ($action === 'deletion') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => '❌ Invalid ID']);
                exit;
            }

            $delete = $db->deleteData($table, ['id' => $id]);
            echo json_encode($delete ? ['success' => true, 'message' => '✅ Role deleted successfully'] : ['success' => false, 'message' => '❌ Failed to delete']);
            exit;
        }
    }

    // ✅ Fetch Single Record
    public function getRoleById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => '❌ Invalid ID']);
            exit;
        }

        $data = $db->selectData('role_master_t', '*', ['id' => $id]);
        echo json_encode(!empty($data)
            ? ['success' => true, 'data' => $data[0]]
            : ['success' => false, 'message' => '❌ Record not found']);
        exit;
    }
}
?>