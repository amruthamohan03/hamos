<?php
class KindController extends Controller
{
    public function index()
    {
        $db     = new Database();
        $result = $db->selectData('kind_master_t', '*', []);
        $data   = [
            'title'  => 'Kind Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/kind', $data);
    }

    public function crudData($action = 'insertion')
    {
        header('Content-Type: application/json');
        $db    = new Database();
        $table = 'kind_master_t';

        // Sanitize helper
        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // 🔹 INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'kind_name'       => sanitize($_POST['kind_name'] ?? ''),
                'kind_short_name' => sanitize($_POST['kind_short_name'] ?? ''),
                'display'         => in_array($_POST['display'] ?? 'Y', ['Y', 'N']) ? $_POST['display'] : 'Y',
                'created_by'      => 1,
                'updated_by'      => 1
            ];

            if (empty($data['kind_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ Kind Name is required.']);
                exit;
            }

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => '✅ Kind added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => '❌ Insert failed.']);
            exit;
        }

        // 🔹 UPDATION
        if ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid Kind ID']);
                exit;
            }

            $data = [
                'kind_name'       => sanitize($_POST['kind_name'] ?? ''),
                'kind_short_name' => sanitize($_POST['kind_short_name'] ?? ''),
                'display'         => in_array($_POST['display'] ?? 'Y', ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'      => 1
            ];

            if (empty($data['kind_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ Kind Name is required.']);
                exit;
            }

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode($update
                ? ['success' => true, 'message' => '✅ Kind updated successfully!']
                : ['success' => false, 'message' => '❌ Update failed.']);
            exit;
        }

        // 🔹 DELETION
        if ($action === 'deletion') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
                exit;
            }

            $delete = $db->deleteData($table, ['id' => $id]);
            echo json_encode($delete
                ? ['success' => true, 'message' => '✅ Kind deleted successfully!']
                : ['success' => false, 'message' => '❌ Delete failed.']);
            exit;
        }

        echo json_encode(['success' => false, 'message' => '⚠️ Invalid request.']);
        exit;
    }

    public function getKindById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            exit;
        }

        $kind = $db->selectData('kind_master_t', '*', ['id' => $id]);
        echo json_encode(!empty($kind)
            ? ['success' => true, 'data' => $kind[0]]
            : ['success' => false, 'message' => 'Record not found']);
        exit;
    }
}
?>
