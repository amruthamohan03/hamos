<?php

class ClearingstatusController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('clearing_status_master_t', '*', []);
        $data = [
            'title'  => 'Clearing Status Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/clearing_status', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db    = new Database();
        $table = 'clearing_status_master_t';

        // Helper sanitize function
        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        header('Content-Type: application/json');

        // 🔹 INSERT
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $clearing_status = sanitize($_POST['clearing_status'] ?? '');
            $display = in_array($_POST['display'] ?? 'Y', ['Y', 'N']) ? $_POST['display'] : 'Y';

            if (empty($clearing_status)) {
                echo json_encode(['success' => false, 'message' => '❌Clearing Status is required.']);
                exit;
            }

            $data = [
                'clearing_status' => $clearing_status,
                'display'         => $display,
                'created_by'      => 1,
                'updated_by'      => 1,
            ];

            $insertId = $db->insertData($table, $data);

            echo json_encode($insertId
                ? ['success' => true, 'message' => '✅Clearing Status added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => '❌Insert failed.']
            );
            exit;
        }

        // 🔹 UPDATE
        if ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => '❌Invalid Clearing Status ID.']);
                exit;
            }

            $data = [
                'clearing_status' => sanitize($_POST['clearing_status'] ?? ''),
                'display'         => in_array($_POST['display'] ?? 'Y', ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'      => 1,
            ];

            if (empty($data['clearing_status'])) {
                echo json_encode(['success' => false, 'message' => '❌Clearing Status is required.']);
                exit;
            }

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode($update
                ? ['success' => true, 'message' => '✅Clearing Status updated successfully!']
                : ['success' => false, 'message' => '❌Update failed.']
            );
            exit;
        }

        // 🔹 DELETE
        if ($action === 'deletion') {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID for deletion.']);
                exit;
            }

            $delete = $db->deleteData($table, ['id' => $id]);
            echo json_encode($delete
                ? ['success' => true, 'message' => '✅Clearing Status deleted successfully!']
                : ['success' => false, 'message' => '❌Delete failed.']
            );
            exit;
        }

        echo json_encode(['success' => false, 'message' => '❌Invalid request.']);
        exit;
    }

    public function getStatusById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $table = 'clearing_status_master_t';
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $status = $db->selectData($table, '*', ['id' => $id]);
        echo json_encode(!empty($status)
            ? ['success' => true, 'data' => $status[0]]
            : ['success' => false, 'message' => '❌Clearing Status not found.']
        );
        exit;
    }
}
?>
