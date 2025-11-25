<?php
class HscodeController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('hscode_master_t', '*', []);
        $data = [
            'title'  => 'HS Code Master',
            'result' => $result
        ];
        $this->viewWithLayout('masters/hscode_master', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'hscode_master_t';

        function sanitize($value) {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // INSERT
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['hscode_number'])) {
                echo json_encode(['success'=>false,'message'=>'HS Code Number required']); exit;
            }
            if (empty($_POST['hscode_ddi'])) {
                echo json_encode(['success'=>false,'message'=>'HS DDI required']); exit;
            }
          $hscode_number = sanitize($_POST['hscode_number']);

            
            $db->query("SELECT id FROM $table WHERE hscode_number = :code");
            $db->bind(":code", $hscode_number);
            $exists = $db->single();

            if ($exists) {
                echo json_encode(['success' => false, 'message' => 'HS Code already exists']);
                exit;
            }
                    $data = [
                'hscode_number' => sanitize($_POST['hscode_number']),
                'hscode_ddi'    => $_POST['hscode_ddi'] ?? 0.00,
                'hscode_ica'    => $_POST['hscode_ica'] ?? 0.00,
                'hscode_dci'    => $_POST['hscode_dci'] ?? 0.00,
                'hscode_dcl'    => $_POST['hscode_dcl'] ?? 0.00,
                'hscode_tpi'    => $_POST['hscode_tpi'] ?? 0.00,
                'display'       => in_array($_POST['display'] ?? 'Y',['Y','N']) ? $_POST['display'] : 'Y',
                'created_by'    => 1,
                'updated_by'    => 1
            ];

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId ? ['success'=>true,'message'=>'Inserted','id'=>$insertId]
                                       : ['success'=>false,'message'=>'Insert failed']);
            exit;
        }

        // UPDATE
        if ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) { echo json_encode(['success'=>false,'message'=>'Invalid ID']); exit; }

            $data = [
                'hscode_number' => sanitize($_POST['hscode_number']),
                'hscode_ddi'    => $_POST['hscode_ddi'] ?? 0.00,
                'hscode_ica'    => $_POST['hscode_ica'] ?? 0.00,
                'hscode_dci'    => $_POST['hscode_dci'] ?? 0.00,
                'hscode_dcl'    => $_POST['hscode_dcl'] ?? 0.00,
                'hscode_tpi'    => $_POST['hscode_tpi'] ?? 0.00,
                'display'       => in_array($_POST['display'] ?? 'Y',['Y','N']) ? $_POST['display'] : 'Y',
                'updated_by'    => 1
            ];

            $update = $db->updateData($table, $data, ['id'=>$id]);
            echo json_encode($update ? ['success'=>true,'message'=>'Updated successfully']
                                     : ['success'=>false,'message'=>'Update failed']);
            exit;
        }

        // DELETE
        if ($action === 'deletion') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) { echo json_encode(['success'=>false,'message'=>'Invalid ID']); exit; }

            $delete = $db->deleteData($table, ['id'=>$id]);
            echo json_encode($delete ? ['success'=>true,'message'=>'Deleted successfully']
                                     : ['success'=>false,'message'=>'Delete failed']);
            exit;
        }
    }

    public function getHscodeById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            exit;
        }

        $data = $db->selectData('hscode_master_t', '*', ['id' => $id]);

        if (!empty($data)) {
            // Safely handle 'type'
            $typeValue = $data[0]['type'] ?? ''; // prevent undefined key
            $data[0]['type'] = $typeValue ? str_split($typeValue) : [];

            echo json_encode(['success' => true, 'data' => $data[0]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
        }
        exit;
    }

}
?>
