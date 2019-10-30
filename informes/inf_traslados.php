<?
include "../lib/sesion.php";
include("../lib/database.php");		
//echo $codigo;
//echo $nombre_aplicacion;
//exit;
$db = new Database();
$db_ver = new Database();
$sql ="SELECT * , (SELECT nom_bod FROM bodega WHERE cod_bod=cod_bod_sal_tras) AS bodega_salida, (SELECT nom_bod FROM bodega WHERE cod_bod=cod_bod_ent_tras) AS bodega_entrada FROM m_traslado WHERE cod_tras=$codigo";
$db_ver->query($sql);	
if($db_ver->next_row()){ 
	$ven_entrega=$db_ver->bodega_salida;
	$ven_recibe=$db_ver->bodega_entrada;
	$fecha=$db_ver->fec_tras;
	$obs_tras=$db_ver->obs_tras;
	$numero_tras=$db_ver->cod_tras;	
}


?>
<script language="javascript">
function imprimir(){
	document.getElementById('imp').style.display="none";
	document.getElementById('cer').style.display="none";
	window.print();
}


</script>
 <link href="styles.css" rel="stylesheet" type="text/css" />
 <link href="../styles.css" rel="stylesheet" type="text/css" />
 <style type="text/css">
<!--
.Estilo1 {font-size: 9px}
.Estilo2 {font-size: 9 }
-->
 </style>
 <link href="../css/styles.css" rel="stylesheet" type="text/css" />
 <link href="../css/stylesforms.css" rel="stylesheet" type="text/css" />
 <title><?=$nombre_aplicacion?> -- Traslado de Mercancia --</title>
<TABLE border="0" cellpadding="2" cellspacing="0"  width="306" <?=$anulacion?> >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">
			<TR>
			  <TD align="center"><div align="center"></div>
<table width="306" border="0">
	    <tr>
	      <td width="78"><div align="center"><strong>Bodega entrada</strong></div></td>
	      <td width="79"><div align="center"><span class="textotabla01">
	        <?=$ven_entrega?>
	      </span></div></td>
	      <td width="67"><div align="center"><strong>Fecha</strong></div></td>
	      <td width="64"><div align="center"><span class="textotabla01">
	        <?=$fecha?>
	      </span></div></td>
	      </tr>
	    <tr>
	      <td><div align="center"><strong>Bodega salida</strong></div></td>
	      <td><div align="center"><span class="textotabla01">
	        <?=$ven_recibe?>
	      </span></div></td>
	      <td><div align="center"><strong>Doc No</strong></div></td>
	      <td><div align="center"><span class="textoproductos1">
	        <?=$numero_tras?>
	      </span></div></td>
	      </tr>
	    <tr>
			      <td><div align="center"><strong>Producto</strong></div></td>
			      <td><div align="center"><strong><span class="Estilo12">Codigo</span></strong></div></td>
			      <td colspan="2"><div align="center"><strong><span class="Estilo12">Total</span></strong></div></td>
		        </tr>
			    <tr>
<? 
$total=0;
			 $sql = " SELECT * 
FROM d_traslado 
LEFT JOIN producto ON producto.cod_pro=d_traslado.cod_ref_dtra
INNER JOIN referencia ON referencia.cod_ref = producto.nom_pro
LEFT JOIN tipo_producto ON tipo_producto.cod_tpro=producto.`cod_tpro_pro`
LEFT JOIN marca ON marca.cod_mar=producto.`cod_mar_pro`
LEFT JOIN peso ON peso.cod_pes=d_traslado.cod_pes_dtra  WHERE cod_mtras_dtra=$codigo  ORDER BY cod_dtra ";
					$db->query($sql);
					$estilo="formsleo";
					while($db->next_row()){ 
?>
			      <td><div align="left"><span class="Estilo12">
			        <?=$db->desc_ref?>
		          </span></div></td>
			      <td><div align="right">
			        <?=$db->cod_fry_pro?>
			      </div></td>
			      <td colspan="2"><div align="right">
			        <?=number_format($db->cant_dtra,0,".",".")?>
			      </div></td>
        </tr>
<? 
} 
?>
			    <tr>
			      <td colspan="2">&nbsp;</td>
			      <td colspan="2">&nbsp;</td>
        </tr>
<?
$total=0;
			 $sql = " SELECT * 
FROM d_traslado 
LEFT JOIN producto ON producto.cod_pro=d_traslado.cod_ref_dtra
INNER JOIN referencia ON referencia.cod_ref = producto.nom_pro
LEFT JOIN tipo_producto ON tipo_producto.cod_tpro=producto.`cod_tpro_pro`
LEFT JOIN marca ON marca.cod_mar=producto.`cod_mar_pro`
LEFT JOIN peso ON peso.cod_pes=d_traslado.cod_pes_dtra  WHERE cod_mtras_dtra=$codigo  ORDER BY cod_dtra ";
					$db->query($sql);
					$estilo="formsleo";
					while($db->next_row()){ 
			?>
            <? } ?>
		      </table>
		        <div align="center">
		          <input name="button" type="button"  class="botones1" id="imp" onClick="imprimir()" value="Imprimr">
                  <input name="button" type="button"  class="botones1"  id="cer" onClick="window.close()" value="Cerrar">
		        </div>
	          <p>&nbsp;</p></TD>
		  </TR>
</TABLE>

	