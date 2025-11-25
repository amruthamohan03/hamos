<?php
class CategoryController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('category_master_t', '*', []);
        $data = [
            'title'  => 'Category Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/category', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'category_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // 🔹 INSERT
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $category_name = sanitize($_POST['category_name'] ?? '');
            $display = isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y';

            if (empty($category_name)) {
                echo json_encode(['success' => false, 'message' => 'Category name is required.']);
                exit;
            }

            $data = [
                'category_name' => $category_name,
                'display'       => $display,
                'created_by'    => 1,
                'updated_by'    => 1,
            ];

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => 'Category added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => 'Insert failed.']
            );
            exit;
        }

        // 🔹 UPDATE
        if ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid Category ID.']);
                exit;
            }

            $data = [
                'category_name' => sanitize($_POST['category_name'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'    => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'Category updated successfully!' : 'Update failed.'
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
                'message' => $delete ? 'Category deleted successfully!' : 'Delete failed.'
            ]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit;
    }

    public function getCategoryById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $category = $db->selectData('category_master_t', '*', ['id' => $id]);
        echo json_encode(!empty($category)
            ? ['success' => true, 'data' => $category[0]]
            : ['success' => false, 'message' => 'Category not found.']
        );
        exit;
    }
}
?>
