/* arreglo para guardar productos en el carrito */
const carrito = [];


    /* funcion para agregar productos al carrito */
    function configurarBotonesAgregar() {
        const botonesAgregar = document.querySelectorAll(".producto button:nth-of-type(2)");
        
        botonesAgregar.forEach((boton) => {
            boton.addEventListener("click", () => {
                const productoElemento = boton.parentElement;
                const nombre = productoElemento.querySelector("h4").textContent;
                const precioTexto = productoElemento.querySelector("p").textContent;
                const precio = parseFloat(precioTexto.replace(/[^\d.]/g, ""));

                carrito.push({ nombre, precio });
                alert(`${nombre} agregado al carrito.`);

                /* actualiza el carrito */
                actualizarCarrito();
                

            });
        });
    }

    /* función que configura el boton "pagar" muestra el total y vacía el carrito */
    function configurarBotonPagar() {
        const botonPagar = document.getElementById("pagar");

        botonPagar.addEventListener("click", () => {
        if (carrito.length === 0) {
            alert("El carrito está vacío.");
            return;

            } 

            const total = carrito.reduce((acc, producto) => acc + producto.precio, 0);
        let mensaje = "resumen de compra:\n";
        carrito.forEach((prod, i) => {
            mensaje += `${i + 1}. ${prod.nombre} - COP $${prod.precio.toLocaleString("es-CO")}\n`;

        });
        mensaje +=  `\nTOTAL: COP $${total.toLocaleString("es-CO")}`;

        alert(mensaje);
        carrito.length = 0;

        actualizarCarrito();
            
        });
        
    }

    /* Función para el botón de "Comprar" (compra directa) */
function configurarBotonesComprarDirecto() {
    const botonesComprar = document.querySelectorAll(".producto button:nth-of-type(1)");

    botonesComprar.forEach((boton) => {
        boton.addEventListener("click", () => {
            const productoElemento = boton.parentElement;
            const nombre = productoElemento.querySelector("h4").textContent;
            const precioTexto = productoElemento.querySelector("p").textContent;
            const precio = parseFloat(precioTexto.replace(/[^\d.]/g, ""));

            alert(`Has comprado directamente:\n${nombre} por COP $${precio.toLocaleString("es-co")}`);
        });
    });
}

/* Función para actualizar visualmente los productos en el carrito */
function actualizarCarrito() {
    const listaCarrito = document.getElementById("lista-carrito");
    const totalElemento = document.getElementById("total");

    /* Limpiar el contenido actual del carrito */
    listaCarrito.innerHTML = "";

    /* Si está vacío, mostrar mensaje */
    if (carrito.length === 0) {
        listaCarrito.innerHTML = "<li>El carrito está vacío.</li>";
        totalElemento.textContent = "0";
        return;
    }

    /* Mostrar productos en lista */
    carrito.forEach(producto => {
        const li = document.createElement("li");
        li.textContent = `${producto.nombre} - COP $${producto.precio.toLocaleString("es-CO")}`;
        listaCarrito.appendChild(li);
    });

    /* Calcular y mostrar total */
    const total = carrito.reduce((acc, producto) => acc + producto.precio, 0);
    totalElemento.textContent = total.toLocaleString("es-CO");
}

/* Ejecutar cuando la página esté lista */
document.addEventListener("DOMContentLoaded", () => {
    configurarBotonesAgregar();
    configurarBotonesComprarDirecto();
    configurarBotonPagar();
});
                
               
               

   


        