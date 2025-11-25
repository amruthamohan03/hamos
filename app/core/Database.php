<?php
/**
 * Database Class
 * Handles database connections and queries using PDO
 */
class Database
{
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $port;
    private $charset;
    private $options;

    private $dbh; // Database handler
    private $stmt; // Statement
    private $error;

    /**
     * Constructor - Initialize database connection
     * 
     * @param string $connection Connection name from config (default: 'mysql')
     */
    public function __construct($connection = null)
    {
        // Get connection name (default from config or specified)
        $connectionName = $connection ?? DB_CONNECTION;

        // Get database config
        $dbConfig = DB_CONFIG;
        $config = $dbConfig['connections'][$connectionName];

        // Set connection parameters
        $this->host = $config['host'] ?? DB_HOST;
        $this->user = $config['username'] ?? DB_USER;
        $this->pass = $config['password'] ?? DB_PASS;
        $this->dbname = $config['database'] ?? DB_NAME;
        $this->port = $config['port'] ?? DB_PORT;
        $this->charset = $config['charset'] ?? DB_CHARSET;

        // Set PDO options
        $this->options = $config['options'] ?? [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        // Create connection
        $this->connect();
    }

    /**
     * Establish database connection
     */
    private function connect()
    {
        try {
            // Build DSN
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset={$this->charset}";

            // Create PDO instance
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $this->options);

            if (DEBUG_MODE) {
                // Log successful connection
                $this->log("Database connected successfully to {$this->dbname}");
            }
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            $this->handleError($e);
        }
    }

    /**
     * Prepare SQL statement
     * 
     * @param string $sql SQL query
     * @return $this
     */
    public function query($sql)
    {
        try {
            $this->stmt = $this->dbh->prepare($sql);
        } catch (PDOException $e) {
            $this->handleError($e);
        }
        return $this;
    }

    public function selectQuery($queryString)
{
    $stmt = $this->dbh->prepare($queryString);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    /**
     * Bind values to prepared statement
     * 
     * @param mixed $param Parameter identifier
     * @param mixed $value Parameter value
     * @param int|null $type PDO parameter type
     * @return $this
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
        return $this;
    }

    /**
     * Execute prepared statement
     * 
     * @return bool
     */
    public function execute()
    {
        try {
            return $this->stmt->execute();
        } catch (PDOException $e) {
            $this->handleError($e);
            return false;
        }
    }

    /**
     * Get multiple records as array of objects
     * 
     * @return array
     */
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    /**
     * Get single record as object
     * 
     * @return object|false
     */
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch();
    }

    /**
     * Get row count
     * 
     * @return int
     */
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    /**
     * Get last insert ID
     * 
     * @return string
     */
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    /**
     * Begin transaction
     * 
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    /**
     * Commit transaction
     * 
     * @return bool
     */
    public function commit()
    {
        return $this->dbh->commit();
    }

    /**
     * Rollback transaction
     * 
     * @return bool
     */
    public function rollBack()
    {
        return $this->dbh->rollBack();
    }

    /**
     * Check if currently in transaction
     * 
     * @return bool
     */
    public function inTransaction()
    {
        return $this->dbh->inTransaction();
    }

    /**
     * Get column count
     * 
     * @return int
     */
    public function columnCount()
    {
        return $this->stmt->columnCount();
    }

    /**
     * Debug dump parameters
     */
    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }

    /**
     * Close cursor
     */
    public function closeCursor()
    {
        return $this->stmt->closeCursor();
    }

    /**
     * Handle database errors
     * 
     * @param PDOException $e
     */
    private function handleError(PDOException $e)
    {
        $this->error = $e->getMessage();

        // Log error
        $this->log('Database Error: ' . $this->error, 'error');

        if (DEBUG_MODE) {
            // Show detailed error in development
            die('<div style="background:#f8d7da;color:#721c24;padding:20px;border:1px solid #f5c6cb;border-radius:5px;margin:20px;">
                    <h3>Database Error</h3>
                    <p><strong>Error:</strong> ' . htmlspecialchars($this->error) . '</p>
                    <p><strong>File:</strong> ' . $e->getFile() . '</p>
                    <p><strong>Line:</strong> ' . $e->getLine() . '</p>
                 </div>');
        } else {
            // Show generic error in production
            die('A database error occurred. Please try again later.');
        }
    }

    /**
     * Log message to file
     * 
     * @param string $message
     * @param string $level
     */
    private function log($message, $level = 'info')
    {
        if (!defined('LOG_FILE')) {
            return;
        }

        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;

        file_put_contents(LOG_FILE, $logMessage, FILE_APPEND);
    }

    /**
     * Get database connection info (for debugging)
     * 
     * @return array
     */
    public function getConnectionInfo()
    {
        if (!DEBUG_MODE) {
            return [];
        }

        return [
            'host' => $this->host,
            'database' => $this->dbname,
            'port' => $this->port,
            'charset' => $this->charset,
            'driver' => $this->dbh->getAttribute(PDO::ATTR_DRIVER_NAME),
            'server_version' => $this->dbh->getAttribute(PDO::ATTR_SERVER_VERSION),
        ];
    }

    /**
     * Test database connection
     * 
     * @return bool
     */
    public function testConnection()
    {
        try {
            $this->query('SELECT 1');
            return $this->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get tables in database
     * 
     * @return array
     */
    public function getTables()
    {
        $this->query('SHOW TABLES');
        $results = $this->resultSet();

        $tables = [];
        foreach ($results as $result) {
            $tables[] = array_values((array) $result)[0];
        }

        return $tables;
    }

    /**
     * Destructor - Close connection
     */
    public function __destruct()
    {
        $this->dbh = null;
        $this->stmt = null;
    }
    public function selectData($table, $fields = '*', $conditions = [],$limit = null)
    {
        // Sanitize table name to prevent SQL injection
        $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);

        // Sanitize fields if not wildcard
        if ($fields !== '*') {
            $fields = preg_replace('/[^a-zA-Z0-9_,\s`]/', '', $fields);
        }

        // Build base SQL query
        $sql = "SELECT {$fields} FROM {$table} WHERE display = 'Y'";

        // Add additional conditions if provided
        if (!empty($conditions)) {
            foreach ($conditions as $column => $value) {
                $column = preg_replace('/[^a-zA-Z0-9_]/', '', $column);
                $sql .= " AND {$column} = :{$column}";
            }
        }

        // Add limit if provided
    if ($limit !== null && is_numeric($limit)) {
        $sql .= " LIMIT " . intval($limit);
    }
        // Prepare the statement
        $this->stmt = $this->dbh->prepare($sql);

        // Bind parameters
        foreach ($conditions as $column => $value) {
            $this->stmt->bindValue(":{$column}", $value);
        }

        // Execute the query
        $this->stmt->execute();

        // Return all rows as an associative array
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function insertData($table, $data)
    {
        // Sanitize table name
        $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);

        // Validate input
        if (empty($data) || !is_array($data)) {
            throw new Exception("Data for insert must be a non-empty associative array.");
        }

        // Prepare column and placeholder lists
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ':' . $col, $columns);

        // Build SQL query
        $sql = "INSERT INTO {$table} (" . implode(', ', $columns) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";

        // Prepare the statement
        $this->stmt = $this->dbh->prepare($sql);

        // Bind each value
        foreach ($data as $column => $value) {
            $this->stmt->bindValue(':' . $column, $value);
        }

        // Execute the query
        $success = $this->stmt->execute();

        // Return last inserted ID or false
        return $success ? $this->dbh->lastInsertId() : false;
    }
    public function updateData($table, $data, $conditions)
    {
        $setPart = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $wherePart = implode(' AND ', array_map(fn($key) => "$key = :cond_$key", array_keys($conditions)));
        $sql = "UPDATE {$table} SET $setPart WHERE $wherePart";
        $stmt = $this->dbh->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":cond_$key", $value);
        }

        return $stmt->execute();
    }

    public function deleteData($table, $conditions)
    {
        $wherePart = implode(' AND ', array_map(fn($key) => "$key = :$key", array_keys($conditions)));
        $sql = "DELETE FROM {$table} WHERE $wherePart";
        $stmt = $this->dbh->prepare($sql);

        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }
    // public function customQuery($sql, $params = [])
    // {
    //     $this->stmt = $this->dbh->prepare($sql);
    //     $this->stmt->execute();
    //     return $this->stmt->fetchAll(PDO::FETCH_ASSOC);  // ✅ return results directly
    // }
    public function customQuery($sql, $params = [])
    {
        try {
            $this->stmt = $this->dbh->prepare($sql);

            // ✅ Execute safely with or without parameters
            if (!empty($params)) {
                $this->stmt->execute($params);
            } else {
                $this->stmt->execute();
            }

            // ✅ Return associative array of results
            return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Optional: Log or display error
            error_log("Database customQuery error: " . $e->getMessage());
            return false;
        }
    }


}