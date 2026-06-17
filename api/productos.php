<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

require '../vendor/autoload.php'; 
require '../Modelo/Producto.php';
require '../config/config.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$headers = apache_request_headers();
$token = null;

if (isset($headers['Authorization'])) {
    $matches = [];
    if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
        $token = $matches[1];
    }
}

if (!$token) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Acceso denegated."]);
    exit();
}

try {
    // Decodificación oficial con la librería Firebase\JWT
    $decoded = JWT::decode($token, new Key(JWT_SECRET_KEY, 'HS256'));
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Token inválido."]);
    exit();
}

$p = new Producto();
$metodo = $_SERVER['REQUEST_METHOD'];

switch($metodo) {
    case "GET":
        echo json_encode($p->listar());
        break;
    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $p->guardar($data["codigo"], $data["producto"], $data["precio"], $data["cantidad"]);
        echo json_encode(["success" => true]);
        break;
}