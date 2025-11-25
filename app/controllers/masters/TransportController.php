<?php

class TransportController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('transport_mode_master_t', '*', []);
        $data = [
            'title' => 'Transport Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/transport', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'transport_mode_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'transport_mode_name' => sanitize($_POST['transport_mode_name'] ?? ''),
                'transport_letter' => sanitize($_POST['transport_letter'] ?? ''),

                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'created_by'    => 1,
                'updated_by'    => 1,
            ];

           if (empty($data['transport_mode_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ Transport mode Name is required.']);
                exit;
            }
            if (empty($data['transport_letter'])) {
                echo json_encode(['success' => false, 'message' => '❌ Transport letter is required.']);
                exit;
            }


            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => '✅ Transport mode added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => '❌ Insert failed.']);
            exit;
        }

        // UPDATION
        elseif ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid transport ID.']);
                exit;
            }

            $data = [
                'transport_mode_name' => sanitize($_POST['transport_mode_name'] ?? ''),
                'transport_letter' => sanitize($_POST['transport_letter'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'    => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'Transport mode updated successfully!' : 'Update failed.'
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
                ? ['success' => true, 'message' => '✅ Transport mode deleted successfully!']
                : ['success' => false, 'message' => '❌ Delete failed.']);
            exit;
        }

        echo json_encode(['success' => false, 'message' => '⚠️ Invalid request.']);
        exit;
    
    }

    public function gettransportById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $transport = $db->selectData('transport_mode_master_t', '*', ['id' => $id]);
        if (!empty($transport)) {
            echo json_encode(['success' => true, 'data' => $transport[0]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Transport mode not found.']);
        }
        exit;
    }
}
