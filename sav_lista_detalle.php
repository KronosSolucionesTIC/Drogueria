<?
	include "../lib/sesion.php";
	include("../lib/database.php");
	include("../conf/clave.php");
	
	$db = new Database();
	$db1 = new Database();
	$sql = "delete from det_lista  where cod_lista= '$idlista'";
	$db->query($sql);
	
	for($i=0;$i<=$ultimo;$i++){
		if($_POST["id_codigo_".$i]){
			$sql="INSERT INTO `det_lista` (`cod_lista`, `cod_pro`, `pre_reg`, `pre_list`) VALUES(".$idlista.",".$_POST["id_codigo_".$i].",".$_POST["id_valor_".$i].", ".$_POST["id_val_lista_".$i].")";
			$db1->query($sql);
		}
	}

$mapa="LISTAS";
		
?>

<FORM method="POST" action="../admon/interna.php" name="myForm">
<INPUT type="hidden" name="mapa" value="<?=$mapa?>"></FORM>
<SCRIPT>
	alert('SE HAN GUARDADO SATISFACTORIAMENTE SUS DATOS');
	document.myForm.submit();
</SCRIPT>