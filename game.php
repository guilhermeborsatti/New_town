<?php
session_start();

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
    <a class="navbar-brand" href="#">New-Town <?=$_SESSION['nome']?></a>      
    <title>Habbo Simples - <?php echo htmlspecialchars($usuario['usuario']); ?></title>
    <link rel="stylesheet" href="static/game-styles.css">
</head>
<body>
    <div id="gameContainer">
        <!-- Header com informações do usuário -->
        <div id="gameHeader">
            <div id="playerInfo">
               <span>Jogador: <?= htmlspecialchars($usuario['nome']) ?></span>
<span>Nível: <?= htmlspecialchars($usuario['nivel']) ?></span>
<span>XP: <?= htmlspecialchars($usuario['xp']) ?></span>

            </div>
            <div id="gameControls">
                <button id="btnSave">Salvar Jogo</button>
                <a href="home.php" class="btn">Voltar ao Início</a>
                <a href="logout.php" class="btn btn-danger">Sair</a>
            </div>
        </div>

        <!-- Área do jogo -->
        <div id="gameArea">
            <canvas id="gameCanvas" width="800" height="600"></canvas>
            
            <!-- Mini mapa -->
            <div id="miniMapContainer">
                <h3>Mini Mapa</h3>
                <canvas id="miniMap" width="150" height="150"></canvas>
            </div>
        </div>

        <!-- Controles e status -->
        <div id="gameStatus">
            <div id="coordinates">Posição: X: <span id="posX"><?php echo $usuario['x'] ?? 8; ?></span>, Y: <span id="posY"><?php echo $usuario['y'] ?? 8; ?></span></div>
            <div id="fpsCounter">FPS: 60</div>
            <div id="controlsHelp">
                Controles: WASD para mover | Shift para correr
            </div>
        </div>
    </div>

    <script>
        // Dados do usuário para o JavaScript
        const userData = {
            id: <?php echo $usuario['id'] ?? 0; ?>,
            nome: '<?php echo $usuario['usuario']; ?>',
            nivel: <?php echo $usuario['nivel']; ?>,
            xp: <?php echo $usuario['xp']; ?>,
            x: <?php echo $usuario['x'] ?? 8; ?>,
            y: <?php echo $usuario['y'] ?? 8; ?>
        };
    </script>
    <script src="js/game.js"></script>
</body>
</html>