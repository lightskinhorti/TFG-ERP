<h2>➕ Nueva empresa</h2>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form action="index.php?controller=empresa&action=store" method="POST" class="mb-5">
    <?php
    require_once 'app/models/EmpresaModel.php';
    $empresaModel = new EmpresaModel();

    $campos = $campos ?? $empresaModel->getColumnNames();
    $valores = $valores ?? [];

    foreach ($campos as $campo):
        if ($campo === 'id') continue;

        $label = ucfirst(str_replace('_', ' ', $campo));
        $value = htmlspecialchars($valores[$campo] ?? '');
        $type = 'text';
        if (str_contains($campo, 'email')) $type = 'email';
        if (str_contains($campo, 'telefono')) $type = 'tel';
        if (str_contains($campo, 'direccion')) $type = 'textarea';
        if (str_contains($campo, 'tipo')) $type = 'select';

        echo "<label for='$campo'>$label:</label><br>";

        if ($type === 'select') {
            echo "<select name='$campo' id='$campo'>";
            echo "<option value='cliente'" . ($value === 'cliente' ? ' selected' : '') . ">Cliente</option>";
            echo "<option value='proveedor'" . ($value === 'proveedor' ? ' selected' : '') . ">Proveedor</option>";
            echo "</select><br><br>";
        }
        elseif ($type === 'textarea') {
            echo "<textarea name='$campo' id='$campo' rows='3' style='width: 100%;'>$value</textarea><br><br>";
        }
        else {
            echo "<input type='$type' name='$campo' id='$campo' style='width: 100%;' value='$value'><br><br>";
        }
    endforeach;
    ?>

    <button type="submit" class="btn btn-success">✅ Guardar empresa</button>
    <a href="index.php?controller=empresa" class="btn btn-secondary">↩️ Volver</a>
</form>
