<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all users
    public function getAllUsers() {
        $this->db->query('SELECT * FROM users ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    // Find user by ID
    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Find user by email
    public function getUserByUsername($username) {
        $sql = "SELECT 
                    users_t.id as user_id,
                    users_t.full_name,
                    users_t.username,
                    users_t.email,
                    users_t.profile_image,
                    users_t.password,
                    role_master_t.id as role_id,
                    role_master_t.role_name
                FROM users_t 
                LEFT JOIN role_master_t ON users_t.role_id = role_master_t.id 
                WHERE users_t.username = :username 
                AND users_t.display = 'Y'
                LIMIT 1";
        
        $this->db->query($sql);
        $this->db->bind(':username', $username);
        return $this->db->single();
    }
    // Create new user
    public function createUser($data) {
        $this->db->query('INSERT INTO users (name, email, password, created_at) 
                         VALUES (:name, :email, :password, NOW())');
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Update user
    public function updateUser($data) {
        $this->db->query('UPDATE users 
                         SET name = :name, email = :email, updated_at = NOW() 
                         WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);

        return $this->db->execute();
    }

    // Delete user
    public function deleteUser($id) {
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Check if email exists
    public function emailExists($email, $excludeId = null) {
        if ($excludeId) {
            $this->db->query('SELECT id FROM users WHERE email = :email AND id != :id');
            $this->db->bind(':id', $excludeId);
        } else {
            $this->db->query('SELECT id FROM users WHERE email = :email');
        }
        $this->db->bind(':email', $email);
        
        return $this->db->single() ? true : false;
    }

    // Verify user login
    public function login($username, $password) {
        $user = $this->getUserByUsername($username);
        if ($user && password_verify($password, $user->password)) {
            unset($user->password);
            return $user;
        }
        return false;
    }

    // Update password
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, HASH_ALGO);
        
        $this->db->query('UPDATE users SET password = :password WHERE id = :id');
        $this->db->bind(':id', $userId);
        $this->db->bind(':password', $hashedPassword);
        
        return $this->db->execute();
    }

    // Get user count
    public function getUserCount() {
        $this->db->query('SELECT COUNT(*) as count FROM users');
        $result = $this->db->single();
        return $result->count;
    }
}