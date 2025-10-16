

<?php

class MenuController extends Controller{
    public function index()
    {
        $db = new Database();
        $menus      = $db->selectData('menu_master_t', 'id,menu_name',['menu_level' => 0,'url'=>'#']);
        $result     = $db->selectData('menu_master_t', '*',[]);
        $this->viewWithLayout('masters/menu', ['menus' => $menus,'result' => $result]);
    }

    public function crudData($action = 'insertion')
    {
        $db     = new Database();
        $table  = 'menu_master_t';

        // Helper function to sanitize
        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // 🔹 INSERTION (Add new menu)
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'menu_id'     => isset($_POST['menu_id']) ? (int) $_POST['menu_id'] : null,
                'menu_level'  => isset($_POST['menu_level']) ? (int) $_POST['menu_level'] : 0,
                'menu_name'   => sanitize($_POST['menu_name'] ?? ''),
                'url'         => sanitize($_POST['url'] ?? '#'),
                'text'        => sanitize($_POST['text'] ?? ''),
                'icon'        => sanitize($_POST['icon'] ?? ''),
                'badge'       => sanitize($_POST['badge'] ?? ''),
                'display'     => isset($_POST['display']) && in_array($_POST['display'], ['Y','N']) ? $_POST['display'] : 'Y',
                'created_by'  => 1,
                'updated_by'  => 1,
            ];

            if (empty($data['menu_name'])) {
                echo "❌ Menu Name is required.";
                return;
            }

            $insertId = $db->insertData($table, $data);

            if ($insertId) {
                header("Location: " . BASE_URL . "/menu/index");
                exit;
            } else {
                echo "❌ Insert failed.";
            }
        }

        // 🔹 UPDATION (Edit existing menu)
        elseif ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            if ($id <= 0) {
                echo "❌ Invalid Menu ID for update.";
                return;
            }

            $data = [
                'menu_id'     => isset($_POST['menu_id']) ? (int) $_POST['menu_id'] : null,
                'menu_level'  => isset($_POST['menu_level']) ? (int) $_POST['menu_level'] : 0,
                'menu_name'   => sanitize($_POST['menu_name'] ?? ''),
                'url'         => sanitize($_POST['url'] ?? '#'),
                'text'        => sanitize($_POST['text'] ?? ''),
                'icon'        => sanitize($_POST['icon'] ?? ''),
                'badge'       => sanitize($_POST['badge'] ?? ''),
                'display'     => isset($_POST['display']) && in_array($_POST['display'], ['Y','N']) ? $_POST['display'] : 'Y',
                'updated_by'  => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);

            if ($update) {
                header("Location: " . BASE_URL . "/menu?msg=update_success");
                exit;
            } else {
                echo "❌ Update failed.";
            }
        }

        // 🔹 DELETION (Delete by ID)
        elseif ($action === 'deletion') {

            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            if ($id <= 0) {
                echo "❌ Invalid ID for deletion.";
                return;
            }

            $delete = $db->deleteData($table, ['id' => $id]);

            if ($delete) {
                header("Location: " . BASE_URL . "/menu?msg=delete_success");
                exit;
            } else {
                echo "❌ Delete failed.";
            }
        }

        // 🔹 DEFAULT case: invalid access
        else {
            echo "⚠️ Invalid request.";
        }
    }



}