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

<title>LISTA DE PRECIOS</title>
<style type="text/css">
<!--
.Estilo1 {font-size: 16px}
-->
</style>

<div class=" textotabla1">
  <div align="center" class="ctablasup Estilo1">
    <?=$nombre?></div>
</div>
<FORM method="POST" action="sav_lista_detalle.php">

<TABLE width="100%" border="1"  cellspacing="1" bgcolor="#E9E9E9" class="textotabla1">
	<TR>	
		<TD  class="textotabla1">TIPO PRODUCTO</TD>
		<TD  class="textotabla1">CATEGORIA</TD>
		<TD  class="textotabla1">TALLA</TD>
		<TD  class="textotabla1">CODIGO</TD>
        <TD  class="textotabla1">NOMBRE PRODUCTO </TD>
		
	 <!-- <TD   class="textotabla1">P. REGULAR</TD>-->
        <TD   class="textotabla1">P. DE LISTA</TD>
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
		$sql = "SELECT * ,(select pre_list from det_lista where det_lista.cod_lista=$codigo and det_lista.cod_pro=producto.cod_pro limit 1) as precio FROM producto 
		INNER JOIN referencia ON referencia.cod_ref = producto.nom_pro
		left JOIN tipo_producto ON tipo_producto.cod_tpro=producto.cod_tpro_pro  
		left JOIN marca ON cod_mar=producto.cod_mar_pro  
		left join peso on producto.cod_pes_pro=peso.cod_pes ";
		$db->query($sql);
		$j=1;
		while($db->next_row()){
			
			echo "<TR>";
			echo "<TD width=\"11%\" class=\"textotabla1\">$db->nom_tpro &nbsp;</TD>";
			echo "<TD width=\"18%\" class=\"textotabla1\">$db->nom_mar &nbsp;</TD>";
			echo "<TD width=\"18%\" class=\"textotabla1\">$db->nom_pes &nbsp;</TD>";
			echo "<TD class='txtablas' width='15%'>$db->cod_fry_pro &nbsp;</TD>";
			echo "<TD class='txtablas' width='15%'>$db->desc_ref &nbsp;</TD>";
			echo "<TD class='txtablas' align='right' width='15%' >
					<INPUT type='hidden' name='id_codigo_".$db->cod_pro."'  maxlength='10' value='$db->cod_pro'>
					<INPUT type='hidden' name='id_valor_".$db->cod_pro."' maxlength='10' value='$db->pre_ven_pro'>".number_format($db->pre_ven_pro,0,".",".")."
				</TD>"; 
			echo "<TD class='textotabla1' align='center' width='15%' >
			<INPUT type='text' onkeypress='return validaInt()'  name='id_val_lista_".$db->cod_pro."' maxlength='10' value='".number_format($db->precio,0,".",".")."'></TD>";
			echo "</TR>";
			$ultimo=$db->cod_pro;
		}
		
}
else {

		$db = new Database();
		$db_temp = new Database();
		$db1 = new Database();
	
	    $sql = "SELECT * ,(select pre_list from det_lista where det_lista.cod_lista=$codigo and det_lista.cod_pro=producto.cod_pro limit 1 ) as precio FROM producto
		INNER JOIN referencia ON referencia.cod_ref = producto.nom_pro
		left JOIN tipo_producto ON tipo_producto.cod_tpro=producto.cod_tpro_pro  
		left JOIN marca ON cod_mar=producto.cod_mar_pro  
		left join peso on producto.cod_pes_pro=peso.cod_pes ";
		$db->query($sql);
		$j=1;
		while($db->next_row()){
			
			echo "<TR>";
			echo "<TD width=\"11%\" class=\"textotabla1\">$db->nom_tpro &nbsp;</TD>";
			echo "<TD width=\"18%\" class=\"textotabla1\">$db->nom_mar &nbsp;</TD>";
			echo "<TD width=\"18%\" class=\"textotabla1\">$db->nom_pes &nbsp;</TD>";
			echo "<TD class='txtablas' width='15%'>$db->cod_fry_pro &nbsp;</TD>";
			echo "<TD class='txtablas' width='15%'>$db->desc_ref &nbsp;</TD>";
			/*echo "<TD class='textotabla1' align='center' width='15%' >
					<INPUT type='hidden' name='id_codigo_".$db->cod_pro."'  maxlength='10' value='$db->cod_pro'>
					<INPUT type='hidden' name='id_valor_".$db->cod_pro."' maxlength='10' value='$db->pre_ven_pro'>".number_format($db->pre_ven_pro,0,".",".")."</TD>";*/
					
					
			echo "<TD class='txtablas' align='right' width='15%'>".number_format($db->precio,0,".",".")."</TD>";

			/*if($db->precio!=0)
				echo "<INPUT type='text' onkeypress='return validaInt()'  name='id_val_lista_".$db->cod_pro."' maxlength='10' value='$db->precio'></TD>";
			else {
			
				$sql="SELECT * FROM det_lista where cod_lista=12 and cod_pro=$db->cod_pro";
				$db_temp->query($sql);
				if($db_temp->next_row()){
					echo "<INPUT type='text' onkeypress='return validaInt()'  name='id_val_lista_".$db->cod_pro."' maxlength='10' value='".$db_temp->pre_list."'></TD>";
				}
			}	*/
			
			echo "</TR>";
			if($ultimo < $db->cod_pro)
			$ultimo=$db->cod_pro;
		}
		

}



?>
</TABLE>
</FORM>
