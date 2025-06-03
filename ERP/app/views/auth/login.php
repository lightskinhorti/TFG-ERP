<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Iniciar sesión</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contraseña:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
    <script>
document.querySelector('form').addEventListener('submit', function(e) {
    const email = document.querySelector('input[name="email"]');
    const password = document.querySelector('input[name="password"]');

    if (!email.value || !password.value) {
        e.preventDefault();
        alert('Debes completar todos los campos.');
    } else if (!/\S+@\S+\.\S+/.test(email.value)) {
        e.preventDefault();
        alert('Introduce un correo válido.');
    }
});
</script>
</body>
</html>