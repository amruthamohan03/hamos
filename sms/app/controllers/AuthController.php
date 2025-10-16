<?php
class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    // Show login form
    public function index() {
        // If already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('user/dashboard');
        }

        if ($this->isPost()) {
            // Verify CSRF token
            $token = $this->getPost('csrf_token');
            if (!$this->verifyCsrfToken($token)) {
                $this->setFlash('login', 'Invalid request', 'alert alert-danger');
                $this->redirect('auth/login');
                return;
            }

            // Sanitize inputs
            $email = $this->sanitize($this->getPost('email'));
            $password = $this->getPost('password');

            // Validate
            $errors = [];
            if (!$this->validateEmail($email)) {
                $errors[] = 'Please enter a valid email';
            }
            if (empty($password)) {
                $errors[] = 'Please enter password';
            }

            if (empty($errors)) {
                // Attempt login
                $user = $this->userModel->login($email, $password);
                
                if ($user) {
                    // Set session
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_data'] = [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email
                    ];
                    
                    $this->redirect('user/dashboard');
                } else {
                    $errors[] = 'Invalid email or password';
                }
            }

            $data = [
                'title' => 'Login',
                'errors' => $errors,
                'email' => $email,
                'csrf_token' => $this->generateCsrfToken()
            ];
            $this->view('auth/login', $data);
        } else {
            $data = [
                'title' => 'Login',
                'csrf_token' => $this->generateCsrfToken()
            ];
            $this->view('auth/login', $data);
        }
    }

    // Show registration form
    public function register() {
        // If already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('user/dashboard');
        }

        if ($this->isPost()) {
            // Verify CSRF token
            $token = $this->getPost('csrf_token');
            if (!$this->verifyCsrfToken($token)) {
                $this->setFlash('register', 'Invalid request', 'alert alert-danger');
                $this->redirect('auth/register');
                return;
            }

            // Sanitize inputs
            $name = $this->sanitize($this->getPost('name'));
            $email = $this->sanitize($this->getPost('email'));
            $password = $this->getPost('password');
            $confirmPassword = $this->getPost('confirm_password');

            // Validate
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

            if (empty($errors)) {
                // Hash password
                $hashedPassword = password_hash($password, HASH_ALGO);

                // Create user
                $userData = [
                    'name' => $name,
                    'email' => $email,
                    'password' => $hashedPassword
                ];

                $userId = $this->userModel->createUser($userData);

                if ($userId) {
                    $this->setFlash('login', 'Registration successful! Please login.', 'alert alert-success');
                    $this->redirect('auth/login');
                } else {
                    $errors[] = 'Something went wrong. Please try again.';
                }
            }

            $data = [
                'title' => 'Register',
                'errors' => $errors,
                'name' => $name,
                'email' => $email,
                'csrf_token' => $this->generateCsrfToken()
            ];
            $this->view('auth/register', $data);
        } else {
            $data = [
                'title' => 'Register',
                'csrf_token' => $this->generateCsrfToken()
            ];
            $this->view('auth/register', $data);
        }
    }

    // Logout
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_data']);
        session_destroy();
        $this->redirect('auth/login');
    }
}