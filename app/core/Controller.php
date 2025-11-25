<?php
class Controller {
    protected $translations = [];
    protected $currentLang = 'en';
 
    public function __construct() {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Load selected language from session or default to English
        $this->currentLang = $_SESSION['lang'] ?? 'en';

        // Load translations from DB
        $this->loadTranslations();
    }

        private function loadTranslations() {
        $db = new Database();
        $sql = "SELECT label, english, french FROM language_translation_t WHERE display = 'Y'";
        $db->query($sql);
        $results = $db->resultSet();

        foreach ($results as $row) {
            $this->translations[$row->label] = [
                'en' => $row->english,
                'fr' => $row->french
            ];
        }
    }

    // Translation helper
    public function translate($label) {
        if (isset($this->translations[$label][$this->currentLang])) {
            return $this->translations[$label][$this->currentLang];
        }
        return $label; // fallback if not found
    }

    // Language switcher
    public function setLanguage($lang) {
        if (in_array($lang, ['en', 'fr'])) {
            $_SESSION['lang'] = $lang;
            $this->currentLang = $lang;
        }
    }
    
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
    public function getMenuorg()
    {
        $db = new Database(); 
        $sql = "SELECT * FROM menu_master_t WHERE display='Y' ORDER BY menu_level, menu_order,menu_id, id";
        $db->query($sql);
        $items = $db->resultSet(); // returns array of stdClass objects


        $menu = [];
        $lookup = [];

        foreach ($items as $item) {
            $item->submenu = []; // prepare submenu array
            $lookup[$item->id] = $item;

            if ($item->menu_level == 0) {
                $menu[] = $item;
            } elseif (isset($lookup[$item->menu_id])) {
                $lookup[$item->menu_id]->submenu[] = $item;
            }
        }

        return $menu;
    }
    public function getMenu()
{
    $db = new Database();
    $roleId = (int)($_SESSION['user_data']['role_id'] ?? 0);
    
    if ($roleId > 0) {
        // Use separate parameter names for multiple uses
        $sql = "
            SELECT DISTINCT
                m.*,
                COALESCE(rmm.can_view, 0) as can_view,
                COALESCE(rmm.can_add, 0) as can_add,
                COALESCE(rmm.can_edit, 0) as can_edit,
                COALESCE(rmm.can_delete, 0) as can_delete
            FROM menu_master_t m
            LEFT JOIN role_menu_mapping_t rmm ON m.id = rmm.menu_id AND rmm.role_id = :role_id1
            WHERE m.display = 'Y'
            AND (
                (rmm.can_view = 1) 
                OR 
                (m.menu_level = 0 AND m.id IN (
                    SELECT DISTINCT m2.menu_id 
                    FROM menu_master_t m2
                    INNER JOIN role_menu_mapping_t rmm2 ON m2.id = rmm2.menu_id
                    WHERE rmm2.role_id = :role_id2 AND rmm2.can_view = 1
                ))
            )
            ORDER BY m.menu_level, m.menu_order, m.menu_id, m.id
        ";
        
        $db->query($sql);
        $db->bind(':role_id1', $roleId);
        $db->bind(':role_id2', $roleId);
        $items = $db->resultSet();
        
        // Store permissions in session
        $_SESSION['menu_permissions'] = [];
        foreach ($items as $menuItem) {
            $_SESSION['menu_permissions'][$menuItem->id] = [
                'can_view' => $menuItem->can_view ?? 0,
                'can_add' => $menuItem->can_add ?? 0,
                'can_edit' => $menuItem->can_edit ?? 0,
                'can_delete' => $menuItem->can_delete ?? 0
            ];
        }
        
        // Organize menu by parent-child hierarchy
        $menu = [];
        $lookup = [];
        
        foreach ($items as $item) {
            $item->submenu = [];
            $lookup[$item->id] = $item;
            
            if ($item->menu_level == 0) {
                $menu[] = $item;
            } elseif (isset($lookup[$item->menu_id])) {
                $lookup[$item->menu_id]->submenu[] = $item;
            }
        }
        
        return $menu;
        
    } else {
        return [];
    }
}
}