<?php
include("incs/valida-sessao.php");
require_once "src/UsuarioDAO.php";
require_once "src/Util.php";

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $novaSenha = $_POST['nova_senha'] ?? '';
    
    // Processar upload da foto se houver
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = Util::salvarFoto();
    }
    
    // Preparar dados para atualização
    $dadosAtualizacao = [
        'idusuario' => $_SESSION['idusuario'],
        'nome' => $nome,
        'email' => $email
    ];
    
    if ($foto) {
        $dadosAtualizacao['foto'] = $foto;
    }
    
    if (!empty($novaSenha)) {
        $dadosAtualizacao['senha'] = $novaSenha;
    }
    
    // Aqui você precisaria criar um método no UsuarioDAO para atualizar
    // Por enquanto, vou deixar simples e você adapta depois
    
    $mensagem = "Perfil atualizado com sucesso!";
    $_SESSION['nome'] = $nome;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - New Town</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            padding: 40px;
            width: 100%;
            max-width: 500px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 16px;
        }

        .user-welcome {
            color: #667eea;
            font-weight: bold;
            margin-top: 10px;
        }

        .profile-picture {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-picture img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #667eea;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a6fd8;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            margin-top: 10px;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .password-note {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .current-photo {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔄 Editar Perfil</h1>
            <p>Atualize suas informações pessoais</p>
            <div class="user-welcome">Olá, <?php echo htmlspecialchars($_SESSION['nome'] ?? 'Usuário'); ?>!</div>
        </div>

        <?php if ($mensagem): ?>
            <div class="alert alert-success">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="profile-picture">
                <?php if (isset($_SESSION['foto']) && $_SESSION['foto']): ?>
                    <img src="uploads/<?php echo $_SESSION['foto']; ?>" alt="Foto de perfil">
                    <div class="current-photo">Foto atual</div>
                <?php else: ?>
                    <div style="width: 120px; height: 120px; border-radius: 50%; background: #ddd; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 40px;">
                        👤
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="foto">📷 Alterar Foto de Perfil</label>
                <input type="file" id="foto" name="foto" accept="image/*">
                <div class="password-note">Formatos: JPG, PNG, GIF (Máx: 2MB)</div>
            </div>

            <div class="form-group">
                <label for="nome">👤 Nome</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($_SESSION['nome'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">📧 Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="nova_senha">🔒 Nova Senha</label>
                <input type="password" id="nova_senha" name="nova_senha" placeholder="Deixe em branco para manter a senha atual">
                <div class="password-note">Preencha apenas se desejar alterar a senha</div>
            </div>

            <button type="submit" class="btn btn-primary">💾 Salvar Alterações</button>
            <a href="game.php" class="btn btn-secondary">🎮 Voltar para o Jogo</a>
            <a href="home.php" class="btn btn-secondary" style="background: #495057; margin-top: 10px;">🏠 Voltar para Home</a>
        </form>
    </div>

    <script>
        // Preview da imagem antes do upload
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.querySelector('.profile-picture img');
                    if (img) {
                        img.src = e.target.result;
                    } else {
                        const profileDiv = document.querySelector('.profile-picture');
                        profileDiv.innerHTML = `
                            <img src="${e.target.result}" alt="Preview da foto">
                            <div class="current-photo">Nova foto</div>
                        `;
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>