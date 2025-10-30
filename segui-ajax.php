<?php
session_start();
include ("incs/valida-sessao.php");
require_once "src/SeguidoDAO.php";
require_once "src/ConexaoBD.php";

header('Content-Type: application/json');

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(['success' => false, 'error' => 'Usuário não logado']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'];
$targetUserId = $input['targetUserId'];
$userId = $_SESSION['idusuario'];

try {
    $conexao = ConexaoBD::conectar();
    
    if ($action === 'follow') {
        // Usa seu SeguidoDAO existente
        SeguidoDAO::seguirUsuario($userId, $targetUserId);
        echo json_encode(['success' => true, 'message' => 'Seguindo usuário']);
        
    } elseif ($action === 'unfollow') {
        $sql = "DELETE FROM seguidos WHERE idusuario = ? AND idseguido = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(1, $userId);
        $stmt->bindParam(2, $targetUserId);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Deixou de seguir']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>