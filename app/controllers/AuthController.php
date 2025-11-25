<?php
require_once 'SessionManager.php';

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        // Initialize session
        SessionManager::init();
        
        $this->userModel = $this->model('User');
    }

    /**
     * Show login form
     */
    public function index() {
        $this->login();
    }

    /**
     * Login page and handler
     */
    public function login() {
        // Redirect if already logged in
        if (SessionManager::isLoggedIn()) {
            $this->redirect('home/dashboard');
            return;
        }

        if ($this->isPost()) {
            $this->handleLogin();
        } else {
            $this->showLoginForm();
        }
    }

    /**
     * Handle login POST request
     */
    private function handleLogin() {
        // Verify CSRF token
        if (!$this->verifyCsrfToken($this->getPost('csrf_token'))) {
            SessionManager::setFlash('login', 'Invalid request. Please try again.', 'alert alert-danger');
            $this->redirect('auth/login');
            return;
        }

        // Sanitize inputs
        $username = $this->sanitize($this->getPost('username'));
        $password = $this->getPost('password');

        // Validate inputs
        $errors = $this->validateLoginInput($username, $password);

        if (empty($errors)) {
            $user = $this->userModel->login($username, $password);
            
            if ($user) {
                // Set session using SessionManager
                SessionManager::setUserSession([
                    'id'            => $user->user_id,
                    'fullname'      => $user->full_name,
                    'username'      => $user->username,
                    'role_name'     => $user->role_name,
                    'role_id'       => $user->role_id,
                    'profile_image' => $user->profile_image ?? null,
                    'email'         => $user->email ?? ''
                ]);
                
                SessionManager::setFlash('dashboard', 'Welcome back, ' . $user->full_name . '!', 'alert alert-success');
                $this->redirect('home/dashboard');
                return;
            } else {
                $errors[] = 'Invalid username or password';
            }
        }

        // Show login form with errors
        $this->showLoginForm($errors, $username);
    }

    /**
     * Display login form
     */
    private function showLoginForm($errors = [], $username = '') {
        $data = [
            'title'       => 'Login',
            'errors'      => $errors,
            'username'    => $username,
            'csrf_token'  => $this->generateCsrfToken()
        ];
        
        $this->view('auth/login', $data);
    }

    /**
     * Validate login input
     */
    private function validateLoginInput($username, $password) {
        $errors = [];
        
        if (empty($username)) {
            $errors[] = 'Please enter your username';
        }
        
        if (empty($password)) {
            $errors[] = 'Please enter your password';
        }
        
        return $errors;
    }

    /**
     * Show registration form
     */
    public function register() {
        // Redirect if already logged in
        if (SessionManager::isLoggedIn()) {
            $this->redirect('home/dashboard');
            return;
        }

        if ($this->isPost()) {
            $this->handleRegistration();
        } else {
            $this->showRegisterForm();
        }
    }

    /**
     * Handle registration POST request
     */
    private function handleRegistration() {
        // Verify CSRF token
        if (!$this->verifyCsrfToken($this->getPost('csrf_token'))) {
            SessionManager::setFlash('register', 'Invalid request. Please try again.', 'alert alert-danger');
            $this->redirect('auth/register');
            return;
        }

        // Sanitize inputs
        $name = $this->sanitize($this->getPost('name'));
        $email = $this->sanitize($this->getPost('email'));
        $password = $this->getPost('password');
        $confirmPassword = $this->getPost('confirm_password');

        // Validate
        $errors = $this->validateRegistrationInput($name, $email, $password, $confirmPassword);

        if (empty($errors)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $userData = [
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword
            ];

            $userId = $this->userModel->createUser($userData);

            if ($userId) {
                SessionManager::setFlash('login', 'Registration successful! Please login.', 'alert alert-success');
                $this->redirect('auth/login');
                return;
            } else {
                $errors[] = 'Something went wrong. Please try again.';
            }
        }

        // Show registration form with errors
        $this->showRegisterForm($errors, $name, $email);
    }

    /**
     * Display registration form
     */
    private function showRegisterForm($errors = [], $name = '', $email = '') {
        $data = [
            'title' => 'Register',
            'errors' => $errors,
            'name' => $name,
            'email' => $email,
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('auth/register', $data);
    }

    /**
     * Validate registration input
     */
    private function validateRegistrationInput($name, $email, $password, $confirmPassword) {
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Name is required';
        }
        
        if (!$this->validateEmail($email)) {
            $errors[] = 'Valid email is required';
        }
        
        if ($this->userModel->emailExists($email)) {
            $errors[] = 'Email already exists';
        }
        
        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }
        
        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match';
        }
        
        return $errors;
    }

    /**
     * Logout user
     */
    public function logout() {
        SessionManager::destroy();
        SessionManager::setFlash('login', 'You have been logged out successfully.', 'alert alert-info');
        $this->redirect('auth/login');
    }

    /**
     * Check session status (AJAX endpoint)
     */
    public function checkSession() {
        header('Content-Type: application/json');
        
        $response = [
            'isLoggedIn' => SessionManager::isLoggedIn(),
            'remaining' => SessionManager::getRemainingTime()
        ];
        
        echo json_encode($response);
        exit;
    }

    /**
     * Keep session alive (AJAX endpoint)
     */
    public function keepAlive() {
        header('Content-Type: application/json');
        
        $response = SessionManager::keepAlive();
        echo json_encode($response);
        exit;
    }

    /**
     * Get session configuration (for JavaScript)
     */
    public function getConfig() {
        header('Content-Type: application/json');
        
        $config = SessionManager::getConfig();
        echo json_encode($config);
        exit;
    }
}