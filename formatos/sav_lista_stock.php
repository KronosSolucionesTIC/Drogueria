<?
include("../lib/database.php");
include("../js/funciones.php");
$cod_bod = $_POST['bodega'];

for($i=0;$i<=$ultimo-1;$i++){
	$cod_pro = $_POST['cod_pro_'.$i];
	$cod_talla = $_POST['cod_talla_'.$i];
	$cant_min = $_POST['cant_min_'.$i];
	$cant_max = $_POST['cant_max_'.$i];
	$campos="cant_min='".$cant_min."',cant_max=".$cant_max."";
	$error=editar2("kardex",$campos,'cod_bod_kar',$cod_bod,'cod_ref_kar',$cod_pro,'cod_talla',$cod_talla); 
}		
?>
<FORM method="POST" action="ver_lista_stock.php" name="myForm">
<INPUT type="hidden" name="codigo" value="<?=$idlista?>"></FORM>
<SCRIPT>
	alert('SE HAN GUARDADO SATISFACTORIAMENTE SUS DATOS');
	window.close();
</SCRIPT>

