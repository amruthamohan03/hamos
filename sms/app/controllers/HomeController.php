<?php
class HomeController extends Controller {
    
    public function __construct() {
        // Constructor logic
    }

    // Home page
    public function index() {
        $data = [
            'title' => 'Welcome to ' . APP_NAME,
            'description' => 'This is the home page',
            'user' => $this->getCurrentUser()
        ];

        $this->viewWithLayout('home/index', $data);
    }

    // About page
    public function about() {
        $data = [
            'title' => 'About Us',
            'version' => APP_VERSION
        ];

        $this->viewWithLayout('home/about', $data);
    }

    // Contact page
    public function contact() {
        if ($this->isPost()) {
            // Verify CSRF token
            $token = $this->getPost('csrf_token');
            if (!$this->verifyCsrfToken($token)) {
                $this->setFlash('contact', 'Invalid request', 'alert alert-danger');
                $this->redirect('home/contact');
                return;
            }

            // Sanitize inputs
            $name = $this->sanitize($this->getPost('name'));
            $email = $this->sanitize($this->getPost('email'));
            $message = $this->sanitize($this->getPost('message'));

            // Validate
            $errors = [];
            if (empty($name)) {
                $errors[] = 'Name is required';
            }
            if (!$this->validateEmail($email)) {
                $errors[] = 'Valid email is required';
            }
            if (empty($message)) {
                $errors[] = 'Message is required';
            }

            if (empty($errors)) {
                // Process form (send email, save to database, etc.)
                // For now, just set success message
                $this->setFlash('contact', 'Thank you! Your message has been sent.', 'alert alert-success');
                $this->redirect('home/contact');
            } else {
                $data = [
                    'title' => 'Contact Us',
                    'errors' => $errors,
                    'name' => $name,
                    'email' => $email,
                    'message' => $message,
                    'csrf_token' => $this->generateCsrfToken()
                ];
                $this->viewWithLayout('home/contact', $data);
            }
        } else {
            $data = [
                'title' => 'Contact Us',
                'csrf_token' => $this->generateCsrfToken()
            ];
            $this->viewWithLayout('home/contact', $data);
        }
    }
}