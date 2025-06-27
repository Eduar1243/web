// Lógica completa para carrito y formulario
// Espera a que todo el contenido del DOM esté cargado antes de ejecutar el código
// Esto asegura que los elementos HTML estén disponibles para manipulación
document.addEventListener("DOMContentLoaded", () => {
  console.log("JavaScript cargado correctamente");
  
  const carrito = []; // Arreglo para almacenar los productos agregados al carrito
  const listaCarrito = document.getElementById("lista-carrito"); // Elemento UL donde se mostrarán los productos del carrito
  const totalSpan = document.getElementById("total"); // Elemento donde se mostrará el total a pagar

  // ===== FUNCIONALIDAD DEL CARRITO =====
  
  // Función para actualizar la visualización del carrito en la página
  function actualizarCarrito() {
    console.log("Actualizando carrito...");
    listaCarrito.innerHTML = "";
    let total = 0;
    const carritoVacio = document.getElementById("carrito-vacio");

    if (carrito.length === 0) {
      carritoVacio.style.display = 'block'; // Muestra mensaje de carrito vacío
      totalSpan.textContent = '0'; // Muestra total en 0
      return;
    } else {
      carritoVacio.style.display = 'none'; // Oculta mensaje de carrito vacío
    }

    carrito.forEach((item, index) => {
      const li = document.createElement("li");
      li.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center");
      
      li.innerHTML = `
        <div>
          <strong>${item.nombre}</strong> - $${item.precio.toLocaleString()}
          <span class="badge bg-secondary ms-2">Cantidad: ${item.cantidad}</span>
        </div>
        <div>
          <button class="btn btn-sm btn-outline-danger me-1" onclick="eliminarDelCarrito(${index})">
            <i class="bi bi-trash"></i> Eliminar
          </button>
        </div>
      `;
      
      listaCarrito.appendChild(li); // Agrega el producto a la lista visual
      total += item.precio * item.cantidad; // Suma el precio total
    });

    totalSpan.textContent = total.toLocaleString(); // Muestra el total formateado
    console.log("Carrito actualizado:", carrito);
  }

  // Función para agregar un producto al carrito
  function agregarAlCarrito(nombre, precio) {
    console.log("Agregando al carrito:", nombre, precio);
    
    // Busca si el producto ya está en el carrito
    const productoExistente = carrito.find(item => item.nombre === nombre);
    
    if (productoExistente) {
      productoExistente.cantidad += 1; // Si existe, aumenta la cantidad
    } else {
      carrito.push({ 
        nombre, 
        precio: parseInt(precio), // Convierte el precio a número
        cantidad: 1 
      });
    }
    
    actualizarCarrito(); // Actualiza la vista del carrito
    mostrarNotificacion(`✅ Producto agregado: ${nombre}`, 'success'); // Notifica al usuario
  }

  // Función global para eliminar un producto del carrito por su índice
  window.eliminarDelCarrito = function(index) {
    console.log("Eliminando del carrito índice:", index);
    carrito.splice(index, 1); // Elimina el producto del arreglo
    actualizarCarrito(); // Actualiza la vista
    mostrarNotificacion('Producto eliminado del carrito', 'info'); // Notifica
  };

  // Conecta los botones de "Agregar al carrito" y "Comprar" de los productos
  function conectarBotonesProductos() {
    console.log("Conectando botones de productos...");
    
    // Selecciona todos los botones con la clase .btn-agregar
    const botonesAgregar = document.querySelectorAll(".btn-agregar");
    console.log("Botones agregar encontrados:", botonesAgregar.length);
    
    botonesAgregar.forEach((btn, index) => {
      btn.addEventListener("click", (e) => {
        e.preventDefault(); // Evita el comportamiento por defecto del botón
        console.log("Botón agregar clickeado:", index);
        const card = btn.closest('.card');
        const nombre = card.querySelector('.card-title').textContent;
        const precioTexto = card.querySelector('.card-text').textContent;
        const precio = precioTexto.replace(/[^\d]/g, ''); // Extraer solo números
        console.log("Datos extraídos:", nombre, precio);
        agregarAlCarrito(nombre, precio); // Agrega el producto al carrito
      });
    });

    // Selecciona todos los botones con la clase .btn-comprar
    const botonesComprar = document.querySelectorAll(".btn-comprar");
    console.log("Botones comprar encontrados:", botonesComprar.length);
    
    botonesComprar.forEach((btn, index) => {
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        console.log("Botón comprar clickeado:", index);
        const card = btn.closest('.card');
        const nombre = card.querySelector('.card-title').textContent;
        const precioTexto = card.querySelector('.card-text').textContent;
        const precio = precioTexto.replace(/[^\d]/g, ''); // Extraer solo números
        console.log("Datos extraídos:", nombre, precio);
        agregarAlCarrito(nombre, precio); // Agrega el producto al carrito
        mostrarNotificacion(`🛒 Compra simulada: ${nombre}`, 'success'); // Notifica
      });
    });
  }

  // Botón para simular el pago del carrito
  const botonPagar = document.getElementById("pagar");
  if (botonPagar) {
    botonPagar.addEventListener("click", () => {
      console.log("Botón pagar clickeado");
      if (carrito.length === 0) {
        mostrarNotificacion('El carrito está vacío', 'warning'); // Si está vacío, avisa
        return;
      }
      
      const total = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0); // Calcula el total
      mostrarNotificacion(`💳 Pago simulado por $${total.toLocaleString()}`, 'success'); // Notifica
      
      // Limpia el carrito después del pago
      carrito.length = 0;
      actualizarCarrito();
    });
  }

  // ===== FUNCIONALIDAD DEL FORMULARIO =====
  
  const formulario = document.getElementById("contactForm"); // Formulario de contacto
  const tablaMensajes = document.getElementById("tablaMensajes"); // Tabla donde se muestran los mensajes
  let modoEdicion = false; // Indica si se está editando un mensaje
  let idEditando = null; // ID del mensaje que se está editando

  // Cargar mensajes al iniciar la página
  cargarMensajes();

  // Maneja el envío del formulario (crear o modificar mensaje)
  if (formulario) {
    formulario.addEventListener("submit", async (e) => {
      e.preventDefault(); // Evita recargar la página
      console.log("Formulario enviado");
      
      const formData = new FormData(formulario);
      const accion = formData.get('accion');
      console.log("Acción:", accion);
      
      try {
        const response = await fetch('guardar.php', {
          method: 'POST',
          body: formData
        });
        
        const resultado = await response.json(); // Espera la respuesta del servidor
        console.log("Respuesta del servidor:", resultado);
        
        if (resultado.status === 'success') {
          mostrarNotificacion(resultado.message, 'success'); // Notifica éxito
          limpiarFormulario(); // Limpia el formulario
          cargarMensajes(); // Recarga los mensajes
        } else {
          mostrarNotificacion(resultado.message, 'error'); // Notifica error
        }
      } catch (error) {
        console.error("Error en formulario:", error);
        mostrarNotificacion('Error de conexión', 'error'); // Notifica error de conexión
      }
    });
  }

  // Función para cargar los mensajes desde el servidor y mostrarlos en la tabla
  async function cargarMensajes() {
    console.log("Cargando mensajes...");
    try {
      const response = await fetch('cargar_mensajes.php'); // Solicita los mensajes
      const html = await response.text(); // Recibe el HTML de la tabla
      console.log("HTML recibido:", html);
      tablaMensajes.innerHTML = html; // Inserta la tabla en la página
      
      // Agrega los event listeners a los botones de editar y eliminar
      agregarEventListenersTabla();
    } catch (error) {
      console.error('Error al cargar mensajes:', error);
    }
  }

  // Función para agregar los event listeners a los botones de la tabla de mensajes
  function agregarEventListenersTabla() {
    console.log("Agregando event listeners a la tabla...");
    
    // Botones editar
    const botonesEditar = document.querySelectorAll('.btn-editar');
    console.log("Botones editar encontrados:", botonesEditar.length);
    
    botonesEditar.forEach(btn => {
      btn.addEventListener("click", () => {
        console.log("Botón editar clickeado");
        const id = btn.getAttribute('data-id');
        const nombre = btn.getAttribute('data-nombre');
        const correo = btn.getAttribute('data-correo');
        const mensaje = btn.getAttribute('data-mensaje');
        
        editarMensaje(id, nombre, correo, mensaje); // Llama a la función para editar
      });
    });

    // Botones eliminar
    const botonesEliminar = document.querySelectorAll('.btn-eliminar');
    console.log("Botones eliminar encontrados:", botonesEliminar.length);
    
    botonesEliminar.forEach(btn => {
      btn.addEventListener("click", () => {
        console.log("Botón eliminar clickeado");
        const id = btn.getAttribute('data-id');
        eliminarMensaje(id); // Llama a la función para eliminar
      });
    });
  }

  // Función para poner los datos de un mensaje en el formulario para editarlo
  function editarMensaje(id, nombre, correo, mensaje) {
    console.log("Editando mensaje:", id, nombre);
    document.getElementById('id').value = id;
    document.getElementById('nombre').value = nombre;
    document.getElementById('correo_electronico').value = correo;
    document.getElementById('mensaje').value = mensaje;
    
    modoEdicion = true; // Activa el modo edición
    idEditando = id; // Guarda el ID que se está editando
    
    // Cambia la visibilidad de los botones de enviar y modificar
    const botonEnviar = document.querySelector('button[value="Enviar"]');
    const botonModificar = document.querySelector('button[value="Modificar"]');
    
    if (botonEnviar) botonEnviar.style.display = 'none';
    if (botonModificar) botonModificar.style.display = 'inline-block';
    
    mostrarNotificacion('Modo edición activado', 'info'); // Notifica
  }

  // Función para eliminar un mensaje por su ID
  async function eliminarMensaje(id) {
    console.log("Eliminando mensaje:", id);
    if (!confirm('¿Estás seguro de que quieres eliminar este mensaje?')) {
      return; // Si el usuario cancela, no hace nada
    }
    
    const formData = new FormData();
    formData.append('accion', 'Eliminar');
    formData.append('id', id);
    
    try {
      const response = await fetch('guardar.php', {
        method: 'POST',
        body: formData
      });
      
      const resultado = await response.json();
      console.log("Respuesta eliminar:", resultado);
      
      if (resultado.status === 'success') {
        mostrarNotificacion(resultado.message, 'success'); // Notifica éxito
        cargarMensajes(); // Recarga los mensajes
      } else {
        mostrarNotificacion(resultado.message, 'error'); // Notifica error
      }
    } catch (error) {
      console.error("Error al eliminar:", error);
      mostrarNotificacion('Error al eliminar', 'error'); // Notifica error
    }
  }

  // ===== FUNCIONES GLOBALES =====
  
  // Función global para limpiar el formulario y restaurar el estado inicial
  window.limpiarFormulario = function() {
    console.log("Limpiando formulario");
    formulario.reset(); // Limpia los campos del formulario
    document.getElementById('id').value = '';
    modoEdicion = false;
    idEditando = null;
    
    // Restaura la visibilidad de los botones
    const botonEnviar = document.querySelector('button[value="Enviar"]');
    const botonModificar = document.querySelector('button[value="Modificar"]');
    
    if (botonEnviar) botonEnviar.style.display = 'inline-block';
    if (botonModificar) botonModificar.style.display = 'none';
    
    mostrarNotificacion('Formulario limpiado', 'info'); // Notifica
  };

  // Función global para cerrar el formulario (limpia y pregunta si hay cambios sin guardar)
  window.cerrarFormulario = function() {
    console.log("Cerrando formulario");
    if (modoEdicion) {
      if (confirm('¿Estás seguro de que quieres cerrar? Se perderán los cambios.')) {
        limpiarFormulario();
      }
    } else {
      limpiarFormulario();
    }
  };

  // Función para mostrar notificaciones flotantes en la pantalla
  function mostrarNotificacion(mensaje, tipo) {
    console.log("Mostrando notificación:", mensaje, tipo);
    // Crea un elemento div para la notificación
    const notificacion = document.createElement('div');
    notificacion.className = `alert alert-${tipo === 'error' ? 'danger' : tipo} alert-dismissible fade show position-fixed`;
    notificacion.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    
    notificacion.innerHTML = `
      ${mensaje}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notificacion);
    
    // Remueve la notificación automáticamente después de 3 segundos
    setTimeout(() => {
      if (notificacion.parentNode) {
        notificacion.remove();
      }
    }, 3000);
  }

  // Inicializa la aplicación conectando los botones y actualizando el carrito
  console.log("Inicializando aplicación...");
  conectarBotonesProductos(); // Conecta los botones de productos
  actualizarCarrito(); // Muestra el carrito vacío o con productos
  console.log("Aplicación inicializada correctamente");
});
