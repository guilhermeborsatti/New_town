<?php

include "incs/topo.php";




?>

<body>
<main>
    <aside>
        <span class="option-title">Tamanho:</span>
        <input type="number" class="input-size" min="4"value="10"> 

        <span class="option-title">Cor:</span>
        <input type="color" class="input-color">

        <span class="option-title">Cores usadas:</span>
        <section class="used-colors"></section>

        <button class="button-save">Baixar imagem</button>
    </aside>

    <section class="canvas"></section>
    <div class="resize"></div>
</main>

<script src="js/teste.js"></script>
</body>