<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de control</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/TFG/ERP/public/css/estilos.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-sm p-5 text-center">

            <!-- Bienvenida -->
            <h1 class="mb-3">👋 Bienvenido, <strong><?= htmlspecialchars($user['name']) ?></strong></h1>
            <p class="text-muted mb-4">Este es tu panel de control.</p>

            <!-- TARJETAS KPI -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card p-4 shadow-sm text-center border-start border-primary border-4 fade-in">
                        <div class="fs-3 mb-2">🏢</div>
                        <h6 class="text-muted mb-1">Empresas activas</h6>
                        <h2 class="text-primary"><?= $totalEmpresas ?></h2>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-4 shadow-sm text-center border-start border-success border-4 fade-in">
                        <div class="fs-3 mb-2">👤</div>
                        <h6 class="text-muted mb-1">Usuarios registrados</h6>
                        <h2 class="text-success"><?= $totalUsuarios ?></h2>
                    </div>
                </div>
            </div>

            <!-- Fecha de actualización -->
            <small class="text-muted d-block mb-4">📅 Actualizado: <?= $ultimaActualizacion ?></small>

            <!-- BOTONES ADMIN -->
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                <div class="d-grid gap-3 col-6 mx-auto mb-4">
                    <a href="index.php?controller=user&action=create" class="btn btn-outline-dark btn-lg">
                        ➕ Crear nuevo usuario
                    </a>
                    <a href="index.php?controller=empresa" class="btn btn-primary btn-lg">
                        🏢 Ir a Gestión de Empresas
                    </a>
                    <a href="index.php?controller=pedido" class="btn btn-secondary btn-lg">
                        📦 Ir a Gestión de Pedidos
                    </a>

                </div>
            <?php endif; ?>

            <!-- Cerrar sesión -->
            <a href="index.php?controller=auth&action=logout" class="btn btn-danger btn-lg">
                🔒 Cerrar sesión
            </a>

        </div>
    </div>
</body>
</html>
