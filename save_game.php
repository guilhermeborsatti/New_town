<?php
session_start();
require_once "ConexaoBD.php";

header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$idusuario = $_SESSION['usuario']['idusuario'];
$x = $input['x'];
$y = $input['y'];

try {
    $conexao = ConexaoBD::conectar();
    $sql = "UPDATE usuarios SET x = ?, y = ? WHERE idusuario = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(1, $x);
    $stmt->bindParam(2, $y);
    $stmt->bindParam(3, $idusuario);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
