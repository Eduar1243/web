<?php
/**
 * =================================================================
 * CARGAR_MENSAJES.PHP - GENERADOR DE FILAS DE TABLA DE MENSAJES
 * =================================================================
 *
 * Este script se conecta a la base de datos, obtiene todos los
 * mensajes de la tabla 'registros_carrito' y genera el código
 * HTML para cada fila de la tabla de mensajes.
 *
 * El HTML generado es luego insertado en la página principal
 * por medio de una petición AJAX desde webmedia.js.
 *
 */

// ===============================
// 1. INCLUYE LA CONEXIÓN A LA BASE DE DATOS
// ===============================
// Incluye el archivo de conexión, necesario para las consultas.
include 'conexion.php';

// ===============================
// 2. CONSULTA TODOS LOS MENSAJES
// ===============================
// Consulta SQL para seleccionar todos los mensajes, ordenados por ID descendente (los más nuevos primero).
$sql = "SELECT id, nombre, correo_electronico, mensaje FROM registros_carrito ORDER BY id DESC";
$resultado = $conn->query($sql);

// ===============================
// 3. GENERA EL HTML DE LA TABLA
// ===============================
// Verifica si la consulta devolvió alguna fila.
if ($resultado->num_rows > 0) {
    // Itera sobre cada fila (mensaje) obtenida de la base de datos.
    while($fila = $resultado->fetch_assoc()) {
        // Imprime el inicio de la fila de la tabla.
        echo "<tr>";
        
        // Imprime cada celda (<td>) con los datos del mensaje.
        // Se usa htmlspecialchars() para prevenir ataques XSS, convirtiendo
        // caracteres especiales de HTML en entidades seguras.
        echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['correo_electronico']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['mensaje']) . "</td>";
        
        // Celda para los botones de acciones (Editar y Eliminar).
        echo "<td>";

        // Botón para Editar
        // Se usan atributos data-* para almacenar los datos del mensaje.
        // Esto es una forma limpia y estándar de pasar datos desde el HTML al JavaScript.
        echo "<button 
                type='button' 
                class='btn btn-warning btn-sm me-2 btn-editar' 
                data-id='" . $fila['id'] . "'
                data-nombre='" . htmlspecialchars($fila['nombre'], ENT_QUOTES) . "'
                data-correo='" . htmlspecialchars($fila['correo_electronico'], ENT_QUOTES) . "'
                data-mensaje='" . htmlspecialchars($fila['mensaje'], ENT_QUOTES) . "'>
                <i class='bi bi-pencil'></i> Editar
              </button>";

        // Botón para Eliminar
        // Al igual que con editar, se usa un atributo data-id para identificar el registro a eliminar.
        // La lógica de eliminación se manejará en JavaScript.
        echo "<button 
                type='button' 
                class='btn btn-danger btn-sm btn-eliminar' 
                data-id='" . $fila['id'] . "'>
                <i class='bi bi-trash'></i> Eliminar
              </button>";

        echo "</td>";
        echo "</tr>";
    }
} else {
    // Si no hay mensajes, muestra una fila con un mensaje informativo.
    echo "<tr><td colspan='4' class='text-center'>No hay mensajes para mostrar</td></tr>";
}

// ===============================
// 4. CIERRE DE CONEXIÓN
// ===============================
// Cierra la conexión a la base de datos para liberar recursos.
$conn->close();
?> 