<?
include("../lib/database.php");
$db = new Database();
/*
$db1 = new Database();
$sql = "SELECT  SUM((SELECT SUM(total_pro) FROM d_factura WHERE d_factura.cod_mfac=m_factura.cod_fac ) ) AS total FROM m_factura  WHERE m_factura.fecha >= '$fec_ini' AND m_factura.fecha <= '$fec_fin'  AND m_factura.estado IS NULL  order by cod_fac desc";
$db->query($sql);
if($db->next_row()){
			$super_total1=$db->total;
}
*/
?>
<link href="../styles.css" rel="stylesheet" type="text/css" />
<link href="../css/stylesforms.css" rel="stylesheet" type="text/css" />
<title>Consulta Facturación</title>
<TABLE width="891" border="1" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
  <TR>
    <TD width="11%" class="subfongris">FACTURA No.</TD>
    <TD width="19%" class="subfongris">RAZON SOCIAL</TD>
    <TD width="20%" class="subfongris">CLIENTE</TD>
    <TD width="20%" class="subfongris">BODEGA</TD>
	 <TD width="20%" class="subfongris">CIUDAD</TD>
    <TD width="12%" class="subfongris">FECHA</TD>
    <TD width="18%" class="subfongris">USUARIO</TD>
    <TD width="18%" class="subfongris">VALOR</TD>
    <TD width="10%" class="subfongris">DEVOLUCION</TD>
  </TR>
  <?	
  /*	
$sql="select distinct punto_venta.cod_bod as valor from punto_venta  inner join  bodega  on punto_venta.cod_bod=bodega.cod_bod ";
$dbdatos= new  Database();
$dbdatos->query($sql);
$where_cli="";	
$primeravez = true;
while($dbdatos->next_row())
{
    if ($primeravez) {
	   $where_cli=" and bodega1.cod_bod_cli in (";
	   $where_cli .= "'".$dbdatos->valor."'";
	   $primeravez = false;
	}
	else
	   $where_cli .= ", '".$dbdatos->valor."'";
}	
if ($primeravez == false) {	
    $where_cli.=" )";
}
	
//echo " WHERE ".$where_cli;		
*/
$sql = "select m_factura.num_fac, rsocial.nom_rso,   bodega1.nom_bod as cliente,  bodega.nom_bod as bodega, ciudad.desc_ciudad as ciudad, m_factura.fecha, usuario.nom_usu,  m_factura.tot_fac,  m_factura.tot_dev_mfac 
from m_factura
left join bodega1 on bodega1.cod_bod=m_factura.cod_cli 
left join rsocial on rsocial.cod_rso=m_factura.cod_razon_fac
left join usuario on usuario.cod_usu=m_factura.cod_usu
left join bodega on bodega.cod_bod =m_factura.cod_bod 
left join ciudad on  bodega1.ciu_bod = ciudad.cod_ciudad
WHERE  m_factura.fecha BETWEEN '$fec_ini' AND '$fec_fin' AND m_factura.estado IS NULL ";
/*if ($where_cli != "")
  $sql .= " $where_cli ";*/
$sql.= " ORDER BY bodega1.ciu_bod, m_factura.cod_razon_fac, m_factura.fecha  ";
//echo $sql;

$super_total=0;
$tot_dev = 0;
$db->query($sql);
while($db->next_row()){
	echo "<TR><TD class='txtablas' width='10%'>$db->num_fac</TD>";	
	echo "<TD class='txtablas' align='center' width='15%'>$db->nom_rso</TD>";
	echo "<TD class='txtablas' align='center' width='15%'>$db->cliente</TD>";
	echo "<TD class='txtablas' align='center' width='15%'>$db->bodega</TD>";
	echo "<TD class='txtablas' align='center' width='15%'>$db->ciudad</TD>";
	echo "<TD class='txtablas' align='center' width='15%'>$db->fecha</TD>";
	echo "<TD class='txtablas' align='center' width='15%'>$db->nom_usu</TD>";
	echo "<TD class='txtablas' align='center' width='15%'>".number_format($db->tot_fac,0,".",".")."</TD>";
	echo "<TD class='txtablas' align='center' width='15%'>".number_format($db->tot_dev_mfac,0,".",".")."</TD>";
	echo "</TR>";
	$super_total+=$db->tot_fac;
	$tot_dev+=$db->tot_dev_mfac;
}
?>
  <TR>
    <TD colspan="4" class="titulogran">PERIODO <? echo $fec_ini." al ".$fec_fin;   ?></TD>
    <TD colspan="4" class="titulogran">TOTAL
      <?=number_format($super_total-$tot_dev,0,".",".")?></TD>
  </TR>
  <FORM method="POST" action="">
    <TR>
      <TD align="center" colspan="8">
        <input name="button" type="button" class="botones" onClick="window.print()" value="Imprimir" />
        <INPUT type="button" value="Cerrar" class="botones" onClick="window.close()">
      </TD>
    </TR>
  </FORM>
</TABLE>