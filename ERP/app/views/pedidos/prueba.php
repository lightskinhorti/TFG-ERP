<!-- Estilos y scripts necesarios -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="/TFG/ERP/public/css/estilos.css">
<script src="/TFG/ERP/public/js/flash.js"></script>
<script src="/TFG/ERP/public/js/pedidos.js" defer></script>
<script>console.log("Se ejecuta esta linea")</script>

<div class="container">
  <!-- Botón de volver al Dashboard -->
  <a href="index.php?controller=dashboard&action=index" 
     class="btn btn-dark position-fixed" 
     style="top: 20px; right: 20px; z-index: 1000;">
     ⬅️ Dashboard
  </a>

  <!-- Título -->
  <h1 class="mb-4">📦 Gestión de Pedidos <small class="text-muted">(Edición Inline)</small></h1>

  <!-- Mensaje flash global -->
  <div id="flash-message" style="display: none;"></div>

  <!-- Botones de acción -->
  <div class="d-flex justify-content-between align-items-center mb-4">
  <button class="btn btn-primary" id="btnCrearPedido" data-bs-toggle="modal" data-bs-target="#modalCrearPedido">
    ➕ Crear nuevo pedido
</button>

    <button id="toggle-eliminadas" class="btn btn-outline-secondary">
        👁️ Ver eliminados
    </button>
  </div>

  <!-- Tabla de pedidos -->
  <div class="table-responsive card p-3">
  <?php
// Incluir campo empresa_nombre para que coincida con el tbody
if (!in_array('empresa_nombre', $campos)) {
    $campos[] = 'empresa_nombre';
}
?>
    <table class="table table-bordered table-hover align-middle" id="tabla-pedidos">
      <thead class="table-light">
        <tr>
          <?php foreach ($campos as $col): ?>
            <th><?= ucfirst(str_replace('_', ' ', htmlspecialchars($col))) ?></th>
          <?php endforeach; ?>
          <th>Acciones</th>
        </tr>
        <tr id="filtros-pedidos">
  <?php foreach ($campos as $col): ?>
    <?php if (!in_array($col, ['id', 'eliminado'])): ?>
      <th>
        <?php if (str_contains($col, 'fecha')): ?>
          <input type="date" class="form-control form-control-sm filtro-columna" data-campo="<?= $col ?>" placeholder="Buscar <?= ucfirst($col) ?>">
        <?php else: ?>
          <input type="text" class="form-control form-control-sm filtro-columna" data-campo="<?= $col ?>" placeholder="Buscar <?= ucfirst($col) ?>">
        <?php endif; ?>
      </th>
    <?php else: ?>
      <th></th>
    <?php endif; ?>
  <?php endforeach; ?>
  <th></th>
</tr>
      </thead>
      <tbody id="cuerpoPedidos">
  <?php foreach ($pedidos as $pedido): ?>
    <tr id="fila-<?= $pedido['id'] ?>" data-id="<?= $pedido['id'] ?>">
      <?php foreach ($campos as $campo): ?>
        <?php if (!in_array($campo, ['id', 'eliminado', 'fecha_creacion', 'fecha_actualizacion'])): ?>
          <td class="editable" data-campo="<?= $campo ?>">
            <span class="texto"><?= htmlspecialchars($pedido[$campo] ?? '') ?></span>
            <input class="editor form-control form-control-sm mt-1"
                   type="<?= str_contains($campo, 'fecha') ? 'date' : 'text' ?>"
                   name="<?= $campo ?>"
                   value="<?= htmlspecialchars($pedido[$campo] ?? '') ?>"
                   style="display: none;">
          </td>
        <?php endif; ?>
      <?php endforeach; ?>
      <td class="text-nowrap">
        <button class="btn btn-sm btn-outline-primary btn-editar" data-id="<?= $pedido['id'] ?>">✏️</button>
        <button class="btn btn-sm btn-success btn-guardar" data-id="<?= $pedido['id'] ?>" style="display: none;">💾</button>
        <button class="btn btn-sm btn-secondary btn-cancelar" data-id="<?= $pedido['id'] ?>" style="display: none;">❌</button>
        <button class="btn btn-sm btn-danger btn-eliminar ms-1">🗑️</button>
        <?php if (!empty($mostrarEliminados)): ?>
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

<!-- Modal de nuevo pedido -->
<div class="modal fade" id="modalCrearPedido" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="formNuevoPedido">
        <div class="modal-header">
          <h5 class="modal-title">➕ Nuevo Pedido</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row">
          <?php foreach ($campos as $campo): ?>
            <?php if (!in_array($campo, ['id', 'eliminado', 'fecha_creacion', 'fecha_actualizacion'])): ?>
              <div class="mb-3 col-md-6">
                <label class="form-label"><?= ucfirst(str_replace('_', ' ', $campo)) ?>:</label>
                <?php if (str_contains($campo, 'fecha')): ?>
                  <input type="date" name="<?= $campo ?>" class="form-control" required>
                <?php elseif ($campo === 'empresa_id'): ?>
                  <input type="number" name="<?= $campo ?>" class="form-control" required>
                <?php else: ?>
                  <input type="text" name="<?= $campo ?>" class="form-control" required>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">✅ Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>
