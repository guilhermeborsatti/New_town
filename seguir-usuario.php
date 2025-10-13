<?php
    include "incs/valida-sessao.php";   
    require_once "src/UsuarioDAO.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <title>Seguir pessoas</title>
</head>
<body>
     <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">Meu Projeto</a>      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="#">Início</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Sobre</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Contato</a></li>
              <li class="nav-item"><a class="nav-link" href="seguir-usuarios">Seguir usuarios</a></li>
        </ul>
      </div>
    </div>
  </nav>

   
    <main >
        <h3 class="text-center">Siga Pessoas</h3>
        <form>
            <div class="mb-3 col-8">
                <label class="form-label">Nome do usuario</label>
                <input type="text"class="form-control" name="nome" placeholder="Nome"  >
            </div>
            <div class="mb-3 col-4">
                <button type="submit" class="btn btn-primary">Buscar</button> 
            </div>
            <?php
            require_once "src/UsuarioDAO.php";

            if (!isset($_GET['nome'])) {
              $_GET['nome'] = '' ;
              
            }
            $usuarios = UsuarioDAO :: buscarUsuarios($_SESSION['idusuario'], $_GET['nome']);

     
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
    

    <footer class="bg-dark text-white text-center py-3 mt-auto">
    <p class="mb-0">&copy; 2025 - Minha Página. Todos os direitos reservados.</p>
  </footer>
</body>
</html>