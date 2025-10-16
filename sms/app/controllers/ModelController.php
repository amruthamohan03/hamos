<?php

class ModelController extends Controller
{
    public function index()
    {
        $db = new Database();

        // Fetch all makes for the dropdown
        $makes = $db->selectData('make_master_t', '*', ['display' => 'Y']);

        // Fetch all models for listing
        // $models = $db->selectData('model_master_t', '*', []);
        $join   = "INNER JOIN make_master_t mk ON model_master_t.make_id = mk.id";
        $models = $db->selectData('model_master_t', '*', [], $join);
        // Join make names for display
        foreach ($models as $model) {
            $make               = $db->selectData('make_master_t', 'make_name', ['id' => $model['make_id']]);
            $model['make_name'] = $make[0]['make_name'] ?? '';
        }
        $this->viewWithLayout('masters/model', [
            'makes'     => $makes,
            'models'    => $models
        ]);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'model_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // 🔹 INSERTION
        // 🔹 INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'make_id'       => isset($_POST['make_id']) ? (int) $_POST['make_id'] : null,
                'model_name'    => sanitize($_POST['model_name'] ?? ''),
                'description'   => sanitize($_POST['description'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'created_by'    => 1,
                'updated_by'    => 1,
            ];

            if (empty($data['model_name'])) {
                echo "❌ Model Name is required.";
                return;
            }

            // ✅ Check for duplicate before insert
            $existing = $db->selectData($table, '*', [
                'make_id' => $data['make_id'],
                'model_name' => $data['model_name']
            ]);

            if (!empty($existing)) {
                echo "❌ Duplicate entry: This model already exists for the selected make.";
                return;
            }

            try {
                $insertId = $db->insertData($table, $data);
                if ($insertId) {
                    header("Location: " . BASE_URL . "/model/index");
                    exit;
                } else {
                    echo "❌ Insert failed.";
                }
            } catch (PDOException $e) {
                echo "❌ Database error: " . $e->getMessage();
            }
        }


        // 🔹 UPDATION
        elseif ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            if ($id <= 0) {
                echo "❌ Invalid Model ID for update.";
                return;
            }

            $data = [
                'make_id' => isset($_POST['make_id']) ? (int) $_POST['make_id'] : null,
                'model_name' => sanitize($_POST['model_name'] ?? ''),
                'description' => sanitize($_POST['description'] ?? ''),
                'display' => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by' => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            if ($update) {
                header("Location: " . BASE_URL . "/model?msg=update_success");
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
                header("Location: " . BASE_URL . "/model?msg=delete_success");
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
