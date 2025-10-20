<?php


require_once "src/UsuarioDAO.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Upload da foto
    $caminhoFoto = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $pasta = "uploads/";
        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }
        $nomeArquivo = uniqid() . "_" . basename($_FILES['imagem']['name']);
        $caminhoFoto = $pasta . $nomeArquivo;
        move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoFoto);
    }

    $dados = [
        "nome"  => $_POST['nome'] ?? null,
        "email" => $_POST['email'] ?? null,
        "senha" => $_POST['senha'] ?? null,
        "foto"  => $caminhoFoto
    ];

    UsuarioDAO::cadastrarUsuario($dados);

    echo "✅ Usuário cadastrado com sucesso!";
}

?>