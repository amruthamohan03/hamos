<?php

class MenuController extends Controller{
    public function index()
    {
        $db         = new Database();
        $menus      = $db->selectData('menu_master_t', 'id,menu_name',['menu_level' => 0,'url'=>'#']);
        $result     = $db->selectData('menu_master_t', '*',[]);
        $data       = ['title'  => 'Menu',
                       'menus'  => $menus,
                       'result' => $result];
        $this->viewWithLayout('masters/menu', $data);
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
                'menu_order'  => isset($_POST['menu_order']) ? (int) $_POST['menu_order'] : 0,
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
                echo json_encode(['success'=>false,'message'=>'Menu Name is required.']);
                exit;
            }

            $insertId = $db->insertData($table, $data);

            if ($insertId) {
                echo json_encode(['success'=>true,'message'=>'Menu inserted successfully.','id'=>$insertId]);
            } else {
                echo json_encode(['success'=>false,'message'=>'Insert failed.']);
            }
            exit;
        }


        // 🔹 UPDATION (Edit existing menu)
       elseif ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid Menu ID']);
                exit;
            }

            $data = [
                'menu_id'    => isset($_POST['menu_id']) ? (int) $_POST['menu_id'] : null,
                'menu_order' => isset($_POST['menu_order']) ? (int) $_POST['menu_order'] : 0,
                'menu_level' => isset($_POST['menu_level']) ? (int) $_POST['menu_level'] : 0,
                'menu_name'  => htmlspecialchars(trim($_POST['menu_name']), ENT_QUOTES),
                'url'        => htmlspecialchars(trim($_POST['url'] ?? '#'), ENT_QUOTES),
                'text'       => htmlspecialchars(trim($_POST['text'] ?? ''), ENT_QUOTES),
                'display'    => in_array($_POST['display'], ['Y','N']) ? $_POST['display'] : 'Y',
                'updated_by' => 1
            ];

            $db = new Database();
            $update = $db->updateData('menu_master_t', $data, ['id' => $id]);

            if($update){
                echo json_encode(['success'=>true,'message'=>'Menu updated successfully']);
            } else {
                echo json_encode(['success'=>false,'message'=>'Update failed']);
            }
            exit;
        }


        // 🔹 DELETION (Delete by ID)
        elseif($action === 'deletion'){
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

            if($id <= 0){
                echo json_encode(['success'=>false,'message'=>'Invalid Menu ID']);
                exit;
            }

            $delete = $db->deleteData('menu_master_t',['id'=>$id]);
            if($delete){
                echo json_encode(['success'=>true,'message'=>'Menu deleted successfully']);
            } else {
                echo json_encode(['success'=>false,'message'=>'Delete failed']);
            }
            exit;
        }
    }
    public function getMenuById() {
        header('Content-Type: application/json');
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if($id <= 0){
            echo json_encode(['success'=>false,'message'=>'Invalid ID']);
            exit;
        }

        $db = new Database();
        $menu = $db->selectData('menu_master_t','*',['id'=>$id]);
        if(!empty($menu)){
            echo json_encode(['success'=>true,'data'=>$menu[0]]);
        } else {
            echo json_encode(['success'=>false,'message'=>'Menu not found']);
        }
        exit;
    }

}

