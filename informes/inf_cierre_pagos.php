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
 <title>INFORME CIERRE - PAGOS</title>
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
			  		<td class="subtitulosproductos">Informe de Cierre de Otros Pagos del Periodo:
			  		  <?=$fec_ini?>
&nbsp;  al     &nbsp;
<?=$fec_fin?></td>
			    </tr>
				</table>					</TD>
		  </TR>
			
			
			<TR>
			  <TD align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                
				  <tr >
            <td width="27%"  class="botones1">CLIENTE </td>
            <td width="13%"  class="botones1">FECHA</td>
            <td width="25%"  class="botones1">TIPO DE PAGO</td>
			<td width="25%"  class="botones1">OBSERVACIONES</td>
			<td width="12%"   class="botones1">VALOR</td>

			</tr>
				<?
				$total=0;

				$sql=" SELECT 
  otros_pagos.cod_usu_otro,
  otros_pagos.fec_otro,
  otros_pagos.cod_cli_otro,
  otros_pagos.obs_otro,
  otros_pagos.val_otro,
  otros_pagos.cod_tpag_otro,
  usuario.nom_usu,
  bodega1.nom_bod,
  tipo_pago.nom_tpag,
  otros_pagos.cod_otro
FROM
  otros_pagos
  INNER JOIN usuario ON (otros_pagos.cod_usu_otro = usuario.cod_usu)
  INNER JOIN bodega1 ON (otros_pagos.cod_cli_otro = bodega1.cod_bod)
  INNER JOIN tipo_pago ON (otros_pagos.cod_tpag_otro = tipo_pago.cod_tpag)
  where cod_usu_otro=$global[2] and  ( fec_otro >='$fec_ini' AND fec_otro <='$fec_fin' ) ORDER BY fec_otro DESC  ";
					$db->query($sql);
					$estilo="formsleo";
					while($db->next_row()){ 	
						
				?>
                <tr id="fila_0"  >
				
                  <td  class="textotabla01"><?=$db->nom_bod?></td>
				  <td  class="textotabla01"><?=$db->fec_otro?></td>
                  
                 
                  <td  class="textotabla01"><?=$db->nom_tpag?></td>
				   <td  class="textotabla01"><?=$db->obs_otro?></td>
				 
				   <td class="textotabla01"><div align="right"><?=number_format($db->val_otro,0,".",".")?></div></td>
			    </tr>
				  
				<?
	$total_gastos=$total_gastos + $db->val_otro;
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
	