<?php
class ClearanceController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('clearance_master_t', '*', []);
        $data = [
            'title'  => 'Clearance Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/clearance', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'clearance_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // 🔹 INSERT
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $clearance_name = sanitize($_POST['clearance_name'] ?? '');
            $display = isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y';

            if (empty($clearance_name)) {
                echo json_encode(['success' => false, 'message' => 'Clearance Name is required.']);
                exit;
            }

            $data = [
                'clearance_name' => $clearance_name,
                'display'        => $display,
                'created_by'     => 1,
                'updated_by'     => 1,
            ];

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => 'Clearance added successfully!']
                : ['success' => false, 'message' => 'Insert failed.']
            );
            exit;
        }

        // 🔹 UPDATE
        if ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid Clearance ID.']);
                exit;
            }

            $data = [
                'clearance_name' => sanitize($_POST['clearance_name'] ?? ''),
                'display'        => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'     => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'Clearance updated successfully!' : 'Update failed.'
            ]);
            exit;
        }

        // 🔹 DELETE
        if ($action === 'deletion') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID for deletion.']);
                exit;
            }

            $delete = $db->deleteData($table, ['id' => $id]);
            echo json_encode([
                'success' => $delete ? true : false,
                'message' => $delete ? 'Clearance deleted successfully!' : 'Delete failed.'
            ]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit;
    }

    public function getClearanceById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $clearance = $db->selectData('clearance_master_t', '*', ['id' => $id]);
        echo json_encode(!empty($clearance)
            ? ['success' => true, 'data' => $clearance[0]]
            : ['success' => false, 'message' => 'Clearance not found.']
        );
        exit;
    }
}
?>
