<?php
class UserController extends Controller {

    public function loginForm(): void {
        AuthMiddleware::redirectIfAuthenticated();
        $this->view('user/login', [
            'title' => 'Log In — ' . APP_NAME,
            'error' => $this->getFlash('error'),
        ]);
    }

    public function login(): void {
        AuthMiddleware::redirectIfAuthenticated();

        $v = new Validator($_POST);
        $v->required('email', 'Email')->email('email')
          ->required('password', 'Password');

        if ($v->fails()) {
            $this->flash('error', implode(' ', $v->errors()));
            $this->redirect('/login');
            return;
        }

        $email    = $v->get('email');
        $password = $v->raw('password');  // must NOT be escaped for verify

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID after successful login (session fixation prevention)
            session_regenerate_id(true);
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            Logger::info('User logged in', ['user_id' => $user['id'], 'email' => $email]);
            
            // Smart redirection based on role
            if ($user['role'] === 'admin') {
                $this->redirect('/admin');
            } else {
                $this->redirect('/dashboard');
            }
        } else {
            Logger::security('Failed login attempt', ['email' => $email]);
            $this->flash('error', 'Invalid email or password.');
            $this->redirect('/login');
        }
    }

    public function registerForm(): void {
        AuthMiddleware::redirectIfAuthenticated();
        $this->view('user/register', ['title' => 'Create Account — ' . APP_NAME]);
    }

    public function register(): void {
        AuthMiddleware::redirectIfAuthenticated();

        $v = new Validator($_POST);
        $v->required('name', 'Name')->min('name', 2)->max('name', 100)
          ->required('email', 'Email')->email('email')
          ->required('password', 'Password')->min('password', 8);

        if ($v->fails()) {
            $this->flash('error', implode(' ', $v->errors()));
            $this->redirect('/register');
            return;
        }

        $userModel = new User();
        if ($userModel->findByEmail($v->get('email'))) {
            $this->flash('error', 'An account with this email already exists.');
            $this->redirect('/register');
            return;
        }

        if ($userModel->create($v->get('name'), $v->get('email'), $v->raw('password'))) {
            Logger::info('New user registered', ['email' => $v->get('email')]);
            $this->flash('success', 'Account created! Please log in.');
            $this->redirect('/login');
        } else {
            $this->flash('error', 'Registration failed. Please try again.');
            $this->redirect('/register');
        }
    }

    public function dashboard(): void {
        AuthMiddleware::requireAuth();
        $userModel = new User();
        $orders = $userModel->getOrders($_SESSION['user_id']);
        $this->view('user/dashboard', [
            'title'   => 'My Dashboard — ' . APP_NAME,
            'orders'  => $orders,
            'success' => $this->getFlash('success'),
        ]);
    }

    public function logout(): void {
        Logger::info('User logged out', ['user_id' => $_SESSION['user_id'] ?? null]);
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        exit;
    }

    /** Show order detail for the logged-in user */
    public function orderDetail(string $id): void {
        AuthMiddleware::requireAuth();
        $orderModel = new Order();
        $order = $orderModel->getByIdForUser((int)$id, (int)$_SESSION['user_id']);
        if (!$order) {
            http_response_code(404);
            $this->view('404', ['title' => 'Order Not Found']);
            return;
        }
        $items = $orderModel->getItems((int)$id);
        $this->view('user/order_detail', [
            'title' => 'Order #' . $id,
            'order' => $order,
            'items' => $items,
        ]);
    }
}
