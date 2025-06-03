<?php
require_once 'core/Controller.php';
require_once 'app/models/UserModel.php';
require_once 'core/Auth.php';

class UserController extends Controller {

    // Muestra el formulario para crear usuarios
    public function create() {
        requireAdmin();
    
        $userModel = new UserModel();
        $roles = $userModel->getRoles(); // se autogestiona
    
        $this->view('users/create', ['roles' => $roles]);
    }
    

    // Procesa el formulario (inserta usuario en DB)
    public function store() {
        requireAdmin(); // Solo admins pueden ejecutar

        // Verifica si se ha enviado el formulario por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $role = trim($_POST['role']);

            // Validación simple
            if (empty($name) || empty($email) || empty($password) || empty($role)) {
                $error = "Todos los campos son obligatorios.";
                $this->view('users/create', ['error' => $error]);
                return;
            }

            // Crea el usuario
            $userModel = new UserModel();
            $userModel->createUser($name, $email, $password, $role);

            // Redirige tras crear (más adelante añadiremos flash)
            header('Location: index.php?controller=dashboard');
            exit;
        }

        // Si no es POST, redirige
        header('Location: index.php?controller=users&action=create');
        exit;
    }
}
