<? include("../lib/database.php");?>
<?  
$dbnom = new Database();
$sql ="SELECT * FROM bodega 
WHERE cod_bod=".$codigo;
$dbnom->query($sql);
if($dbnom->next_row()){ 
	$nombre = $dbnom->nom_bod;
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

<title><?=$nombre?></title><div class=" textotabla1"></div>
<FORM method="POST" action="sav_lista_stock.php">

<TABLE width="100%" border="1"  cellspacing="1" bgcolor="#D1D8DE" class="textotabla1">
	<TR>	
		<TD width="13%"  class="textotabla1">CATEGORIA</TD>
		<TD width="13%"  class="textotabla1">TIPO PRODUCTO</TD>
		<TD width="13%"  class="textotabla1">REFERENCIA</TD>
		<TD width="13%"  class="textotabla1">CODIGO</TD>
        <TD width="13%"  class="textotabla1">TALLA</TD>
      <TD width="18%"   class="textotabla1">CANTIDAD MINIMA</TD>
        <TD width="17%"   class="textotabla1">CANTIDAD MAXIMA</TD>
	</TR>
<?

		$db = new Database();
		$sql = "SELECT * FROM atributo
		INNER JOIN producto ON producto.cod_pro = atributo.cod_referencia
		INNER JOIN peso ON peso.cod_pes = atributo.cod_peso
		WHERE estado_producto = 1 ORDER BY cod_fry_pro,cod_pes ASC"; 
		$db->query($sql);
		$i=0;
		while($db->next_row()){
			
			echo "<TR>";
			$dbc = new Database();
			$sqlc = "SELECT * FROM marca
			WHERE cod_mar = '$db->cod_mar_pro'"; 
			$dbc->query($sqlc);
			$dbc->next_row();
			echo "<TD width=\"11%\" class=\"textotabla1\">$dbc->nom_mar</TD>";
			$dbtp = new Database();
			$sqltp = "SELECT * FROM tipo_producto
			WHERE cod_tpro = '$db->cod_tpro_pro'"; 
			$dbtp->query($sqltp);
			$dbtp->next_row();
			echo "<TD width=\"18%\" class=\"textotabla1\">$dbtp->nom_tpro &nbsp;</TD>";
			$dbr = new Database();
			$sqlr = "SELECT * FROM referencia
			WHERE cod_ref = '$db->nom_pro'"; 
			$dbr->query($sqlr);
			$dbr->next_row();
			echo "<TD width=\"18%\" class=\"textotabla1\">$dbr->desc_ref &nbsp;
			<INPUT type='hidden' name='cod_pro_$i' id='cod_pro_$i' value='$db->cod_pro'>
			<INPUT type='hidden' name='cod_talla_$i' id='cod_talla_$i' value='$db->cod_peso'></TD>";
			echo "<TD class='txtablas' width='15%'>$db->cod_fry_pro &nbsp;</TD>";
			echo "<TD class='txtablas' width='15%'>$db->nom_pes &nbsp;</TD>";
			$dbk = new Database();
			$sqlk = "SELECT * FROM kardex 
			WHERE cod_bod_kar = '$codigo' AND cod_ref_kar = '$db->cod_pro' AND cod_talla='$db->cod_peso'"; 
			$dbk->query($sqlk);
			$dbk->next_row();			
			echo "<TD class='textotabla1' align='center' width='15%' >
			<INPUT type='text' onkeypress='return validaInt()'  name='cant_min_$i' maxlength='10' value='".number_format($dbk->cant_min,0,".",".")."'></TD>";
			echo "<TD class='textotabla1' align='center' width='15%' >
			<INPUT type='text' onkeypress='return validaInt()'  name='cant_max_$i' maxlength='10' value='".number_format($dbk->cant_max,0,".",".")."'></TD>";
			echo "</TR>";
			$i=$i+1;
		}			
?>

<TR><TD align="center" colspan="8">	
			<INPUT type="hidden" name="bodega" value="<?=$codigo?>">
            <INPUT type="hidden" name="ultimo" value="<?=$i?>">
			<INPUT type="submit" value="Guardar" class="botones"></TD>
</TR>
</TABLE>
</FORM>
