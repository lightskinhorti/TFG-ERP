<?php
require_once 'core/Controller.php';
require_once 'app/models/EmpresaModel.php';
require_once 'core/Auth.php';

class EmpresaController extends Controller {

    // Listado principal con paginación
    public function index() {
        requireLogin();

        $empresaModel = new EmpresaModel();

        // Recogemos el número de página actual, por defecto 1
        $pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $porPagina = 10; // Más adelante lo haremos configurable

        // Calculamos offset
        $offset = ($pagina - 1) * $porPagina;

        // Obtenemos datos paginados
        $empresas = $empresaModel->getAllPaginado($offset, $porPagina);
        $total = $empresaModel->contarEmpresas();

        $totalPaginas = ceil($total / $porPagina);

        $this->view('empresas/index', [
            'empresas' => $empresas,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'mostrarEliminadas' => isset($_GET['mostrarEliminadas']) && $_GET['mostrarEliminadas'] == 1
        ]);
        
    }
    // Devuelve empresas paginadas en formato JSON (para AJAX)
    public function paginado_ajax() {
        requireLogin();
    
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $mostrarEliminadas = isset($_GET['mostrarEliminadas']) && $_GET['mostrarEliminadas'] == 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
    
        $empresaModel = new EmpresaModel();
        $empresas = $empresaModel->getAllPaginado($offset, $limit, $mostrarEliminadas);
        $totalEmpresas = $empresaModel->contarEmpresas($mostrarEliminadas);
        $totalPaginas = ceil($totalEmpresas / $limit);
    
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'empresas' => $empresas,
            'totalPaginas' => $totalPaginas
        ]);
        exit;
    }
    

    // Muestra el formulario de creación
    public function create() {
        requireAdmin();

        $this->view('empresas/create');
    }

    // Inserta una empresa de forma dinámica
    public function store() {
        requireAdmin();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empresaModel = new EmpresaModel();
            $data = [];
    
            foreach ($empresaModel->getColumnNames() as $campo) {
                $data[$campo] = $_POST[$campo] ?? null;
            }
    
            // ✅ Validación del campo teléfono
            if (isset($data['telefono']) && !preg_match('/^\+?[0-9\s\-]{7,20}$/', $data['telefono'])) {
                $error = "❌ El teléfono introducido no es válido. Solo se permiten números, espacios y guiones.";
                $this->view('empresas/create', ['campos' => $empresaModel->getColumnNames(), 'error' => $error, 'valores' => $data]);
                return;
            }
    
            $empresaModel->insert($data);
            header('Location: index.php?controller=empresa');
            exit;
        }
    
        header('Location: index.php?controller=empresa&action=create');
        exit;
    }
    
    
   
    public function edit() {
        requireAdmin();

        if (!isset($_GET['id'])) {
            die('ID de empresa no especificado.');
        }

        $id = intval($_GET['id']);
        $empresaModel = new EmpresaModel();
        $empresa = $empresaModel->getById($id);

        if (!$empresa) {
            die('Empresa no encontrada.');
        }

        $this->view('empresas/edit', ['empresa' => $empresa]);
    }
    public function update() {
        requireAdmin();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            $empresaModel = new EmpresaModel();
    
            $data = [];
            foreach ($empresaModel->getColumnNames() as $campo) {
                $data[$campo] = $_POST[$campo] ?? null;
            }
    
            $empresaModel->update($id, $data);
    
            header("Location: index.php?controller=empresa");
            exit;
        }
    
        header("Location: index.php?controller=empresa&action=index");
        exit;
    }
    public function update_inline() {
        requireAdmin(); // Validación de rol
    
        // Leer JSON desde la petición
        $input = json_decode(file_get_contents('php://input'), true);
    
        // Verificamos si vienen datos
        if (!isset($input['id']) || !isset($input['datos']) || !is_array($input['datos'])) {
            echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
            return;
        }
    
        $id = $input['id'];
        $data = $input['datos'];
    
        // Validaciones básicas (puedes añadir más si quieres)
        foreach ($data as $campo => $valor) {
            if (trim($valor) === '') {
                echo json_encode(['success' => false, 'error' => "Campo $campo vacío"]);
                return;
            }
        }
    
        // Carga modelo y actualiza
        $empresaModel = new EmpresaModel();
        $success = $empresaModel->update($id, $data);
    
        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo actualizar']);
        }
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
        $limite = 10;
        $offset = ($pagina - 1) * $limite;
    
        $empresaModel = new EmpresaModel();
        $empresas = $empresaModel->buscarEmpresas($filtros, $offset, $limite, $mostrarEliminadas);
        $total = $empresaModel->contarEmpresasFiltradas($filtros, $mostrarEliminadas);
        $totalPaginas = ceil($total / $limite);
    
        echo json_encode([
            'success' => true,
            'empresas' => $empresas,
            'totalPaginas' => $totalPaginas
        ]);
        exit;
    }
    
    public function exportar_excel() {
        requireLogin();
    
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Método no permitido";
            exit;
        }
    
        $filtros = json_decode($_POST['filtros_json'] ?? '{}', true) ?? [];

        $empresaModel = new EmpresaModel();
        $empresas = $empresaModel->buscarEmpresas($filtros, 0, 10000); // sin paginación, máximo 10k registros
    
        // 🧾 Cabeceras para forzar descarga Excel
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=empresas_exportadas.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
    
        echo "<table border='1'>";
        if (!empty($empresas)) {
            // Encabezados
            echo "<tr>";
            foreach (array_keys($empresas[0]) as $col) {
                if ($col !== 'eliminado') {
                    echo "<th>" . htmlspecialchars(strtoupper($col)) . "</th>";
                }
            }
            echo "</tr>";
    
            // Filas
            foreach ($empresas as $empresa) {
                echo "<tr>";
                foreach ($empresa as $key => $valor) {
                    if ($key !== 'eliminado') {
                        echo "<td>" . htmlspecialchars($valor) . "</td>";
                    }
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10'>Sin resultados para exportar</td></tr>";
        }
        echo "</table>";
        exit;
    }
    public function softDelete() {
        requireAdmin();
    
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
    
        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
            return;
        }
    
        $empresaModel = new EmpresaModel();
        $success = $empresaModel->softDelete($id);
    
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
    
        $empresaModel = new EmpresaModel();
        $success = $empresaModel->restaurar($id);
    
        echo json_encode(['success' => $success]);
    }
    
    
    
    

    

}
