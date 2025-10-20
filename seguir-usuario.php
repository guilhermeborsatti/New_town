<?php
     
    include "incs/topo.php";
?>


   
    <main >
        <h3 class="text-center">Siga Pessoas</h3>
        <form>
            <div class="mb-3 col-8">
                <label class="form-label">Nome do usuario</label>
                <input type="text"class="form-control" name="nome" placeholder="Nome"  >
            </div>
            <div class="mb-3 col-8">
                <button type="submit" class="btn btn-primary mb-3 col-8 ">Buscar</button> 
            </div>
            <?php
            require_once "src/UsuarioDAO.php";

            if (isset($_GET['nome'])) {
              
            $usuarios = UsuarioDAO :: buscarUsuarioParaSeguir($_SESSION['idusuario'], $_GET['nome']);
}else{
  $usuarios = [];
}   
            foreach ($usuarios as $usuario) {

            ?>

            <p class=" m-3"><?=$usuario['nome']?>
            <a href="seguir.php?idseguido=<?=$usuario['idusuario']?>" class="btn btn-primary mx-3">
                adicionar
            </a>
            </p>

            <?php
            }
            ?>
        </form>
    </main>