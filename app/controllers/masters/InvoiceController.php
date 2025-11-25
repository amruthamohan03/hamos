<?php

class InvoiceController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('invoice_bank_master_t', '*', []);
        $data = [
            'title' => 'Invoice bank Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/invoice', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'invoice_bank_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'invoice_bank_account_name' => sanitize($_POST['invoice_bank_account_name'] ?? ''),
                'invoice_bank_account_number' => sanitize($_POST['invoice_bank_account_number'] ?? ''),
                'invoice_bank_address' => sanitize($_POST['invoice_bank_address'] ?? ''),
                'invoice_bank_name' => sanitize($_POST['invoice_bank_name'] ?? ''),
                'invoice_bank_swift' => sanitize($_POST['invoice_bank_swift'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'created_by'    => 1,
                'updated_by'    => 1,
            ];

           if (empty($data['invoice_bank_account_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ bank account name is required.']);
                exit;
            }
            if (empty($data['invoice_bank_account_number'])) {
                echo json_encode(['success' => false, 'message' => '❌ bank account number is required.']);
                exit;
            }


            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => '✅ Invoice bank details added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => '❌ Insert failed.']);
            exit;
        }

        // UPDATION
        elseif ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid invoice bank ID.']);
                exit;
            }

            $data = [
                'invoice_bank_account_name' => sanitize($_POST['invoice_bank_account_name'] ?? ''),
                'invoice_bank_account_number' => sanitize($_POST['invoice_bank_account_number'] ?? ''),
                'invoice_bank_address' => sanitize($_POST['invoice_bank_address'] ?? ''),
                'invoice_bank_name' => sanitize($_POST['invoice_bank_name'] ?? ''),
                'invoice_bank_swift' => sanitize($_POST['invoice_bank_swift'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'    => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'invoice bank details updated successfully!' : 'Update failed.'
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
                ? ['success' => true, 'message' => '✅ invoice bank details deleted successfully!']
                : ['success' => false, 'message' => '❌ Delete failed.']);
            exit;
        }

        echo json_encode(['success' => false, 'message' => '⚠️ Invalid request.']);
        exit;
    
    }

    public function getinvoiceById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $invoice = $db->selectData('invoice_bank_master_t', '*', ['id' => $id]);
        if (!empty($invoice)) {
            echo json_encode(['success' => true, 'data' => $invoice[0]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'invoice bank details not found.']);
        }
        exit;
    }
}
