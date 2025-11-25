<?php
class RolemenumappingController extends Controller
{
    public function index()
    {
        $db = new Database();

        $roles = $db->selectData('role_master_t', '*', ['display' => 'Y']);
        $menus = $db->customQuery("
            SELECT id, menu_name, url, menu_level, menu_order 
            FROM menu_master_t 
            WHERE display='Y' 
            ORDER BY menu_level, menu_order
        ");

        $data = [
            'title' => 'Role Menu Mapping',
            'roles' => $roles,
            'menus' => $menus
        ];

        $this->viewWithLayout('mapping/role_menu_mapping', $data);
    }

    public function getMapping()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $role_id = (int)($_GET['role_id'] ?? 0);

        if ($role_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid role']);
            exit;
        }

        $sql = "
            SELECT 
                m.id AS menu_id,
                m.menu_name,
                COALESCE(rm.can_view,0) AS can_view,
                COALESCE(rm.can_add,0) AS can_add,
                COALESCE(rm.can_edit,0) AS can_edit,
                COALESCE(rm.can_delete,0) AS can_delete,
                COALESCE(rm.can_approve,0) AS can_approve
            FROM menu_master_t m
            LEFT JOIN role_menu_mapping_t rm 
                ON m.id = rm.menu_id AND rm.role_id = {$role_id}
            WHERE m.display='Y'
            ORDER BY m.menu_level, m.menu_order
        ";

        $result = $db->customQuery($sql);
        echo json_encode(['success' => true, 'data' => $result]);
        exit;
    }

    public function saveMapping()
    {
        header('Content-Type: application/json');
        $db = new Database();

        $role_id = (int)($_POST['role_id'] ?? 0);
        $permissions = $_POST['permissions'] ?? [];

        if ($role_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid role']);
            exit;
        }

        // delete old mappings
        $db->deleteData('role_menu_mapping_t', ['role_id' => $role_id]);

        // insert new mappings
        foreach ($permissions as $menu_id => $perm) {
            $data = [
                'role_id' => $role_id,
                'menu_id' => (int)$menu_id,
                'can_view' => isset($perm['view']) ? 1 : 0,
                'can_add' => isset($perm['add']) ? 1 : 0,
                'can_edit' => isset($perm['edit']) ? 1 : 0,
                'can_delete' => isset($perm['delete']) ? 1 : 0,
                'can_approve' => isset($perm['approve']) ? 1 : 0,
                'created_by' => $_SESSION['user_id'] ?? 1
            ];
            $db->insertData('role_menu_mapping_t', $data);
        }

        echo json_encode(['success' => true, 'message' => '✅ Role-Menu mapping saved successfully.']);
        exit;
    }
}
?>
