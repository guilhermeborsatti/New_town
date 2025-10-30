<?php
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
        display: block;
      }

      /* Modal */
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

      .profile-header {
        background: #34495e;
        padding: 15px;
        border-radius: 10px 10px 0 0;
        text-align: center;
      }

      .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin: 0 auto 10px;
        background: #ecf0f1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        color: #2c3e50;
        overflow: hidden;
      }

      .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      .profile-info {
        padding: 15px;
      }

      .profile-stats {
        display: flex;
        justify-content: space-around;
        margin: 15px 0;
        text-align: center;
      }

      .stat {
        flex: 1;
      }

      .stat-number {
        font-size: 18px;
        font-weight: bold;
        color: #3498db;
      }

      .stat-label {
        font-size: 12px;
        color: #bdc3c7;
      }

      .profile-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
      }

      .btn {
        flex: 1;
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        transition: background 0.3s;
      }

      .btn-follow {
        background: #3498db;
        color: white;
      }

      .btn-follow:hover {
        background: #2980b9;
      }

      .btn-following {
        background: #27ae60;
        color: white;
      }

      .btn-edit {
        background: #e74c3c;
        color: white;
      }

      .btn-edit:hover {
        background: #c0392b;
      }

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

      #openProfile:hover {
        background-color: #2980b9;
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
        <h3 id="profileName">Carregando...</h3>
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

    <!-- 🔗 Script do jogo REAL -->
    <script src="script.js"></script>

    <!-- Código Live Server -->
    <script>
      // Live Server code...
    </script>
  </body>
</html>