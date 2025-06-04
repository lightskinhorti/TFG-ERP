<?php

require_once 'core/Model.php';

class PedidoModel extends Model {

    public function __construct() {
        parent::__construct('pedidos'); // tabla de pedidos
    }

    public function getAllPaginado($offset, $limite, $mostrarEliminadas = false) {
        $sql = "SELECT * FROM {$this->table} WHERE eliminado = ?";
        $sql .= " ORDER BY id DESC LIMIT ?, ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $mostrarEliminadas ? 1 : 0, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(3, (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarPedidos($mostrarEliminadas = false) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE eliminado = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $mostrarEliminadas ? 1 : 0, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function buscarPedidos(array $filtros = [], int $offset = 0, int $limite = 10, bool $mostrarEliminadas = false): array {
        $sql = "SELECT * FROM {$this->table} WHERE eliminado = ?";
        $params = [$mostrarEliminadas ? 1 : 0];

        $camposValidos = $this->getCamposPermitidos();

        foreach ($filtros as $campo => $valor) {
            if (in_array($campo, $camposValidos)) {
                $sql .= " AND $campo LIKE ?";
                $params[] = '%' . $valor . '%';
            }
        }

        $sql .= " ORDER BY id DESC LIMIT ?, ?";
        $params[] = $offset;
        $params[] = $limite;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarPedidosFiltrados(array $filtros = [], bool $mostrarEliminadas = false): int {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE eliminado = ?";
        $params = [$mostrarEliminadas ? 1 : 0];

        $camposValidos = $this->getCamposPermitidos();

        foreach ($filtros as $campo => $valor) {
            if (in_array($campo, $camposValidos)) {
                $sql .= " AND $campo LIKE ?";
                $params[] = '%' . $valor . '%';
            }
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function softDelete($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET eliminado = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function restaurar($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET eliminado = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getCamposPermitidos(): array {
        return array_filter($this->getColumnNames(), function ($campo) {
            return !in_array($campo, ['id', 'eliminado']);
        });
    }
    public function getColumnNames(): array {
        $todos = parent::getColumnNames();
        return array_filter($todos, fn($col) => !in_array($col, ['fecha_creacion', 'fecha_actualizacion']));
    }
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? AND eliminado = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // app/models/PedidoModel.php
    public function obtenerTotalFacturado() {
        $stmt = $this->db->prepare("SELECT SUM(importe * 1.21) AS total_facturado FROM pedidos WHERE eliminado = 0");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_facturado'] ?? 0;
    }
    // Devuelve el total de pedidos con estado 'confirmado'
public function contarPedidosConfirmados() {
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM pedidos WHERE estado = 'confirmado' AND eliminado = 0");
    $stmt->execute();
    return $stmt->fetchColumn();
}

}
