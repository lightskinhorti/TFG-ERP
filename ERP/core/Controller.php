<?php
// Clase base Controller
class Controller {
    // Método para cargar vistas y pasarles datos
    public function view($view, $data = []) {
        extract($data); // Convierte cada clave del array en una variable
    
        // Captura el contenido de la vista específica
        ob_start();
        require __DIR__ . "/../app/views/$view.php";
        $content = ob_get_clean();
    
        // Carga el layout base e inyecta $content
        require __DIR__ . "/../app/views/layout.php";
    }
    
}