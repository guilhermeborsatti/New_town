<?php 
include "incs/valida-sessao.php";
require_once "src/PostagemDAO.php";

PostagemDAO::cadastrar($_POST, $_SESSION["idusuario"]);

header("Location: home.php");
?>