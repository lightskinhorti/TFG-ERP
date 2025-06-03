<?php
require_once 'core/Controller.php';
require_once 'app/models/UserModel.php';

class AuthController extends Controller {
    public function login() {
        
        // Si ya hay sesión, redirige
        session_start();
        if (isset($_SESSION['user'])) {
            header('Location: index.php?controller=dashboard');
            exit;
        }

        // Si envía formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
     
        
            if (empty($email) || empty($password)) {
                $error = "Rellena todos los campos.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Email no válido.";
            } else {
                $userModel = new UserModel();
                $user = $userModel->login($email, $password);
                       // Depuración temporal
                    /* var_dump($email);
                    var_dump($password);
                    var_dump($user); */ // Esto mostrará false o el array del usuario
                            
                if ($user) {
                    $_SESSION['user'] = $user;
                    require_once 'app/models/LogModel.php'; 
                    if (isset($_SESSION['user']['id'])) {
                        $logModel = new LogModel();
                        $logModel->add($_SESSION['user']['id'], 'login');
                    }
                    

                    header('Location: index.php?controller=dashboard');
                    exit;
                } else {
                    $error = "Credenciales incorrectas.";
                }
            }
        }
        
        $this->view('auth/login', isset($error) ? ['error' => $error] : []);
    }

    public function logout() {
        session_start();
    
        // Guardamos el ID antes de destruir la sesión
        $userId = $_SESSION['user']['id'] ?? null;
    
        // Elimina todos los datos de sesión
        $_SESSION = [];
    
        // Solo registramos el logout si hay un usuario válido
        if ($userId !== null) {
            require_once 'app/models/LogModel.php';
            $logModel = new LogModel();
            $logModel->add($userId, 'logout');
        }
    
        session_destroy();
    
        // Redirige al login
        header('Location: index.php?controller=auth&action=login');
        exit;
    }
    
}