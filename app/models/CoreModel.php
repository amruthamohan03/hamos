<?php
/**
 * Core Model Class
 * Base model for all models to inherit database access
 */
class CoreModel
{
    /** @var Database */
    protected $db;

    /**
     * Constructor - initializes a shared Database instance
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Get the database instance
     */
    protected function getDB()
    {
        return $this->db;
    }

    /* ---------------- INSERT ---------------- */
    public function insertData($table, $data)
    {
        $current_time = date("Y-m-d H:i:s");
        $data['is_active'] = 'Y';
        $data['created_by'] = $_SESSION['user_id'] ?? null;
        $data['created_at'] = $current_time;

        $fields = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
        $this->db->query($sql);

        foreach ($data as $key => $value) {
            $this->db->bind(":{$key}", $value);
        }

        $this->db->execute();

        $insertId = $this->db->lastInsertId();
        $this->logActivity("Insertion to {$table}", $sql, $insertId);

        return $insertId;
    }

    /* ---------------- UPDATE ---------------- */
    public function updateWhere($table, $update_data, $field, $match)
    {
        $current_time = date("Y-m-d H:i:s");
        $update_data['updated_by'] = $_SESSION['user_id'] ?? null;
        $update_data['updated_at'] = $current_time;

        $setStr = implode(", ", array_map(fn($col) => "{$col} = :{$col}", array_keys($update_data)));

        $sql = "UPDATE {$table} SET {$setStr} WHERE {$field} = :match";
        $this->db->query($sql);

        foreach ($update_data as $key => $value) {
            $this->db->bind(":{$key}", $value);
        }
        $this->db->bind(":match", $match);

        $this->db->execute();

        $this->logActivity("Update on {$table}", $sql, $match);

        return $this->db->rowCount();
    }

    /* ---------------- SELECT ---------------- */
    public function selectData($table, $fields = '*')
    {
        $sql = "SELECT {$fields} FROM {$table} WHERE is_active = 'Y'";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    /* ---------------- DELETE ---------------- */
    public function deleteWhere($table, $field, $match)
    {
        $sql = "DELETE FROM {$table} WHERE {$field} = :match";
        $this->db->query($sql);
        $this->db->bind(":match", $match);
        $this->db->execute();

        $this->logActivity("Delete from {$table}", $sql, $match);
        return $this->db->rowCount();
    }

    /* ---------------- CUSTOM QUERY ---------------- */
    public function customQuery($sql, $params = [])
    {
        $this->db->query($sql);
        foreach ($params as $key => $value) {
            $this->db->bind(is_string($key) ? $key : ":param{$key}", $value);
        }
        return $this->db->resultSet();
    }

    /* ---------------- LOG ACTIVITY ---------------- */
    protected function logActivity($action, $query, $reference = null)
    {
        if (!defined('LOG_FILE')) return;

        $user = $_SESSION['user_id'] ?? 'system';
        $timestamp = date('Y-m-d H:i:s');
        $message = "[{$timestamp}] [user: {$user}] {$action} | Ref: {$reference}\nQuery: {$query}\n\n";
        file_put_contents(LOG_FILE, $message, FILE_APPEND);
    }
}
