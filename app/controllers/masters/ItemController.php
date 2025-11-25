<?php

class ItemController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('item_master_t', '*', [], 'id DESC');

        $data = [
            'title' => 'Item Master',
            'result' => $result
        ];

        $this->viewWithLayout('masters/item', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'item_master_t';

        function s($v) { return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8'); }

        /* ------------------------------------
         * INSERTION
         * ------------------------------------ */
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'short_name'          => s($_POST['short_name']),
                'item_name'           => s($_POST['item_name']),
                'customes_clearance'  => isset($_POST['customes_clearance']) ? 1 : 0,
                'other_charge'        => isset($_POST['other_charge']) ? 1 : 0,
                'operational_cost'    => isset($_POST['operational_cost']) ? 1 : 0,
                'service_fee'         => isset($_POST['service_fee']) ? 1 : 0,
                'display'             => ($_POST['display'] ?? 'Y'),
                'created_by'          => 1,
                'updated_by'          => 1,
            ];

            if (!$data['short_name'] || !$data['item_name']) {
                echo json_encode(['success' => false, 'message' => '❌ Please fill required fields.']);
                exit;
            }

            $insertId = $db->insertData($table, $data);

            echo json_encode([
                'success' => $insertId ? true : false,
                'message' => $insertId ? 'Item added successfully!' : 'Insert failed.'
            ]);
            exit;
        }

        /* ------------------------------------
         * UPDATION
         * ------------------------------------ */
        if ($action === 'updation' && $_SERVER['REQUEST_METHOD'] == 'POST') {

            header('Content-Type: application/json');

            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID']);
                exit;
            }

            $data = [
                'short_name'          => s($_POST['short_name']),
                'item_name'           => s($_POST['item_name']),
                'customes_clearance'  => isset($_POST['customes_clearance']) ? 1 : 0,
                'other_charge'        => isset($_POST['other_charge']) ? 1 : 0,
                'operational_cost'    => isset($_POST['operational_cost']) ? 1 : 0,
                'service_fee'         => isset($_POST['service_fee']) ? 1 : 0,
                'display'             => ($_POST['display'] ?? 'Y'),
                'updated_by'          => 1,
                'updated_at'          => date('Y-m-d H:i:s')
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);

            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'Item updated successfully!' : 'Update failed.'
            ]);
            exit;
        }

        /* ------------------------------------
         * DELETION
         * ------------------------------------ */
        if ($action === 'deletion') {
            $id = (int)($_GET['id'] ?? 0);

            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID']);
                exit;
            }

            $delete = $db->deleteData($table, ['id' => $id]);

            echo json_encode([
                'success' => $delete ? true : false,
                'message' => $delete ? 'Item deleted successfully!' : 'Delete failed.'
            ]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }

    public function getItemById()
    {
        header('Content-Type: application/json');

        $db = new Database();
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            exit;
        }

        $row = $db->selectData('item_master_t', '*', ['id' => $id]);

        echo json_encode(!empty($row)
            ? ['success' => true, 'data' => $row[0]]
            : ['success' => false, 'message' => 'Item not found']
        );
        exit;
    }
}
?>
