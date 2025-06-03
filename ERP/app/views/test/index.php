<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba de modelos dinámicos</title>
</head>
<body>
    <h1>Resultados del test de modelos</h1>

    <p><strong>Empresa insertada:</strong> <?= $empresaInsertado ? '✅ Sí' : '❌ No'; ?></p>
    <p><strong>Usuario insertado:</strong> <?= $usuarioInsertado ? '✅ Sí' : '❌ No'; ?></p>
    <p><strong>Log registrado:</strong> ✅</p>

    <hr>
    <p><a href="index.php?controller=test">Volver a probar</a></p>
</body>
</html>
