<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>INICIO</title>
</head>



<body>
    <header>
        <!--ENCABEZADO-->
        <?php include_once("include/encabezado.php"); ?>
        <!--ENCABEZADO-->
    </header>



    <h1>Hola, este es el apartado productos de la pagina!</h1>



    <div class="container" style="text-align: center;">




        <?php if ($_SESSION['rol'] == 1) {;   ?>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">AGREGAR NUEVO PRODUCTO</button>
        <?php    } ?>
    </div>







    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Registro de Productos</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body">
                    <form style="padding:   5px; text-align:left;" method="POST">
                        <div>
                            <?php echo isset($alert) ? $alert : ""; ?>
                        </div>
                        <div class="form-group">
                            <label for="correo" class="form-label">Clave del producto</label>
                            <input type="email" class="form-control" id="cam1" name="cam1" aria-describedby="emailHelp">

                        </div>
                        <div class="form-group">
                            <label for="contra" class="form-label">Nombre del producto</label>
                            <input type="password" class="form-control" id="cam2" name="cam2">
                        </div>

                        <label for="contra" class="form-label">Seleccione su tipo de categoria</label>
                        <select class="form-select" aria-label="Default select example" id="cam7" name="cam7">
                            <option selected>Elija la categoria</option>

                            <?php
                            include_once("../Servidor/conexion.php");
                            $cone = mysqli_query(
                                $conexion,
                                "SELECT * FROM tipousuario ;"
                            );
                            $res = mysqli_num_rows($cone);
                            while ($dat = mysqli_fetch_assoc($cone)) {

                            ?>
                                <option value=<?php echo $dat['idtipo'];  ?>> <?php echo $dat['tipousu'];          ?>
                                </option>
                            <?php } ?>
                        </select>


                        <div class="form-group">
                            <label for="contra" class="form-label">Precio del producto</label>
                            <input type="password" class="form-control" id="cam2" name="cam2">
                        </div>
                </div>



                </form>





            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar informacion</button>
            </div>
        </div>
    </div>
    </div>
    <!--termina modal -->





    <footer>
        <!--PIE-->
        <?php include_once("include/footer.php"); ?>
        <!--PIE-->
    </footer>

</body>




</html>