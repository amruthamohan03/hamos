<?php

class DocumentstatusController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('document_status_master_t', '*', []);
        $data = [
            'title' => 'Document Status Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/documentstatus', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'document_status_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'document_status' => sanitize($_POST['document_status'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'created_by'    => 1,
                'updated_by'    => 1,
            ];

           if (empty($data['document_status'])) {
                echo json_encode(['success' => false, 'message' => '❌ Document status is required.']);
                exit;
            }
           

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => '✅ Document status added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => '❌ Insert failed.']);
            exit;
        }

        // UPDATION
        elseif ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid document status ID.']);
                exit;
            }

            $data = [
                'document_status' => sanitize($_POST['document_status'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'    => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'Document status  updated successfully!' : 'Update failed.'
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
                ? ['success' => true, 'message' => '✅ Document status deleted successfully!']
                : ['success' => false, 'message' => '❌ Delete failed.']);
            exit;
        }

        echo json_encode(['success' => false, 'message' => '⚠️ Invalid request.']);
        exit;
    
    }

    public function getdocumentstatusById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $document_status = $db->selectData('document_status_master_t', '*', ['id' => $id]);
        if (!empty($document_status)) {
            echo json_encode(['success' => true, 'data' => $document_status[0]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'document_status  not found.']);
        }
        exit;
    }
}
