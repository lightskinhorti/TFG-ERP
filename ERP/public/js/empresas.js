/**
 * EMPRESAS.JS
 * Edición inline de empresas con validación, cancelación y feedback visual
 */

let filtrosActivos = {};
let mostrarEliminadas = false;

// ============================
// ⚙️ Inicialización principal
// ============================

function inicializarEmpresas() {
  cargarPaginaEmpresas(1); // Cargar primera página

  // 🎯 Escucha de inputs de filtro en vivo por columna
  document.querySelectorAll('.filtro-columna').forEach(input => {
    input.addEventListener('input', () => {
      const filtros = {};
      document.querySelectorAll('.filtro-columna').forEach(f => {
        const campo = f.dataset.campo;
        const valor = f.value.trim();
        if (valor !== '') filtros[campo] = valor;
      });
      buscarEmpresas(filtros, 1);
    });
  });

  // 📤 Exportación
  const formExportar = document.getElementById('form-exportar');
  if (formExportar) {
    formExportar.addEventListener('submit', function () {
      const filtros = {};
      document.querySelectorAll('.filtro-columna').forEach(f => {
        const campo = f.dataset.campo;
        const valor = f.value.trim();
        if (valor !== '') filtros[campo] = valor;
      });
      document.getElementById('filtros_json').value = JSON.stringify(filtros);
    });
  }

  // 👁️ Toggle mostrar eliminadas
  const toggleBtn = document.getElementById('toggle-eliminadas');
  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      mostrarEliminadas = !mostrarEliminadas;
      toggleBtn.textContent = mostrarEliminadas ? '👁️ Ver activas' : '👁️ Ver eliminadas';
      if (Object.keys(filtrosActivos).length > 0) {
        buscarEmpresas(filtrosActivos, 1);
      } else {
        cargarPaginaEmpresas(1);
      }
    });
  }
}

document.addEventListener('DOMContentLoaded', inicializarEmpresas);

// ============================
// 🧠 Funciones
// ============================

function toggleBotones(row, editando) {
  row.querySelector('.btn-editar').style.display = editando ? 'none' : 'inline-block';
  row.querySelector('.btn-guardar').style.display = editando ? 'inline-block' : 'none';
  row.querySelector('.btn-cancelar').style.display = editando ? 'inline-block' : 'none';
}

function guardarCambios(btn) {
  const row = btn.closest('tr');
  const id = row.getAttribute('data-id');
  const datos = {};
  let hayError = false;

  row.querySelectorAll('.editable').forEach(td => {
    const campo = td.getAttribute('data-campo');
    const input = td.querySelector('.editor');
    if (input) {
      const valor = input.value.trim();
      if (valor === '') {
        mostrarMensaje(`El campo "${campo}" no puede estar vacío`, 'error');
        hayError = true;
      } else if (campo === 'email' && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(valor)) {
        mostrarMensaje(`El campo "${campo}" no es un email válido`, 'error');
        hayError = true;
      }
      datos[campo] = valor;
    }
  });

  if (hayError) return;

  fetch(`index.php?controller=empresa&action=update_inline`, {
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
        mostrarMensaje("Empresa actualizada correctamente", 'success');
        toggleBotones(row, false);
      } else {
        mostrarMensaje(data.error || "Error al actualizar", 'error');
      }
    })
    .catch(() => mostrarMensaje("Error al conectar con el servidor", 'error'));
}

function cargarPaginaEmpresas(pagina) {
  fetch(`index.php?controller=empresa&action=paginado_ajax&page=${pagina}&mostrarEliminadas=${mostrarEliminadas ? 1 : 0}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        actualizarTablaEmpresas(data.empresas);
        actualizarPaginacion(data.totalPaginas, pagina);
      } else {
        mostrarMensaje("Error al cargar empresas", "error");
      }
    })
    .catch(() => mostrarMensaje("Error de conexión con el servidor", "error"));
}

function actualizarTablaEmpresas(empresas) {
  const tbody = document.querySelector('#tabla-empresas tbody');
  tbody.innerHTML = '';

  empresas.forEach(empresa => {
    const tr = document.createElement('tr');
    tr.setAttribute('data-id', empresa.id);

    Object.keys(empresa).forEach(campo => {
      const td = document.createElement('td');
      td.classList.add(campo === 'id' ? 'no-editable' : 'editable');
      td.setAttribute('data-campo', campo);

      const span = document.createElement('span');
      span.classList.add('texto');
      span.textContent = empresa[campo];
      td.appendChild(span);

      if (campo !== 'id') {
        const input = document.createElement('input');
        input.classList.add('editor');
        input.style.display = 'none';
        input.value = empresa[campo];
        input.setAttribute('name', campo);
        td.appendChild(input);
      }

      tr.appendChild(td);
    });

    // Celda de acciones
const tdAcciones = document.createElement('td');

// Si estamos viendo eliminadas, mostramos solo el botón de restaurar
if (mostrarEliminadas) {
  tdAcciones.innerHTML = `
    <button class="btn-restaurar btn btn-sm btn-secondary ms-1">♻️ Restaurar</button>
  `;
} else {
  tdAcciones.innerHTML = `
    <button class="btn-editar">✏️</button>
    <button class="btn-guardar" style="display: none;">💾</button>
    <button class="btn-cancelar" style="display: none;">❌</button>
    <button class="btn-eliminar btn btn-sm btn-danger ms-1">🗑️</button>
  `;
}

tr.appendChild(tdAcciones);

    tbody.appendChild(tr);
  });

  asignarEventosEmpresas();
}

function actualizarPaginacion(totalPaginas, paginaActual) {
  const paginacion = document.getElementById('paginacion');
  paginacion.innerHTML = '';
  for (let i = 1; i <= totalPaginas; i++) {
    const btn = document.createElement('button');
    btn.textContent = i;
    btn.disabled = (i === paginaActual);
    btn.addEventListener('click', () => {
      if (Object.keys(filtrosActivos).length > 0) {
        buscarEmpresas(filtrosActivos, i);
      } else {
        cargarPaginaEmpresas(i);
      }
    });
    paginacion.appendChild(btn);
  }
}

function asignarEventosEmpresas() {
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
    btn.addEventListener('click', () => guardarCambios(btn));
  });

  document.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.addEventListener('click', () => {
      const row = btn.closest('tr');
      const id = row.getAttribute('data-id');
      if (!confirm("¿Estás seguro de que deseas eliminar esta empresa?")) return;

      fetch(`index.php?controller=empresa&action=softDelete`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            mostrarMensaje("Empresa eliminada correctamente", "success");
            cargarPaginaEmpresas(1);
          } else {
            mostrarMensaje(data.error || "Error al eliminar empresa", "error");
          }
        })
        .catch(() => mostrarMensaje("Error de conexión", "error"));
    });
  });
  document.querySelectorAll('.btn-restaurar').forEach(btn => {
    btn.addEventListener('click', () => {
      const row = btn.closest('tr');
      const id = row.getAttribute('data-id');
  
      if (!confirm("¿Deseas restaurar esta empresa?")) return;
  
      fetch(`index.php?controller=empresa&action=restaurar`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            mostrarMensaje("Empresa restaurada correctamente", "success");
            cargarPaginaEmpresas(1);
          } else {
            mostrarMensaje(data.error || "Error al restaurar empresa", "error");
          }
        })
        .catch(() => mostrarMensaje("Error de conexión", "error"));
    });
  });
}

function buscarEmpresas(filtros, pagina = 1) {
  filtrosActivos = filtros;
  fetch('index.php?controller=empresa&action=buscar_ajax', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ filtros, pagina, mostrarEliminadas })
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        actualizarTablaEmpresas(data.empresas);
        actualizarPaginacion(data.totalPaginas, pagina);
      } else {
        mostrarMensaje("Error al filtrar empresas", "error");
      }
    })
    .catch(() => mostrarMensaje("Error de conexión con el servidor", "error"));
}
