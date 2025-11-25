<?php

class TruckstatusController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('truck_status_master_t', '*', []);
        $data = [
            'title' => 'Truck status Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/truckstatus', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'truck_status_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'truck_status' => sanitize($_POST['truck_status'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'created_by'    => 1,
                'updated_by'    => 1,
            ];

           if (empty($data['truck_status'])) {
                echo json_encode(['success' => false, 'message' => '❌ Truck status is required.']);
                exit;
            }
           

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => '✅ Truck status  added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => '❌ Insert failed.']);
            exit;
        }

        // UPDATION
        elseif ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid truckstatus ID.']);
                exit;
            }

            $data = [
                'truck_status' => sanitize($_POST['truck_status'] ?? ''),
                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'    => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'Truck status  updated successfully!' : 'Update failed.'
            ]);
            exit;
        }

        // DELETION
        if ($action === 'deletion') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
                exit;
            }

            $delete = $db->deleteData($table, ['id' => $id]);
            echo json_encode($delete
                ? ['success' => true, 'message' => '✅ Truckstatus deleted successfully!']
                : ['success' => false, 'message' => '❌ Delete failed.']);
            exit;
        }

        echo json_encode(['success' => false, 'message' => '⚠️ Invalid request.']);
        exit;
    
    }

    public function gettruckstatusById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $truckstatus = $db->selectData('truck_status_master_t', '*', ['id' => $id]);
        if (!empty($truckstatus)) {
            echo json_encode(['success' => true, 'data' => $truckstatus[0]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Truck status  not found.']);
        }
        exit;
    }
}
