<?php
class GroupcompanyController extends Controller
{
    public function index()
    {
        $db     = new Database();
        $result = $db->selectData('group_company_master_t', '*', [], 'id DESC');
        $data   = [
            'title'  => 'Group Company Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/group_company', $data);
    }

    public function crudData($action = 'insertion')
    {
        header('Content-Type: application/json');
        $db    = new Database();
        $table = 'group_company_master_t';

        // Helper for sanitizing inputs
        function sanitize($val)
        {
            return htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
        }

        // 🔹 INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'group_company_name' => sanitize($_POST['group_company_name'] ?? ''),
                'display'            => in_array($_POST['display'] ?? 'Y', ['Y', 'N']) ? $_POST['display'] : 'Y',
                'created_by'         => 1,
                'updated_by'         => 1
            ];

            if (empty($data['group_company_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ Group Company Name is required.']);
                exit;
            }

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => '✅ Group Company added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => '❌ Insert failed.']);
            exit;
        }

        // 🔹 UPDATION
        if ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid Group Company ID']);
                exit;
            }

            $data = [
                'group_company_name' => sanitize($_POST['group_company_name'] ?? ''),
                'display'            => in_array($_POST['display'] ?? 'Y', ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'         => 1
            ];

            if (empty($data['group_company_name'])) {
                echo json_encode(['success' => false, 'message' => '❌ Group Company Name is required.']);
                exit;
            }

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode($update
                ? ['success' => true, 'message' => '✅ Group Company updated successfully!']
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
                ? ['success' => true, 'message' => '✅ Group Company deleted successfully!']
                : ['success' => false, 'message' => '❌ Delete failed.']);
            exit;
        }

        echo json_encode(['success' => false, 'message' => '⚠️ Invalid request.']);
        exit;
    }

    public function getGroupCompanyById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            exit;
        }

        $company = $db->selectData('group_company_master_t', '*', ['id' => $id]);
        echo json_encode(!empty($company)
            ? ['success' => true, 'data' => $company[0]]
            : ['success' => false, 'message' => 'Record not found']);
        exit;
    }
}
?>
