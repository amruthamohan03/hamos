<?php

class LicenseHsCodeController extends Controller
{
  private $db;
  private $userId;

  public function __construct()
  {
    $this->db = new Database();
    $this->userId = $_SESSION['user_id'] ?? 1;
  }

  public function index()
  {
    // Single optimized query with all necessary data
    $sql = "SELECT l.id, l.license_number, c.short_name as client_name, b.bank_name,
            COUNT(lh.id) as assigned_count
            FROM licenses_t l 
            LEFT JOIN clients_t c ON l.client_id = c.id 
            LEFT JOIN banklist_master_t b ON l.bank_id = b.id
            LEFT JOIN license_hscode_t lh ON l.id = lh.license_id AND lh.display = 'Y'
            WHERE l.display = 'Y' 
            GROUP BY l.id, l.license_number, c.short_name, b.bank_name
            ORDER BY l.license_number ASC";
    $licenses = $this->db->customQuery($sql);

    // Optimized HS codes query with indexed fields
    $sql = "SELECT id, hscode_number, hscode_ddi, hscode_ica, hscode_dci, hscode_dcl, hscode_tpi
            FROM hscode_master_t 
            WHERE display = 'Y' 
            ORDER BY hscode_number ASC";
    $hscodes = $this->db->customQuery($sql);

    $data = [
      'title' => 'License HS Code Assignment',
      'licenses' => $licenses,
      'hscodes' => $hscodes
    ];

    $this->viewWithLayout('licenses/licensehscode', $data);
  }

  public function getHsCodesForLicense()
  {
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');
    
    $licenseId = filter_input(INPUT_GET, 'license_id', FILTER_VALIDATE_INT);

    if (!$licenseId || $licenseId <= 0) {
      echo json_encode(['success' => false, 'message' => 'Invalid license ID']);
      return;
    }

    // Single optimized query with all data
    $sql = "SELECT 
              lh.id,
              lh.license_id,
              lh.hscode_id,
              lh.ddi as license_ddi,
              h.hscode_number,
              h.hscode_ddi as master_ddi,
              h.hscode_ica,
              h.hscode_dci,
              h.hscode_dcl,
              h.hscode_tpi
            FROM license_hscode_t lh
            INNER JOIN hscode_master_t h ON lh.hscode_id = h.id AND h.display = 'Y'
            WHERE lh.license_id = :license_id AND lh.display = 'Y'
            ORDER BY h.hscode_number ASC";

    try {
      $result = $this->db->customQuery($sql, [':license_id' => $licenseId]);
      
      echo json_encode([
        'success' => true,
        'data' => $result ?? [],
        'count' => count($result ?? [])
      ]);
    } catch (Exception $e) {
      echo json_encode([
        'success' => false, 
        'message' => 'Database error occurred'
      ]);
    }
  }

  public function crudData($action = 'insertion')
  {
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');

    try {
      switch ($action) {
        case 'insertion':
          $this->addHsCodeToLicense();
          break;
        case 'deletion':
          $this->removeHsCodeFromLicense();
          break;
        default:
          echo json_encode(['success' => false, 'message' => 'Invalid action']);
      }
    } catch (Exception $e) {
      error_log('License HS Code Error: ' . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Server error occurred']);
    }
    exit;
  }

  private function addHsCodeToLicense()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(['success' => false, 'message' => 'Invalid request method']);
      return;
    }

    $licenseId = filter_input(INPUT_POST, 'license_id', FILTER_VALIDATE_INT);
    $hscodeData = $_POST['hscode_data'] ?? [];

    if (!$licenseId || $licenseId <= 0) {
      echo json_encode(['success' => false, 'message' => 'Please select a license']);
      return;
    }

    if (empty($hscodeData) || !is_array($hscodeData)) {
      echo json_encode(['success' => false, 'message' => 'Please select at least one HS Code']);
      return;
    }

    // Validate all HS code IDs first
    $hscodeIds = array_map(function($item) {
      return (int)($item['id'] ?? 0);
    }, $hscodeData);
    
    $hscodeIds = array_filter($hscodeIds, function($id) {
      return $id > 0;
    });

    if (empty($hscodeIds)) {
      echo json_encode(['success' => false, 'message' => 'No valid HS Codes provided']);
      return;
    }

    // Single query to get all already assigned HS codes
    $placeholders = implode(',', array_fill(0, count($hscodeIds), '?'));
    $sql = "SELECT hscode_id FROM license_hscode_t 
            WHERE license_id = ? AND hscode_id IN ($placeholders) AND display = 'Y'";
    
    $params = array_merge([$licenseId], $hscodeIds);
    $existing = $this->db->customQuery($sql, $params);
    $existingIds = array_column($existing, 'hscode_id');

    // Start transaction for bulk insert
    $this->db->beginTransaction();
    
    try {
      $successCount = 0;
      $skipCount = count($existingIds);
      $insertData = [];
      $timestamp = date('Y-m-d H:i:s');

      foreach ($hscodeData as $item) {
        $hscodeId = (int)($item['id'] ?? 0);
        
        if ($hscodeId <= 0 || in_array($hscodeId, $existingIds)) {
          continue;
        }

        $ddi = isset($item['ddi']) && $item['ddi'] !== '' ? (float)$item['ddi'] : 0.00;
        
        $insertData[] = [
          'license_id' => $licenseId,
          'hscode_id' => $hscodeId,
          'ddi' => $ddi,
          'display' => 'Y',
          'created_by' => $this->userId,
          'updated_by' => $this->userId,
          'created_at' => $timestamp,
          'updated_at' => $timestamp
        ];
      }

      // Bulk insert if we have data
      if (!empty($insertData)) {
        $fields = array_keys($insertData[0]);
        $placeholders = '(' . implode(',', array_fill(0, count($fields), '?')) . ')';
        $allPlaceholders = implode(',', array_fill(0, count($insertData), $placeholders));
        
        $sql = "INSERT INTO license_hscode_t (" . implode(',', $fields) . ") VALUES $allPlaceholders";
        
        $values = [];
        foreach ($insertData as $row) {
          foreach ($row as $value) {
            $values[] = $value;
          }
        }
        
        $result = $this->db->customQuery($sql, $values);
        $successCount = count($insertData);
      }

      $this->db->commit();

      // Build response message
      $message = "";
      if ($successCount > 0) {
        $message = "✅ $successCount HS Code(s) assigned successfully!";
      }
      if ($skipCount > 0) {
        $message .= " ($skipCount already assigned)";
      }

      echo json_encode([
        'success' => $successCount > 0,
        'message' => $message ?: 'No new HS Codes were assigned',
        'assigned' => $successCount,
        'skipped' => $skipCount
      ]);

    } catch (Exception $e) {
      $this->db->rollback();
      error_log('Bulk insert error: ' . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to assign HS Codes']);
    }
  }

  private function removeHsCodeFromLicense()
  {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) 
       ?: filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$id || $id <= 0) {
      echo json_encode(['success' => false, 'message' => 'Invalid ID']);
      return;
    }

    // Soft delete with optimized query
    $sql = "UPDATE license_hscode_t 
            SET display = 'N', updated_by = ?, updated_at = NOW()
            WHERE id = ? AND display = 'Y'";

    try {
      $affected = $this->db->customQuery($sql, [$this->userId, $id]);

      if ($affected) {
        echo json_encode([
          'success' => true,
          'message' => 'HS Code removed successfully!'
        ]);
      } else {
        echo json_encode(['success' => false, 'message' => 'HS Code not found or already removed']);
      }
    } catch (Exception $e) {
      error_log('Delete error: ' . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Failed to remove HS Code']);
    }
  }
}