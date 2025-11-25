<?php

class CurrencyController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('currency_master_t', '*', []);
        $data = [
            'title' => 'currency Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/currency', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'currency_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'currency_short_name' => sanitize($_POST['currency_short_name'] ?? ''),
                'currency_name' => sanitize($_POST['currency_name'] ?? ''),

                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'created_by'    => 1,
                'updated_by'    => 1,
            ];

           if (empty($data['currency_short_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ short Name is required.']);
                exit;
            }
            if (empty($data['currency_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ full Name is required.']);
                exit;
            }


            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => '✅ Currency added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => '❌ Insert failed.']);
            exit;
        }

        // UPDATION
        elseif ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid currency ID.']);
                exit;
            }

            $data = [
                'currency_short_name' => sanitize($_POST['currency_short_name'] ?? ''),
                'currency_name' => sanitize($_POST['currency_name'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'    => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'currency updated successfully!' : 'Update failed.'
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
                ? ['success' => true, 'message' => '✅ currency deleted successfully!']
                : ['success' => false, 'message' => '❌ Delete failed.']);
            exit;
        }

        echo json_encode(['success' => false, 'message' => '⚠️ Invalid request.']);
        exit;
    
    }

    public function getcurrencyById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $currency = $db->selectData('currency_master_t', '*', ['id' => $id]);
        if (!empty($currency)) {
            echo json_encode(['success' => true, 'data' => $currency[0]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'currency not found.']);
        }
        exit;
    }
}
