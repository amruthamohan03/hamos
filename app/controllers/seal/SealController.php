<?php
class SealController extends Controller
{
    /*
     * INDEX PAGE
     */
    public function index()
    {
        $db = new Database();

        // Office Location cards (exclude ID 3)
        $sql = "
            SELECT 
                id,
                main_location_name
            FROM main_office_master_t
            WHERE display = 'Y' AND id != 3
            ORDER BY main_location_name ASC
        ";
        $officeLocations = $db->customQuery($sql);

        // Generate CSRF Token
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $data = [
            'title' => 'Seal Master',
            'officeLocations' => $officeLocations,
            'csrf_token' => $_SESSION['csrf_token']
        ];

        $this->viewWithLayout('seal/seal', $data);
    }

    /*
     * CRUD - INSERT, UPDATE, DELETE, LISTING
     */
    public function crudData($action = 'insertion')
    {
        $db = new Database();
        $table = 'seal_nos_t';

        function s($v) {
            return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
        }

        /*
         * DATATABLE LISTING
         */
        if ($action === 'listing' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            header('Content-Type: application/json');

            try {
                $draw = intval($_GET['draw'] ?? 1);
                $start = intval($_GET['start'] ?? 0);
                $length = intval($_GET['length'] ?? 25);
                $searchValue = trim($_GET['search']['value'] ?? '');
                $orderColumnIndex = intval($_GET['order'][0]['column'] ?? 0);
                $orderDir = strtolower($_GET['order'][0]['dir'] ?? 'desc');
                
                // Validate order direction
                $orderDir = in_array($orderDir, ['asc', 'desc']) ? $orderDir : 'desc';

                $columns = ['sn.id', 'mo.main_location_name', 'sn.purchase_date', 'sn.total_amount', 'sn.total_seal', 'sn.display'];
                $orderColumn = $columns[$orderColumnIndex] ?? 'sn.id';

                // Base query
                $baseQuery = "
                    FROM seal_nos_t sn
                    LEFT JOIN main_office_master_t mo ON sn.office_location_id = mo.id
                ";

                $whereClause = " WHERE 1=1 ";
                $params = [];

                // Search filter - FIXED
                if (!empty($searchValue)) {
                    $whereClause .= " AND (
                        mo.main_location_name LIKE ? OR
                        sn.purchase_date LIKE ? OR
                        CAST(sn.total_amount AS CHAR) LIKE ? OR
                        CAST(sn.total_seal AS CHAR) LIKE ?
                    )";
                    $searchParam = "%{$searchValue}%";
                    $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
                }

                // Location filter
                if (!empty($_GET['location_filter']) && $_GET['location_filter'] != '0') {
                    $locationFilter = intval($_GET['location_filter']);
                    $whereClause .= " AND sn.office_location_id = ? ";
                    $params[] = $locationFilter;
                }

                // Status filter (for Used/Damaged seals)
                if (!empty($_GET['status_filter'])) {
                    $statusFilter = $db->escapeString($_GET['status_filter']);
                    $whereClause .= " AND sn.id IN (
                        SELECT DISTINCT seal_master_id 
                        FROM seal_individual_numbers_t 
                        WHERE status = ?
                    )";
                    $params[] = $statusFilter;
                }

                // Total records
                $totalRecordsQuery = "SELECT COUNT(*) as total " . $baseQuery . $whereClause;
                $totalRecordsResult = $db->customQuery($totalRecordsQuery, $params);
                $totalRecords = $totalRecordsResult[0]['total'] ?? 0;

                // Fetch data with seal number count
                $dataQuery = "
                    SELECT 
                        sn.id,
                        sn.office_location_id,
                        sn.purchase_date,
                        sn.total_amount,
                        sn.total_seal,
                        sn.display,
                        sn.created_at,
                        sn.updated_at,
                        mo.main_location_name,
                        (SELECT COUNT(*) FROM seal_individual_numbers_t WHERE seal_master_id = sn.id) as added_seals
                    " . $baseQuery . $whereClause . "
                    ORDER BY $orderColumn $orderDir
                    LIMIT ?, ?
                ";
                
                $params[] = $start;
                $params[] = $length;

                $records = $db->customQuery($dataQuery, $params);

                $data = [];
                foreach ($records as $row) {
                    $data[] = [
                        'id' => $row['id'],
                        'office_location_id' => $row['office_location_id'],
                        'location_name' => $row['main_location_name'] ?? 'N/A',
                        'purchase_date' => $row['purchase_date'] ?? '',
                        'total_amount' => $row['total_amount'] ?? 0,
                        'total_seal' => $row['total_seal'] ?? 0,
                        'added_seals' => $row['added_seals'] ?? 0,
                        'display' => $row['display'] ?? 'Y',
                        'created_at' => $row['created_at'],
                        'updated_at' => $row['updated_at']
                    ];
                }

                echo json_encode([
                    'draw' => $draw,
                    'recordsTotal' => $totalRecords,
                    'recordsFiltered' => $totalRecords,
                    'data' => $data
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'draw' => intval($_GET['draw'] ?? 1),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $e->getMessage()
                ]);
            }
            exit;
        }

        /*
         * GET STATISTICS
         */
        if ($action === 'statistics' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            header('Content-Type: application/json');

            try {
                // Total seals (sum of all total_seal values)
                $totalQuery = "SELECT COALESCE(SUM(total_seal), 0) as total FROM seal_nos_t WHERE display = 'Y'";
                $totalSeals = $db->customQuery($totalQuery)[0]['total'] ?? 0;

                // Total added seals
                $addedQuery = "
                    SELECT COUNT(*) as total 
                    FROM seal_individual_numbers_t sin
                    INNER JOIN seal_nos_t sn ON sin.seal_master_id = sn.id
                    WHERE sn.display = 'Y'
                ";
                $addedSeals = $db->customQuery($addedQuery)[0]['total'] ?? 0;

                // Used seals count
                $usedQuery = "
                    SELECT COUNT(*) as total 
                    FROM seal_individual_numbers_t sin
                    INNER JOIN seal_nos_t sn ON sin.seal_master_id = sn.id
                    WHERE sn.display = 'Y' AND sin.status = 'Used'
                ";
                $usedSeals = $db->customQuery($usedQuery)[0]['total'] ?? 0;

                // Damaged seals count
                $damagedQuery = "
                    SELECT COUNT(*) as total 
                    FROM seal_individual_numbers_t sin
                    INNER JOIN seal_nos_t sn ON sin.seal_master_id = sn.id
                    WHERE sn.display = 'Y' AND sin.status = 'Damaged'
                ";
                $damagedSeals = $db->customQuery($damagedQuery)[0]['total'] ?? 0;

                // Location-wise breakdown (exclude ID 3)
                $locationQuery = "
                    SELECT 
                        mo.id,
                        mo.main_location_name,
                        COALESCE(SUM(sn.total_seal), 0) as seal_count,
                        (SELECT COUNT(*) 
                         FROM seal_individual_numbers_t sin2 
                         INNER JOIN seal_nos_t sn2 ON sin2.seal_master_id = sn2.id 
                         WHERE sn2.office_location_id = mo.id AND sn2.display = 'Y'
                        ) as added_count
                    FROM main_office_master_t mo
                    LEFT JOIN seal_nos_t sn ON mo.id = sn.office_location_id AND sn.display = 'Y'
                    WHERE mo.display = 'Y' AND mo.id != 3
                    GROUP BY mo.id, mo.main_location_name
                    ORDER BY mo.main_location_name ASC
                ";
                $locationCounts = $db->customQuery($locationQuery);

                echo json_encode([
                    'success' => true,
                    'data' => [
                        'total_seals' => $totalSeals,
                        'added_seals' => $addedSeals,
                        'used_seals' => $usedSeals,
                        'damaged_seals' => $damagedSeals,
                        'location_counts' => $locationCounts
                    ]
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to load statistics: ' . $e->getMessage()
                ]);
            }
            exit;
        }

        /*
         * GET SINGLE SEAL
         */
        if ($action === 'getSeal' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            header('Content-Type: application/json');

            $id = intval($_GET['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID']);
                exit;
            }

            $query = "
                SELECT 
                    sn.*,
                    mo.main_location_name
                FROM seal_nos_t sn
                LEFT JOIN main_office_master_t mo ON sn.office_location_id = mo.id
                WHERE sn.id = ?
            ";
            $result = $db->customQuery($query, [$id]);

            if (!empty($result)) {
                echo json_encode(['success' => true, 'data' => $result[0]]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Seal not found']);
            }
            exit;
        }

        /*
         * GET SEAL NUMBERS FOR A MASTER
         */
        if ($action === 'getSealNumbers' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            header('Content-Type: application/json');

            $seal_master_id = intval($_GET['seal_master_id'] ?? 0);
            if ($seal_master_id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid Seal Master ID']);
                exit;
            }

            $query = "
                SELECT 
                    sin.id,
                    sin.seal_number,
                    sin.status,
                    sin.notes,
                    sin.display,
                    sin.created_at,
                    mo.main_location_name as location
                FROM seal_individual_numbers_t sin
                LEFT JOIN seal_nos_t sn ON sin.seal_master_id = sn.id
                LEFT JOIN main_office_master_t mo ON sn.office_location_id = mo.id
                WHERE sin.seal_master_id = ?
                ORDER BY sin.id DESC
            ";
            $sealNumbers = $db->customQuery($query, [$seal_master_id]);

            echo json_encode([
                'success' => true,
                'data' => $sealNumbers
            ]);
            exit;
        }

        /*
         * GET SINGLE SEAL NUMBER
         */
        if ($action === 'getSingleSealNumber' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            header('Content-Type: application/json');

            $id = intval($_GET['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID']);
                exit;
            }

            $query = "
                SELECT 
                    sin.*,
                    mo.main_location_name as location
                FROM seal_individual_numbers_t sin
                LEFT JOIN seal_nos_t sn ON sin.seal_master_id = sn.id
                LEFT JOIN main_office_master_t mo ON sn.office_location_id = mo.id
                WHERE sin.id = ?
            ";
            $result = $db->customQuery($query, [$id]);

            if (!empty($result)) {
                echo json_encode(['success' => true, 'data' => $result[0]]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Seal number not found']);
            }
            exit;
        }

        /*
         * ADD SEAL NUMBERS
         */
        if ($action === 'addSealNumbers' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            // CSRF validation
            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo json_encode(['success' => false, 'message' => 'Invalid security token']);
                exit;
            }

            $seal_master_id = (int)($_POST['seal_master_id'] ?? 0);
            $seal_numbers = $_POST['seal_numbers'] ?? '';

            if ($seal_master_id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid Seal Master ID']);
                exit;
            }

            if (empty($seal_numbers)) {
                echo json_encode(['success' => false, 'message' => 'Please enter seal numbers']);
                exit;
            }

            // Split seal numbers by new line or comma
            $sealNumbersArray = preg_split('/[\r\n,]+/', $seal_numbers);
            $sealNumbersArray = array_filter(array_map('trim', $sealNumbersArray));

            if (empty($sealNumbersArray)) {
                echo json_encode(['success' => false, 'message' => 'No valid seal numbers found']);
                exit;
            }

            // Check total seal limit
            $masterInfo = $db->selectData('seal_nos_t', 'total_seal', ['id' => $seal_master_id]);
            if (empty($masterInfo)) {
                echo json_encode(['success' => false, 'message' => 'Seal Master not found']);
                exit;
            }

            $totalAllowed = (int)$masterInfo[0]['total_seal'];
            
            $currentCount = $db->customQuery(
                "SELECT COUNT(*) as cnt FROM seal_individual_numbers_t WHERE seal_master_id = ?",
                [$seal_master_id]
            )[0]['cnt'];

            $newCount = $currentCount + count($sealNumbersArray);
            if ($newCount > $totalAllowed) {
                echo json_encode([
                    'success' => false,
                    'message' => "Cannot add " . count($sealNumbersArray) . " seal number(s). Limit: $totalAllowed, Current: $currentCount, Available: " . ($totalAllowed - $currentCount)
                ]);
                exit;
            }

            // Check for duplicates
            $duplicates = [];
            foreach ($sealNumbersArray as $sealNum) {
                $check = $db->selectData('seal_individual_numbers_t', 'id', ['seal_number' => $sealNum]);
                if (!empty($check)) {
                    $duplicates[] = $sealNum;
                }
            }

            if (!empty($duplicates)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Duplicate seal numbers found: ' . implode(', ', $duplicates)
                ]);
                exit;
            }

            // Insert seal numbers
            $inserted = 0;
            foreach ($sealNumbersArray as $sealNum) {
                $data = [
                    'seal_master_id' => $seal_master_id,
                    'seal_number' => $sealNum,
                    'status' => 'Available',
                    'display' => 'Y',
                    'created_by' => $_SESSION['user_id'] ?? 1,
                    'updated_by' => $_SESSION['user_id'] ?? 1,
                ];

                if ($db->insertData('seal_individual_numbers_t', $data)) {
                    $inserted++;
                }
            }

            echo json_encode([
                'success' => true,
                'message' => "$inserted seal number(s) added successfully!"
            ]);
            exit;
        }

        /*
         * UPDATE SEAL NUMBER - WITH STATUS VALIDATION
         */
        if ($action === 'updateSealNumber' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            // CSRF validation
            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo json_encode(['success' => false, 'message' => 'Invalid security token']);
                exit;
            }

            $id = (int)($_POST['seal_number_id'] ?? 0);
            $seal_number = s($_POST['seal_number'] ?? '');
            $status = s($_POST['status'] ?? 'Available');
            $notes = s($_POST['notes'] ?? '');

            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID']);
                exit;
            }

            if (empty($seal_number)) {
                echo json_encode(['success' => false, 'message' => 'Seal number is required']);
                exit;
            }

            // Get current status
            $currentData = $db->selectData('seal_individual_numbers_t', '*', ['id' => $id]);
            if (empty($currentData)) {
                echo json_encode(['success' => false, 'message' => 'Seal number not found']);
                exit;
            }

            $currentStatus = $currentData[0]['status'];

            // VALIDATION: Cannot change from "Used" to "Damaged"
            if ($currentStatus === 'Used' && $status === 'Damaged') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Cannot change status from "Used" to "Damaged". Once a seal is marked as Used, it cannot be changed to Damaged.'
                ]);
                exit;
            }

            // Check for duplicate seal number (excluding current record)
            $checkDuplicate = $db->customQuery(
                "SELECT id FROM seal_individual_numbers_t WHERE seal_number = ? AND id != ?",
                [$seal_number, $id]
            );

            if (!empty($checkDuplicate)) {
                echo json_encode(['success' => false, 'message' => 'This seal number already exists']);
                exit;
            }

            $data = [
                'seal_number' => $seal_number,
                'status' => $status,
                'notes' => $notes,
                'updated_by' => $_SESSION['user_id'] ?? 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $update = $db->updateData('seal_individual_numbers_t', $data, ['id' => $id]);

            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'Seal number updated successfully!' : 'Update failed.'
            ]);
            exit;
        }

        /*
         * DELETE SEAL NUMBER
         */
        if ($action === 'deleteSealNumber' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            // CSRF validation
            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo json_encode(['success' => false, 'message' => 'Invalid security token']);
                exit;
            }

            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID']);
                exit;
            }

            $delete = $db->deleteData('seal_individual_numbers_t', ['id' => $id]);

            echo json_encode([
                'success' => $delete ? true : false,
                'message' => $delete ? 'Seal number deleted successfully!' : 'Delete failed.'
            ]);
            exit;
        }

        /*
         * INSERT
         */
        if ($action === 'insert' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            // CSRF validation
            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo json_encode(['success' => false, 'message' => 'Invalid security token']);
                exit;
            }

            $office_location_id = (int)($_POST['office_location_id'] ?? 0);
            $purchase_date = s($_POST['purchase_date'] ?? '');
            $total_amount = floatval($_POST['total_amount'] ?? 0);
            $total_seal = intval($_POST['total_seal'] ?? 0);
            $display = ($_POST['display'] ?? 'Y') === 'N' ? 'N' : 'Y';

            // Validation
            if ($office_location_id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Please select Office Location']);
                exit;
            }

            if ($purchase_date === '') {
                echo json_encode(['success' => false, 'message' => 'Please select Purchase Date']);
                exit;
            }

            if ($total_amount <= 0) {
                echo json_encode(['success' => false, 'message' => 'Total Amount must be greater than 0']);
                exit;
            }

            $data = [
                'office_location_id' => $office_location_id,
                'purchase_date'      => $purchase_date,
                'total_amount'       => $total_amount,
                'total_seal'         => $total_seal,
                'display'            => $display,
                'created_by'         => $_SESSION['user_id'] ?? 1,
                'updated_by'         => $_SESSION['user_id'] ?? 1,
            ];

            $insertId = $db->insertData($table, $data);

            echo json_encode($insertId
                ? ['success' => true, 'message' => 'Seal added successfully!', 'id' => $insertId]
                : ['success' => false, 'message' => 'Insert failed.']
            );
            exit;
        }

        /*
         * UPDATE
         */
        if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            // CSRF validation
            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo json_encode(['success' => false, 'message' => 'Invalid security token']);
                exit;
            }

            $id = (int)($_POST['seal_id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID']);
                exit;
            }

            $office_location_id = (int)($_POST['office_location_id'] ?? 0);
            $purchase_date = s($_POST['purchase_date'] ?? '');
            $total_amount = floatval($_POST['total_amount'] ?? 0);
            $total_seal = intval($_POST['total_seal'] ?? 0);
            $display = ($_POST['display'] ?? 'Y') === 'N' ? 'N' : 'Y';

            // Validation
            if ($office_location_id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Please select Office Location']);
                exit;
            }

            if ($purchase_date === '') {
                echo json_encode(['success' => false, 'message' => 'Please select Purchase Date']);
                exit;
            }

            if ($total_amount <= 0) {
                echo json_encode(['success' => false, 'message' => 'Total Amount must be greater than 0']);
                exit;
            }

            // Check if seal exists
            $oldRow = $db->selectData('seal_nos_t', 'office_location_id', ['id' => $id]);
            if (empty($oldRow)) {
                echo json_encode(['success' => false, 'message' => 'Seal not found']);
                exit;
            }

            $data = [
                'office_location_id' => $office_location_id,
                'purchase_date'      => $purchase_date,
                'total_amount'       => $total_amount,
                'total_seal'         => $total_seal,
                'display'            => $display,
                'updated_by'         => $_SESSION['user_id'] ?? 1,
                'updated_at'         => date('Y-m-d H:i:s'),
            ];

            $update = $db->updateData($table, $data, ['id' => $id]);

            echo json_encode([
                'success' => $update ? true : false,
                'message' => $update ? 'Seal updated successfully!' : 'Update failed.'
            ]);
            exit;
        }

        /*
         * DELETE
         */
        if ($action === 'deletion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            // CSRF validation
            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo json_encode(['success' => false, 'message' => 'Invalid security token']);
                exit;
            }

            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid delete ID']);
                exit;
            }

            $delete = $db->deleteData($table, ['id' => $id]);

            echo json_encode([
                'success' => $delete ? true : false,
                'message' => $delete ? 'Seal deleted successfully!' : 'Delete failed.'
            ]);
            exit;
        }

        /*
         * EXPORT SINGLE SEAL TO EXCEL
         */
        if ($action === 'exportSeal' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = intval($_GET['id'] ?? 0);
            if ($id <= 0) {
                die('Invalid ID');
            }

            $query = "
                SELECT 
                    sn.*,
                    mo.main_location_name
                FROM seal_nos_t sn
                LEFT JOIN main_office_master_t mo ON sn.office_location_id = mo.id
                WHERE sn.id = ?
            ";
            $sealData = $db->customQuery($query, [$id]);

            if (empty($sealData)) {
                die('Seal not found');
            }

            $seal = $sealData[0];

            // Get seal numbers
            $sealNumbersQuery = "
                SELECT 
                    sin.seal_number, 
                    sin.status, 
                    sin.notes,
                    sin.created_at
                FROM seal_individual_numbers_t sin
                WHERE sin.seal_master_id = ? 
                ORDER BY sin.id
            ";
            $sealNumbers = $db->customQuery($sealNumbersQuery, [$id]);

            // Correct path to vendor autoload
            $vendorPath = dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php';
            
            if (!file_exists($vendorPath)) {
                die('PhpSpreadsheet library not found. Please run: composer require phpoffice/phpspreadsheet');
            }
            
            require_once $vendorPath;
            
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            
            // ========== SHEET 1: SEAL DETAILS ==========
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Seal Details');

            // Header styling
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ];

            // Title
            $sheet->mergeCells('A1:D1');
            $sheet->setCellValue('A1', 'SEAL DETAILS');
            $sheet->getStyle('A1')->applyFromArray($headerStyle);
            $sheet->getRowDimension(1)->setRowHeight(30);

            // Data
            $row = 3;
            
            $fields = [
                'ID' => $seal['id'],
                'Purchase Location' => $seal['main_location_name'] ?? 'N/A',
                'Purchase Date' => date('d-m-Y', strtotime($seal['purchase_date'])),
                'Total Amount' => '$' . number_format($seal['total_amount'], 2),
                'Total Seal' => $seal['total_seal'],
                'Added Seals' => count($sealNumbers),
                'Per Seal Amount' => '$10.00',
                'Display' => $seal['display'] == 'Y' ? 'Yes' : 'No',
                'Created At' => date('d-m-Y H:i', strtotime($seal['created_at'])),
                'Updated At' => date('d-m-Y H:i', strtotime($seal['updated_at']))
            ];

            foreach ($fields as $label => $value) {
                $sheet->setCellValue('A' . $row, $label);
                $sheet->setCellValue('B' . $row, $value);
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                $row++;
            }

            // Auto-size columns for Sheet 1
            foreach (range('A', 'D') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // ========== SHEET 2: SEAL NUMBERS ==========
            if (!empty($sealNumbers)) {
                $sheet2 = $spreadsheet->createSheet();
                $sheet2->setTitle('Seal Numbers');
                
                // Header styling for Sheet 2
                $sealHeaderStyle = [
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '28a745']],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                ];
                
                // Title
                $sheet2->mergeCells('A1:E1');
                $sheet2->setCellValue('A1', 'SEAL NUMBERS - ' . ($seal['main_location_name'] ?? 'N/A'));
                $sheet2->getStyle('A1')->applyFromArray($sealHeaderStyle);
                $sheet2->getRowDimension(1)->setRowHeight(25);
                
                // Headers
                $row2 = 3;
                $headers2 = ['#', 'Seal Number', 'Status', 'Notes', 'Created Date'];
                $col2 = 'A';
                foreach ($headers2 as $header) {
                    $sheet2->setCellValue($col2 . $row2, $header);
                    $sheet2->getStyle($col2 . $row2)->applyFromArray($sealHeaderStyle);
                    $col2++;
                }
                
                // Data
                $row2++;
                $counter = 1;
                foreach ($sealNumbers as $sn) {
                    $sheet2->setCellValue('A' . $row2, $counter);
                    $sheet2->setCellValue('B' . $row2, $sn['seal_number']);
                    $sheet2->setCellValue('C' . $row2, $sn['status']);
                    $sheet2->setCellValue('D' . $row2, $sn['notes'] ?? '-');
                    $sheet2->setCellValue('E' . $row2, date('d-m-Y H:i', strtotime($sn['created_at'])));
                    $row2++;
                    $counter++;
                }
                
                // Auto-size columns for Sheet 2
                foreach (range('A', 'E') as $col) {
                    $sheet2->getColumnDimension($col)->setAutoSize(true);
                }
                
                // Add summary at the bottom
                $row2 += 2;
                $sheet2->setCellValue('A' . $row2, 'Total Seal Numbers:');
                $sheet2->setCellValue('B' . $row2, count($sealNumbers));
                $sheet2->getStyle('A' . $row2 . ':B' . $row2)->getFont()->setBold(true);
            }

            // Set active sheet back to first sheet
            $spreadsheet->setActiveSheetIndex(0);

            // Download
            $filename = 'Seal_' . $seal['id'] . '_' . date('Y-m-d') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        }

        /*
         * EXPORT ALL SEALS TO EXCEL - WITH ENHANCED SUMMARY AND CHARTS
         */
        if ($action === 'exportAll' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $query = "
                SELECT 
                    sn.*,
                    mo.main_location_name,
                    (SELECT COUNT(*) FROM seal_individual_numbers_t WHERE seal_master_id = sn.id) as added_seals
                FROM seal_nos_t sn
                LEFT JOIN main_office_master_t mo ON sn.office_location_id = mo.id
                ORDER BY sn.id DESC
            ";
            $seals = $db->customQuery($query);
            
            // Get ALL seal numbers
            $allSealsQuery = "
                SELECT 
                    sin.seal_number,
                    sin.status,
                    sin.notes,
                    sin.created_at,
                    sn.id as seal_master_id,
                    sn.purchase_date,
                    mo.main_location_name as location
                FROM seal_individual_numbers_t sin
                INNER JOIN seal_nos_t sn ON sin.seal_master_id = sn.id
                LEFT JOIN main_office_master_t mo ON sn.office_location_id = mo.id
                ORDER BY sn.id, sin.id
            ";
            $allSealNumbers = $db->customQuery($allSealsQuery);

            // Correct path to vendor autoload
            $vendorPath = dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php';
            
            if (!file_exists($vendorPath)) {
                die('PhpSpreadsheet library not found. Please run: composer require phpoffice/phpspreadsheet');
            }
            
            require_once $vendorPath;
            
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            
            // ========== SHEET 1: ALL SEALS SUMMARY ==========
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('All Seals');

            // Header styling
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '28a745']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ];

            // Headers
            $headers = ['ID', 'Purchase Location', 'Purchase Date', 'Total Amount', 'Total Seal', 'Added Seals', 'Display', 'Created At', 'Updated At'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $sheet->getStyle($col . '1')->applyFromArray($headerStyle);
                $col++;
            }

            // Data
            $row = 2;
            foreach ($seals as $seal) {
                $sheet->setCellValue('A' . $row, $seal['id']);
                $sheet->setCellValue('B' . $row, $seal['main_location_name'] ?? 'N/A');
                $sheet->setCellValue('C' . $row, date('d-m-Y', strtotime($seal['purchase_date'])));
                $sheet->setCellValue('D' . $row, '$' . number_format($seal['total_amount'], 2));
                $sheet->setCellValue('E' . $row, $seal['total_seal']);
                $sheet->setCellValue('F' . $row, $seal['added_seals']);
                $sheet->setCellValue('G' . $row, $seal['display'] == 'Y' ? 'Yes' : 'No');
                $sheet->setCellValue('H' . $row, date('d-m-Y H:i', strtotime($seal['created_at'])));
                $sheet->setCellValue('I' . $row, date('d-m-Y H:i', strtotime($seal['updated_at'])));
                $row++;
            }

            // Auto-size columns for Sheet 1
            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // ========== SHEET 2: ALL SEAL NUMBERS ==========
            if (!empty($allSealNumbers)) {
                $sheet2 = $spreadsheet->createSheet();
                $sheet2->setTitle('All Seal Numbers');
                
                // Header styling for Sheet 2
                $sealHeaderStyle = [
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                ];
                
                // Title
                $sheet2->mergeCells('A1:G1');
                $sheet2->setCellValue('A1', 'ALL SEAL NUMBERS');
                $sheet2->getStyle('A1')->applyFromArray($sealHeaderStyle);
                $sheet2->getRowDimension(1)->setRowHeight(25);
                
                // Headers
                $row2 = 3;
                $headers2 = ['#', 'Seal Number', 'Status', 'Purchase Location', 'Purchase Date', 'Notes', 'Created Date'];
                $col2 = 'A';
                foreach ($headers2 as $header) {
                    $sheet2->setCellValue($col2 . $row2, $header);
                    $sheet2->getStyle($col2 . $row2)->applyFromArray($sealHeaderStyle);
                    $col2++;
                }
                
                // Data
                $row2++;
                $counter = 1;
                foreach ($allSealNumbers as $sn) {
                    $sheet2->setCellValue('A' . $row2, $counter);
                    $sheet2->setCellValue('B' . $row2, $sn['seal_number']);
                    $sheet2->setCellValue('C' . $row2, $sn['status']);
                    $sheet2->setCellValue('D' . $row2, $sn['location'] ?? 'N/A');
                    $sheet2->setCellValue('E' . $row2, date('d-m-Y', strtotime($sn['purchase_date'])));
                    $sheet2->setCellValue('F' . $row2, $sn['notes'] ?? '-');
                    $sheet2->setCellValue('G' . $row2, date('d-m-Y H:i', strtotime($sn['created_at'])));
                    $row2++;
                    $counter++;
                }
                
                // Auto-size columns for Sheet 2
                foreach (range('A', 'G') as $col) {
                    $sheet2->getColumnDimension($col)->setAutoSize(true);
                }
            }

            // ========== SHEET 3: SUMMARY WITH CHART DATA ==========
            $sheet3 = $spreadsheet->createSheet();
            $sheet3->setTitle('Summary & Analytics');
            
            // Summary Header
            $summaryHeaderStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 14],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ff6b6b']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ];
            
            $sheet3->mergeCells('A1:E1');
            $sheet3->setCellValue('A1', 'SEAL MANAGEMENT SUMMARY & ANALYTICS');
            $sheet3->getStyle('A1')->applyFromArray($summaryHeaderStyle);
            $sheet3->getRowDimension(1)->setRowHeight(35);
            
            // Overall Statistics
            $row3 = 3;
            $sheet3->setCellValue('A' . $row3, 'OVERALL STATISTICS');
            $sheet3->getStyle('A' . $row3)->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('667eea');
            $row3++;
            
            $totalSeals = array_sum(array_column($seals, 'total_seal'));
            $totalAdded = count($allSealNumbers);
            
            // Calculate status counts
            $statusCounts = [];
            foreach ($allSealNumbers as $sn) {
                $status = $sn['status'] ?? 'Unknown';
                if (!isset($statusCounts[$status])) {
                    $statusCounts[$status] = 0;
                }
                $statusCounts[$status]++;
            }
            
            $usedCount = $statusCounts['Used'] ?? 0;
            $damagedCount = $statusCounts['Damaged'] ?? 0;
            $availableCount = $statusCounts['Available'] ?? 0;
            
            $stats = [
                'Total Seal Purchases' => count($seals),
                'Total Seals Capacity' => $totalSeals,
                'Total Seals Added' => $totalAdded,
                'Available Seals' => $availableCount,
                'Used Seals' => $usedCount,
                'Damaged Seals' => $damagedCount,
                'Utilization Rate' => $totalSeals > 0 ? round(($totalAdded / $totalSeals) * 100, 2) . '%' : '0%'
            ];
            
            foreach ($stats as $label => $value) {
                $sheet3->setCellValue('A' . $row3, $label);
                $sheet3->setCellValue('B' . $row3, $value);
                $sheet3->getStyle('A' . $row3)->getFont()->setBold(true);
                $row3++;
            }
            
            // Location-wise breakdown
            $row3 += 2;
            $sheet3->setCellValue('A' . $row3, 'LOCATION-WISE BREAKDOWN');
            $sheet3->getStyle('A' . $row3)->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('667eea');
            $row3++;
            
            $sheet3->setCellValue('A' . $row3, 'Location');
            $sheet3->setCellValue('B' . $row3, 'Total Seals');
            $sheet3->setCellValue('C' . $row3, 'Added Seals');
            $sheet3->setCellValue('D' . $row3, 'Available');
            $sheet3->setCellValue('E' . $row3, 'Usage %');
            $sheet3->getStyle('A' . $row3 . ':E' . $row3)->getFont()->setBold(true);
            $row3++;
            
            // Group by location
            $locationData = [];
            foreach ($seals as $seal) {
                $loc = $seal['main_location_name'] ?? 'N/A';
                if (!isset($locationData[$loc])) {
                    $locationData[$loc] = ['total' => 0, 'added' => 0];
                }
                $locationData[$loc]['total'] += $seal['total_seal'];
                $locationData[$loc]['added'] += $seal['added_seals'];
            }
            
            foreach ($locationData as $location => $data) {
                $available = $data['total'] - $data['added'];
                $usagePercent = $data['total'] > 0 ? round(($data['added'] / $data['total']) * 100, 2) : 0;
                
                $sheet3->setCellValue('A' . $row3, $location);
                $sheet3->setCellValue('B' . $row3, $data['total']);
                $sheet3->setCellValue('C' . $row3, $data['added']);
                $sheet3->setCellValue('D' . $row3, $available);
                $sheet3->setCellValue('E' . $row3, $usagePercent . '%');
                $row3++;
            }
            
            // Status-wise breakdown with chart data
            $row3 += 2;
            $sheet3->setCellValue('A' . $row3, 'STATUS-WISE BREAKDOWN (FOR PIE CHART)');
            $sheet3->getStyle('A' . $row3)->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('667eea');
            $row3++;
            
            $sheet3->setCellValue('A' . $row3, 'Status');
            $sheet3->setCellValue('B' . $row3, 'Count');
            $sheet3->setCellValue('C' . $row3, 'Percentage');
            $sheet3->getStyle('A' . $row3 . ':C' . $row3)->getFont()->setBold(true);
            $row3++;
            
            $statusChartStart = $row3;
            foreach ($statusCounts as $status => $count) {
                $percentage = $totalAdded > 0 ? round(($count / $totalAdded) * 100, 2) : 0;
                $sheet3->setCellValue('A' . $row3, $status);
                $sheet3->setCellValue('B' . $row3, $count);
                $sheet3->setCellValue('C' . $row3, $percentage . '%');
                $row3++;
            }
            
            // Auto-size columns for Sheet 3
            foreach (range('A', 'E') as $col) {
                $sheet3->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Add note about charts
            $row3 += 2;
            $sheet3->mergeCells('A' . $row3 . ':E' . $row3);
            $sheet3->setCellValue('A' . $row3, 'NOTE: You can create charts in Excel using the data above:');
            $sheet3->getStyle('A' . $row3)->getFont()->setBold(true)->getColor()->setRGB('dc3545');
            $row3++;
            
            $sheet3->mergeCells('A' . $row3 . ':E' . $row3);
            $sheet3->setCellValue('A' . $row3, '• Select "Status-wise Breakdown" data → Insert → Pie Chart');
            $row3++;
            
            $sheet3->mergeCells('A' . $row3 . ':E' . $row3);
            $sheet3->setCellValue('A' . $row3, '• Select "Location-wise Breakdown" data → Insert → Bar Chart / Column Chart');

            // Set active sheet back to first sheet
            $spreadsheet->setActiveSheetIndex(0);

            // Download
            $filename = 'All_Seals_Complete_' . date('Y-m-d_His') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        }

        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }
}
?>