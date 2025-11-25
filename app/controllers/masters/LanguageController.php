<?php
class LanguageController extends Controller
{
    

    public function index()
    {
        $db = new Database();
        $result = $db->selectQuery("SELECT s.id,s.label,s.english,s.french,
               t.menu_name,
               s.display,
               s.created_at,
               s.updated_at
        FROM language_translation_t s
        LEFT JOIN menu_master_t t ON s.module_id = t.id
        ORDER BY s.id ASC
    ");

        $modules = $db->selectData('menu_master_t', 'id, menu_name', [], 'menu_name ASC');

        $data = [
            'title'  => 'Language Master',
            'result' => $result,
            'modules' => $modules
        ];
        $this->viewWithLayout('masters/language', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'language_translation_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // 🔹 INSERT
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $label = sanitize($_POST['label'] ?? '');
            $english = sanitize($_POST['english'] ?? '');
            $french = sanitize($_POST['french'] ?? '');
            $menu_name = sanitize($_POST['menu_name'] ?? '');
            $display = isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y';

            if (empty($label)) {
                echo json_encode(['success' => false, 'message' => 'label is required.']);
                exit;
            }

            $data = [
                'label' => $label,
                'english' => $english,
                'french' => $french,
                'module_id'=>$menu_name,
                'display'       => $display,
                'created_by'    => 1,
                'updated_by'    => 1,
            ];

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => 'Language added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => 'Insert failed.']
            );
            exit;
        }

        // 🔹 UPDATE
        if ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid language ID.']);
                exit;
            }

            $data = [
                'label' => sanitize($_POST['label'] ?? ''),
                'english' => sanitize($_POST['english'] ?? ''),
                'french' => sanitize($_POST['french'] ?? ''),
                'module_id' => sanitize($_POST['module_id'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'    => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'language updated successfully!' : 'Update failed.'
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
                'message' => $delete ? 'Language deleted successfully!' : 'Delete failed.'
            ]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit;
    }

    

    public function switch($lang = 'en') {
        $this->setLanguage($lang);
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? URL_ROOT));
        exit();
    }

}
?>
