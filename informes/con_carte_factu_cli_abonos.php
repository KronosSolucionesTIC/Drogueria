<?
include("../lib/database.php");
$db_ver = new Database();
$sql = "SELECT * FROM bodega1  WHERE cod_bod=$codigo";
$db_ver->query($sql);	
if($db_ver->next_row()){ 
	$nombre=$db_ver->nom_bod;
	$identificacion=$db_ver->iden_bod;
}	
?>
<script language="javascript">
function imprimir_factura(ruta,codigo,tamano){
var ancho=0;
var alto=0;
	
if(tamano=="mediano") {
	ancho=900;
	alto=600;
}

if(tamano=="grande") {
	ancho=900;
	alto=700;
}

var marginleft = (screen.width - ancho) / 2;
var margintop = (screen.height - alto) / 2;
propiedades = 'menubar=0,resizable=1,height='+alto+',width='+ancho+',top='+margintop+',left='+marginleft+',toolbar=0,scrollbars=yes';
window.open(""+ruta+"?codigo="+codigo,"factura",propiedades)

}
</script>
<link href="styles.css" rel="stylesheet" type="text/css" />
<link href="../css/styles.css" rel="stylesheet" type="text/css">
<title>Abonos por Cliente</title><table width="72%" border="1" cellpadding="2" cellspacing="1"  class="textoproductos1">
  <tr>
    <td colspan="5" class="ctablasup" align="center"> ABONOS  POR CLIENTE </td>
  </tr>
  
  <tr>
    <td colspan="5" class="ctablasup"> CLIENTE :
      <?=$nombre?></td>
  </tr>
    <td width="17%" height="16" class="botones1">FECHA</td>
      <td width="25%" class="botones1">VALOR</td>
      <td width="23%" class="botones1">N. ABONO</td>
      <td width="15%" colspan="2" class="botones1">OPCION</td>
	<!--<TD width="11%" class="botones1" >OPCIONES</TD>-->
  </tr>
  <?
		$db = new Database();
		$db1 = new Database();
		
		$sql=" SELECT 
  bodega1.nom_bod,
  abono.cod_abo,
  abono.cod_bod_Abo,
  abono.val_abo,
  abono.cod_usu_abo,
  abono.fec_abo
FROM
  abono
  INNER JOIN bodega1 ON (abono.cod_bod_Abo = bodega1.cod_bod)
WHERE
  abono.cod_bod_Abo=$codigo ";
				
		$db->query($sql);
		$totalfacturacion=0;
		$totalcorriente=0;
		$total30=0;
		$total60=0;
		$total90=0;
		$validador=0;
		while($db->next_row()){
			$validador=0;
			$totalfacturacion=$totalfacturacion + $db->val_abo ;
			echo "<FORM action='liq_cartera.php' method='POST'> <INPUT type='hidden' name='mapa' value='$mapa'> <TR>";
			echo "<TD class='ctablablanc' >$db->fec_abo</TD>";
			echo "<TD class='ctablablanc' ><div align='right'>".number_format($db->val_abo,0,".",".")."</div></TD>";
			echo "<TD class='ctablablanc' >$db->nom_bod</TD>";			
			echo "<td align='center'><img src='../imagenes/mirar.png' width='16' height='16'  style=\"cursor:pointer\" onClick=\"imprimir_factura('ver_abono.php',$db->cod_abo,'mediano')\" /></td>";	
			echo "</TR>	</FORM>";
			
		}
	?>
  <tr>
    <td height="16" colspan="5" class="subtitulosproductos">TOTAL ABONOS:
        <?=number_format($totalfacturacion,0,".",".")?>    </td>
    <!--<TD width="11%" class="botones1" >OPCIONES</TD>-->
  </tr>
  <tr>
    <td height="16" colspan="5" class="tituloproductos">
	  <div align="center">
	    <input name="button" type="button" class="botones"  onclick="window.print()" value="Imprimir" />
      </div></td>
    <!--<TD width="11%" class="botones1" >OPCIONES</TD>-->
  </tr>
</table>	
