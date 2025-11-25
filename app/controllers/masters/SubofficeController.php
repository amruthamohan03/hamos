<?php
class SubofficeController extends Controller
{
    

    public function index()
    {
        $db = new Database();
        $result = $db->selectQuery("SELECT s.id,s.sub_office_name,s.main_office_id,
               t.main_location_name,
               s.display,
               s.created_at,
               s.updated_at
        FROM sub_office_master_t s
        LEFT JOIN main_office_master_t t ON s.main_office_id = t.id
        ORDER BY s.id ASC
    ");

        $mainoffice = $db->selectData('main_office_master_t', 'id, main_location_name', [], 'main_location_name ASC');

        $data = [
            'title'  => 'Sub Office Master',
            'result' => $result,
            'mainoffice' => $mainoffice
        ];
        $this->viewWithLayout('masters/suboffice', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'sub_office_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // 🔹 INSERT
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $sub_office_name = sanitize($_POST['sub_office_name'] ?? '');
            $main_office_id = sanitize($_POST['main_office_id'] ?? '');
            $display = isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y';

            if (empty($sub_office_name)) {
                echo json_encode(['success' => false, 'message' => 'Sub office is required.']);
                exit;
            }

            $data = [
                'sub_office_name' => $sub_office_name,
                'main_office_id'=>$main_office_id,
                'display'       => $display,
                'created_by'    => 1,
                'updated_by'    => 1,
            ];

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => 'Sub office added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => 'Insert failed.']
            );
            exit;
        }

        // 🔹 UPDATE
        if ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid sub office ID.']);
                exit;
            }

            $data = [
                'sub_office_name' => sanitize($_POST['sub_office_name'] ?? ''),
                'main_office_id' => sanitize($_POST['main_office_id'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'    => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'Sub office updated successfully!' : 'Update failed.'
            ]);
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
            echo json_encode([
                'success' => $delete ? true : false,
                'message' => $delete ? 'Sub office deleted successfully!' : 'Delete failed.'
            ]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit;
    }

    public function getsubofficeById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $result =  $db->selectQuery("SELECT s.id,s.sub_office_name,s.main_office_id,
               t.main_location_name,
               s.display,
               s.created_at,
               s.updated_at
        FROM sub_office_master_t s
        LEFT JOIN main_office_master_t t ON s.main_office_id = t.id
        ORDER BY s.id ASC
    ");

        echo json_encode(!empty($result)
            ? ['success' => true, 'data' => $result[0]]
            : ['success' => false, 'message' => 'Sub office not found.']
        );
        exit;
    }
}
?>
