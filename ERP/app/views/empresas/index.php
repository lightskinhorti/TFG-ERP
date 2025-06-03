<!-- Estilos y scripts necesarios -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="/TFG/ERP/public/css/estilos.css">
<script src="/TFG/ERP/public/js/flash.js"></script>
<script src="/TFG/ERP/public/js/empresas.js"></script>

<div class="container">
<a href="index.php?controller=dashboard&action=index" 
   class="btn btn-dark position-fixed" 
   style="top: 20px; right: 20px; z-index: 1000;">
   ⬅️ Dashboard
</a>

    <h1 class="mb-4">📋 Empresas registradas <small class="text-muted">(Edición Inline)</small></h1>

    <!-- Mensaje flash global -->
    <div id="flash-message" style="display: none;"></div>

    <!-- Botón exportar -->
    <form id="form-exportar" method="POST" action="index.php?controller=empresa&action=exportar_excel" target="_blank" class="mb-3">
        <input type="hidden" name="filtros_json" id="filtros_json">
        <button type="submit" class="btn btn-outline-primary">
            📤 Exportar a Excel
        </button>
    </form>

    <!-- Botones de acción -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="index.php?controller=empresa&action=create" class="btn btn-success">
            ➕ Crear nueva empresa
        </a>
        <button id="toggle-eliminadas" class="btn btn-outline-secondary">
            👁️ Ver eliminadas
        </button>
    </div>

    <!-- Tabla de empresas -->
    <div class="table-responsive card p-3">
        <table class="table table-bordered table-hover align-middle" id="tabla-empresas">
            <thead class="table-light">
                <tr>
                    <?php foreach (array_keys($empresas[0]) as $col): ?>
                        <th><?= ucfirst(htmlspecialchars($col)) ?></th>
                    <?php endforeach; ?>
                    <th>Acciones</th>
                </tr>
                <tr id="filtros-empresas">
                    <?php foreach (array_keys($empresas[0]) as $col): ?>
                        <?php if (!in_array($col, ['id', 'eliminado'])): ?>
                            <th>
                                <input type="text" class="form-control form-control-sm filtro-columna" data-campo="<?= $col ?>" placeholder="Buscar <?= ucfirst($col) ?>">
                            </th>
                        <?php else: ?>
                            <th></th>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empresas as $empresa): ?>
                    <tr data-id="<?= $empresa['id'] ?>">
                        <?php foreach ($empresa as $campo => $valor): ?>
                            <td class="<?= $campo === 'id' ? 'no-editable' : 'editable' ?>" data-campo="<?= $campo ?>">
                                <span class="texto"><?= htmlspecialchars($valor) ?></span>
                                <?php if ($campo !== 'id'): ?>
                                    <input class="editor form-control form-control-sm mt-1" type="text" name="<?= $campo ?>" value="<?= htmlspecialchars($valor) ?>" style="display: none;">
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                        <td class="text-nowrap">
                            <button class="btn btn-sm btn-outline-primary btn-editar">✏️</button>
                            <button class="btn btn-sm btn-success btn-guardar" style="display: none;">💾</button>
                            <button class="btn btn-sm btn-secondary btn-cancelar" style="display: none;">❌</button>
                            <button class="btn btn-sm btn-danger btn-eliminar ms-1">🗑️</button>
                            <?php if (!empty($mostrarEliminadas)): ?>
                                <button class="btn btn-sm btn-outline-dark btn-restaurar ms-1">♻️ Restaurar</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div id="paginacion" class="mt-4"></div>

</div>
