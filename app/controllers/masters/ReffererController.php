<?php

class ReffererController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('refferer_master_t', '*', []);
        $data = [
            'title' => 'refferer Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/refferer', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'refferer_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'refferer_name' => sanitize($_POST['refferer_name'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'created_by'    => 1,
                'updated_by'    => 1,
            ];

            if (empty($data['refferer_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ Refferer Name is required.']);
                exit;
            }

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => '✅ Refferer added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => '❌ Insert failed.']);
            exit;
        }

        // UPDATION
        elseif ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid refferer ID.']);
                exit;
            }

            $data = [
                'refferer_name' => sanitize($_POST['refferer_name'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'    => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'refferer updated successfully!' : 'Update failed.'
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
                ? ['success' => true, 'message' => '✅ Refferer deleted successfully!']
                : ['success' => false, 'message' => '❌ Delete failed.']);
            exit;
        }

        echo json_encode(['success' => false, 'message' => '⚠️ Invalid request.']);
        exit;
    
    }

    public function getreffererById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $refferer = $db->selectData('refferer_master_t', '*', ['id' => $id]);
        if (!empty($refferer)) {
            echo json_encode(['success' => true, 'data' => $refferer[0]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'refferer not found.']);
        }
        exit;
    }
}
