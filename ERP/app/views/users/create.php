<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="/TFG/ERP/public/css/estilos.css">

<div class="container mt-5">

    <!-- Botón volver al dashboard -->
    <a href="index.php?controller=dashboard&action=index" class="btn btn-outline-dark mb-4">
        ⬅️ Volver al dashboard
    </a>

    <!-- Título -->
    <h2 class="mb-4">👤 Crear nuevo usuario</h2>

    <!-- Mensaje de error -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Formulario -->
    <form method="POST" action="index.php?controller=user&action=store" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>

        <div class="mb-4">
            <label for="role" class="form-label">Rol</label>
            <select name="role" id="role" class="form-select" required>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= htmlspecialchars($role) ?>"><?= ucfirst($role) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            ✅ Crear usuario
        </button>
    </form>

</div>
