<?php
    include "incs/valida-sessao.php";   
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="stylesheet" href="assets/teste.css">

    <title>Home</title>
</head>
<body>
     <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">New-Town <?=$_SESSION['nome']?></a>      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="home.php">Início</a></li>
          <li class="nav-item"><a class="nav-link" href="teste.php">Criação pixel-Art</a></li>
           <li class="nav-item"><a class="nav-link" href="nova-postagem.php">Postar</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Contato</a></li>
            <a class="nav-link" href="seguir-usuario.php">Usuários</a>
                    <a class="nav-link" href="menu.php">Game-Menu</a>
        </ul>
      </div>
    </div>
  </nav>


<footer class="bg-dark text-white text-center py-3"
        style="position: fixed; bottom: 0; left: 0; width: 100%;">
  <p class="mb-0">&copy; 2025 - Minha Página. Todos os direitos reservados.</p>
</footer>

</body>

</html>