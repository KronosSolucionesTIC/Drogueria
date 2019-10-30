<? include("js/funciones.php")?>
<? include("lib/database.php")?>
<link href="informes/styles.css" rel="stylesheet" type="text/css" />
<link href="informes/styles1.css" rel="stylesheet" type="text/css" />
<link href="styles.css" rel="stylesheet" type="text/css" />
<link href="css/stylesforms.css" rel="stylesheet" type="text/css" />
<link href="css/styles2.css" rel="stylesheet" type="text/css" />
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<TABLE width="100%" border="1" cellpadding="2" cellspacing="1"   class="textoproductos1">
  <tr>
    <td colspan="8" class="ctablasup">CLIENTES</td>
  </tr>
  <TR>
    <TD width="15%" height="16" class="subfongris">NOMBRE </TD>
    <TD width="15%" height="16"class="subfongris">NIT</TD>
    <TD width="15%" class="subfongris">DIRECCION</TD>
    <TD width="12%" class="subfongris">CUIDAD</TD>
    <TD width="12%" class="subfongris">RUTA</TD>
    <TD width="16%" class="subfongris" >REGIMEN</TD>
    <TD width="15%" class="subfongris" >TELEFONOS</TD>
    <TD width="15%" class="subfongris" >EMAIL</TD>
  </tr>
  <?
$db = new Database();
$sql="SELECT *,CONCAT(nom_bod,apel_bod) as nombre FROM bodega1 
INNER JOIN ciudad ON (ciudad.cod_ciudad =bodega1.ciu_bod )
INNER JOIN ruta ON (ruta.cod_ruta = bodega1.cod_ruta )
WHERE estado_bodega1 = 1 
ORDER BY nombre  ASC";
$db->query($sql);
while($db->next_row()){
		echo "<TR>";
		echo "<TD class='ctablablanc' >$db->nombre</TD>";
		echo "<TD class='ctablablanc' >$db->iden_bod</TD>";
		echo "<TD class='ctablablanc' >$db->dir_bod</TD>";
		echo "<TD class='ctablablanc' >$db->desc_ciudad</TD>";
		echo "<TD class='ctablablanc' >$db->desc_ruta</TD>";
		echo "<TD class='ctablablanc' >$db->regimen_cli</TD>";
		echo "<TD class='ctablablanc' >$db->tel_bod</TD>";
		echo "<TD class='ctablablanc' >$db->mail_bod</TD>";			
		echo "</TR>";
}
	?>
	<TD height="16" colspan="8" class="tituloproductos" align="center"><INPUT type="button" value="Imprimir" class="botones"  onclick="abrir()">
	  </TR>
</TABLE>
<script>
function abrir(){
	window.print();
}
</script>