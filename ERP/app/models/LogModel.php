<?php
require_once 'core/Model.php';

class LogModel extends Model {

    public function __construct() {
        parent::__construct('logs'); // 👈 Le decimos que trabajamos con la tabla 'logs'
    }

    /**
     * Añade un registro de log con la IP del usuario
     */
    public function add($userId, $action) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $stmt = $this->db->prepare("INSERT INTO logs (user_id, action, ip_address) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $action, $ip]);
    }
}
