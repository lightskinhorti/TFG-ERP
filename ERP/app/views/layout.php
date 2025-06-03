<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'ERP' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/TFG/ERP/public/css/estilos.css">

    <style>
        body { padding: 2rem; }
    </style>
</head>
<body>

    <!-- Botón modo oscuro global -->
    <div class="container mb-3 text-end">
        <button id="modoOscuroToggle" class="btn btn-outline-secondary btn-sm">
            🌓 Modo oscuro
        </button>
    </div>

    <!-- Contenido principal -->
    <?= $content ?>

    <!-- Script para modo oscuro persistente -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('modoOscuroToggle');

            // Aplicar modo oscuro si estaba activado
            if (localStorage.getItem('modoOscuro') === 'true') {
                document.body.classList.add('modo-oscuro');
            }

            // Alternar modo y guardar preferencia
            toggle.addEventListener('click', () => {
                document.body.classList.toggle('modo-oscuro');
                const activo = document.body.classList.contains('modo-oscuro');
                localStorage.setItem('modoOscuro', activo);
            });
        });
    </script>

</body>
</html>
