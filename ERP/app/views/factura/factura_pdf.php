<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      color: #333;
      margin: 0;
      padding: 0;
    }

    .factura {
      padding: 40px;
      max-width: 700px;
      margin: 0 auto;
      border: 1px solid #ccc;
    }

    h1 {
      font-size: 20px;
      margin-bottom: 20px;
      text-align: center;
      border-bottom: 2px solid #000;
      padding-bottom: 10px;
    }

    .info-cliente, .info-pedido {
      margin-bottom: 20px;
    }

    .info-pedido table {
      width: 100%;
      border-collapse: collapse;
    }

    .info-pedido th, .info-pedido td {
      text-align: left;
      padding: 8px;
      border-bottom: 1px solid #ddd;
    }

    .total {
      text-align: right;
      font-size: 14px;
      font-weight: bold;
      margin-top: 30px;
    }

    .footer {
      margin-top: 40px;
      font-size: 10px;
      text-align: center;
      color: #888;
    }
  </style>
</head>
<body>
  <div class="factura">
  <div style="display: flex; justify-content: space-between; align-items: flex-start;">
  <h1>Factura simplificada</h1>
  <div style="text-align: right; font-size: 12px;">
    <strong><?= htmlspecialchars($empresa['nombre']) ?></strong><br>
    CIF: <?= htmlspecialchars($empresa['cif_nif']) ?><br>
    Tel: <?= htmlspecialchars($empresa['telefono']) ?><br>
    Dirección: <?= htmlspecialchars($empresa['direccion']) ?>
  </div>
</div>

    <div class="info-cliente">
      <strong>ID Pedido:</strong> <?= $pedido['id'] ?><br>
      <strong>Fecha:</strong> <?= date('d/m/Y', strtotime($pedido['fecha_pedido'])) ?><br>
      <strong>Estado:</strong> <?= ucfirst($pedido['estado']) ?>
    </div>

    <div class="info-pedido">
      <table>
        <tr>
          <th>Campo</th>
          <th>Valor</th>
        </tr>
        <tr>
          <td><strong>Empresa ID</strong></td>
          <td><?= $pedido['empresa_id'] ?></td>
        </tr>
        <tr>
          <td><strong>Observaciones</strong></td>
          <td><?= $pedido['observaciones'] ?: '—' ?></td>
        </tr>
        <?php
$base = (float)$pedido['importe'];
$iva = $base * 0.21;
$total = $base + $iva;
?>

<tr>
  <td><strong>Importe (base)</strong></td>
  <td><?= number_format($base, 2) ?> €</td>
</tr>
<tr>
  <td><strong>IVA (21%)</strong></td>
  <td><?= number_format($iva, 2) ?> €</td>
</tr>
<tr>
  <td><strong>Total con IVA</strong></td>
  <td><?= number_format($total, 2) ?> €</td>
</tr>
      </table>
    </div>

    <div class="total">Total a pagar: <?= number_format($total, 2) ?> €</div>


    <div class="footer">
      Factura generada automáticamente por el sistema ERP · <?= date('d/m/Y H:i') ?>
    </div>
  </div>
</body>
</html>
