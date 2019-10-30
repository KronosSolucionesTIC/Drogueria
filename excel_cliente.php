<?
include "lib/sesion.php";
include("lib/database.php");
include("conf/clave.php");				
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Exportar ventas</title>
<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
<script language="javascript">
$(document).ready(function() {
	$(".botonExcel").click(function(event) {
		document.forma.submit();
        
});
});

</script>
<style type="text/css">
.botonExcel{cursor:pointer;}
</style>
</head>
<body>
<form action="exportar_cliente.php" method="post" enctype="multipart/form-data" id="forma" name="forma">
<table width='1000' border='1' id='Exportar_a_Excel' bgcolor='#D1D8DE' class='textotabla1'>
<div align="right">
  <p>
    <?
	$db_ver = new Database();
	$sql = "select * from bodega1 
	INNER JOIN ciudad ON ciudad.cod_ciudad = bodega1.ciu_bod";
	$db_ver->query($sql);	
	$k=0;
	while($db_ver->next_row())
	{ 
		echo "<tr>";
    	echo "<td>$db_ver->iden_bod<input type = 'hidden' id='v_identificacion_$k' name='v_identificacion_$k' value='$db_ver->iden_bod'></td>";						//#1
    	echo "<td>$db_ver->digito_bod<input type = 'hidden' id='v_digito_$k' name='v_digito_$k' value='$db_ver->digito_bod'></td>";		//#2
    	echo "<td>$db_ver->nom_bod<input type = 'hidden' id='v_nombre_$k' name='v_nombre_$k' value='$db_ver->nom_bod'></td>";						//#3
    	echo "<td></td>";									//#4
    	echo "<td>1</td>";//#5
		if ($db_ver->regimen_cli == 'COMUN') {
		$tipo_cliente= 1 ;
		} else {
		$tipo_cliente= 2 ;
		}
    	echo "<td>$tipo_cliente<input type = 'hidden' id='v_tipo_$k' name='v_tipo_$k' value='$tipo_cliente'></td>";			//#6
  		echo "<td>$db_ver->dir_bod<input type = 'hidden' id='v_direccion_$k' name='v_direccion_$k' value='$db_ver->dir_bod'></td>";									//#7
  		echo "<td>$db_ver->desc_ciudad<input type = 'hidden' id='v_ciudad_$k' name='v_ciudad_$k' value='$db_ver->desc_ciudad'></td>";							//#8
  		echo "<td>$db_ver->tel_bod<input type = 'hidden' id='v_telefono_$k' name='v_telefono_$k' value='$db_ver->tel_bod'></td>";				//#9
   		echo "</tr>";
		$k++;
	}
		
		echo "</table>";
?>
  </p>
  </div>
<p>&nbsp;</p>
<p>Exportar a Excel <img src="export_to_excel.gif" class="botonExcel" /></p>
<div align="right">
  <p>&nbsp;</p>
  </div>
  <input type="hidden" name="contador2" id="contador2" value="<?=$k?>" />
</form>
</body>
</html>
