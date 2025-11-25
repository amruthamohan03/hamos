<?php
class BanklistController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('banklist_master_t', '*', []);
        $data = [
            'title'  => 'Bank List',
            'result' => $result
        ];
        $this->viewWithLayout('masters/banklist', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'banklist_master_t';

        function sanitize($value) {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // INSERT
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['bank_name']) || empty($_POST['bank_code'])) {
                echo json_encode(['success'=>false,'message'=>'Bank Name and Code are required.']);
                exit;
            }

            $data = [
                'bank_name'  => sanitize($_POST['bank_name']),
                'bank_code'  => sanitize($_POST['bank_code']),
                'display'    => in_array($_POST['display'] ?? 'Y', ['Y','N']) ? $_POST['display'] : 'Y',
                'created_by' => 1,
                'updated_by' => 1
            ];

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId ? ['success'=>true,'message'=>'Inserted successfully!','id'=>$insertId]
                                      : ['success'=>false,'message'=>'Insert failed']);
            exit;
        }

        // UPDATE
        if ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0 || empty($_POST['bank_name']) || empty($_POST['bank_code'])) {
                echo json_encode(['success'=>false,'message'=>'Invalid data for update.']);
                exit;
            }

            $data = [
                'bank_name'  => sanitize($_POST['bank_name']),
                'bank_code'  => sanitize($_POST['bank_code']),
                'display'    => in_array($_POST['display'] ?? 'Y', ['Y','N']) ? $_POST['display'] : 'Y',
                'updated_by' => 1
            ];

            $update = $db->updateData($table, $data, ['id'=>$id]);
            echo json_encode($update ? ['success'=>true,'message'=>'Updated successfully!']
                                     : ['success'=>false,'message'=>'Update failed']);
            exit;
        }

        // DELETE
        if ($action === 'deletion') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) { echo json_encode(['success'=>false,'message'=>'Invalid ID']); exit; }

            $delete = $db->deleteData($table, ['id'=>$id]);
            echo json_encode($delete ? ['success'=>true,'message'=>'Deleted successfully!']
                                     : ['success'=>false,'message'=>'Delete failed']);
            exit;
        }
    }

    public function getBankById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            echo json_encode(['success'=>false,'message'=>'Invalid ID']);
            exit;
        }

        $bank = $db->selectData('banklist_master_t', '*', ['id'=>$id]);
        if (!empty($bank)) {
            echo json_encode(['success'=>true,'data'=>$bank[0]]);
        } else {
            echo json_encode(['success'=>false,'message'=>'Bank not found']);
        }
        exit;
    }
}
?>
