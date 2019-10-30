<? include("../lib/database.php");?>
<?  
$dbnom = new Database();
$sql ="select nom_list from listaprecio where cos_list=".$codigo;
$dbnom->query($sql);
if($dbnom->next_row()){ 
	$nombre = $dbnom->nom_list;
}

?>
<link href="../css/styles.css" rel="stylesheet" type="text/css">
<script language="javascript">
function validaInt(){
	if (event.keyCode>47 & event.keyCode<58) {
		return true;
		}
	else{
		return false;
		}
}
</script>

<title>LISTA DE PRECIOS</title><div class=" textotabla1"><?=$nombre?></div>
<FORM method="POST" action="sav_lista_detalle.php">

<TABLE width="100%" border="1"  cellspacing="1" bgcolor="#F2F2F2" class="textotabla1">
	<TR>	
		<TD  class="textotabla1"><div align="center">CODIGO</div></TD>
		<TD  class="textotabla1"><div align="center">CATEGORIA</div></TD>
		<TD  class="textotabla1"><div align="center">TIPO PRODUCTO</div></TD>
		<TD  class="textotabla1"><div align="center">REFERENCIA</div></TD>
      <TD   class="textotabla1"><div align="center">P. DE LISTA</div></TD>
    </TR>
<?
$sql ="select count(*) as total from det_lista where cod_lista=".$codigo;
$db = new Database();
$db->query($sql);
if($db->next_row()){ 
	$total = $db->total;
}


if ($total==0){
		$db = new Database();
		$db1 = new Database();
		$sql = "SELECT * FROM producto 
		left JOIN tipo_producto ON tipo_producto.cod_tpro=producto.cod_tpro_pro  
		left JOIN marca ON cod_mar=producto.cod_mar_pro
		WHERE estado_producto = 1"; 
		$db->query($sql);
		$j=1;
		while($db->next_row()){
			
			echo "<TR>";
			echo "<TD width=\"11%\" class=\"textotabla1\">$db->cod_fry_pro</TD>";
			echo "<TD width=\"18%\" class=\"textotabla1\">$db->nom_mar</TD>";
			echo "<TD width=\"18%\" class=\"textotabla1\">$db->nom_tpro</TD>";
			echo "<TD class='txtablas' width='15%'>$db->nom_pro</TD>";
			echo "<TD class='textotabla1' align='center' width='15%'><INPUT type='hidden' name='id_codigo_".$db->cod_pro."'  maxlength='10' value='$db->cod_pro'>
			<INPUT type='hidden' name='id_valor_".$db->cod_pro."' maxlength='10' value='$db->pre_ven_pro'></TD>
			<INPUT type='text' onkeypress='return validaInt()'  name='id_val_lista_".$db->cod_pro."' maxlength='10' value='".number_format($db->precio,0,".",".")."'></TD>";
			echo "</TR>";
			$ultimo=$db->cod_pro;
		}
		
}
else {

		$db = new Database();
		$db_temp = new Database();
		$db1 = new Database();
	
	    $sql = "SELECT *,(select pre_list from det_lista where det_lista.cod_lista=$codigo and det_lista.cod_pro=producto.cod_pro limit 1) as precio FROM producto 
		left JOIN tipo_producto ON tipo_producto.cod_tpro=producto.cod_tpro_pro  
		left JOIN marca ON cod_mar=producto.cod_mar_pro
		WHERE estado_producto = 1";
		
		$db->query($sql);
		$j=1;
		while($db->next_row()){
			
			echo "<TR>";
			echo "<TD width=\"11%\" class=\"textotabla1\">$db->cod_fry_pro</TD>";
			echo "<TD width=\"18%\" class=\"textotabla1\">$db->nom_mar</TD>";
			echo "<TD width=\"18%\" class=\"textotabla1\">$db->nom_tpro</TD>";
			echo "<TD class='txtablas' width='15%'>$db->nom_pro</TD>";
			echo "<TD class='txtablas' align='center' width='15%'>";
			echo "<INPUT type='hidden' name='id_codigo_".$db->cod_pro."'  maxlength='10' value='$db->cod_pro'>
					<INPUT type='hidden' name='id_valor_".$db->cod_pro."' maxlength='10' value='$db->pre_ven_pro'>
					<INPUT type='text' onkeypress='return validaInt()'  name='id_val_lista_".$db->cod_pro."' maxlength='10' value='$db->precio'></TD>";
			
			echo "</TR>";
			if($ultimo < $db->cod_pro)
			$ultimo=$db->cod_pro;
		}
		

}



?>

<TR><TD align="center" colspan="5">	
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
            <INPUT type="hidden" name="idlista" value="<?=$codigo?>">
            <INPUT type="hidden" name="ultimo" value="<?=$ultimo?>">
			<INPUT type="submit" value="Guardar" class="botones"></TD>
</TR>
</TABLE>
</FORM>
