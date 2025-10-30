<?php
session_start();
include("incs/valida-sessao.php");
require_once "src/UsuarioDAO.php";
require_once "src/SeguidoDAO.php";
require_once "src/PostagemDAO.php";
require_once "src/ConexaoBD.php";

header('Content-Type: application/json');

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(['success' => false, 'error' => 'Usuário não logado']);
    exit;
}

$userId = $_SESSION['idusuario'];
$targetUserId = isset($_GET['id']) ? $_GET['id'] : $userId;

try {
    // DEBUG: Verificar dados da sessão
    error_log("🔄 Buscando perfil - Sessão: " . $_SESSION['idusuario'] . ", Nome: " . $_SESSION['nome']);
    
    // Buscar dados básicos do usuário
    $sql = "SELECT idusuario, nome, email, foto FROM usuarios WHERE idusuario = ?";
    $conexao = ConexaoBD::conectar();
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(1, $targetUserId);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // DEBUG: Verificar dados do banco
    error_log("📊 Dados do banco: " . print_r($usuario, true));
    
    if (!$usuario) {
        echo json_encode(['success' => false, 'error' => 'Usuário não encontrado']);
        exit;
    }
    
    // ... resto do código igual ...
    
    $profileData = [
        'nome' => $usuario['nome'],
        'foto' => $usuario['foto'],
        'seguidores' => $seguidores['total'],
        'seguindo' => $seguindo['total'],
        'posts' => $totalPosts,
        'isFollowing' => $isFollowing['total'] > 0
    ];
    
    // DEBUG: Verificar dados finais
    error_log("🎯 Dados enviados: " . print_r($profileData, true));
    
    echo json_encode(['success' => true, 'data' => $profileData]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>