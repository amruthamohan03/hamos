<?php
class PaymentsubtypeController extends Controller
{
    

    public function index()
    {
        $db = new Database();
        $result = $db->selectQuery("SELECT s.id,s.payment_subtype,s.payment_type_id,
               t.payment_type_name,
               s.display,
               s.created_at,
               s.updated_at
        FROM payment_subtype_master_t s
        LEFT JOIN payment_type_master_t t ON s.payment_type_id = t.id
        ORDER BY s.id ASC
    ");

        $paymenttype = $db->selectData('payment_type_master_t', 'id, payment_type_name', [], 'payment_type_name ASC');

        $data = [
            'title'  => 'Payment Subtype Master',
            'result' => $result,
            'paymenttype' => $paymenttype
        ];
        $this->viewWithLayout('masters/paymentsubtype', $data);
    }

    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'payment_subtype_master_t';

        function sanitize($value)
        {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // 🔹 INSERT
        if ($action === 'insertion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $payment_subtype = sanitize($_POST['payment_subtype'] ?? '');
            $payment_type_id = sanitize($_POST['payment_type_name'] ?? '');
            $display = isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y';

            if (empty($payment_subtype)) {
                echo json_encode(['success' => false, 'message' => 'Payment subtype is required.']);
                exit;
            }

            $data = [
                'payment_subtype' => $payment_subtype,
                'payment_type_id'=>$payment_type_id,
                'display'       => $display,
                'created_by'    => 1,
                'updated_by'    => 1,
            ];

            $insertId = $db->insertData($table, $data);
            echo json_encode($insertId
                ? ['success' => true, 'message' => 'Payment subtype added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => 'Insert failed.']
            );
            exit;
        }

        // 🔹 UPDATE
        if ($action === 'updation' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid payment subtype ID.']);
                exit;
            }

            $data = [
                'payment_subtype' => sanitize($_POST['payment_subtype'] ?? ''),
                'payment_type_id' => sanitize($_POST['payment_type_name'] ?? ''),

                'display'       => isset($_POST['display']) && in_array($_POST['display'], ['Y', 'N']) ? $_POST['display'] : 'Y',
                'updated_by'    => 1,
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);
            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'Payment subtype updated successfully!' : 'Update failed.'
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
                'message' => $delete ? 'Payment subtype deleted successfully!' : 'Delete failed.'
            ]);
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit;
    }

    public function getpaymentsubtypeById()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        $paymentsubtype =  $db->selectQuery("SELECT s.id,s.payment_subtype,s.payment_type_id,
               t.payment_type_name,
               s.display,
               s.created_at,
               s.updated_at
        FROM payment_subtype_master_t s
        LEFT JOIN payment_type_master_t t ON s.payment_type_id = t.id
        ORDER BY s.id ASC
    ");

        echo json_encode(!empty($paymentsubtype)
            ? ['success' => true, 'data' => $paymentsubtype[0]]
            : ['success' => false, 'message' => 'paymentsubtype not found.']
        );
        exit;
    }
}
?>
