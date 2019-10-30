<?
include "../lib/sesion.php";
include("../lib/database.php");
			
//echo $codigo;
//echo $nombre_aplicacion;
//exit;
$db = new Database();
$db_ver = new Database();
$sql = "SELECT  *  FROM m_entrada 
INNER JOIN bodega ON bodega.cod_bod=m_entrada.cod_bod 
INNER JOIN proveedor ON (proveedor.cod_pro = m_entrada.cod_prove_ment)
WHERE cod_ment=$codigo";
$db_ver->query($sql);	
if($db_ver->next_row()){ 
	$proveedor = $db_ver->nom_pro;
	$fecha=$db_ver->fec_ment;
	$obser=$db_ver->obs_ment;
	$bodega=$db_ver->nom_bod;
	$factura=$db_ver->num_fac;	
	$total_nuevo_saldo=$db_ver->total_ment;
	$numero_doc=$db_ver->cod_ment;
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
 <link href="../css/styles.css" rel="stylesheet" type="text/css" />
 <link href="../css/stylesforms.css" rel="stylesheet" type="text/css" />
 <title><?=$nombre_aplicacion?> -- ENTRADAS DE INVENTARIO --</title>
 <style type="text/css">
<!--
.Estilo4 {font-size: 18px}
-->
 </style>
 <TABLE width="100%" border="0" cellspacing="0" cellpadding="0"   >
	
	<TR>
		<TD align="center">
		<TABLE width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

			<TR>
			  <TD colspan="2" class='txtablas'>
			  <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  	<tr>
			  		<td width="47%" rowspan="3"><span class="Estilo4">ENTRADA DE INVENTARIOS</span> </td>
				    <td width="22%" height="22" class="ctablaform"> <span class="textoproductos1"> &nbsp;&nbsp;Fecha:<span class="textotabla01">
                    <?=$fecha?>
				    </span></span></td>
			  	   
			  	    <td width="31%" class="ctablaform"><span class="textoproductos1">Numero Doc: &nbsp;<?=$numero_doc?></span></td>
			  	</tr>
			  	<tr>
			  	  <td  class="ctablaform"><span class="textoproductos1">&nbsp;&nbsp;Factura No :<span class="textotabla01">
			  	    <?=$factura?>
			  	    </span></span><span class="textoproductos1">&nbsp;&nbsp;</span></td>
			  	  <td  class="ctablaform"><span class="textoproductos1">Bodega:<span class="textotabla01">
			  	    <?=$bodega?>
			  	    </span></span></td>
		  	    </tr>
			  	<tr>
			  	  <td  class="ctablaform"><span class="textoproductos1">&nbsp;&nbsp;Proveedor:<span class="textotabla01">
			  	    <?=$proveedor?>
			  	  </span></span><span class="textoproductos1">&nbsp;&nbsp;</span></td>
			      <td  class="ctablaform">&nbsp;</td>
			  	</tr>
				</table>					</TD>
		  </TR>
			
			
			<TR>
			  <TD colspan="2" align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                
				  <tr >
            <td  class="botones1">Tipo Producto </td>
            <td  class="botones1">Proveedor</td>
			<td  class="botones1">Referencia</td>
            <td  class="botones1">Codigo</td>
			 <td   class="botones1">Talla/Color</td>
            <td   class="botones1">Cantidad</td>
			<td    class="botones1">Costo</td>
          </tr>
				<?
				$total=0;
				 $sql = "  SELECT * FROM d_entrada 
				 INNER JOIN tipo_producto ON tipo_producto.cod_tpro=d_entrada.cod_tpro_dent 
				 INNER JOIN marca ON marca.cod_mar=d_entrada.cod_mar_dent INNER JOIN peso ON peso.cod_pes=d_entrada.cod_pes_dent INNER JOIN producto ON producto.cod_pro=d_entrada.cod_ref_dent WHERE cod_ment_dent=$codigo order by cod_dent   ";
					$db->query($sql);
					$estilo="formsleo";
					while($db->next_row()){ 	
						
				?>
                <tr id="fila_0"  >
                  <td  class="textotabla01"><?=$db->nom_tpro?></td>
				  <td  class="textotabla01"><?=$db->nom_mar?></td>
                  <td colspan="1" class="textotabla01"><?=$db->cod_fry_pro?></td>
                  <td  class="textotabla01"><div align="right"><?=$db->nom_pro?></div></td>
                  <td  class="textotabla01"><div align="right">
                    <?=$db->nom_pes?></div></td>
				   <td class="textotabla01"><div align="right"><?=number_format($db->cant_dent,0,".",".")?></div></td>
				 <td  class="textotabla01"><div align="right"><?=number_format($db->cos_dent,0,".",".")?></div></td>
                </tr>
				  
				<?
	
				  } 
				 
				 ?>
				 
				  <tr >
			  
                  <td colspan="7" >
				  <table  width="100%"  > 
				  <tr>
				  <td class="subfongris"><div align="right">Total Cargue </div></td>
				    <td><div align="right"><span class="tituloproductos">
				      <?=number_format($total_nuevo_saldo,0,".",".")?> 
			        </span></div></td>	
				  </tr>
				  </table>				  </td>
				  </tr>
              </table></TD>
		  </TR>
			<TR>
			  <TD colspan="2" align="center">             </TD>
		  </TR>
			<TR>
			  <TD colspan="2" align="center"><p></TD>
		  </TR>
			<TR>
			
			
			
			  <TD width="13%" height="40" align="center" valign="top"><div align="center" class="textoproductos1">
			    <div align="left" class="subtitulosproductos">Observaciones:			    </div>
			  </div></TD>
		      <TD width="87%" valign="top" ><span class="textotabla01">
		        <?=$obser?> 
		      </span></TD>
			</TR>
</TABLE>

 
<TABLE width="70%" border="0" cellspacing="0" cellpadding="0">
	
	<TR><TD colspan="3" align="center"><input name="button" type="button"  class="botones1" id="imp" onClick="imprimir()" value="Imprimr">
        <input name="button" type="button"  class="botones1"  id="cer" onClick="window.close()" value="Cerrar"></TD>
	</TR>

	<TR>
		<TD width="1%" background="images/bordefondo.jpg" style="background-repeat:repeat-y" rowspan="2"></TD>
		<TD bgcolor="#F4F4F4" class="pag_actual">&nbsp;</TD>
		<TD width="1%" background="images/bordefondo.jpg" style="background-repeat:repeat-y" rowspan="2"></TD>
	</TR>
	<TR>
	  <TD align="center">
	