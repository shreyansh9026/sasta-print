<?php
class UserController extends Controller {
    public function loginForm() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        $this->view('user/login', ['title' => 'Log In']);
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $userModel = new User();
        $user = $userModel->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            $this->redirect('/dashboard');
        } else {
            $this->view('user/login', ['title' => 'Log In', 'error' => 'Invalid credentials']);
        }
    }

    public function registerForm() {
        $this->view('user/register', ['title' => 'Create Account']);
    }

    public function register() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        if ($userModel->create($name, $email, $password)) {
            $this->redirect('/login');
        } else {
            $this->view('user/register', ['title' => 'Create Account', 'error' => 'Registration failed. Email might exist.']);
        }
    }

    public function dashboard() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        $this->view('user/dashboard', ['title' => 'My Dashboard']);
    }

    public function logout() {
        session_destroy();
        $this->redirect('/');
    }
}
