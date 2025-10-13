<?php
require_once "ConexaoBD.php";

class UsuarioDAO{

    public static function cadastrarUsuario($dados){
        $conexao = ConexaoBD::conectar();
        
        $sql = "INSERT INTO usuarios (email, senha, nome, foto) VALUES (?,?,?,?)";
        $stmt = $conexao->prepare($sql);
        
        $stmt->bindParam(1, $dados['email']);

        $senhaCriptografada = md5($dados['senha']);
        $stmt->bindParam(2, $senhaCriptografada);

        $stmt->bindParam(3, $dados['nome']);
        $stmt->bindParam(4, $dados['foto']);

        $stmt->execute();

    }

    public static function validarUsuario($dados){
        $senhaCriptografada = md5($dados['senha']);
        $sql = "SELECT * FROM usuarios WHERE email=? AND senha=?";

        $conexao = ConexaoBD::conectar();
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(1, $dados['email']);
        $stmt->bindParam(2, $senhaCriptografada);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount()>0){
            return $usuario['idusuario'];

        }else{
            return false;
        }
    }

    public static function listarUsuarios($idusuario){
        $sql = "select * from usuarios where idusuario!=?";
        $conexao = ConexaoBD :: conectar();
        $stmt = $conexao -> prepare($sql);
        $stmt -> bindParam(1, $idusuario);
        $stmt -> execute();

        return $stmt -> fetch(PDO::FETCH_ASSOC);
    }

    public static function buscarUsuarios($idusuario, $nome){
        $sql = "select * from usuarios where idusuario!=? and nome like ? ";
        $conexao = ConexaoBD :: conectar();
        $stmt = $conexao -> prepare($sql);
        $stmt -> bindParam(1, $idusuario);
        $nome = '%'.$nome.'%';
        $stmt -> bindParam(2, $nome);
        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);
    }
}
?>