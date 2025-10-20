<?php
session_start();
include "incs/topo.php";

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New-Town - <?= htmlspecialchars($usuario['nome']) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="static/game-styles.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">New-Town <?= htmlspecialchars($usuario['nome']) ?></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="home.php">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="seguir-usuario.php">Usuários</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Container do jogo -->
    <div id="gameContainer" class="p-3">
        <!-- Cabeçalho do jogo -->
        <div id="gameHeader" class="d-flex justify-content-between align-items-center bg-secondary text-white p-2 rounded">
            <div id="playerInfo">
                <span><strong>Jogador:</strong> <?= htmlspecialchars($usuario['nome']) ?></span> |
                <span><strong>Nível:</strong> <?= htmlspecialchars($usuario['nivel'] ?? 1) ?></span> |
                <span><strong>XP:</strong> <?= htmlspecialchars($usuario['xp'] ?? 0) ?></span>
            </div>
            <div id="gameControls">
                <button id="btnSave" class="btn btn-success btn-sm">💾 Salvar Jogo</button>
                <a href="home.php" class="btn btn-light btn-sm">🏠 Início</a>
                <a href="logout.php" class="btn btn-danger btn-sm">🚪 Sair</a>
            </div>
        </div>

        <!-- Área do jogo -->
        <div id="gameArea" class="d-flex mt-3">
            <canvas id="gameCanvas" width="800" height="600" class="border rounded shadow"></canvas>
            <div id="miniMapContainer" class="ms-3">
                <h5 class="text-white">Mini Mapa</h5>
                <canvas id="miniMap" width="150" height="150" class="border rounded"></canvas>
            </div>
        </div>

        <!-- Status do jogo -->
        <div id="gameStatus" class="text-white mt-3">
            <div id="coordinates">Posição: X: <span id="posX"><?= $usuario['x'] ?? 8 ?></span>, Y: <span id="posY"><?= $usuario['y'] ?? 8 ?></span></div>
            <div id="fpsCounter">FPS: 60</div>
            <div id="controlsHelp" class="text-secondary">
                Controles: WASD para mover | Shift para correr
            </div>
        </div>
    </div>

    <!-- Dados do usuário disponíveis no JavaScript -->
    <script>
        const userData = {
            id: <?= (int)$usuario['idusuario'] ?>,
            nome: "<?= addslashes($usuario['nome']) ?>",
            nivel: <?= (int)($usuario['nivel'] ?? 1) ?>,
            xp: <?= (int)($usuario['xp'] ?? 0) ?>,
            x: <?= (int)($usuario['x'] ?? 8) ?>,
            y: <?= (int)($usuario['y'] ?? 8) ?>
        };
    </script>

    <script src="js/game.js"></script>
</body>
</html>
