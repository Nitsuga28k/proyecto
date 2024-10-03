<?php
$id = $_GET["id"];
// Obtener ID del usuario a modificar
include_once("conexion.php");
$result = $conexion->query("SELECT * FROM usuarios WHERE idusu = $id");

include_once("../Servidor/conexion.php");

if (!empty($_POST)) {
  ///pa firma agregar los campos para funcione, replicar la insecion, consulta y edicion en consulta en CATEGORIAS

  //para validar que los campos no esten vacios
  if (empty($_POST['cam1']) || empty($_POST['cam2']) || empty($_POST['cam3']) || empty($_POST['cam4']) || empty($_POST['cam5']) || empty($_POST['cam6']) || empty($_POST['cam7'])) {
    $alert = '<div class="alert alert-primary" role="alert">Todos los datos son obligatorios</div>';
  } else {
    $id = $_GET["id"];
    $c1 = $_POST['cam1'];
    $c2 = $_POST['cam2'];
    $c3 = $_POST['cam3'];
    $c4 = $_POST['cam4'];
    $c5 = $_POST['cam5'];
    $c6 = $_POST['cam6'];
    $c7 = $_POST['cam7'];
    $sql = $conexion->query("UPDATE usuarios SET nomusu = '$c1', apausu =  '$c2', amausu =  '$c3', correo =  '$c4', contra =  '$c5', telefono =  '$c6', idtipo =  '$c7'
                            WHERE idusu = $id");
    

    if ($sql) {
      header('Location: ../Cliente/Uusaurios.php');
      $alert = '<div class="alert alert-success" role="alert">Datos actualizados correctamente</div>';
    } else {
      $alert = '<div class="alert alert-danger" role="alert">Error al actualizar los datos</div>';
    }
  }
}
?>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">MODIFICACIÓN</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="window.location.href='../Cliente/Uusaurios.php'"></button>
    </div>

    <div class="modal-body">
      <form style="padding: 5px; text-align:left;" method="POST">
        <div>
          <?php echo isset($alert) ? $alert : ""; ?>
        </div>
        <?php while ($modifc = $result->fetch_object()) { ?>

          <div class="form-group">
            <label for="cam1" class="form-label">Nombres</label>
            <input type="text" class="form-control" id="cam1" name="cam1" value="<?php echo $modifc->nomusu; ?>" readonly >
          </div>
          <div class="form-group">
            <label for="cam2" class="form-label">Apellido paterno</label>
            <input type="text" class="form-control" id="cam2" name="cam2" value="<?php echo $modifc->apausu; ?>">
          </div>
          <div class="form-group">
            <label for="cam3" class="form-label">Apellido materno</label>
            <input type="text" class="form-control" id="cam3" name="cam3" value="<?php echo $modifc->amausu; ?>">
          </div>
          <div class="form-group">
            <label for="cam4" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="cam4" name="cam4" value="<?php echo $modifc->correo; ?>">
          </div>
          <div class="form-group">
            <label for="cam5" class="form-label">Contraseña</label>
            <input type="text" class="form-control" id="cam5" name="cam5" value="<?php echo $modifc->contra; ?>">
          </div>
          <div class="form-group">
            <label for="cam6" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="cam6" name="cam6" value="<?php echo $modifc->telefono; ?>">
          </div>
          <div class="form-group">
            <label for="cam7" class="form-label">Tipo de usuario</label>
            <select class="form-select" id="cam7" name="cam7">
              <option selected>Tipo de usuario</option>
              <?php
              $tipos = mysqli_query($conexion, "SELECT * FROM tipousuario");
              while ($dat = mysqli_fetch_assoc($tipos)) { ?>
                <option value="<?php echo $dat['idtipo']; ?>" <?php echo ($dat['idtipo'] == $modifc->idtipo) ? 'selected' : ''; ?>>
                  <?php echo $dat['tipousu']; ?>
                </option>
              <?php } ?>
            </select>
          </div>

        <?php } ?>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="window.location.href='../Cliente/Uusaurios.php'">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar información</button>
        </div>
      </form>
    </div>
  </div>
</div>