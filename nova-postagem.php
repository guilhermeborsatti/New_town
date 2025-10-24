<?php
include "incs/topo.php";
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2 class="mb-4">Nova Postagem</h2>
            <form action="processa-postagem.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label  class="form-label">Texto</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                </div>

                <div class="mb-3">
                    <label  class="form-label">Foto</label>
                    <input type="file" class="form-control" id="foto" name="foto" required>
                </div>

                <div class="mb-3">
                    <label  class="form-label">Visibilidade</label>
                    <select class="form-select" id="visibilidade" name="visibilidade" required>
                        <option value="publico">Público</option>
                        <option value="privado">Privado</option>
                        </select>
                </div>

                <button type="submit" class="btn btn-primary">Publicar</button>
            </form>

</div>