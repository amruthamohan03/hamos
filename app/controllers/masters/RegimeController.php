<?php
class RegimeController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('regime_master_t', '*', []);
        $data = [
            'title'  => 'Regimes',
            'result' => $result
        ];
        $this->viewWithLayout('masters/regime_master', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'regime_master_t';

        function sanitize($value) {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }
        
        $availableTypes = ['I','E'];
        $selectedTypes  = isset($_POST['type']) && is_array($_POST['type'])
            ? array_intersect($_POST['type'], $availableTypes)
            : [];
        $typeStr = implode('', $selectedTypes);

        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['regime_name']) || empty($selectedTypes)) {
                echo json_encode(['success'=>false,'message'=>'Regime Name and Type are required.']); exit;
            }
            $data = [
                'regime_name' => sanitize($_POST['regime_name']),
                'type'        => $typeStr,
                'display'     => in_array($_POST['display'] ?? 'Y',['Y','N']) ? $_POST['display'] : 'Y',
                'created_by'  => 1,
                'updated_by'  => 1
            ];
            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId ? ['success'=>true,'message'=>'Inserted','id'=>$insertId]
                                       : ['success'=>false,'message'=>'Insert failed']);
            exit;
        }

        if ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0 || empty($_POST['regime_name']) || empty($selectedTypes)) {
                echo json_encode(['success'=>false,'message'=>'Invalid request']); exit;
            }

            $data = [
                'regime_name' => sanitize($_POST['regime_name']),
                'type'        => $typeStr,
                'display'     => in_array($_POST['display'] ?? 'Y',['Y','N']) ? $_POST['display'] : 'Y',
                'updated_by'  => 1
            ];

            $update = $db->updateData($table, $data, ['id'=>$id]);
            echo json_encode($update ? ['success'=>true,'message'=>'Updated successfully']
                                     : ['success'=>false,'message'=>'Update failed']);
            exit;
        }

        if ($action === 'deletion') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) { echo json_encode(['success'=>false,'message'=>'Invalid ID']); exit; }

            $delete = $db->deleteData($table, ['id'=>$id]);
            echo json_encode($delete ? ['success'=>true,'message'=>'Deleted successfully']
                                     : ['success'=>false,'message'=>'Delete failed']);
            exit;
        }
    }

    public function getRegimeById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) { echo json_encode(['success'=>false,'message'=>'Invalid ID']); exit; }

        $regime = $db->selectData('regime_master_t','*',['id'=>$id]);
        if (!empty($regime)) {
            $regime[0]['type'] = explode(',', $regime[0]['type']);
            echo json_encode(['success'=>true,'data'=>$regime[0]]);
        } else {
            echo json_encode(['success'=>false,'message'=>'Record not found']);
        }
        exit;
    }
}
?>
