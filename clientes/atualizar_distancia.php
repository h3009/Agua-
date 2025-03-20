<?php
session_start();
include '../../config/bd.php';

// Coordenadas do usuário e motorista
$latitude_usuario = $_SESSION['latitude'];
$longitude_usuario = $_SESSION['longitude'];
$id_motorista = $_POST['id_motorista'];

// Calcula a distância
$query = "SELECT ROUND(6371 * ACOS(
               COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) +
               SIN(RADIANS(?)) * SIN(RADIANS(latitude))
           ), 2) AS distancia_km
    FROM motoristas
    WHERE id = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "dddi", $latitude_usuario, $longitude_usuario, $latitude_usuario, $id_motorista);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

header('Content-Type: application/json');
echo json_encode(['distancia' => $data['distancia_km']]);
?>