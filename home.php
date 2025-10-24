<?php

include "incs/topo.php";


require_once "src/PostagemDAO.php";


session_start();
$idusuario = $_SESSION['idusuario']; 

// Busca as postagens da timeline do usuário
$postagens = PostagemDAO::listarTimeline($idusuario);


?>
   <main class="container my-4">
    <h2 class="display-4">Bem-Vindo</h2>

    <?php
   
    if (count($postagens) > 0) {
        foreach ($postagens as $p) {
            ?>
            <div class="card mb-3">
                <div class="card-body">
               
                    <div class="d-flex align-items-center">
                        <img src="<?= htmlspecialchars($p['foto']) ?>" alt="Foto de perfil" class="rounded-circle" width="40" height="40">
                        <h5 class="card-title ms-3"><?= htmlspecialchars($p['nome']) ?></h5>
                    </div>

      
                    <?php if (!empty($p['foto'])): ?>
                        <img src="<?= htmlspecialchars($p['foto']) ?>" alt="Foto da postagem" class="img-fluid mt-3">
                    <?php endif; ?>

                
                    <p class="card-text mt-3"><?= nl2br(htmlspecialchars($p['texto'])) ?></p>

                    <p class="text-muted small">Postado em <?= htmlspecialchars($p['criado_em']) ?></p>

             
                    <a href="comentar.php?idpostagem=<?= $p['idpostagem'] ?>" class="btn btn-primary">Comentar</a>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>Nenhuma postagem encontrada.</p>";
    }
    ?>
</main>

   