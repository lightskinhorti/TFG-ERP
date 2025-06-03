<?php

require_once 'core/Model.php';

class EmpresaModel extends Model {

    public function __construct() {
        parent::__construct('empresas'); // Le decimos qué tabla gestionar
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM empresas WHERE eliminado = 0 ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todas las empresas con paginación
     */
    public function getAllPaginado($offset, $limite, $mostrarEliminadas = false) {
        $sql = "SELECT * FROM {$this->table}";
        if (!$mostrarEliminadas) {
            $sql .= " WHERE eliminado = 0";
        } else {
            $sql .= " WHERE eliminado = 1";
        }
    
        $sql .= " ORDER BY nombre ASC LIMIT :offset, :limite";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    /**
     * Cuenta el número total de registros en la tabla
     */
    public function contarEmpresas($mostrarEliminadas = false) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE eliminado = :estado";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':estado', $mostrarEliminadas ? 1 : 0, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total'];
    }
    

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM empresas WHERE id = ? AND eliminado = 0");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE empresas SET eliminado = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function existsByCif($cif_nif) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM empresas WHERE cif_nif = ? AND eliminado = 0");
        $stmt->execute([$cif_nif]);
        return $stmt->fetchColumn() > 0;
    }
    public function buscarPorTexto($texto) {
        $texto = "%$texto%";
    
        // Obtenemos los campos válidos dinámicamente (ya excluidos en el modelo base)
        $campos = $this->getColumnNames(); // usa Model::getColumnNames()
    
        if (empty($campos)) return [];
    
        // Creamos una consulta dinámica con LIKE para cada campo
        $filtros = implode(" OR ", array_map(fn($c) => "$c LIKE :texto", $campos));
        $sql = "SELECT * FROM {$this->table} WHERE eliminado = 0 AND ($filtros)";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':texto', $texto, PDO::PARAM_STR);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
        /**
     * Búsqueda por columnas específicas con paginación (usado por el filtro en vivo)
     */
    public function buscarEmpresas(array $filtros = [], int $offset = 0, int $limite = 10, bool $mostrarEliminadas = false): array {
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
    

    /**
     * Cuenta empresas que cumplen con filtros aplicados
     */
    public function contarEmpresasFiltradas(array $filtros = [], bool $mostrarEliminadas = false): int {
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
    

    /**
     * Lista blanca de campos permitidos para filtrado por columna
     */
    public function getCamposPermitidos(): array {
        return array_filter($this->getColumnNames(), function ($campo) {
            return !in_array($campo, ['id', 'eliminado']);
        });
    }
    public function softDelete($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET eliminado = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function restaurar($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET eliminado = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function contarEmpresasActivas() {
        $sql = "SELECT COUNT(*) AS total FROM empresas WHERE eliminado = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
    

    
}
