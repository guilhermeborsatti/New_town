<?php
// Remove session_start() daqui - já está no valida-sessao.php
include("incs/valida-sessao.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jogo Plataforma 2D</title>
    <link rel="stylesheet" href="assets/style.css" />
    <style>
      /* 🔥 Canvas básico */
      #gameCanvas {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #87CEEB; /* Azul céu para ver se aparece */
        display: block; /* IMPORTANTE */
      }

      /* Modal (mantenha igual) */
      .profile-modal {
        display: none;
        position: fixed;
        top: 70px;
        right: 20px;
        z-index: 1000;
        background: #2c3e50;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        width: 300px;
        color: white;
        padding: 0;
      }

      .profile-modal.active {
        display: block;
      }

      /* ... (mantenha o resto do CSS do modal igual) ... */

      #openProfile {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 100;
        padding: 10px 15px;
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
      }

      body {
        margin: 0;
        padding: 0;
        overflow: hidden;
      }
    </style>
  </head>

  <body>
    <!-- 🎮 Canvas do jogo -->
    <canvas id="gameCanvas"></canvas>

    <!-- 👤 Botão para abrir o perfil -->
    <button id="openProfile">👤 Meu Perfil</button>

    <!-- 🎭 Modal de Perfil -->
    <div id="profileModal" class="profile-modal">
      <div class="profile-header">
        <div class="profile-avatar" id="profileAvatar">
          👤
        </div>
        <h3 id="profileName"><?php echo $_SESSION['nome'] ?? 'Player_GDP'; ?></h3>
      </div>
      
      <div class="profile-info">
        <div class="profile-stats">
          <div class="stat">
            <div class="stat-number" id="followersCount">0</div>
            <div class="stat-label">Seguidores</div>
          </div>
          <div class="stat">
            <div class="stat-number" id="followingCount">0</div>
            <div class="stat-label">Seguindo</div>
          </div>
          <div class="stat">
            <div class="stat-number" id="postsCount">0</div>
            <div class="stat-label">Posts</div>
          </div>
        </div>
        
        <div class="profile-actions">
          <button id="followBtn" class="btn btn-follow">Seguir</button>
          <button id="editProfileBtn" class="btn btn-edit">Editar</button>
        </div>
      </div>
    </div>

    <!-- Campos hidden -->
    <input type="hidden" id="userId" value="<?php echo $_SESSION['idusuario'] ?? ''; ?>">
    <input type="hidden" id="userName" value="<?php echo $_SESSION['nome'] ?? ''; ?>">
    <input type="hidden" id="userPhoto" value="<?php echo $_SESSION['foto'] ?? ''; ?>">

    <!-- 🔗 TESTE SIMPLES - remova o script.js complexo por enquanto -->
    <script>
      console.log("🎯 Iniciando teste do jogo...");
      
      const canvas = document.getElementById("gameCanvas");
      const ctx = canvas.getContext("2d");
      
      // Redimensiona o canvas
      function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        console.log("📏 Canvas:", canvas.width, "x", canvas.height);
      }
      
      // Desenha o jogo básico
      function draw() {
        // Fundo
        ctx.fillStyle = "#87CEEB";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // Chão
        ctx.fillStyle = "#8B4513";
        ctx.fillRect(0, canvas.height - 100, canvas.width, 100);
        
        // Personagem
        ctx.fillStyle = "red";
        ctx.fillRect(100, canvas.height - 200, 50, 100);
        
        // Nome do jogador
        ctx.fillStyle = "black";
        ctx.font = "20px Arial";
        ctx.fillText("<?php echo $_SESSION['nome'] ?? 'Player_GDP'; ?>", 80, canvas.height - 220);
        
        // Mensagem
        ctx.fillStyle = "white";
        ctx.font = "16px Arial";
        ctx.fillText("Clique no personagem vermelho para abrir o perfil!", 50, 50);
      }
      
      // Inicializa
      resizeCanvas();
      draw();
      window.addEventListener("resize", resizeCanvas);
      
      // Sistema simples do modal
      const modal = document.getElementById('profileModal');
      const openProfileBtn = document.getElementById('openProfile');
      
      openProfileBtn.addEventListener('click', function() {
        modal.classList.toggle('active');
      });
      
      // Fechar modal clicando fora
      document.addEventListener('click', function(event) {
        if (!modal.contains(event.target) && !openProfileBtn.contains(event.target)) {
          modal.classList.remove('active');
        }
      });
      
      // Clique no personagem (área vermelha)
      canvas.addEventListener('click', function(event) {
        const rect = canvas.getBoundingClientRect();
        const clickX = event.clientX - rect.left;
        const clickY = event.clientY - rect.top;
        
        // Verifica se clicou no personagem (área vermelha)
        if (clickX >= 100 && clickX <= 150 && clickY >= canvas.height - 200 && clickY <= canvas.height - 100) {
          modal.classList.add('active');
          console.log("🎭 Modal aberto pelo clique no personagem!");
        }
      });
      
      console.log("✅ Jogo carregado! Clique no quadrado vermelho.");
    </script>

    <!-- Código Live Server (opcional) -->
    <script>
      // Live Server code...
    </script>
  </body>
</html>