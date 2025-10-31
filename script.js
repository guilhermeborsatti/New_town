const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");

// =============================================
// 🎮 SISTEMA DO JOGO
// =============================================

// Faz o canvas preencher a tela
function resizeCanvas() {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
  console.log("📏 Canvas redimensionado:", canvas.width, "x", canvas.height);
}
resizeCanvas();
window.addEventListener("resize", resizeCanvas);

// 🏙️ Fundo da cidade
const background = new Image();
background.src = "img/background.png";
background.onload = () => console.log("✅ Fundo carregado");
background.onerror = () => console.error("❌ Erro ao carregar fundo");

// 👨 Personagem - Sprites de Animação
const playerSprites = [new Image(), new Image()];
playerSprites[0].src = "sprites/parado1.png";
playerSprites[1].src = "sprites/parado2.gif";

// Verificar carregamento dos sprites
playerSprites.forEach((sprite, index) => {
  sprite.onload = () => console.log(`✅ Sprite ${index} carregado`);
  sprite.onerror = () => console.error(`❌ Erro ao carregar sprite ${index}`);
});

let gravity = 0.8;
let groundY = 0;

// 🔥 PLAYER AGORA USA O NOME DO USUÁRIO LOGADO
const player = {
  x: 100,
  y: 0,
  vy: 0,
  onGround: false,
  width: 174,
  height: 174,
  name: usuarioLogado.nome || "Player_GDP", // Nome da sessão do PHP
  // Controle de Animação
  currentFrame: 0,
  frameTimer: 0,
  frameRate: 30,
  // 🧭 Direção: 1 para direita, -1 para esquerda
  direction: 1, 
};

const keys = {};

window.addEventListener("keydown", (e) => {
  keys[e.key] = true;
  console.log("⌨️ Tecla pressionada:", e.key);
});
window.addEventListener("keyup", (e) => (keys[e.key] = false));

function update() {
  groundY = canvas.height - 120;

  // Lógica de Movimentação
  if (keys["a"] || keys["ArrowLeft"]) {
    player.x -= 5;
    player.direction = -1;
    console.log("⬅️ Movendo para esquerda");
  }
  if (keys["d"] || keys["ArrowRight"]) {
    player.x += 5;
    player.direction = 1;
    console.log("➡️ Movendo para direita");
  }
  
  // Lógica de Pulo
  if ((keys["w"] || keys["ArrowUp"]) && player.onGround) {
    player.vy = -15;
    player.onGround = false;
    console.log("🦘 Pulando!");
  }

  // Gravidade e Colisão
  player.y += player.vy;
  player.vy += gravity;

  if (player.y >= groundY) {
    player.y = groundY;
    player.vy = 0;
    player.onGround = true;
  }

  // Animação
  player.frameTimer++;
  if (player.frameTimer >= player.frameRate) {
    player.frameTimer = 0;
    player.currentFrame = (player.currentFrame + 1) % playerSprites.length;
  }
}

function draw() {
  // Limpa o canvas
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  // Desenha fundo
  if (background.complete) {
    ctx.drawImage(background, 0, 0, canvas.width, canvas.height);
  } else {
    // Fallback se o fundo não carregar
    ctx.fillStyle = "#87CEEB";
    ctx.fillRect(0, 0, canvas.width, canvas.height);
  }

  // Sombra do chão
  ctx.fillStyle = "#0000000e";
  ctx.fillRect(0, groundY + 32, canvas.width, canvas.height - groundY - 32);

  const currentSprite = playerSprites[player.currentFrame];
  
  // Desenho do personagem com espelhamento
  ctx.save(); 

  if (player.direction === -1) {
    ctx.scale(-1, 1);
    if (currentSprite.complete) {
      ctx.drawImage(
        currentSprite,
        -(player.x + player.width), 
        player.y - player.height,
        player.width,
        player.height
      );
    } else {
      // Fallback se sprite não carregar
      ctx.fillStyle = "red";
      ctx.fillRect(-(player.x + player.width), player.y - player.height, player.width, player.height);
    }
  } else {
    if (currentSprite.complete) {
      ctx.drawImage(
        currentSprite,
        player.x,
        player.y - player.height,
        player.width,
        player.height
      );
    } else {
      // Fallback se sprite não carregar
      ctx.fillStyle = "blue";
      ctx.fillRect(player.x, player.y - player.height, player.width, player.height);
    }
  }
  
  ctx.restore(); 
  
  // Nome do jogador
  ctx.fillStyle = "white";
  ctx.font = "20px Arial";
  ctx.textAlign = "center";
  
  const nameX = player.x + player.width / 2;
  const nameY = player.y - player.height - 10; 
  
  ctx.fillText(player.name, nameX, nameY);
  ctx.textAlign = "start";

  // Debug: mostra área de clique
  ctx.strokeStyle = "rgba(0, 255, 0, 0.5)";
  ctx.lineWidth = 2;
  ctx.strokeRect(player.x, player.y - player.height, player.width, player.height);
}

function loop() {
  update();
  draw();
  requestAnimationFrame(loop);
}

// =============================================
// 🎭 SISTEMA DO MODAL DE PERFIL
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
  const playerTop = player.y - player.height;
  const playerBottom = player.y;
  const playerLeft = player.x;
  const playerRight = player.x + player.width;

  const dentroX = clickX >= playerLeft && clickX <= playerRight;
  const dentroY = clickY >= playerTop && clickY <= playerBottom;

  console.log("🎯 Verificando clique no personagem:", {
    clickX, clickY,
    playerX: player.x, playerY: player.y,
    playerTop, playerBottom,
    playerLeft, playerRight,
    dentroX, dentroY
  });

  return dentroX && dentroY;
}

// Detectar clique no canvas
canvas.addEventListener('click', function(event) {
  const rect = canvas.getBoundingClientRect();
  const clickX = event.clientX - rect.left;
  const clickY = event.clientY - rect.top;

  console.log("🖱️ Clique no canvas:", { x: clickX, y: clickY });

  if (isClickOnPlayer(clickX, clickY)) {
    console.log("✅ Clique NO PERSONAGEM - Abrindo modal");
    toggleModal();
  } else {
    console.log("❌ Clique FORA do personagem");
  }
});

// Abrir/fechar modal pelo botão
openProfileBtn.addEventListener('click', function() {
  console.log("🔘 Botão do modal clicado");
  toggleModal();
});

// Fechar modal clicando fora
document.addEventListener('click', function(event) {
  if (!modal.contains(event.target) && !openProfileBtn.contains(event.target)) {
    modal.classList.remove('active');
    console.log("🚪 Modal fechado (clique fora)");
  }
});

function toggleModal() {
  if (modal.classList.contains('active')) {
    modal.classList.remove('active');
    console.log("🚪 Modal fechado");
  } else {
    loadPlayerProfile();
    modal.classList.add('active');
    console.log("📂 Modal aberto");
  }
}

// Carregar dados do perfil do jogador
async function loadPlayerProfile() {
  try {
    const userId = getLoggedUserId();
    console.log("🔄 Carregando perfil do usuário ID:", userId);

    const response = await fetch(`get-player-profile.php?id=${userId}`);
    
    if (!response.ok) {
      throw new Error(`Erro HTTP: ${response.status}`);
    }
    
    const profileData = await response.json();
    console.log("📊 Dados recebidos do servidor:", profileData);

    if (profileData.success) {
      updateProfileUI(profileData.data);
      console.log("✅ Perfil carregado com sucesso");
    } else {
      console.error('❌ Erro do servidor:', profileData.error);
      // Fallback para dados locais
      useFallbackProfile();
    }
  } catch (error) {
    console.error('❌ Erro ao carregar perfil:', error);
    // Fallback para dados locais
    useFallbackProfile();
  }
}

// Fallback caso o servidor não responda
function useFallbackProfile() {
  console.log("🔄 Usando dados fallback");
  
  // 🔥 USA OS DADOS DO USUÁRIO LOGADO COMO FALLBACK
  updateProfileUI({
    nome: usuarioLogado.nome || player.name,
    seguidores: 0,
    seguindo: 0,
    posts: 0,
    foto: usuarioLogado.foto || null,
    isFollowing: false
  });
}

// Atualizar interface do perfil
function updateProfileUI(profile) {
  console.log("🎨 Atualizando UI do perfil:", profile);
  
  profileName.textContent = profile.nome || player.name;
  followersCount.textContent = profile.seguidores || 0;
  followingCount.textContent = profile.seguindo || 0;
  postsCount.textContent = profile.posts || 0;
  
  // Atualizar avatar
  if (profile.foto) {
    profileAvatar.innerHTML = `<img src="uploads/${profile.foto}" alt="${profile.nome}" onerror="this.style.display='none'; this.parentElement.innerHTML='👤';">`;
    console.log("🖼️ Foto definida:", profile.foto);
  } else {
    profileAvatar.innerHTML = '👤';
    console.log("🖼️ Usando avatar padrão");
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
    console.log("✅ Botão: Seguindo");
  } else {
    followBtn.textContent = 'Seguir';
    followBtn.classList.remove('btn-following');
    followBtn.classList.add('btn-follow');
    console.log("🔘 Botão: Seguir");
  }
}

// Ação de seguir/parar de seguir
followBtn.addEventListener('click', async function() {
  console.log("👥 Botão seguir clicado");
  try {
    const userId = getLoggedUserId();
    const targetUserId = currentPlayerId || userId;
    
    console.log("🔄 Enviando ação:", isFollowing ? 'unfollow' : 'follow');
    
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
    console.log("📨 Resposta do servidor:", result);
    
    if (result.success) {
      isFollowing = !isFollowing;
      updateFollowButton();
      
      // Atualizar contador de seguidores
      const currentFollowers = parseInt(followersCount.textContent);
      if (isFollowing) {
        followersCount.textContent = currentFollowers + 1;
        console.log("📈 Seguidor adicionado");
      } else {
        followersCount.textContent = currentFollowers - 1;
        console.log("📉 Seguidor removido");
      }
    } else {
      console.error('❌ Erro na ação:', result.error);
    }
  } catch (error) {
    console.error('❌ Erro ao seguir:', error);
  }
});

// Botão editar perfil
editProfileBtn.addEventListener('click', function() {
  console.log("✏️ Redirecionando para editar perfil");
  window.location.href = 'editar-perfil.php';
});

// Função para obter ID do usuário logado
function getLoggedUserId() {
  // 🔥 USA O ID DO USUÁRIO LOGADO
  if (usuarioLogado && usuarioLogado.id) {
    console.log("🔑 ID do usuário encontrado:", usuarioLogado.id);
    return usuarioLogado.id;
  }
  
  // Fallback para os campos hidden (mantido para compatibilidade)
  const userIdElement = document.getElementById('userId');
  if (userIdElement && userIdElement.value) {
    const id = parseInt(userIdElement.value);
    console.log("🔑 ID do usuário encontrado (fallback):", id);
    return id;
  }
  
  console.warn('⚠️ ID do usuário não encontrado, usando fallback');
  
  // Tenta pegar da URL ou usa fallback
  const urlParams = new URLSearchParams(window.location.search);
  const idFromUrl = urlParams.get('id');
  if (idFromUrl) {
    return parseInt(idFromUrl);
  }
  
  return 1; // Fallback para desenvolvimento
}

// =============================================
// 🚀 INICIALIZAÇÃO DO JOGO
// =============================================

// Aguarda um pouco para garantir que tudo carregou
window.addEventListener('load', function() {
  console.log("🎮 Iniciando jogo...");
  console.log("👤 Nome do personagem:", player.name);
  console.log("🎯 Dicas:");
  console.log("   - Use WASD ou setas para mover");
  console.log("   - Clique no personagem para abrir o perfil");
  console.log("   - Ou use o botão 'Meu Perfil' no canto");
  
  // Inicia o loop do jogo
  loop();
  
  // Verifica se os elementos do modal existem
  if (!modal) console.error("❌ Modal não encontrado");
  if (!openProfileBtn) console.error("❌ Botão do modal não encontrado");
  
  console.log("✅ Jogo inicializado com sucesso!");
});

// Fecha modal com ESC
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape' && modal.classList.contains('active')) {
    modal.classList.remove('active');
    console.log("🚪 Modal fechado com ESC");
  }
});