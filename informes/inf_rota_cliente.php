<?
include("../lib/database.php");
?>
<link href="../styles.css" rel="stylesheet" type="text/css" />
<link href="../css/stylesforms.css" rel="stylesheet" type="text/css" />
<title>Consulta Rotacion</title><TABLE width="725" border="1" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
<TR>
  <TD colspan="8" class="titulogran"><div align="center">ROTACION DEL CLIENTE </div></TD>
  </TR>
<TR>
		<TD width="9%" class="subfongris">CANTIDAD</TD>
		<TD width="9%" class="subfongris">CODIGO</TD>
        <TD width="25%" class="subfongris">NOMBRE</TD>
		<TD width="13%" class="subfongris">TALLA</TD>
    <TD width="13%" class="subfongris">CATEGORIA</TD>
       <TD width="31%" class="subfongris">BODEGA</TD>
  </TR>
	
	<?		
	$sql = " SELECT 
  m_factura.cod_usu,
  usuario.nom_usu,
  m_factura.cod_cli as cliente,
  bodega1.nom_bod,
  m_factura.cod_bod,
  bodega.nom_bod as bodega,
  m_factura.fecha,
  m_factura.num_fac,
  m_factura.cod_razon_fac,
  rsocial.nom_rso,
  m_factura.tipo_pago,
  m_factura.tot_fac,
  m_factura.tot_dev_mfac,
  m_factura.cod_fac,
  d_factura.cod_tpro,
  tipo_producto.nom_tpro,
  d_factura.cod_cat,
  marca.nom_mar,
  d_factura.cod_peso,
  peso.nom_pes,
  d_factura.cod_pro,
  producto.cod_fry_pro,
  producto.nom_pro,
  d_factura.cant_pro
FROM  m_factura 
  left JOIN usuario ON (m_factura.cod_usu = usuario.cod_usu)
  left JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)
  left JOIN bodega ON (m_factura.cod_bod = bodega.cod_bod)
  left JOIN rsocial ON (m_factura.cod_razon_fac = rsocial.cod_rso)
  left JOIN d_factura ON (m_factura.cod_fac = d_factura.cod_mfac)
  left JOIN tipo_producto ON (d_factura.cod_tpro = tipo_producto.cod_tpro)
  left JOIN marca ON (d_factura.cod_cat = marca.cod_mar)
  left JOIN peso ON (d_factura.cod_peso = peso.cod_pes)
  left JOIN producto ON (d_factura.cod_pro = producto.cod_pro)
WHERE  cod_cli = '$codigo_bodega' group by  d_factura.cod_pro ORDER BY cant_pro DESC ";

		$db->query($sql);
		while($db->next_row()){
			echo "<FORM action='agr_traslado.php' method='POST'> ";
			echo "<TR><TD class='txtablas' width='10%'>$db->cant_pro</TD>";	
			
			echo "<TD class='txtablas' align='center' width='15%'>$db->cod_fry_pro</TD>";
			
			echo "<TD class='txtablas' align='center' width='15%'>$db->nom_pro</TD>";
			
			echo "<TD class='txtablas' align='center' width='15%'>$db->nom_pes</TD>";
				
			echo "<TD class='txtablas' align='center' width='15%'>$db->nom_mar</TD>";
			
			echo "<TD class='txtablas' align='center' width='15%'>$db->bodega</TD>";
			
			
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


	<FORM method="POST" action="../agr_prin_factura.php">
	<TR><TD align="center" colspan="8">	
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<input name="button" type="button" class="botones" onClick="window.print()" value="Imprimir" />
			<INPUT type="button" value="Cerrar" class="botones" onClick="window.close()">
	</TD></TR></FORM>
</TABLE>
