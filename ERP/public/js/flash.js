/**
 * FLASH.JS
 * Sistema simple de mensajes flotantes tipo toast (para éxito o error)
 */

/**
 * Muestra un mensaje flash (tipo notificación visual)
 * @param {string} mensaje - El texto del mensaje
 * @param {string} tipo - 'success' o 'error'
 */
function mostrarMensaje(mensaje, tipo = 'success') {
    const flash = document.getElementById('flash-message');
    if (!flash) return;
  
    flash.textContent = mensaje;
    flash.style.display = 'block';
    flash.style.backgroundColor = tipo === 'success' ? '#d4edda' : '#f8d7da';
    flash.style.color = tipo === 'success' ? '#155724' : '#721c24';
    flash.style.border = tipo === 'success' ? '1px solid #c3e6cb' : '1px solid #f5c6cb';
  
    setTimeout(() => {
      flash.style.display = 'none';
    }, 3000);
  }
  