<?php

class PaymentmethodController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('payment_method_master_t', '*', []);
        $data = [
            'title' => 'Payment Method Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/paymentmethod', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'payment_method_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'payment_method_name' => sanitize($_POST['payment_method_name'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'created_by'    => 1,
                'updated_by'    => 1,
            ];

             if (empty($data['payment_method_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ Payment method Name is required.']);
                exit;
            }

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => '✅ Payment method added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => '❌ Insert failed.']);
            exit;
        }

        // UPDATION
        elseif ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid Payment method ID.']);
                exit;
            }

            $data = [
                'payment_method_name' => sanitize($_POST['payment_method_name'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'    => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'Payment method updated successfully!' : 'Update failed.'
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
                ? ['success' => true, 'message' => '✅ Payment method deleted successfully!']
                : ['success' => false, 'message' => '❌ Delete failed.']);
            exit;
        }

        echo json_encode(['success' => false, 'message' => '⚠️ Invalid request.']);
        exit;
    
    }

    public function getPaymentmethodById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $paymentmethod = $db->selectData('payment_method_master_t', '*', ['id' => $id]);
        if (!empty($paymentmethod)) {
            echo json_encode(['success' => true, 'data' => $paymentmethod[0]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'paymentmethod not found.']);
        }
        exit;
    }
}
