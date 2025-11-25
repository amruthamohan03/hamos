<?php
class PhaseController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('phase_master_t', '*', []);
        $data = [
            'title' => 'Phase Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/phase', $data);
    }

    public function crudData($action = 'insertion')
    {
        header('Content-Type: application/json');
        $db = new Database();
        $table = 'phase_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // ➕ INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'phase_name' => sanitize($_POST['phase_name'] ?? ''),
                'phase_code' => sanitize($_POST['phase_code'] ?? ''),
                'display' => in_array($_POST['display'] ?? 'Y', ['Y', 'N']) ? $_POST['display'] : 'Y',
                'created_by' => 1,
                'updated_by' => 1
            ];

            if (empty($data['phase_name']) || empty($data['phase_code'])) {
                echo json_encode(['success' => false, 'message' => '❌ Phase Name and Code are required.']);
                exit;
            }

            $insert = $db->insertData($table, $data);
            echo json_encode($insert
                ? ['success' => true, 'message' => '✅ Phase added successfully!']
                : ['success' => false, 'message' => '❌ Failed to add phase.']);
            exit;
        }

        // ✏️ UPDATION
        if ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_GET['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid Phase ID']);
                exit;
            }

            $data = [
                'phase_name' => sanitize($_POST['phase_name'] ?? ''),
                'phase_code' => sanitize($_POST['phase_code'] ?? ''),
                'display' => in_array($_POST['display'] ?? 'Y', ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by' => 1
            ];

            if (empty($data['phase_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ Phase Name is required.']);
                exit;
            }

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode($update
                ? ['success' => true, 'message' => '✅ Phase updated successfully!']
                : ['success' => false, 'message' => '❌ Failed to update phase.']);
            exit;
        }

        // ❌ DELETION
        if ($action === 'deletion') {
            $id = (int) ($_GET['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
                exit;
            }

            $delete = $db->deleteData($table, ['id' => $id]);
            echo json_encode($delete
                ? ['success' => true, 'message' => '✅ Phase deleted successfully!']
                : ['success' => false, 'message' => '❌ Delete failed.']);
            exit;
        }

        echo json_encode(['success' => false, 'message' => '⚠️ Invalid request.']);
        exit;
    }

    // 🔍 GET SINGLE RECORD
    public function getPhaseById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            exit;
        }

        $phase = $db->selectData('phase_master_t', '*', ['id' => $id]);
        echo json_encode(!empty($phase)
            ? ['success' => true, 'data' => $phase[0]]
            : ['success' => false, 'message' => 'Record not found']);
        exit;
    }
}
?>