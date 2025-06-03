<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Carga la configuración de conexión a base de datos
require_once 'config/database.php';

// Carga las clases base del controlador y modelo
require_once 'core/Controller.php';
require_once 'core/Model.php';

// Obtiene el nombre del controlador desde la URL o usa "auth" por defecto
$controller = $_GET['controller'] ?? 'auth';
$controllerFile = 'app/controllers/' . ucfirst($controller) . 'Controller.php';

// Verifica si existe el archivo del controlador
if (file_exists($controllerFile)) {
    require_once $controllerFile; // Incluye el archivo del controlador
    $controllerName = ucfirst($controller) . 'Controller'; // Nombre de la clase controlador
    $ctrl = new $controllerName(); // Crea instancia

    // Si se pasa ?action=... lo usamos, si no, usamos el primer método público del controlador
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    } else {
        $methods = get_class_methods($ctrl);
        $action = $methods[0] ?? null;
    }

    // Ejecuta el método si existe
    if ($action && method_exists($ctrl, $action)) {
        $ctrl->$action();
    } else {
        echo "⚠️ Acción no encontrada en el controlador.";
    }
} else {
    echo "❌ Controlador no encontrado.";
}
