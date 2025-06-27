<!-- =============================== -->
<!-- Conexión a la API de bicicletas -->
<!-- =============================== -->
<?php
// URL de la API externa que contiene los datos de bicicletas
$API_URL = "https://68507457e7c42cfd1798bdda.mockapi.io/api/v1/bicicletas";
// Obtiene el contenido JSON de la API
$json   = @file_get_contents($API_URL);
// Decodifica el JSON a un arreglo asociativo de PHP
$datos  = json_decode($json, true);
// Si no se pudo conectar, muestra un mensaje de error
if ($datos === null) {
    $errorApi = "❌ No se pudo conectar con la API.";
}
// Obtiene el término de búsqueda si existe
$busqueda   = isset($_GET['q']) ? trim($_GET['q']) : '';
$productos = [];
// Si hay búsqueda, filtra los productos por nombre
if ($busqueda && is_array($datos)) {
    $buscMin = mb_strtolower($busqueda);
    foreach ($datos as $bici) {
        if (strpos(mb_strtolower($bici['nombre']), $buscMin) !== false) {
            $productos[] = $bici;
        }
    }
} elseif (is_array($datos) && $busqueda != '') {
    $productos = $datos;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BiciAccesorios</title>
  <!-- =============================== -->
  <!-- Enlaces a Bootstrap y estilos   -->
  <!-- =============================== -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script defer src="webmedia.js"></script>
</head>
<body>
  <!-- =============================== -->
  <!-- Encabezado y navegación         -->
  <!-- =============================== -->
  <header class="bg-primary text-white text-center py-4 mb-4">
    <h1>Bici Accesorios</h1>
    <section class="d-flex justify-content-center align-items-center">
      <img src="img/carrito de compras.png" alt="carrito" width="40" class="me-2">
      <h2 class="h5">Carrito</h2>
    </section>
    <nav class="mt-3">
      <ul class="nav justify-content-center">
        <li class="nav-item"><a href="#productos" class="nav-link text-white">Productos</a></li>
        <li class="nav-item"><a href="#contacto" class="nav-link text-white">Contacto</a></li>
      </ul>
    </nav>
  </header>

  <main class="container">
    <!-- =============================== -->
    <!-- Sección de bienvenida           -->
    <!-- =============================== -->
    <section id="bienvenida" class="text-center mb-4">
      <h3>Bienvenido a Bici Accesorios</h3>
      <p>Tu tienda de confianza para conseguir accesorios de calidad para todo tipo de bicicletas.</p>
    </section>

    <!-- =============================== -->
    <!-- Productos desde la API          -->
    <!-- =============================== -->
    <section id="productos">
      <h4 class="text-center">Nuestros Productos</h4>

      <!-- Formulario de búsqueda de productos -->
      <form method="GET" class="d-flex justify-content-center my-3">
        <input type="text" name="q" class="form-control w-50 me-2" placeholder="Buscar bicicleta por categoría..." required>
        <button class="btn btn-primary" type="submit">Buscar</button>
      </form>

      <!-- Mensaje de error si la API no responde -->
      <?php if (isset($errorApi)): ?>
        <p class="text-danger text-center fw-bold">
          <?= $errorApi ?>
        </p>
      <?php endif; ?>

      <!-- Muestra los productos filtrados de la API -->
      <?php if (!empty($productos)): ?>
        <div class="row">
        <?php foreach ($productos as $producto): ?>
          <div class="col-md-4 mb-4">
            <div class="card h-100">
              <img src="<?= $producto['imagen'] ?>" class="card-img-top" alt="<?= $producto['nombre'] ?>">
              <div class="card-body">
                <h5 class="card-title"><?= $producto['nombre'] ?></h5>
                <p class="card-text"><strong>Terreno:</strong> <?= htmlspecialchars($producto['tipo_terreno']) ?></p>
                <p class="card-text"><?= $producto['descripcion'] ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>

    <!-- =============================== -->
    <!-- Productos locales (siempre visibles) -->
    <!-- =============================== -->
    <section id="producto" class="row">
      <!-- productos locales visibles siempre -->
      <div class="col-md-3 mb-4">
        <div class="card h-100">
          <img src="img/relacion.jpg" class="card-img-top" alt="Shimano Deore">
          <div class="card-body">
            <h5 class="card-title">Relación MTB 1x10</h5>
            <p class="card-text">$450.000</p>
            <p class="card-text">Relación Shimano Deore MTB 1x10.</p>
            <!-- Botones para el carrito -->
            <button class="btn btn-primary btn-comprar">
              <i class="bi bi-cart-plus"></i> Comprar
            </button>
            <button class="btn btn-outline-secondary btn-agregar">
              <i class="bi bi-plus-circle"></i> Agregar al carrito
            </button>
          </div>
        </div>
      </div>
      <!-- Repite la estructura para otros productos locales -->
      <div class="col-md-3 mb-4">
        <div class="card h-100">
          <img src="img/suspension.jpg" class="card-img-top" alt="Suspensión Fox 29">
          <div class="card-body">
            <h5 class="card-title">Suspensión Fox Racing Shox</h5>
            <p class="card-text">$4.300.000</p>
            <p class="card-text">Protege tus articulaciones y reduce los impactos causados por la vibración.</p>
            <button class="btn btn-primary btn-comprar">
              <i class="bi bi-cart-plus"></i> Comprar
            </button>
            <button class="btn btn-outline-secondary btn-agregar">
              <i class="bi bi-plus-circle"></i> Agregar al carrito
            </button>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card h-100">
          <img src="img/llantas.jpg" class="card-img-top" alt="Llantas Maxxis ikon29">
          <div class="card-body">
            <h5 class="card-title">Maxxis Ikon 29</h5>
            <p class="card-text">$95.000</p>
            <p class="card-text">Desafía los terrenos más difíciles con nuestras llantas Maxxis.</p>
            <button class="btn btn-primary btn-comprar">
              <i class="bi bi-cart-plus"></i> Comprar
            </button>
            <button class="btn btn-outline-secondary btn-agregar">
              <i class="bi bi-plus-circle"></i> Agregar al carrito
            </button>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card h-100">
          <img src="img/guantes.jpg" class="card-img-top" alt="GUANTES FOX">
          <div class="card-body">
            <h5 class="card-title">GUANTES FOX FLEXAIR PARK</h5>
            <p class="card-text">$84.900</p>
            <p class="card-text">La mejor protección para tus manos.</p>
            <button class="btn btn-primary btn-comprar">
              <i class="bi bi-cart-plus"></i> Comprar
            </button>
            <button class="btn btn-outline-secondary btn-agregar">
              <i class="bi bi-plus-circle"></i> Agregar al carrito
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- =============================== -->
    <!-- Video informativo de la tienda  -->
    <!-- =============================== -->
    <section class="my-5">
      <h3 class="text-center mb-3">¿Por qué elegir Bici Accesorios?</h3>
      <div class="ratio ratio-16x9">
        <iframe src="https://www.youtube.com/embed/XUD2l2UYELI"
                title="Video de Bici Accesorios"
                allowfullscreen
                loading="lazy"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                style="border-radius: 12px;"></iframe>
      </div>
    </section>

    <!-- =============================== -->
    <!-- Formulario de contacto          -->
    <!-- =============================== -->
    <section id="contacto" class="mb-5">
      <h3>Contáctanos</h3>
      
      <!-- FORMULARIO DE CONTACTO
        - id="contactForm": Usado por JavaScript para identificar el formulario.
        - action y method: Aunque usamos AJAX, es una buena práctica mantenerlos como respaldo.
        - La clase "row g-3" es de Bootstrap para el layout de los campos.
      -->
      <form action="guardar.php" method="POST" id="contactForm" class="row g-3">
        
        <!-- Campo oculto para guardar el ID del mensaje cuando se está editando -->
        <input type="hidden" id="id" name="id">

        <div class="col-md-6">
          <label for="nombre" class="form-label">Nombre:</label>
          <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label for="correo_electronico" class="form-label">Correo electrónico:</label>
          <input type="email" id="correo_electronico" name="correo_electronico" class="form-control" required>
        </div>
        <div class="col-12">
          <label for="mensaje" class="form-label">Mensaje:</label>
          <textarea id="mensaje" name="mensaje" rows="4" class="form-control" required></textarea>
        </div>
        <div class="col-12">
          <!-- 
            BOTONES DE ACCIÓN DEL FORMULARIO
            - Enviar (submit): Crea un nuevo registro.
            - Modificar (submit): Actualiza un registro existente. Su visibilidad es controlada por JS.
            - Limpiar (button): Llama a la función JS limpiarFormulario().
            - Cerrar (button): Llama a la función JS cerrarFormulario().
            - El botón Eliminar se ha quitado de aquí, ya que la acción se realiza en la tabla.
          -->
          <!-- Aquí irían los botones, controlados por JS -->
        </div>
      </form>
      <!-- Aquí se mostrará la tabla de mensajes cargada por AJAX -->
      <div class="mensajes-tabla mt-4">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Correo electrónico</th>
              <th>Mensaje</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="tablaMensajes">
            <!-- Las filas se cargan dinámicamente con AJAX -->
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <!-- =============================== -->
  <!-- Pie de página                   -->
  <!-- =============================== -->
  <footer class="bg-dark text-white text-center py-3 mt-5">
    <p>&copy; <?php echo date('Y'); ?> Bici Accesorios. Todos los derechos reservados.</p>
  </footer>
</body>
</html>