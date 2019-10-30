<?
include "../lib/sesion.php";
include("../lib/database.php");
			
//echo $codigo;
//echo $nombre_aplicacion;
//exit;

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
 <title>INFORME CIERRE - GASTOS</title>
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0"   >
	
	<TR>
		<TD align="center">
		<TABLE width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

			<TR>
			  <TD width="100%" class='txtablas'>
			  <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  	<tr>
			  		<td class="subtitulosproductos">Informe de Cierre de Gastos del Periodo: <?=$fec_ini?>  &nbsp;  al     &nbsp;<?=$fec_fin?></td>
			    </tr>
				</table>					</TD>
		  </TR>
			
			
			<TR>
			  <TD align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                
				  <tr >
            <td  class="botones1">PUNTO DE VENTA </td>
            <td  class="botones1">FECHA</td>
            
			<td  class="botones1">RESPONSABLE</td>
            <td  class="botones1">TIPO DE GASTO</td>
			<td   class="botones1">VALOR</td>

			</tr>
				<?
				$total=0;
			$sql=" SELECT 
resumen_gastos.cod_res,
resumen_gastos.tipo_gas,
resumen_gastos.fec_res,
resumen_gastos.val_res,
resumen_gastos.rso_res,
resumen_gastos.pun_res,
resumen_gastos.resp_res,
resumen_gastos.obs_res,
tipo_gastos.nom_gas,
rsocial.nom_rso,
bodega.nom_bod,
usuario.nom_usu
FROM
resumen_gastos
left JOIN tipo_gastos ON (resumen_gastos.tipo_gas = tipo_gastos.cod_gas)
left JOIN rsocial ON (resumen_gastos.rso_res = rsocial.cod_rso)
left JOIN bodega ON (resumen_gastos.pun_res = bodega.cod_bod)
left JOIN usuario ON (resumen_gastos.resp_res = usuario.cod_usu) where resp_res=$global[2]  and  ( fec_res >='$fec_ini' AND fec_res <='$fec_fin' ) ORDER BY fec_res DESC ";

				/*$sql = " SELECT * FROM kardex	
					INNER JOIN producto ON kardex.cod_ref_kar=producto.cod_pro
					LEFT JOIN 	tipo_producto ON producto.cod_tpro_pro = tipo_producto.cod_tpro 
					LEFT JOIN  marca ON producto.cod_mar_pro = marca.cod_mar
					LEFT JOIN peso ON kardex.cod_talla = peso.cod_pes 
					WHERE kardex.cod_bod_kar=$codigo_bodega and kardex.cant_ref_kar>0 order by nom_pro, cod_talla  ";*/
					$db->query($sql);
					$estilo="formsleo";
					while($db->next_row()){ 	
						
				?>
                <tr id="fila_0"  >
				
                  <td  class="textotabla01"><?=$db->nom_bod?></td>
				  <td  class="textotabla01"><?=$db->fec_res?></td>
                  
                  <td  class="textotabla01"><?=$db->nom_usu?></td>
                  <td  class="textotabla01"><?=$db->nom_gas?></td>
				 
				   <td class="textotabla01"><div align="right"><?=number_format($db->val_res,0,".",".")?></div></td>
			    </tr>
				  
				<?
	$total_gastos=$total_gastos + $db->val_res;
				  } 
				 
				 ?>
				 
				  <tr >
			  
                  <td colspan="6" >&nbsp;</td>
				  </tr>
              </table></TD>
		  </TR>
			<TR>
			  <TD align="center">             </TD>
		  </TR>
			<TR>
			  <TD align="center"><p class="subtitulosproductos">Total Gastos del Periodo $<?=number_format($total_gastos,0,".",".")?> </TD>
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
	