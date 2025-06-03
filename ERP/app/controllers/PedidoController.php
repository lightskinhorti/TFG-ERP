<?php
require_once 'core/Controller.php';
require_once 'app/models/PedidoModel.php';
require_once 'core/Auth.php';

class PedidoController extends Controller {

    public function index() {
        requireLogin();
    
        $pedidoModel = new PedidoModel();
    
        $pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $porPagina = 10;
        $offset = ($pagina - 1) * $porPagina;
    
        $mostrarEliminadas = isset($_GET['mostrarEliminadas']) && $_GET['mostrarEliminadas'] == 1;
        $pedidos = $pedidoModel->getAllPaginado($offset, $porPagina, $mostrarEliminadas);
        $total = $pedidoModel->contarPedidos($mostrarEliminadas);
        $totalPaginas = ceil($total / $porPagina);
    
        // ✅ Cargar empresas activas para el formulario de creación
        require_once 'app/models/EmpresaModel.php';
        $empresaModel = new EmpresaModel();
        $empresasDisponibles = $empresaModel->getAll(); // Solo activas
        
    
        $this->view('pedidos/index', [
            'pedidos' => $pedidos,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'mostrarEliminadas' => $mostrarEliminadas,
            'campos' => $pedidoModel->getColumnNames(),
            'empresasDisponibles' => $empresasDisponibles
        ]);
    }
    

    public function paginado_ajax() {
        requireLogin();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $mostrarEliminadas = isset($_GET['mostrarEliminadas']) && $_GET['mostrarEliminadas'] == 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $pedidoModel = new PedidoModel();
        $pedidos = $pedidoModel->getAllPaginado($offset, $limit, $mostrarEliminadas);
        // Eliminamos campos no deseados
$pedidos = array_map(function ($pedido) {
    unset($pedido['fecha_creacion'], $pedido['fecha_actualizacion']);
    return $pedido;
}, $pedidos);
        $total = $pedidoModel->contarPedidos($mostrarEliminadas);
        $totalPaginas = ceil($total / $limit);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'pedidos' => $pedidos,
            'totalPaginas' => $totalPaginas
        ]);
        exit;
    }

    public function buscar_ajax() {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $filtros = $input['filtros'] ?? [];
        $pagina = isset($input['pagina']) ? max(1, (int)$input['pagina']) : 1;
        $mostrarEliminadas = !empty($input['mostrarEliminadas']);
        $limit = 10;
        $offset = ($pagina - 1) * $limit;

        $pedidoModel = new PedidoModel();
        $pedidos = $pedidoModel->buscarPedidos($filtros, $offset, $limit, $mostrarEliminadas);
        // Eliminamos campos no deseados
$pedidos = array_map(function ($pedido) {
    unset($pedido['fecha_creacion'], $pedido['fecha_actualizacion']);
    return $pedido;
}, $pedidos);
        $total = $pedidoModel->contarPedidosFiltrados($filtros, $mostrarEliminadas);
        $totalPaginas = ceil($total / $limit);

        echo json_encode([
            'success' => true,
            'pedidos' => $pedidos,
            'totalPaginas' => $totalPaginas
        ]);
        exit;
    }

    public function update_inline() {
        requireAdmin();

        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['id']) || !isset($input['datos']) || !is_array($input['datos'])) {
            echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
            return;
        }

        $id = $input['id'];
        $data = $input['datos'];

        foreach ($data as $campo => $valor) {
            if (trim($valor) === '') {
                echo json_encode(['success' => false, 'error' => "Campo $campo vacío"]);
                return;
            }
        }

        $pedidoModel = new PedidoModel();
        $success = $pedidoModel->update($id, $data);

        echo json_encode(['success' => $success]);
    }

    public function store_ajax() {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
            return;
        }

        $pedidoModel = new PedidoModel();
        $data = [];

        foreach ($pedidoModel->getColumnNames() as $campo) {
            if (!in_array($campo, ['id', 'fecha_creacion', 'fecha_actualizacion', 'eliminado'])) {
                $data[$campo] = $_POST[$campo] ?? null;
            }
        }

        $success = $pedidoModel->insert($data);
        echo json_encode([
            'success' => $success,
            'mensaje' => $success ? 'Pedido creado correctamente' : 'Error al crear pedido'
        ]);
    }

    public function softDelete() {
        requireAdmin();

        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
            return;
        }

        $pedidoModel = new PedidoModel();
        $success = $pedidoModel->softDelete($id);

        echo json_encode(['success' => $success]);
    }

    public function restaurar() {
        requireAdmin();

        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
            return;
        }

        $pedidoModel = new PedidoModel();
        $success = $pedidoModel->restaurar($id);

        echo json_encode(['success' => $success]);
    }
}
