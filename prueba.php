<?php
//Si se pulsa el botón de enviar
if (isset($_POST['enviar'])) {
   //Si el checkbox condiciones tiene valor y es igual a 1
   if (isset($_POST['condiciones']) && $_POST['condiciones'] == '1')
      echo '<div style="color:green">Has aceptado correctamente las condiciones de uso.</div>';
   else
      echo '<div style="color:red">Debes aceptar las condiciones de uso.</div>';
}
?>
<form action="prueba.php" method="post">
<input type="checkbox" name="condiciones" value="1"> Aceptar condiciones de uso<br><br>
<input type="submit" name="enviar" value="Enviar"/>
</form>