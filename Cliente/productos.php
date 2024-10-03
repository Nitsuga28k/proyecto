<?php

include_once("../Servidor/conexion.php");
if (!empty($_POST)) {
    $alert = "";

    // Validación de campos vacíos
    if (empty($_POST['nombre']) || empty($_POST['descripcion']) || empty($_POST['cantidad']) || empty($_POST['precio']) || empty($_POST['color']) || empty($_POST['tamanio']) || empty($_FILES['foto']['name']) || empty($_POST['idcate'])) {
        $alert = '<div class="alert alert-primary" role="alert">Todos los datos son obligatorios</div>';
    } else {
        // Verifica si se ha enviado una imagen
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto'])) {
            // Directorio donde se guardarán las imágenes
            $directorio = '../imagenes/';

            // Asegurarse de que el directorio exista, si no, crearlo
            if (!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }

            // Información del archivo subido
            $nombreArchivo = basename($_FILES['foto']['name']);
            $rutaArchivo = $directorio . $nombreArchivo;
            $tipoArchivo = strtolower(pathinfo($rutaArchivo, PATHINFO_EXTENSION));

            // Validar el tipo de archivo
            $tiposPermitidos = array('jpg', 'jpeg', 'png', 'gif');
            if (!in_array($tipoArchivo, $tiposPermitidos)) {
                echo "Error: Solo se permiten archivos de imagen (JPG, JPEG, PNG, GIF).";
                exit;
            }

            // Validar el tamaño del archivo (máximo 2MB)
            $tamanoMaximo = 2 * 1024 * 1024; // 2MB
            if ($_FILES['foto']['size'] > $tamanoMaximo) {
                echo "Error: El tamaño de la imagen es demasiado grande.";
                exit;
            }

            // Intentar mover el archivo a la ubicación deseada
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaArchivo)) {
                // Recogiendo datos del formulario
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $cantidad = $_POST['cantidad'];
                $precio = $_POST['precio'];
                $color = $_POST['color'];
                $tamanio = $_POST['tamanio'];
                $foto = $rutaArchivo;
                $idcate = $_POST['idcate'];

                // Query para insertar el nuevo producto
                $consulta = mysqli_query($conexion, "INSERT INTO productos (nombre, descripcion, cantidad, precio, color, tamanio, foto, idcate) VALUES ('$nombre', '$descripcion', '$cantidad', '$precio', '$color', '$tamanio', '$foto', '$idcate')");

                if ($consulta) {
                    header("Location: " . $_SERVER['PHP_SELF'] . "?insert=success");
                    exit();
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">Error al guardar la información: ' . mysqli_error($conexion) . '</div>';
                }
            } else {
                echo "Error al subir la imagen.";
            }
        } else {
            echo "No se ha seleccionado ninguna imagen.";
        }
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.5.1/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.5.1/uicons-regular-rounded/css/uicons-regular-rounded.css'>

    <title>PRODUCTOS</title>
</head>


<body>
    <?php include_once("include/encabezado.php"); ?>
    <div class="container" style="text-align:center">
        <h2>Administración de Productos</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Nuevo Producto
        </button>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Color</th>
                    <th scope="col">Tamaño</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $con = mysqli_query(
                    $conexion,
                    "SELECT p.*, c.categoria 
                 FROM productos p 
                 INNER JOIN categorias c ON p.idcate = c.idcate"
                );
                while ($datos = mysqli_fetch_assoc($con)) {
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($datos['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($datos['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($datos['cantidad']); ?></td>
                        <td><?php echo htmlspecialchars($datos['precio']); ?></td>
                        <td><?php echo htmlspecialchars($datos['color']); ?></td>
                        <td><?php echo htmlspecialchars($datos['tamanio']); ?></td>
                        <td>
                            <?php
                            $rutaImagen = htmlspecialchars($datos['foto']);
                            echo "<img src='$rutaImagen' width='50px' height='50px'>";
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($datos['categoria']); ?></td>
                        <?php
                        //se abre llave (amarilla) para mostrar solo lo que queremos que muestre a tal usuario
                        if ($_SESSION['rol'] == 1) {;     ?>
                            <!--BOTON DE EDITAR-->
                            <td><a href="editar_producto.php? id= <?php echo $datos['idprod'] ?>">
                                    <button type="button" class="btn btn-secondary"><i class="fi fi-rr-blog-pencil"></i>
                                    </button>
                                </a>
                                <!--BOTON DE BORRAR-->
                                <a href="../Servidor/borrar_producto.php?id=  <?php echo $datos['idprod'] ?>">
                                    <button type="button" class="btn btn-danger"><i class="fi fi-rr-trash-xmark"></i>
                                    </button>
                                </a>
                            </td>

                            <!--SE CIERRA LA LLAVE PARA MOSTRAR A CIERTOS USUARIOS -->
                        <?php   } ?>

                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Registro de Productos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div><?php echo isset($alert) ? $alert : ""; ?></div>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Nombre</span>
                            <input type="text" class="form-control" name="nombre">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Descripción</span>
                            <input type="text" class="form-control" name="descripcion">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Cantidad</span>
                            <input type="text" class="form-control" name="cantidad">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Precio</span>
                            <input type="text" class="form-control" name="precio">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Color</span>
                            <input type="text" class="form-control" name="color">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Tamaño</span>
                            <input type="text" class="form-control" name="tamanio">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Foto</span>
                            <input type="file" class="form-control" name="foto" required>
                        </div>
                        <br>
                        <select class="form-select" name="idcate">
                            <?php
                            $cone = mysqli_query($conexion, "SELECT * FROM categorias");
                            while ($datos = mysqli_fetch_assoc($cone)) {
                            ?>
                                <option value="<?php echo $datos['idcate']; ?>"><?php echo $datos['categoria']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <?php include_once("include/footer.php"); ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>