<h2>➕ Nuevo pedido</h2>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form action="index.php?controller=pedido&action=store" method="POST" class="mb-5">
    <?php
    require_once 'app/models/PedidoModel.php';
    $pedidoModel = new PedidoModel();

    $campos = $campos ?? $pedidoModel->getColumnNames();
    $valores = $valores ?? [];

    foreach ($campos as $campo):
        if (in_array($campo, ['id', 'eliminado', 'fecha_creacion', 'fecha_actualizacion'])) continue;

        $label = ucfirst(str_replace('_', ' ', $campo));
        $value = htmlspecialchars($valores[$campo] ?? '');
        $type = 'text';

        if (str_contains($campo, 'fecha')) $type = 'date';
        if ($campo === 'empresa_id') $type = 'number';
        if ($campo === 'estado') $type = 'select';

        echo "<label for='$campo'>$label:</label><br>";

        if ($type === 'select') {
            echo "<select name='$campo' id='$campo' class='form-control'>";
            echo "<option value='pendiente'" . ($value === 'pendiente' ? ' selected' : '') . ">Pendiente</option>";
            echo "<option value='confirmado'" . ($value === 'confirmado' ? ' selected' : '') . ">Confirmado</option>";
            echo "<option value='cancelado'" . ($value === 'cancelado' ? ' selected' : '') . ">Cancelado</option>";
            echo "</select><br><br>";
        } else {
            echo "<input type='$type' name='$campo' id='$campo' class='form-control' value='$value'><br><br>";
        }

    endforeach;
    ?>

    <button type="submit" class="btn btn-success">✅ Guardar pedido</button>
    <a href="index.php?controller=pedido" class="btn btn-secondary">↩️ Volver</a>
</form>
