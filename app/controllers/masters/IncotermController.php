<?php

class IncotermController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('incoterm_master_t', '*', []);
        $data = [
            'title' => 'Incoterm Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/incoterm', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'incoterm_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'incoterm_short_name' => sanitize($_POST['incoterm_short_name'] ?? ''),
                'incoterm_full_name' => sanitize($_POST['incoterm_full_name'] ?? ''),

                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'created_by'    => 1,
                'updated_by'    => 1,
            ];

           if (empty($data['incoterm_short_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ short Name is required.']);
                exit;
            }
            if (empty($data['incoterm_full_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ full Name is required.']);
                exit;
            }


            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => '✅ Incoterm added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => '❌ Insert failed.']);
            exit;
        }

        // UPDATION
        elseif ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid Incoterm ID.']);
                exit;
            }

            $data = [
                'incoterm_short_name' => sanitize($_POST['incoterm_short_name'] ?? ''),
                'incoterm_full_name' => sanitize($_POST['incoterm_full_name'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'    => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'Incoterm updated successfully!' : 'Update failed.'
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
                ? ['success' => true, 'message' => '✅ incoterm deleted successfully!']
                : ['success' => false, 'message' => '❌ Delete failed.']);
            exit;
        }

        echo json_encode(['success' => false, 'message' => '⚠️ Invalid request.']);
        exit;
    
    }

    public function getIncotermById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $incoterm = $db->selectData('incoterm_master_t', '*', ['id' => $id]);
        if (!empty($incoterm)) {
            echo json_encode(['success' => true, 'data' => $incoterm[0]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'incoterm not found.']);
        }
        exit;
    }
}
