<?php

class FeetContainerController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('feet_container_master_t', '*', []);
        $data = [
            'title' => 'Feet Container Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/feetcontainer', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'feet_container_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'feet_container_size' => sanitize($_POST['feet_container_size'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'created_by'    => 1,
                'updated_by'    => 1,
            ];

            if (empty($data['feet_container_size'])) {
                echo json_encode(['success' => false, 'message' => '❌ Size is required.']);
                exit;
            }

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => '✅ Feet container added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => '❌ Insert failed.']);
            exit;
        }

        // UPDATION
        elseif ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid Feet Container ID.']);
                exit;
            }

            $data = [
                'feet_container_size' => sanitize($_POST['feetcontainer_size'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'    => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'Feet container size updated successfully!' : 'Update failed.'
            ]);
            exit;
        }

        // DELETION
         if ($action === 'deletion') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
                exit;
            }

            $delete = $db->deleteData($table, ['id' => $id]);
            echo json_encode($delete
                ? ['success' => true, 'message' => '✅ feet container deleted successfully!']
                : ['success' => false, 'message' => '❌ Delete failed.']);
            exit;
        }

        echo json_encode(['success' => false, 'message' => '⚠️ Invalid request.']);
        exit;
    
    }

    public function getFeetcontainerById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $feet_container = $db->selectData('feet_container_master_t', '*', ['id' => $id]);
        if (!empty($feet_container)) {
            echo json_encode(['success' => true, 'data' => $feet_container[0]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Feet Container not found.']);
        }
        exit;
    }
}
