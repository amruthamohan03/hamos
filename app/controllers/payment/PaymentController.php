<?php

class PaymentController extends Controller
{
    /* -----------------------------------------------------------
       LOAD PAGE
    -------------------------------------------------------------*/
    public function index()
    {
        $db = new Database();
        $dept     = $db->selectData('department_master_t','*',[]);
        $loc      = $db->selectData('main_office_master_t','*',[]);
        $client   = $db->selectData('clients_t','*',[]);
        $currency = $db->selectData('currency_master_t','*',[],2);
        $expense  = $db->selectData('expense_type_master_t','*',[]);

        $data = [
            'title'     => 'Payment Request',
            'dept'      => $dept,
            'loc'       => $loc,
            'client'    => $client,
            'currency'  => $currency,
            'expense'   => $expense
        ];

        $this->viewWithLayout('payment/payment', $data);
    }



    /* -----------------------------------------------------------
       DATATABLE LIST
    -------------------------------------------------------------*/
    public function get_list()
    {
        header('Content-Type: application/json');

        $db = new Database();

        $draw   = intval($_POST['draw'] ?? 1);
        $start  = intval($_POST['start'] ?? 0);
        $length = intval($_POST['length'] ?? 10);
        $search = trim($_POST['search']['value'] ?? '');

        $sql = "SELECT pr.*, d.department_name, c.short_name AS client_name,
                       cu.currency_short_name
                FROM payment_requests pr
                LEFT JOIN department_master_t d ON d.id = pr.department
                LEFT JOIN clients_t c ON c.id = pr.client_id
                LEFT JOIN currency_master_t cu ON cu.id = pr.currency
                WHERE 1 ";

        $params = [];

        if (!empty($search)) {
            $sql .= " AND (pr.beneficiary LIKE :search OR pr.motif LIKE :search)";
            $params[':search'] = "%{$search}%";
        }

        $totalResult = $db->customQuery("SELECT COUNT(*) AS total FROM payment_requests");
        $recordsTotal = $totalResult[0]['total'] ?? 0;

        $countSql = "SELECT COUNT(*) AS filtered FROM payment_requests pr WHERE 1 ";
        if (!empty($search)) {
            $countSql .= " AND (pr.beneficiary LIKE :search OR pr.motif LIKE :search)";
        }
        $countResult = $db->customQuery($countSql, $params);
        $recordsFiltered = $countResult[0]['filtered'] ?? 0;

        $sql .= " ORDER BY pr.id DESC LIMIT :start, :length";
        $params[':start'] = $start;
        $params[':length'] = $length;

        $data = $db->customQuery($sql, $params);

        echo json_encode([
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ]);
        exit;
    }




    /* -----------------------------------------------------------
       STORE PAYMENT REQUEST  (INSERT / UPDATE)
    -------------------------------------------------------------*/
    public function store()
{
    $db = new Database();

    $action        = $_POST['action'] ?? 'insert';
    $payment_id    = $_POST['payment_id'] ?? null;

    $department_id = $_POST['department'] ?? null; 
    $location_id   = $_POST['location'] ?? null;
    $beneficiary   = $_POST['beneficiary'] ?? null;
        $requestee   = $_POST['requestee'] ?? null;

    $client_id     = $_POST['client_id'] ?? null;
    $pay_for       = $_POST['pay_for'] ?? null;
    $currency_id   = $_POST['currency'] ?? null;
    $amount        = $_POST['amount'] ?? 0;
    $payment_type  = $_POST['payment_type'] ?? null;
    $expense_type  = $_POST['expense_type'] ?? null;
    $motif         = $_POST['motif'] ?? null;
    
    $mca_refs      = $_POST['mca_reference'] ?? [];
    $mca_amounts   = $_POST['mca_amount'] ?? [];

    // Basic validation
    if (!$department_id || !$location_id || !$client_id || !$currency_id || !$amount) {
        echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
        return;
    }

    // MCA total validation
    if (!empty($mca_amounts)) {
        $totalMca = array_sum(array_map('floatval', $mca_amounts));

        if ((float)$totalMca != (float)$amount) {
            echo json_encode([
                'success' => false,
                'message' => "MCA total ($totalMca) must match Amount ($amount)"
            ]);
            return;
        }
    }

    $db->beginTransaction();

    try {

        // Build MCA JSON array
        $mca_data = [];
        foreach ($mca_refs as $i => $ref) {
            if (!trim($ref) || !isset($mca_amounts[$i])) continue;

            $mca_data[] = [
                "mca_ref" => trim($ref),
                "amount"  => (float)$mca_amounts[$i]
            ];
        }

        // Insert/update data
        $paymentData = [
            "beneficiary"  => $beneficiary,
            "requestee"    => $requestee,
            "department"   => $department_id,
            "location_id"  => $location_id,
            "client_id"    => $client_id,
            "pay_for"      => $pay_for,
            "currency"     => $currency_id,
            "amount"       => $amount,
            "payment_type" => $payment_type,
            "expense_type" => $expense_type,
            "motif"        => $motif,

            // Save first MCA reference + whole MCA JSON
            "mca_ref"      => $mca_data[0]['mca_ref'] ?? null,
            "mca_data"     => json_encode($mca_data),
        ];

        if ($action === "update" && $payment_id) {

            $paymentData['updated_at'] = date('Y-m-d H:i:s');
            $paymentData['updated_by'] = $_SESSION['userid'] ?? 0;

            $db->updateData('payment_requests', $paymentData, ['id' => $payment_id]);

        } else {

            $paymentData['created_at'] = date('Y-m-d H:i:s');
            $paymentData['created_by'] = $_SESSION['userid'] ?? 0;

            $payment_id = $db->insertData('payment_requests', $paymentData);
        }

        $db->commit();

        echo json_encode([
            'success' => true,
            'message' => ($action === 'update')
                ? 'Payment Updated Successfully'
                : 'Payment Created Successfully'
        ]);

    } catch (Exception $e) {

        $db->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}




    /* -----------------------------------------------------------
       DELETE
    -------------------------------------------------------------*/
    public function delete($id)
    {
        $db = new Database();

        $db->deleteData("payment_requests", ["id" => $id]);

        echo json_encode([
            "success" => true,
            "message" => "Payment Request Deleted Successfully"
        ]);
    }

    public function getExpenseTypesByCategory()
{ 
    header('Content-Type: application/json');

    $category = $_POST['pay_for'] ?? null;
    
    // Column mapping
    $columns = [
        '0' => 'import',
        '1' => 'export',
        '2' => 'local',
        '3' => 'other',
        '4' => 'advance'
    ];

    if (!isset($columns[$category])) {
        echo json_encode([]);
        return;
    }

    $column = $columns[$category];

    $db = new Database();

    // Fetch expense types where column = 1
    $expenseTypes = $db->selectData(
        "expense_type_master_t",
        "*",
        [$column => 1]
    );

    echo json_encode($expenseTypes);
}



}
?>
