const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");

// Faz o canvas preencher a tela
function resizeCanvas() {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
}
resizeCanvas();
window.addEventListener("resize", resizeCanvas);

// 🏙️ Fundo da cidade
const background = new Image();
background.src = "imagens/background.png";

// 👨 Personagem - Sprites de Animação
const playerSprites = [new Image(), new Image()];
playerSprites[0].src = "sprites/parado1.png";
playerSprites[1].src = "sprites/parado2.png";

let gravity = 0.8;
let groundY = 0;

const player = {
  x: 100,
  y: 0,
  vy: 0,
  onGround: false,
  width: 174,
  height: 174,
  name: "Player_GDP",
  // Controle de Animação
  currentFrame: 0,
  frameTimer: 0,
  frameRate: 30, // Animação mais lenta
  // 🧭 Propriedade para rastrear a direção: 1 para direita, -1 para esquerda
  direction: 1, 
};

const keys = {};

window.addEventListener("keydown", (e) => (keys[e.key] = true));
window.addEventListener("keyup", (e) => (keys[e.key] = false));

function update() {
  groundY = canvas.height - 120;

  // Lógica de Movimentação e atualização da direção
  if (keys["a"] || keys["ArrowLeft"]) {
      player.x -= 5;
      player.direction = -1; // Virar para a esquerda
  }
  if (keys["d"] || keys["ArrowRight"]) {
      player.x += 5;
      player.direction = 1; // Virar para a direita
  }
  
  // Lógica de Pulo
  if ((keys["w"] || keys["ArrowUp"]) && player.onGround) {
    player.vy = -15;
    player.onGround = false;
  }

  // Lógica de Gravidade e Colisão com o chão
  player.y += player.vy;
  player.vy += gravity;

  if (player.y >= groundY) {
    player.y = groundY;
    player.vy = 0;
    player.onGround = true;
  }

  // Lógica de Animação (Parado)
  player.frameTimer++;
  if (player.frameTimer >= player.frameRate) {
    player.frameTimer = 0;
    // Alterna entre os sprites (índice 0 e 1)
    player.currentFrame = (player.currentFrame + 1) % playerSprites.length;
  }
}

function draw() {
  ctx.drawImage(background, 0, 0, canvas.width, canvas.height);

  ctx.fillStyle = "#0000000e";
  ctx.fillRect(0, groundY + 32, canvas.width, canvas.height - groundY - 32);

  const currentSprite = playerSprites[player.currentFrame];
  
  // 1. Salva o estado ATUAL (sem transformações)
  ctx.save(); 

  // --- DESENHO E ESPELHAMENTO DO SPRITE ---
  const scaleX = player.direction;
  
  if (scaleX === -1) {
    // Aplica o espelhamento
    ctx.scale(scaleX, 1);
    
    // Desenha o sprite espelhado
    if (currentSprite.complete && currentSprite.naturalHeight !== 0) {
        ctx.drawImage(
          currentSprite,
          // Posição ajustada para compensar a inversão do eixo X
          -(player.x + player.width), 
          player.y - player.height,
          player.width,
          player.height
        );
    }
  } else {
    // Desenha o sprite normalmente
    if (currentSprite.complete && currentSprite.naturalHeight !== 0) {
        ctx.drawImage(
          currentSprite,
          player.x,
          player.y - player.height,
          player.width,
          player.height
        );
    }
  }
  
  // 2. Restaura o estado ORIGINAL (remove a transformação ctx.scale())
  ctx.restore(); 
  
  // 3. Desenha o nome *APÓS* restaurar, para que NÃO seja afetado pelo espelhamento
  ctx.fillStyle = "white";
  ctx.font = "20px Arial";
  ctx.textAlign = "center"; // Centraliza o texto para melhor posicionamento
  
  // Posição X: Centro do personagem (player.x + metade da largura)
  const nameX = player.x + player.width / 2;
  // Posição Y: Um pouco acima da cabeça do personagem
  const nameY = player.y - player.height - 10; 
  
  ctx.fillText(player.name, nameX, nameY);
  ctx.textAlign = "start"; // Restaura o alinhamento padrão (boa prática)
}

function loop() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  update();
  draw();
  requestAnimationFrame(loop);
}

// =============================================
// 🎭 MODAL DE PERFIL - ADICIONE A PARTIR DAQUI
// =============================================

const modal = document.getElementById('profileModal');
const openProfileBtn = document.getElementById('openProfile');
const profileName = document.getElementById('profileName');
const profileAvatar = document.getElementById('profileAvatar');
const followersCount = document.getElementById('followersCount');
const followingCount = document.getElementById('followingCount');
const postsCount = document.getElementById('postsCount');
const followBtn = document.getElementById('followBtn');
const editProfileBtn = document.getElementById('editProfileBtn');

let currentPlayerId = null;
let isFollowing = false;

// Função para verificar se o clique foi no personagem
function isClickOnPlayer(clickX, clickY) {
  return (
    clickX >= player.x &&
    clickX <= player.x + player.width &&
    clickY >= player.y - player.height &&
    clickY <= player.y
  );
}

// Detectar clique no canvas
canvas.addEventListener('click', function(event) {
  const rect = canvas.getBoundingClientRect();
  const clickX = event.clientX - rect.left;
  const clickY = event.clientY - rect.top;

  if (isClickOnPlayer(clickX, clickY)) {
    toggleModal();
  }
});

// Abrir/fechar modal
openProfileBtn.addEventListener('click', toggleModal);

// Fechar modal clicando fora
document.addEventListener('click', function(event) {
  if (!modal.contains(event.target) && !openProfileBtn.contains(event.target)) {
    modal.classList.remove('active');
  }
});

function toggleModal() {
  if (modal.classList.contains('active')) {
    modal.classList.remove('active');
  } else {
    loadPlayerProfile();
    modal.classList.add('active');
  }
}

// Carregar dados do perfil do jogador
async function loadPlayerProfile() {
  try {
    const userId = getLoggedUserId();
    
    const response = await fetch(`get-player-profile.php?id=${userId}`);
    const profileData = await response.json();
    
    if (profileData.success) {
      updateProfileUI(profileData.data);
    } else {
      console.error('Erro ao carregar perfil:', profileData.error);
    }
  } catch (error) {
    console.error('Erro:', error);
    // Fallback para dados locais
    updateProfileUI({
      nome: player.name,
      seguidores: 0,
      seguindo: 0,
      posts: 0,
      foto: null,
      isFollowing: false
    });
  }
}

// Atualizar interface do perfil
function updateProfileUI(profile) {
  profileName.textContent = profile.nome || player.name;
  followersCount.textContent = profile.seguidores || 0;
  followingCount.textContent = profile.seguindo || 0;
  postsCount.textContent = profile.posts || 0;
  
  // Atualizar avatar
  if (profile.foto) {
    profileAvatar.innerHTML = `<img src="uploads/${profile.foto}" alt="${profile.nome}">`;
  } else {
    profileAvatar.innerHTML = '👤';
  }
  
  // Atualizar botão de seguir
  isFollowing = profile.isFollowing || false;
  updateFollowButton();
}

// Atualizar botão de seguir
function updateFollowButton() {
  if (isFollowing) {
    followBtn.textContent = 'Seguindo';
    followBtn.classList.remove('btn-follow');
    followBtn.classList.add('btn-following');
  } else {
    followBtn.textContent = 'Seguir';
    followBtn.classList.remove('btn-following');
    followBtn.classList.add('btn-follow');
  }
}

// Ação de seguir/parar de seguir
followBtn.addEventListener('click', async function() {
  try {
    const userId = getLoggedUserId();
    const targetUserId = currentPlayerId || userId;
    
    const response = await fetch('segui-ajax.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        action: isFollowing ? 'unfollow' : 'follow',
        targetUserId: targetUserId
      })
    });
    
    const result = await response.json();
    
    if (result.success) {
      isFollowing = !isFollowing;
      updateFollowButton();
      
      // Atualizar contador de seguidores
      if (isFollowing) {
        followersCount.textContent = parseInt(followersCount.textContent) + 1;
      } else {
        followersCount.textContent = parseInt(followersCount.textContent) - 1;
      }
    }
  } catch (error) {
    console.error('Erro ao seguir:', error);
  }
});

// Botão editar perfil
editProfileBtn.addEventListener('click', function() {
  window.location.href = 'editar-perfil.php';
});

// Função para obter ID do usuário logado (você precisa implementar)
function getLoggedUserId() {
  // Tenta pegar do campo hidden que você vai adicionar no HTML
  const userIdElement = document.getElementById('userId');
  if (userIdElement && userIdElement.value) {
    return parseInt(userIdElement.value);
  }
  
  // Se não encontrar, usa um fallback
  console.warn('ID do usuário não encontrado no HTML, verifique se adicionou o campo hidden');
  
  // Fallback - você pode ajustar conforme necessário
  // Se estiver em ambiente de desenvolvimento, pode retornar um ID fixo
  // Ou buscar de outra forma (localStorage, cookie, etc.)
  return 1; // Apenas para teste - ajuste conforme sua necessidade
}
// No loadPlayerProfile(), adicione um console.log para ver o que está vindo:
async function loadPlayerProfile() {
  try {
    const userId = getLoggedUserId();
    
    console.log("🔄 Carregando perfil do usuário ID:", userId); // DEBUG
    
    const response = await fetch(`get-player-profile.php?id=${userId}`);
    const profileData = await response.json();
    
    console.log("📊 Dados recebidos:", profileData); // DEBUG
    
    if (profileData.success) {
      updateProfileUI(profileData.data);
    } else {
      console.error('Erro ao carregar perfil:', profileData.error);
    }
  } catch (error) {
    console.error('Erro:', error);
    // Fallback para dados locais
    updateProfileUI({
      nome: player.name,
      seguidores: 0,
      seguindo: 0,
      posts: 0,
      foto: null,
      isFollowing: false
    });
  }
}
// =============================================
// FIM DO MODAL - O LOOP DO JOGO VEM DEPOIS
// =============================================

// INICIA O JOGO - isso deve ficar NO FINAL
loop();