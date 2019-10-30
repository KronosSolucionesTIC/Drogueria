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
<title>Facturacion por Cliente</title><table width="72%" border="1" cellpadding="2" cellspacing="1"  class="textoproductos1">
  <tr>
    <td colspan="6" class="ctablasup" align="center"> FACTURACION  POR CLIENTE </td>
  </tr>
  
  <tr>
    <td colspan="6" class="ctablasup"> CLIENTE :
      <?=$nombre?></td>
  </tr>
    <td width="17%" height="16" class="botones1">FECHA</td>
      <td width="20%" height="16" class="botones1">TIPO DE PAGO </td>
      <td width="25%" class="botones1">VALOR</td>
      <td width="23%" class="botones1">N. FAC.</td>
    <td width="15%" colspan="2" class="botones1">OPCION</td>
	<!--<TD width="11%" class="botones1" >OPCIONES</TD>-->
  </tr>
  <?
		$db = new Database();
		$db1 = new Database();
		
		$sql=" SELECT 
  m_factura.cod_usu,
  m_factura.cod_cli,
  bodega1.nom_bod,
  m_factura.cod_bod,
  m_factura.fecha,
  m_factura.num_fac,
  m_factura.cod_razon_fac,
  m_factura.tipo_pago,
  m_factura.tot_fac,
  m_factura.tot_dev_mfac,
  m_factura.cod_fac
FROM
  m_factura
  INNER JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)
WHERE   cod_cli=$codigo
ORDER BY   cod_fac DESC";
				
		$db->query($sql);
		$totalfacturacion=0;
		$totalcorriente=0;
		$total30=0;
		$total60=0;
		$total90=0;
		$validador=0;
		while($db->next_row()){
			$validador=0;
			$totalfacturacion=$totalfacturacion+$db->tot_fac;
			echo "<FORM action='liq_cartera.php' method='POST'> <INPUT type='hidden' name='mapa' value='$mapa'> <TR>";
			echo "<TD class='ctablablanc' >$db->fecha</TD>";
			echo "<TD class='ctablablanc' >$db->tipo_pago</TD>";
			echo "<TD class='ctablablanc' ><div align='right'>".number_format($db->tot_fac,0,".",".")."</div></TD>";
			echo "<TD class='ctablablanc' >$db->num_fac</TD>";			
			echo "<td align='center'><img src='../imagenes/mirar.png' width='16' height='16'  style=\"cursor:pointer\" onClick=\"imprimir_factura('ver_factura_v.php',$db->cod_fac,'grande')\" /></td>";	
			echo "</TR>	</FORM>";
		}
	?>
  <tr>
    <td height="16" colspan="6" class="subtitulosproductos">TOTAL FACTURACION:
        <?=number_format($totalfacturacion,0,".",".")?>    </td>
    <!--<TD width="11%" class="botones1" >OPCIONES</TD>-->
  </tr>
  <tr>
    <td height="16" colspan="6" class="tituloproductos">
	  <div align="center">
	    <input name="button" type="button" class="botones"  onclick="window.print()" value="Imprimir" />
      </div></td>
    <!--<TD width="11%" class="botones1" >OPCIONES</TD>-->
  </tr>
</table>	
