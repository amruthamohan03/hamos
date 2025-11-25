<?php

class UnitController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('unit_master_t', '*', []);
        $data = [
            'title' => 'Unit Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/unit', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'unit_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'unit_name' => sanitize($_POST['unit_name'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'created_by'    => 1,
                'updated_by'    => 1,
            ];

           if (empty($data['unit_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ unit name is required.']);
                exit;
            }
           

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => '✅ unit  added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => '❌ Insert failed.']);
            exit;
        }

        // UPDATION
        elseif ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid unit ID.']);
                exit;
            }

            $data = [
                'unit_name' => sanitize($_POST['unit_name'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'    => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'unit  updated successfully!' : 'Update failed.'
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
                ? ['success' => true, 'message' => '✅ unit deleted successfully!']
                : ['success' => false, 'message' => '❌ Delete failed.']);
            exit;
        }

        echo json_encode(['success' => false, 'message' => '⚠️ Invalid request.']);
        exit;
    
    }

    public function getunitById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $unit = $db->selectData('unit_master_t', '*', ['id' => $id]);
        if (!empty($unit)) {
            echo json_encode(['success' => true, 'data' => $unit[0]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'unit  not found.']);
        }
        exit;
    }
}
