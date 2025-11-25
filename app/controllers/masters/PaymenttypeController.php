<?php

class PaymenttypeController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('payment_type_master_t', '*', []);
        $data = [
            'title' => 'Payment type Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/paymenttype', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'payment_type_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'payment_type_name' => sanitize($_POST['payment_type_name'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'created_by'    => 1,
                'updated_by'    => 1,
            ];

             if (empty($data['payment_type_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ Payment type Name is required.']);
                exit;
            }

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => '✅ Payment type added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => '❌ Insert failed.']);
            exit;
        }

        // UPDATION
        elseif ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid Payment type ID.']);
                exit;
            }

            $data = [
                'payment_type_name' => sanitize($_POST['payment_type_name'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'    => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'Payment type updated successfully!' : 'Update failed.'
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
                ? ['success' => true, 'message' => '✅ payment type deleted successfully!']
                : ['success' => false, 'message' => '❌ Delete failed.']);
            exit;
        }

        echo json_encode(['success' => false, 'message' => '⚠️ Invalid request.']);
        exit;
    
    }

    public function getPaymenttypeById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $paymenttype = $db->selectData('payment_type_master_t', '*', ['id' => $id]);
        if (!empty($paymenttype)) {
            echo json_encode(['success' => true, 'data' => $paymenttype[0]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'paymenttype not found.']);
        }
        exit;
    }
}
