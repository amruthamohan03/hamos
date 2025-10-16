<?php
class Controller {
    // Load model
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    // Load view
    public function view($view, $data = []) {
        // Check if view exists
        if (file_exists('../app/views/' . $view . '.php')) {
            // Extract data to variables
            extract($data);
            
            // Load view
            require_once '../app/views/' . $view . '.php';
        } else {
            die('View does not exist: ' . $view);
        }
    }

    // Load view WITH master layout (new method)
    public function viewWithLayout($view, $data = [], $layout = 'layouts/main') {
        if (file_exists('../app/views/' . $view . '.php')) {
            // Start output buffering
            ob_start();
            
            // Extract data and load the view content
            extract($data);
            require_once '../app/views/' . $view . '.php';
            
            // Get the view content
            $content = ob_get_clean();
            
            // Pass content to layout
            $data['content'] = $content;
            
            // Load the layout
            if (file_exists('../app/views/' . $layout . '.php')) {
                extract($data);
                require_once '../app/views/' . $layout . '.php';
            } else {
                die('Layout does not exist: ' . $layout);
            }
        } else {
            die('View does not exist: ' . $view);
        }
    }

    // Redirect helper
    public function redirect($url) {
        header('Location: ' . URL_ROOT . '/' . $url);
        exit();
    }

    // Check if user is logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Get current user
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return $_SESSION['user_data'] ?? null;
        }
        return null;
    }

    // Set flash message
    public function setFlash($name, $message, $class = 'alert alert-success') {
        $_SESSION['flash_' . $name] = [
            'message' => $message,
            'class' => $class
        ];
    }

    // Display flash message
    public function flash($name) {
        if (isset($_SESSION['flash_' . $name])) {
            $flash = $_SESSION['flash_' . $name];
            unset($_SESSION['flash_' . $name]);
            return '<div class="' . $flash['class'] . '">' . $flash['message'] . '</div>';
        }
        return '';
    }

    // Sanitize input
    public function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    // Validate email
    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Generate CSRF token
    public function generateCsrfToken() {
        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }

    // Verify CSRF token
    public function verifyCsrfToken($token) {
        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            return false;
        }
        return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }

    // Check if request is POST
    public function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    // Get POST data
    public function getPost($key, $default = null) {
        return $_POST[$key] ?? $default;
    }

    // Get GET data
    public function getGet($key, $default = null) {
        return $_GET[$key] ?? $default;
    }

    // JSON response
    public function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
}