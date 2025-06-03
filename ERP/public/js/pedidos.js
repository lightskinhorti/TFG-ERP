let filtrosPedidos = {};
let mostrarEliminadasPedidos = false;

document.addEventListener('DOMContentLoaded', function () {
  inicializarPedidos();

  const form = document.getElementById('formNuevoPedido');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = new FormData(form);

      fetch('index.php?controller=pedido&action=store_ajax', {
        method: 'POST',
        body: formData
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            mostrarMensaje(data.mensaje || "Pedido creado correctamente", "success");
            form.reset();
            const modal = bootstrap.Modal.getInstance(document.getElementById("modalCrearPedido"));
            if (modal) modal.hide();
            cargarPaginaPedidos(1);
          } else {
            mostrarMensaje(data.mensaje || "Error al crear pedido", "error");
          }
        })
        .catch(() => mostrarMensaje("Error en la petición", "error"));
    });
  }
});

function inicializarPedidos() {
  cargarPaginaPedidos(1);

  document.querySelectorAll('.filtro-columna').forEach(input => {
    input.addEventListener('input', () => {
      const filtros = {};
      document.querySelectorAll('.filtro-columna').forEach(f => {
        const campo = f.dataset.campo;
        const valor = f.value.trim();
        if (valor !== '') filtros[campo] = valor;
      });
      buscarPedidos(filtros, 1);
    });
  });

  const toggleBtn = document.getElementById('toggle-eliminadas');
  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      mostrarEliminadasPedidos = !mostrarEliminadasPedidos;
      toggleBtn.textContent = mostrarEliminadasPedidos ? '👁️ Ver activas' : '👁️ Ver eliminadas';
      if (Object.keys(filtrosPedidos).length > 0) {
        buscarPedidos(filtrosPedidos, 1);
      } else {
        cargarPaginaPedidos(1);
      }
    });
  }
}

function cargarPaginaPedidos(pagina) {
  fetch(`index.php?controller=pedido&action=paginado_ajax&page=${pagina}&mostrarEliminadas=${mostrarEliminadasPedidos ? 1 : 0}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        actualizarTablaPedidos(data.pedidos);
        actualizarPaginacionPedidos(data.totalPaginas, pagina);
      } else {
        mostrarMensaje("Error al cargar pedidos", "error");
      }
    })
    .catch(() => mostrarMensaje("Error de conexión", "error"));
}

function actualizarTablaPedidos(pedidos) {
  const tbody = document.querySelector('#tabla-pedidos tbody');
  tbody.innerHTML = '';

  pedidos.forEach(pedido => {
    const tr = document.createElement('tr');
    tr.setAttribute('data-id', pedido.id);

    Object.keys(pedido).forEach(campo => {
      const td = document.createElement('td');
      td.classList.add(campo === 'id' ? 'no-editable' : 'editable');
      td.setAttribute('data-campo', campo);

      const span = document.createElement('span');
      span.classList.add('texto');
      span.textContent = pedido[campo];
      td.appendChild(span);

      if (campo !== 'id') {
        const input = document.createElement('input');
        input.classList.add('editor', 'form-control', 'form-control-sm', 'mt-1');
        input.style.display = 'none';
        input.value = pedido[campo];
        input.setAttribute('name', campo);
        input.setAttribute('type', campo.includes('fecha') ? 'date' : 'text');
        td.appendChild(input);
      }

      tr.appendChild(td);
    });

    const tdAcciones = document.createElement('td');
    if (mostrarEliminadasPedidos) {
      tdAcciones.innerHTML = `<button class="btn-restaurar btn btn-sm btn-secondary ms-1">♻️ Restaurar</button>`;
    } else {
      tdAcciones.innerHTML = `
        <button class="btn-editar">✏️</button>
        <button class="btn-guardar" style="display: none;">💾</button>
        <button class="btn-cancelar" style="display: none;">❌</button>
        <button class="btn-eliminar btn btn-sm btn-danger ms-1">🗑️</button>
        <a href="index.php?controller=factura&action=generar&id=${pedido.id}" 
       target="_blank" 
       class="btn btn-sm btn-outline-dark ms-1">
       📄 Factura PDF
    </a>
      `;
    }

    tr.appendChild(tdAcciones);
    tbody.appendChild(tr);
  });

  asignarEventosPedidos();
}

function actualizarPaginacionPedidos(totalPaginas, paginaActual) {
  const paginacion = document.getElementById('paginacion');
  paginacion.innerHTML = '';
  for (let i = 1; i <= totalPaginas; i++) {
    const btn = document.createElement('button');
    btn.textContent = i;
    btn.disabled = (i === paginaActual);
    btn.addEventListener('click', () => {
      if (Object.keys(filtrosPedidos).length > 0) {
        buscarPedidos(filtrosPedidos, i);
      } else {
        cargarPaginaPedidos(i);
      }
    });
    paginacion.appendChild(btn);
  }
}

function asignarEventosPedidos() {
  document.querySelectorAll('.btn-editar').forEach(btn => {
    btn.addEventListener('click', () => {
      const row = btn.closest('tr');
      row.classList.add('editando');
      row.querySelectorAll('.editable').forEach(td => {
        const span = td.querySelector('.texto');
        const input = td.querySelector('.editor');
        if (input && span) {
          input.setAttribute('data-original', input.value);
          span.style.display = 'none';
          input.style.display = 'block';
        }
      });
      toggleBotones(row, true);
    });
  });

  document.querySelectorAll('.btn-cancelar').forEach(btn => {
    btn.addEventListener('click', () => {
      const row = btn.closest('tr');
      row.classList.remove('editando');
      row.querySelectorAll('.editable').forEach(td => {
        const span = td.querySelector('.texto');
        const input = td.querySelector('.editor');
        if (input && span) {
          input.value = input.getAttribute('data-original') || '';
          span.textContent = input.value;
          input.style.display = 'none';
          span.style.display = 'inline';
        }
      });
      toggleBotones(row, false);
    });
  });

  document.querySelectorAll('.btn-guardar').forEach(btn => {
    btn.addEventListener('click', () => {
      const row = btn.closest('tr');
      const id = row.getAttribute('data-id');
      const datos = {};

      row.querySelectorAll('.editable').forEach(td => {
        const campo = td.getAttribute('data-campo');
        const input = td.querySelector('.editor');
        if (input) datos[campo] = input.value.trim();
      });

      fetch(`index.php?controller=pedido&action=update_inline`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, datos })
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            row.classList.remove('editando');
            row.querySelectorAll('.editable').forEach(td => {
              const span = td.querySelector('.texto');
              const input = td.querySelector('.editor');
              if (input && span) {
                span.textContent = input.value;
                input.style.display = 'none';
                span.style.display = 'inline';
              }
            });
            mostrarMensaje("Pedido actualizado correctamente", 'success');
            toggleBotones(row, false);
          } else {
            mostrarMensaje(data.error || "Error al actualizar pedido", 'error');
          }
        })
        .catch(() => mostrarMensaje("Error al conectar con el servidor", 'error'));
    });
  });

  document.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.addEventListener('click', () => {
      const row = btn.closest('tr');
      const id = row.getAttribute('data-id');
      if (!confirm("¿Estás seguro de que deseas eliminar este pedido?")) return;

      fetch(`index.php?controller=pedido&action=softDelete`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            mostrarMensaje("Pedido eliminado correctamente", "success");
            cargarPaginaPedidos(1);
          } else {
            mostrarMensaje(data.error || "Error al eliminar pedido", "error");
          }
        })
        .catch(() => mostrarMensaje("Error de conexión", "error"));
    });
  });

  document.querySelectorAll('.btn-restaurar').forEach(btn => {
    btn.addEventListener('click', () => {
      const row = btn.closest('tr');
      const id = row.getAttribute('data-id');
      if (!confirm("¿Deseas restaurar este pedido?")) return;

      fetch(`index.php?controller=pedido&action=restaurar`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            mostrarMensaje("Pedido restaurado correctamente", "success");
            cargarPaginaPedidos(1);
          } else {
            mostrarMensaje(data.error || "Error al restaurar pedido", "error");
          }
        })
        .catch(() => mostrarMensaje("Error de conexión", "error"));
    });
  });
}

function toggleBotones(row, editando) {
  row.querySelector('.btn-editar').style.display = editando ? 'none' : 'inline-block';
  row.querySelector('.btn-guardar').style.display = editando ? 'inline-block' : 'none';
  row.querySelector('.btn-cancelar').style.display = editando ? 'inline-block' : 'none';
}

function buscarPedidos(filtros, pagina = 1) {
  filtrosPedidos = filtros;
  fetch('index.php?controller=pedido&action=buscar_ajax', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ filtros, pagina, mostrarEliminadas: mostrarEliminadasPedidos })
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        actualizarTablaPedidos(data.pedidos);
        actualizarPaginacionPedidos(data.totalPaginas, pagina);
      } else {
        mostrarMensaje("Error al filtrar pedidos", "error");
      }
    })
    .catch(() => mostrarMensaje("Error de conexión con el servidor", "error"));
}
