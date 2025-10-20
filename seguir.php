<?php
include ("incs/valida-sessao.php");
require_once "src/SeguidoDAO.php";
if (isset($_GET['idseguido'])) {
    SeguidoDAO::seguirUsuario($_SESSION['idusuario'], $_GET['idseguido']);
}

header("Location:seguir-usuario.php");