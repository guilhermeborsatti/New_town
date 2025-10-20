<?php
    session_start();
    require_once "src/UsuarioDAO.php";

    if ($usuario = UsuarioDAO::validarUsuario($_POST)){    
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['idusuario'] = $usuario ['idusuario'];
           $_SESSION['nome'] = $usuario ['nome'];
              $_SESSION['foto'] = $usuario ['foto'];

        header("Location:home.php");
    }else{
        $_SESSION['msg'] = "Usuário ou senha inválido.";
        header("Location:login.php");
    }
?>