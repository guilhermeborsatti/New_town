<?php
session_start();
require_once "src/UsuarioDAO.php";

if (!empty($_POST['email']) && !empty($_POST['senha'])) {
    $dados = [
        'email' => $_POST['email'],
        'senha' => $_POST['senha']
    ];

    $usuario = UsuarioDAO::validarUsuario($dados);

    if ($usuario) {
        // Salva todas as informações do usuário na sessão
        $_SESSION['usuario'] = $usuario;
        header("Location: home.php");
        exit;
    } else {
        $_SESSION['msg'] = "Email ou senha incorretos!";
        header("Location: login.php");
        exit;
    }
} else {
    $_SESSION['msg'] = "Preencha todos os campos!";
    header("Location: login.php");
    exit;
}
?>
