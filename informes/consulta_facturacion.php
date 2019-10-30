<?
include("../lib/database.php");


$db = new Database();
$db1 = new Database();
$sql = "SELECT  SUM((SELECT SUM(total_pro) FROM d_factura WHERE d_factura.cod_mfac=m_factura.cod_fac ) ) AS total FROM m_factura  WHERE m_factura.fecha >= '$fec_ini' AND m_factura.fecha <= '$fec_fin'  AND estado IS NULL  order by cod_fac desc";
		$db->query($sql);
		if($db->next_row()){
			$super_total=$db->total;
		}

?>

<link href="../styles.css" rel="stylesheet" type="text/css" />
<link href="../css/stylesforms.css" rel="stylesheet" type="text/css" />
<title>Consulta Facturacion</title><TABLE width="891" border="1" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
<TR>
		<TD width="11%" class="subfongris">FACTURA. No.</TD>
		<TD width="19%" class="subfongris">RAZON SOCIAL.</TD>
        <TD width="20%" class="subfongris">CLIENTE.</TD>
		<TD width="20%" class="subfongris">BODEGA.</TD>
    <TD width="12%" class="subfongris">FECHA</TD>
       <TD width="18%" class="subfongris">USUARIO</TD>
	   <TD width="18%" class="subfongris">VALOR</TD>
	   <TD width="18%" class="subfongris">DEVOLUCION</TD>
	  
  </TR>
	
	<?		
	$where_cli="";
$sql="select distinct punto_venta.cod_bod as valor , nom_bod as nombre from punto_venta  inner join  bodega  on punto_venta.cod_bod=bodega.cod_bod where cod_ven=$global[2]";
		$dbdatos= new  Database();
		$dbdatos->query($sql);	
		$where_cli="";
		while($dbdatos->next_row())
		{
			$where_cli .= "bodega1.cod_bod_cli= ".$dbdatos->valor  ;
			$where_cli .= " or ";
		}		
		$where_cli .= " bodega1.cod_bod < 0 "; 

	
	
	
	$sql = " SELECT 
  m_factura.cod_usu,
  usuario.nom_usu,
  m_factura.cod_cli,
  bodega1.nom_bod as cliente,
  m_factura.cod_bod,
  bodega.nom_bod as bodega,
  m_factura.fecha,
  m_factura.num_fac,
  m_factura.cod_razon_fac,
  rsocial.nom_rso,
  m_factura.tipo_pago,
  m_factura.tot_fac,
  m_factura.tot_dev_mfac,
  m_factura.cod_fac
FROM
  m_factura
  INNER JOIN usuario ON (m_factura.cod_usu = usuario.cod_usu)
  INNER JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)
  INNER JOIN bodega ON (m_factura.cod_bod = bodega.cod_bod)
  INNER JOIN rsocial ON (m_factura.cod_razon_fac = rsocial.cod_rso)
WHERE
  m_factura.fecha   >= '$fec_ini' AND
  m_factura.fecha <= '$fec_fin' and  ( $where_cli )  AND estado IS NULL
ORDER BY
  cod_fac DESC   ";
$super_total=0;
		$db->query($sql);
		while($db->next_row()){
			echo "<FORM action='agr_traslado.php' method='POST'>
					<INPUT type='hidden' name='mapa' value='$mapa'>";
			echo "<TR><TD class='txtablas' width='10%'>$db->num_fac</TD>";	
			
			echo "<TD class='txtablas' align='center' width='15%'>$db->nom_rso</TD>";
			
			echo "<TD class='txtablas' align='center' width='15%'>$db->cliente</TD>";
			
			echo "<TD class='txtablas' align='center' width='15%'>$db->bodega</TD>";
			
				
			echo "<TD class='txtablas' align='center' width='15%'>$db->fecha</TD>";
			
			echo "<TD class='txtablas' align='center' width='15%'>$db->nom_usu</TD>";
			
			echo "<TD class='txtablas' align='center' width='15%'>".number_format($db->tot_fac,0,".",".")."</TD>";
			$super_total+=$db->tot_fac;
			
			echo "<TD class='txtablas' align='center' width='15%'>".number_format($db->tot_dev_mfac,0,".",".")."</TD>";
			$tot_dev+=$db->tot_dev_mfac;
			echo "</TR></FORM>";
		}
	?>

<script>
function ver_documento(codigo,mapa)
{
	 window.open("ver_factura.php?codigo="+codigo,"ventana","menubar=0,resizable=1,width=700,height=400,toolbar=0,scrollbars=yes")
	// window.open("ver_traslado.php?codigo="+codigo,"ventana") 
} 
</script>

<TR>
  <TD colspan="4" class="titulogran">PERIODO <? echo $fec_ini." al ".$fec_fin;   ?></TD>
  <TD colspan="4" class="titulogran">TOTAL <?=number_format($super_total-$tot_dev,0,".",".")?></TD>
  </TR>


	<FORM method="POST" action="../agr_prin_factura.php">
	<TR><TD align="center" colspan="8">	
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<input name="button" type="button" class="botones" onClick="window.print()" value="Imprimir" />
			<INPUT type="button" value="Cerrar" class="botones" onClick="window.close()">
	</TD></TR></FORM>
</TABLE>