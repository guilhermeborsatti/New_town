<?php
require_once "ConexaoBD.php";

class SeguidoDAO {
    public static function seguirUsuario($idusuario, $idseguido) {
        $conexao = ConexaoBD::conectar();
        $sql = " insert into seguidos (idusuario,idseguido) values(?,?)";

        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(1, $idusuario);
        $stmt->bindParam(2, $idseguido);

      $stmt->execute();
    }
}

?>