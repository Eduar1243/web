<?php
/**
 * =================================================================
 * GUARDAR.PHP - GESTOR DE OPERACIONES CRUD PARA MENSAJES
 * =================================================================
 *
 * Este script se encarga de todas las operaciones de la base de datos
 * para los mensajes del formulario de contacto (Crear, Leer,
 * Actualizar, Eliminar - CRUD).
 *
 * Funciona como una API: en lugar de recargar la página, recibe
 * peticiones (POST para escribir, GET para leer), procesa los datos
 * y devuelve una respuesta en formato JSON.
 *
 */

// ===============================
// 1. INCLUYE LA CONEXIÓN A LA BASE DE DATOS
// ===============================
// Incluye el archivo de conexión a la base de datos.
// Es crucial para poder interactuar con MySQL.
include 'conexion.php';

// ===============================
// 2. CONFIGURA LA RESPUESTA COMO JSON
// ===============================
// Establece la cabecera de la respuesta a JSON.
// Esto le dice al navegador que la respuesta de este script
// debe ser interpretada como un objeto JSON.
header('Content-Type: application/json');

// Prepara un array para la respuesta JSON.
// Este array contendrá el estado de la operación (éxito/error)
// y un mensaje descriptivo.
$response = ['status' => 'error', 'message' => 'Acción no válida.'];

// ===============================
// 3. PROCESA LA PETICIÓN POST (CRUD)
// ===============================
// Determina el tipo de acción a realizar basándose en el método de la petición.
// Usamos el método POST para operaciones que modifican datos (crear, actualizar, eliminar).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Se requiere un campo 'accion' en la petición para saber qué hacer.
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        // Extrae y limpia los datos comunes del formulario.
        // Se usa `isset` para asegurar que las variables existan.
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
        $correo = isset($_POST['correo_electronico']) ? trim($_POST['correo_electronico']) : '';
        $mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';

        // Usa una estructura 'switch' para ejecutar el código correspondiente a la acción.
        switch ($accion) {
            case 'Enviar':
                // ===============================
                // CREAR NUEVO MENSAJE
                // ===============================
                // Validación simple de campos.
                if (!empty($nombre) && !empty($correo) && !empty($mensaje)) {
                    // Prepara la consulta SQL para evitar inyección SQL.
                    // Los signos de interrogación (?) son marcadores de posición para los datos.
                    $stmt = $conn->prepare("INSERT INTO registros_carrito (nombre, correo_electronico, mensaje) VALUES (?, ?, ?)");
                    // Vincula las variables a los marcadores de posición.
                    // "sss" significa que las tres variables son de tipo string (cadena).
                    $stmt->bind_param("sss", $nombre, $correo, $mensaje);

                    // Ejecuta la consulta y actualiza la respuesta.
                    if ($stmt->execute()) {
                        $response = ['status' => 'success', 'message' => 'Mensaje enviado correctamente.'];
                    } else {
                        $response['message'] = 'Error al enviar el mensaje: ' . $stmt->error;
                    }
                    // Cierra el statement para liberar recursos.
                    $stmt->close();
                } else {
                    $response['message'] = 'Por favor, completa todos los campos.';
                }
                break;

            case 'Modificar':
                // ===============================
                // MODIFICAR MENSAJE EXISTENTE
                // ===============================
                // Se necesita un ID válido para modificar un registro.
                if ($id > 0 && !empty($nombre) && !empty($correo) && !empty($mensaje)) {
                    // Prepara la consulta de actualización.
                    $stmt = $conn->prepare("UPDATE registros_carrito SET nombre = ?, correo_electronico = ?, mensaje = ? WHERE id = ?");
                    // "sssi" significa 3 strings y 1 integer (entero).
                    $stmt->bind_param("sssi", $nombre, $correo, $mensaje, $id);

                    if ($stmt->execute()) {
                        $response = ['status' => 'success', 'message' => 'Mensaje actualizado correctamente.'];
                    } else {
                        $response['message'] = 'Error al actualizar el mensaje: ' . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $response['message'] = 'Faltan datos o el ID es inválido para modificar.';
                }
                break;

            case 'Eliminar':
                // ===============================
                // ELIMINAR MENSAJE POR ID
                // ===============================
                // Solo se necesita el ID para eliminar.
                if ($id > 0) {
                    // Prepara la consulta de eliminación.
                    $stmt = $conn->prepare("DELETE FROM registros_carrito WHERE id = ?");
                    // "i" significa que la variable es de tipo integer.
                    $stmt->bind_param("i", $id);

                    if ($stmt->execute()) {
                        $response = ['status' => 'success', 'message' => 'Mensaje eliminado correctamente.'];
                    } else {
                        $response['message'] = 'Error al eliminar el mensaje: ' . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $response['message'] = 'ID no proporcionado para eliminar.';
                }
                break;
        }
    }
}

// ===============================
// 4. CIERRE Y RESPUESTA FINAL
// ===============================
// Cierra la conexión a la base de datos al final del script.
$conn->close();

// Imprime la respuesta en formato JSON.
// La función `json_encode` convierte el array de PHP en una cadena JSON.
echo json_encode($response);

?>