<?php
require_once 'core/Controller.php';
require_once 'app/models/PedidoModel.php';
require_once 'app/models/EmpresaModel.php';
require_once 'core/Auth.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class FacturaController extends Controller {

    public function generar() {
        requireLogin();

        // Validar ID
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            die('ID de pedido inválido.');
        }

        $pedidoModel = new PedidoModel();
        $empresaModel = new EmpresaModel();

        $idPedido = (int) $_GET['id'];
        $pedido = $pedidoModel->getById($idPedido);

        if (!$pedido) {
            die("Pedido no encontrado.");
        }

        $empresa = $empresaModel->getById($pedido['empresa_id']);
        if (!$empresa) {
            die("Empresa asociada no encontrada.");
        }

        // Preparar PDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true); // por si usas imágenes o fuentes externas

        $dompdf = new Dompdf($options);

        ob_start();
        include 'app/views/factura/factura_pdf.php'; // Usa $pedido y $empresa directamente
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $nombreArchivo = "factura_pedido_" . $pedido['id'] . ".pdf";
        $dompdf->stream($nombreArchivo, ["Attachment" => false]);
        exit;
    }
}
