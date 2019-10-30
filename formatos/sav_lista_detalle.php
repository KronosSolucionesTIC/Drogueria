<?
include("../lib/database.php");
include("../js/funciones.php");
$db = new Database();
$db1 = new Database();
$sql = "delete from det_lista  where cod_lista= '$idlista'";
$db->query($sql);
$db->escribe_sql($sql);

for($i=0;$i<=$ultimo;$i++){
	if($_POST["id_codigo_".$i]){
		$sql="INSERT INTO `det_lista` (`cod_lista`, `cod_pro`, `pre_reg`, `pre_list`) VALUES(".$idlista.",'".$_POST["id_codigo_".$i]."','".$_POST["id_valor_".$i]."', '".$_POST["id_val_lista_".$i]."')";
		$db1->query($sql);
		$db->escribe_sql($sql);
	}
}

$mapa="LISTAS";
		
?>

<FORM method="POST" action="ver_lista.php" name="myForm">
<INPUT type="hidden" name="codigo" value="<?=$idlista?>"></FORM>
<SCRIPT>
	alert('SE HAN GUARDADO SATISFACTORIAMENTE SUS DATOS');
	window.close();
</SCRIPT>
