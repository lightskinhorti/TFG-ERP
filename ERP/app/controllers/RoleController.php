<?php
require_once 'core/Controller.php';
require_once 'app/models/RoleModel.php';

/**
 * Controlador para la gestión de roles en el sistema ERP
 * 
 * Este controlador permite:
 * - Listar todos los roles
 * - Crear un nuevo rol
 * - Editar un rol existente
 * - Eliminar (soft delete o hard delete, según definamos más adelante)
 * 
 * Las respuestas pueden ser HTML o JSON según el tipo de solicitud (AJAX o normal).
 */
class RoleController extends Controller
{
    private $roleModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
    }

    /**
     * Muestra la vista principal de roles con la tabla de gestión.
     */
    public function index()
    {
        $roles = $this->roleModel->getAll();
        $this->view('roles/index', ['roles' => $roles]);
    }

    /**
     * Crea un nuevo rol a partir de los datos del formulario (POST)
     */
    public function create()
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if ($nombre === '') {
            echo json_encode(['status' => 'error', 'message' => 'El nombre del rol es obligatorio']);
            return;
        }

        $result = $this->roleModel->create($nombre, $descripcion);

        echo json_encode([
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'Rol creado correctamente' : 'Error al crear el rol'
        ]);
    }

    /**
     * Actualiza un rol existente a partir de su ID
     */
    public function update()
    {
        $id = intval($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if ($id <= 0 || $nombre === '') {
            echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
            return;
        }

        $result = $this->roleModel->update($id, $nombre, $descripcion);

        echo json_encode([
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'Rol actualizado' : 'Error al actualizar'
        ]);
    }

    /**
     * Elimina un rol por ID
     * Por ahora lo hacemos hard delete, pero podríamos cambiarlo a soft delete si lo ves necesario.
     */
    public function delete()
    {
        $id = intval($_POST['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
            return;
        }

        $result = $this->roleModel->delete($id);

        echo json_encode([
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'Rol eliminado' : 'Error al eliminar'
        ]);
    }
}
