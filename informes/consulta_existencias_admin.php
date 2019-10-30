<? include("../lib/database.php");?>
<?


$aaa = split("\,",$arreglo_clientes);
for($g=0; $g<=$val_inicial; $g++)
{  
 $ttt = $aaa[$g]; 

 if($ttt!="") { 
 



$db1 = new Database();
$sql = " select * 
from bodega where cod_bod=".$ttt;
$db1->query($sql);
while($db1->next_row()){

$nombre=$db1->nom_bod;
$lista=$db1->nom_list;
}
?>
<link href="styles.css" rel="stylesheet" type="text/css" />
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<title>EXISTENCIAS EN BODEGA</title><TABLE width="99%" border="1" align="center" cellpadding="2" cellspacing="1"   class="textoproductos1">

<TR>
	  <TD colspan="6" class="ctablasup"><div align="center">NOMBRE BODEGA : 
        <?=$nombre?>
	  </div></TD>
  </TR>
	<TR>
		<TD width="10%"  class="subtitulosproductos">CODIGO</TD>
		<TD width="34%" class="subtitulosproductos">REFERENCIA</TD>
        <TD width="17%"  class="subtitulosproductos">CANTIDAD </TD>
		<TD width="12%"  class="subtitulosproductos">TALLA </TD>
        <TD width="14%"  class="subtitulosproductos">VALOR UNIDAD</TD>
		<TD width="13%" class="subtitulosproductos">VALOR TOTAL</TD>
	</TR>
	
	<?
		$db = new Database();	
		if($ttt>=0){
		 $db->pre_reg;
		 $ttt;			
		 $combo_lista;
		 $sql = "SELECT 
  kardex.cod_kar,
  kardex.cod_ref_kar,
  kardex.cant_ref_kar,
  kardex.cod_talla,
  producto.cod_fry_pro,
  producto.nom_pro,
  bodega.cod_bod,
  bodega.nom_bod,
  det_lista.cod_lista,
  det_lista.cod_pro,
  det_lista.pre_list,
  listaprecio.cos_list,
  listaprecio.nom_list,
  producto.cod_pro,
  peso.nom_pes,
  peso.cod_pes,
  
  (kardex.cant_ref_kar * det_lista.pre_list) AS total_producto
  
FROM
  kardex
  INNER JOIN producto ON (kardex.cod_ref_kar = producto.cod_pro)
  INNER JOIN bodega ON (kardex.cod_bod_kar = bodega.cod_bod)
  INNER JOIN det_lista ON (producto.cod_pro = det_lista.cod_pro)
  INNER JOIN peso ON (kardex.cod_talla = peso.cod_pes)
  INNER JOIN listaprecio ON (det_lista.cod_lista = listaprecio.cos_list) WHERE cod_bod_kar=$ttt and cos_list=$combo_lista"; 
  
			$db->query($sql);
			$total_bodega=0;

			while($db->next_row()){
			
			$total_bodega=$total_bodega+$db->total_producto;
			
				echo "<FORM action='agr_bod.php' method='POST'>
						<INPUT type='hidden' name='mapa' value='$mapa'>";
				echo "<TR><TD class='textoproductos1' >$db->cod_fry_pro</TD>";
				echo "<TD class='textoproductos1' >$db->nom_pro</TD>";
				echo "<TD class='textoproductos1' >$db->cant_ref_kar</TD>";
				echo "<TD class='textoproductos1' >$db->nom_pes</TD>";
				echo "<TD class='textoproductos1' >".number_format($db->pre_list,0,".",".")."</TD>";
				echo "<TD class='textoproductos1' >".number_format($db->total_producto,0,".",".")."</TD>";
				
				//echo "<TD class='textoproductos1' align='center' width='25%' colspan='2'>".number_format($db->cant_ref_kar*$db->valor,0,".",".")." </TD>";
				echo "</TR></FORM>";
				
				
				//$total_valor += $db->total_producto;
			}
		}
		else{
				echo "<FORM action='agr_bod.php' method='POST'>
						<INPUT type='hidden' name='mapa' value='$mapa'>";
				echo "<TR><TD class='textoproductos1' >$db->cod_fry_pro</TD>";
				echo "<TD class='textoproductos1' >$db->nom_pro</TD>";
				echo "<TD class='textoproductos1' >$db->cant_ref_kar</TD>";
				echo "<TD class='textoproductos1' >$db->nom_pes</TD>";
				echo "<TD class='textoproductos1' >".number_format($db->pre_list,0,".",".")."</TD>";
				echo "<TD class='textoproductos1' >".number_format($db->total_producto,0,".",".")."</TD>";
				
				//echo "<TD class='textoproductos1' align='center' width='25%' colspan='2'>".number_format($db->cant_ref_kar*$db->valor,0,".",".")." </TD>";
				echo "</TR></FORM>";
				
				//$total_valor += $db->total_producto;
			
		}
	?>



	<FORM method="POST" action="interna.php">
	<TR>
	  <TD colspan="4" align="center" class="tituloproductos">&nbsp;</TD>
	  <TD align="center" class="tituloproductos"><div align="left" class="textotabla01">
	    <div align="right">Total de la Bodega: </div>
	  </div></TD>
	  <TD align="center" class="tituloproductos"><div align="left" class="textotabla01">$</span> 
	    <?=number_format($total_bodega,0,".",".")  ?>
	  </div>      </TD>
	</TR>
	<TR><? $gran_total_bodega=$gran_total_bodega+$total_bodega;}  }?>
  </FORM>
</TABLE>
<table width="993" height="25"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
  <tr>
    <td width="854" class="textotabla01"><div align="right"> Total  </div></td>
    <td width="125"><span class="textotabla1">$
      <?=number_format($gran_total_bodega,0,".",".")?>
    </span></td>
  </tr>
</table>

<p><br/>
</p>
<div align="center">
  <script language="javascript">
function abrir(id){
	window.open("ver_abono.php?codigo="+id,"ventana","menubar=0,resizable=1,width=700,height=400,toolbar=0,scrollbars=yes")
}
</script>
  <input type="hidden" name="mapa" value="<?=$mapa?>" />
  <input name="button" type="button" class="botones"  onclick="window.print()" value="Imprimir" />
  <input name="button" type="button" class="botones"  onclick="window.close()" value="Cerrar" />
</div>
