<?php
require_once 'core/Controller.php';
require_once 'core/Auth.php';
require_once 'app/models/EmpresaModel.php';
require_once 'app/models/UserModel.php';
require_once 'app/models/PedidoModel.php';

/**
 * Controlador del panel de control (dashboard)
 * Muestra la bienvenida y estadísticas clave del sistema (KPIs)
 */
class DashboardController extends Controller {
    public function index() {
        $user = requireLogin();

        // Obtener datos del usuario logueado
        $user = $_SESSION['user'];

        // Obtener KPIs desde los modelos
        $empresaModel = new EmpresaModel();
        $userModel = new UserModel();
        $pedidoModel = new PedidoModel();

        $totalEmpresas = $empresaModel->contarEmpresasActivas();
        $totalUsuarios = $userModel->contarUsuarios();
        $totalFacturado = $pedidoModel->obtenerTotalFacturado();
        $pedidosConfirmados = $pedidoModel->contarPedidosConfirmados();
        $ultimaActualizacion = date('d/m/Y H:i');

        // Cargar la vista dashboard y pasar los datos
        $this->view('dashboard/index', [
            'user' => $user,
            'totalEmpresas' => $totalEmpresas,
            'totalUsuarios' => $totalUsuarios,
            'totalFacturado' => $totalFacturado,
            'pedidosConfirmados' => $pedidosConfirmados,
            'ultimaActualizacion' => $ultimaActualizacion
        ]);
    }
}
