<?php
// ===============================
// DATOS DE CONEXIÓN A LA BASE DE DATOS
// ===============================
$host = "localhost";      // Dirección del servidor MySQL (localhost si está en la misma máquina)
$user = "root";           // Usuario de la base de datos (por defecto en XAMPP es 'root')
$password = "";           // Contraseña del usuario (vacía por defecto en XAMPP)
$db = "base_carrito";     // Nombre de la base de datos que vas a usar

// ===============================
// CREAR LA CONEXIÓN
// ===============================
$conn = new mysqli($host, $user, $password, $db); // Crea la conexión usando MySQLi

// ===============================
// VERIFICAR SI HAY ERRORES DE CONEXIÓN
// ===============================
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error); // Si hay error, muestra mensaje y detiene el script
}
?>