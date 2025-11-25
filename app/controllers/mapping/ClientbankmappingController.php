<?php
class ClientbankmappingController extends Controller
{
    public function index()
    {
        $db = new Database();

        // Get all active clients
        $clients = $db->selectData('clients_t', 'id, company_name, short_name', ['display' => 'Y']);
        
        // Get all active banks
        $banks = $db->selectData('invoice_bank_master_t', 'id, invoice_bank_name', ['display' => 'Y']);

        $data = [
            'title' => 'Client Bank Mapping',
            'clients' => $clients,
            'banks' => $banks
        ];

        $this->viewWithLayout('mapping/client_bank_mapping', $data);
    }

    public function getMapping()
    {
        header('Content-Type: application/json');
        $db = new Database();
        $client_id = (int)($_GET['client_id'] ?? 0);

        if ($client_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid client']);
            exit;
        }

        // Get all banks with mapping status for selected client
        $sql = "
            SELECT 
                b.id AS bank_id,
                b.invoice_bank_name,
                CASE WHEN cbm.id IS NOT NULL THEN 1 ELSE 0 END AS is_mapped
            FROM invoice_bank_master_t b
            LEFT JOIN client_bank_mapping_t cbm 
                ON b.id = cbm.bank_id AND cbm.client_id = {$client_id}
            WHERE b.display = 'Y'
            ORDER BY b.invoice_bank_name ASC
        ";

        $result = $db->customQuery($sql);
        echo json_encode(['success' => true, 'data' => $result]);
        exit;
    }

    public function saveMapping()
    {
        header('Content-Type: application/json');
        $db = new Database();

        $client_id = (int)($_POST['client_id'] ?? 0);
        $selected_banks = $_POST['banks'] ?? [];

        if ($client_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid client']);
            exit;
        }

        try {
            // Start transaction if your Database class supports it
            // $db->beginTransaction();

            // Delete all existing mappings for this client
            $db->deleteData('client_bank_mapping_t', ['client_id' => $client_id]);

            // Insert new mappings for selected banks
            if (!empty($selected_banks)) {
                foreach ($selected_banks as $bank_id) {
                    $data = [
                        'client_id' => $client_id,
                        'bank_id' => (int)$bank_id,
                        'created_by' => $_SESSION['user_id'] ?? 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $db->insertData('client_bank_mapping_t', $data);
                }
            }

            // Commit transaction if supported
            // $db->commit();

            $count = count($selected_banks);
            $message = "✅ Client-Bank mapping saved successfully. {$count} bank(s) mapped.";
            
            echo json_encode(['success' => true, 'message' => $message]);
        } catch (Exception $e) {
            // Rollback if supported
            // $db->rollback();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    // Helper function to get all banks for a specific client
    public function getClientBanks($client_id)
    {
        $db = new Database();
        
        $sql = "
            SELECT 
                cbm.id,
                cbm.bank_id,
                b.invoice_bank_name,
                b.bank_code
            FROM client_bank_mapping_t cbm
            INNER JOIN invoice_bank_master_t b ON cbm.bank_id = b.id
            WHERE cbm.client_id = ? AND b.display = 'Y'
            ORDER BY b.invoice_bank_name ASC
        ";
        
        return $db->customQuery($sql, [$client_id]);
    }

    // API endpoint to get client's banks (for use in other modules)
    public function getClientBanksJson()
    {
        header('Content-Type: application/json');
        $client_id = (int)($_GET['client_id'] ?? 0);
        
        if ($client_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid client', 'data' => []]);
            exit;
        }
        
        $banks = $this->getClientBanks($client_id);
        echo json_encode(['success' => true, 'data' => $banks]);
        exit;
    }
}
?>