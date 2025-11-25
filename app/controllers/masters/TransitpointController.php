<?php
class TransitpointController extends Controller
{
    public function index()
    {
        $db = new Database();
        $result = $db->selectData('transit_point_master_t', '*', []);
        $data = [
            'title'  => 'Transit Points',
            'result' => $result
        ];
        $this->viewWithLayout('masters/transit_point', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db    = new Database();
        $table = 'transit_point_master_t';

        function sanitize($value) {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        $availableTypes = ['entry', 'exit', 'loading', 'destination', 'warehouse','location'];

        // ✅ INSERTION
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $types = $_POST['type'] ?? [];

            $data = [
                'transit_point_name' => sanitize($_POST['transit_point_name'] ?? ''),
                'entry_point'        => in_array('entry', $types) ? 'Y' : 'N',
                'exit_point'         => in_array('exit', $types) ? 'Y' : 'N',
                'loading'            => in_array('loading', $types) ? 'Y' : 'N',
                'destination'        => in_array('destination', $types) ? 'Y' : 'N',
                'warehouse'          => in_array('warehouse', $types) ? 'Y' : 'N',
                'location'           => in_array('location', $types) ? 'Y' : 'N',
                'display'            => in_array($_POST['display'] ?? 'Y', ['Y', 'N']) ? $_POST['display'] : 'Y',
                'created_by'         => 1,
                'updated_by'         => 1,
            ];

            if (empty($data['transit_point_name'])) {
                echo json_encode(['success'=>false,'message'=>'❌Transit Point Name is required.']);
                exit;
            }

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success'=>true,'message'=>'✅Transit Point added successfully.']
                : ['success'=>false,'message'=>'Failed to add record.']);
            exit;
        }

        // ✅ UPDATION
        if ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) { echo json_encode(['success'=>false,'message'=>'❌Invalid ID']); exit; }

            $types = $_POST['type'] ?? [];

            $data = [
                'transit_point_name' => sanitize($_POST['transit_point_name'] ?? ''),
                'entry_point'        => in_array('entry', $types) ? 'Y' : 'N',
                'exit_point'         => in_array('exit', $types) ? 'Y' : 'N',
                'loading'            => in_array('loading', $types) ? 'Y' : 'N',
                'destination'        => in_array('destination', $types) ? 'Y' : 'N',
                'warehouse'          => in_array('warehouse', $types) ? 'Y' : 'N',
                'location'           => in_array('location', $types) ? 'Y' : 'N',
                'display'            => in_array($_POST['display'] ?? 'Y',['Y','N']) ? $_POST['display'] : 'Y',
                'updated_by'         => 1
            ];

            $update = $db->updateData($table, $data, ['id'=>$id]);
            echo json_encode($update
                ? ['success'=>true,'message'=>'✅Transit Point updated successfully.']
                : ['success'=>false,'message'=>'❌Failed to update record.']);
            exit;
        }

        // ✅ DELETION
        if ($action === 'deletion') {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) { echo json_encode(['success'=>false,'message'=>'Invalid ID']); exit; }

            $delete = $db->deleteData($table, ['id'=>$id]);
            echo json_encode($delete
                ? ['success'=>true,'message'=>'✅Transit Point deleted successfully.']
                : ['success'=>false,'message'=>'❌Failed to delete record.']);
            exit;
        }
    }

    // ✅ FETCH SINGLE RECORD
    public function getTransitPointById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) { echo json_encode(['success'=>false,'message'=>'Invalid ID']); exit; }

        $tp = $db->selectData('transit_point_master_t', '*', ['id' => $id]);
        if (!empty($tp)) {
            $row = $tp[0];
            $row['type'] = [];
            foreach (['entry_point'=>'entry','exit_point'=>'exit','loading'=>'loading','destination'=>'destination','warehouse'=>'warehouse','location'=>'location'] as $col => $key) {
                if ($row[$col] === 'Y') $row['type'][] = $key;
            }
            echo json_encode(['success'=>true,'data'=>$row]);
        } else {
            echo json_encode(['success'=>false,'message'=>'❌Record not found']);
        }
        exit;
    }
}
?>
