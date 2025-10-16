<?php

class ItemController extends Controller
{
    public function index()
    {
        $db = new Database();

        // Fetch all makes for the dropdown
        $makes = $db->selectData('make_master_t', '*', ['display' => 'Y']); // adjust table name if needed

        // Fetch all models for the dropdown (can be filtered dynamically later)
        $models = $db->selectData('model_master_t', '*', ['display' => 'Y']); // adjust table name

        // Fetch all items for listing
        $items = $db->selectData('item_master_t', '*', []); // adjust table name

        // Join make and model names for display (optional)
        foreach ($items as &$item) {
            $make = $db->selectData('make_master_t', 'make_name', ['id' => $item['make_id']]);
            $model = $db->selectData('model_master_t', 'model_name', ['id' => $item['model_id']]);
            $item['make_name'] = $make[0]['make_name'] ?? '';
            $item['model_name'] = $model[0]['model_name'] ?? '';
        }

        $this->viewWithLayout('masters/item', [
            'makes'  => $makes,
            'models' => $models,
            'items'  => $items
        ]);
    }

    public function crudData($action = 'insertion')
    {
        $db    = new Database();
        $table = 'item_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // 🔹 INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'make_id'     => isset($_POST['make_id']) ? (int) $_POST['make_id'] : null,
                'model_id'    => isset($_POST['model_id']) ? (int) $_POST['model_id'] : null,
                'item_name'   => sanitize($_POST['item_name'] ?? ''),
                'item_code'   => sanitize($_POST['item_code'] ?? ''),
                'description' => sanitize($_POST['description'] ?? ''),
                'uom'         => sanitize($_POST['uom'] ?? ''),
                'display'     => isset($_POST['display']) && in_array($_POST['display'], ['Y','N']) ? $_POST['display'] : 'Y',
                'created_by'  => 1,
                'updated_by'  => 1,
            ];

            if (empty($data['item_name']) || empty($data['item_code'])) {
                echo "❌ Item Name and Item Code are required.";
                return;
            }

            $insertId = $db->insertData($table, $data);

            if ($insertId) {
                header("Location: " . BASE_URL . "/item/index");
                exit;
            } else {
                echo "❌ Insert failed.";
            }
        }

        // 🔹 UPDATION
        elseif ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            if ($id <= 0) {
                echo "❌ Invalid Item ID for update.";
                return;
            }

            $data = [
                'make_id'     => isset($_POST['make_id']) ? (int) $_POST['make_id'] : null,
                'model_id'    => isset($_POST['model_id']) ? (int) $_POST['model_id'] : null,
                'item_name'   => sanitize($_POST['item_name'] ?? ''),
                'item_code'   => sanitize($_POST['item_code'] ?? ''),
                'description' => sanitize($_POST['description'] ?? ''),
                'uom'         => sanitize($_POST['uom'] ?? ''),
                'display'     => isset($_POST['display']) && in_array($_POST['display'], ['Y','N']) ? $_POST['display'] : 'Y',
                'updated_by'  => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);

            if ($update) {
                header("Location: " . BASE_URL . "/item?msg=update_success");
                exit;
            } else {
                echo "❌ Update failed.";
            }
        }

        // 🔹 DELETION
        elseif ($action === 'deletion') {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            if ($id <= 0) {
                echo "❌ Invalid ID for deletion.";
                return;
            }

            $delete = $db->deleteData($table, ['id' => $id]);

            if ($delete) {
                header("Location: " . BASE_URL . "/item?msg=delete_success");
                exit;
            } else {
                echo "❌ Delete failed.";
            }
        }

        // 🔹 DEFAULT
        else {
            echo "⚠️ Invalid request.";
        }
    }
}
