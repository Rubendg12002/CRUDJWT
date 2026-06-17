<?php
// 1. Las importaciones de librerías SIEMPRE van arriba del todo
use Firebase\JWT\JWT;

// 2. Ahora sí, limpiamos el búfer y configuramos las cabeceras
ob_clean();
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    // 3. Cargamos los archivos de soporte
    require '../vendor/autoload.php';   
    require '../Modelo/conexion.php';   
    require '../config/config.php';     

    $db = new DB();
    $cn = $db->conectar();

    $data = json_decode(file_get_contents("php://input"), true);

    $usuario = isset($data["usuario"]) ? trim($data["usuario"]) : '';
    $password = isset($data["password"]) ? trim($data["password"]) : '';

    if (empty($usuario) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Campos vacíos"]);
        exit();
    }

    $sql = $cn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $sql->execute([$usuario]);
    $user = $sql->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user["password"])) {
        $payload = [
            "usuario" => $usuario,
            "exp" => time() + 3600 
        ];

        // Generamos el token oficial con Firebase JWT
        $token = JWT::encode($payload, JWT_SECRET_KEY, "HS256");

        echo json_encode([
            "success" => true,
            "token" => $token
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Usuario o contraseña incorrectos"
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error en el servidor: " . $e->getMessage()
    ]);
}