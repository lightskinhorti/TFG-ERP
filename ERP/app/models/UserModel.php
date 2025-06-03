<?php
require_once 'core/Model.php';

class UserModel extends Model {

    public function __construct() {
        parent::__construct('users'); // tabla a gestionar
    }

    /**
     * Verifica si el usuario existe y la contraseña es correcta
     */
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND active = 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    /**
     * Devuelve lista única de roles encontrados en la tabla users
     */
    public function getRoles() {
        $stmt = $this->db->query("SELECT DISTINCT role FROM users ORDER BY role");
        $roles = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Roles por defecto si aún no hay datos en la tabla
        return !empty($roles) ? $roles : ['admin', 'empleado', 'contable'];
    }
    public function contarUsuarios() {
        $sql = "SELECT COUNT(*) AS total FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
    
}
