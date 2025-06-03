<?php
require_once 'config/database.php';

/**
 * Clase base dinámica para todos los modelos
 * 
 * Esta clase obtiene automáticamente los campos de la tabla
 * y genera métodos insert/update genéricos y reutilizables.
 */
class Model {
    protected $db;
    protected $table;
    protected $columns = [];

    /**
     * Constructor: recibe el nombre de la tabla y establece conexión
     */
    public function __construct($table) {
        $this->db = Database::connect();
        $this->table = $table;
        $this->loadColumns(); // Carga las columnas automáticamente
    }

    /**
     * Carga dinámicamente los campos de la tabla desde la BBDD
     */
    protected function loadColumns() {
        $stmt = $this->db->prepare("SHOW COLUMNS FROM {$this->table}");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $column) {
            // Excluimos campos que no se deben actualizar directamente
            if (!in_array($column['Field'], ['id', 'fecha_creacion', 'fecha_actualizacion', 'eliminado'])) {
                $this->columns[] = $column['Field'];
            }
        }
    }

    /**
     * Inserta un nuevo registro en la tabla con campos dinámicos
     */
    public function insert($data) {
        // Filtramos solo columnas válidas
        $fields = array_intersect_key($data, array_flip($this->columns));
        $keys = array_keys($fields);
        $placeholders = array_map(fn($k) => ":$k", $keys);

        $sql = "INSERT INTO {$this->table} (" . implode(",", $keys) . ") VALUES (" . implode(",", $placeholders) . ")";
        $stmt = $this->db->prepare($sql);

        // Preparamos los valores
        $params = [];
        foreach ($fields as $key => $value) {
            $params[":$key"] = $value;
        }

        return $stmt->execute($params);
    }

    /**
     * Actualiza un registro existente por su ID
     */
    public function update($id, $data) {
        $fields = array_intersect_key($data, array_flip($this->columns));
        $set = implode(", ", array_map(fn($k) => "$k = :$k", array_keys($fields)));

        $sql = "UPDATE {$this->table} SET $set WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        $params = [':id' => $id];
        foreach ($fields as $key => $value) {
            $params[":$key"] = $value;
        }

        return $stmt->execute($params);
    }
    //Getter de columnas
    public function getColumnNames() {
        return $this->columns;
    }
    
}
