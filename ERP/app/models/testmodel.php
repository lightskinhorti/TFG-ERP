<?php
require_once 'core/Model.php';

class PedidoModel extends Model
{
    public function __construct() {
        parent::__construct('pedidos');
    }

    public function getAll() {
        $sql = "SELECT p.*, e.nombre AS empresa_nombre 
                FROM pedidos p
                JOIN empresas e ON p.empresa_id = e.id
                WHERE p.eliminado = 0
                ORDER BY p.fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllPaginado($offset, $limite, $mostrarEliminados = false) {
        $sql = "SELECT p.*, e.nombre AS empresa_nombre 
                FROM pedidos p
                JOIN empresas e ON p.empresa_id = e.id
                WHERE p.eliminado = :eliminado
                ORDER BY p.fecha_creacion DESC
                LIMIT :offset, :limite";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':eliminado', $mostrarEliminados ? 1 : 0, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarPedidos($mostrarEliminados = false) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE eliminado = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $mostrarEliminados ? 1 : 0, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetch()['total'];
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM pedidos WHERE id = ? AND eliminado = 0");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function softDelete($id) {
        $stmt = $this->db->prepare("UPDATE pedidos SET eliminado = 1, fecha_actualizacion = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function restaurar($id) {
        $stmt = $this->db->prepare("UPDATE pedidos SET eliminado = 0, fecha_actualizacion = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function crearPedido($data) {
        $sql = "INSERT INTO pedidos (empresa_id, fecha_pedido, estado, observaciones, fecha_creacion, fecha_actualizacion)
                VALUES (?, ?, ?, ?, NOW(), NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['empresa_id'],
            $data['fecha_pedido'],
            $data['estado'],
            $data['observaciones']
        ]);
    }

    public function buscarPedidos(array $filtros = [], int $offset = 0, int $limite = 10, bool $mostrarEliminados = false): array {
        $sql = "SELECT p.*, e.nombre AS empresa_nombre 
                FROM pedidos p
                JOIN empresas e ON p.empresa_id = e.id
                WHERE p.eliminado = ?";
        $params = [$mostrarEliminados ? 1 : 0];

        $camposValidos = $this->getCamposPermitidos();

        foreach ($filtros as $campo => $valor) {
            if (in_array($campo, $camposValidos)) {
                if (str_contains($campo, 'fecha')) {
                    $sql .= " AND p.$campo = ?";
                    $params[] = $valor;
                } else {
                    $sql .= " AND p.$campo LIKE ?";
                    $params[] = '%' . $valor . '%';
                }
            }
        }

        $sql .= " ORDER BY p.fecha_creacion DESC LIMIT ?, ?";
        $params[] = $offset;
        $params[] = $limite;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarPedidosFiltrados(array $filtros = [], bool $mostrarEliminados = false): int {
        $sql = "SELECT COUNT(*) FROM pedidos WHERE eliminado = ?";
        $params = [$mostrarEliminados ? 1 : 0];

        $camposValidos = $this->getCamposPermitidos();

        foreach ($filtros as $campo => $valor) {
            if (in_array($campo, $camposValidos)) {
                if (str_contains($campo, 'fecha')) {
                    $sql .= " AND $campo = ?";
                    $params[] = $valor;
                } else {
                    $sql .= " AND $campo LIKE ?";
                    $params[] = '%' . $valor . '%';
                }
            }
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function update($id, $datos) {
        $camposPermitidos = $this->getCamposPermitidos();
        $set = [];
        $params = [];

        foreach ($datos as $campo => $valor) {
            if (in_array($campo, $camposPermitidos)) {
                $set[] = "$campo = ?";
                $params[] = $valor;
            }
        }

        if (empty($set)) return false;

        $params[] = $id;

        $sql = "UPDATE {$this->table} SET " . implode(", ", $set) . ", fecha_actualizacion = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function getCamposPermitidos(): array {
        return array_filter($this->getColumnNames(), function ($campo) {
            return !in_array($campo, ['id', 'eliminado']);
        });
    }
    public function getEstadosUnicos() {
        $stmt = $this->db->prepare("SELECT DISTINCT estado FROM pedidos WHERE estado IS NOT NULL AND estado != ''");
        $stmt->execute();
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'estado');
    }
    
}
