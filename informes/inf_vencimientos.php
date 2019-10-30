<?
include "../lib/sesion.php";
include("../lib/database.php");
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
 <title><?=$nombre_aplicacion?> -- VENCIMIENTOS --</title>
 <style type="text/css">
<!--
.Estilo4 {font-size: 18px}
-->
 </style>
 <TABLE width="100%" border="0" cellspacing="0" cellpadding="0"   >
 <?=$credito?>
	
	<TR>
		<TD align="center">
		<TABLE width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

			<TR>
			  <TD width="100%" class='txtablas'>
			  <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  	<tr>
			  		<td width="47%" height="26"><div align="center"><span class="Estilo4">INFORME DE VENCIMIENTOS DE
	  		      <?=$fec_ini?> HASTA <?=$fec_fin?></span></div></td>
			    </tr>
				</table>					</TD>
		  </TR>
			
			
			<TR>
			  <TD align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                
				  <tr >
            <td width="26%"  class="botones1"><div align="center">TERCERO</div></td>
            <td width="12%"  class="botones1"><div align="center">NO FACTURA</div></td>
            <td width="15%"  class="botones1"><div align="center">FECHA EMISION</div></td>
            <td width="15%"  class="botones1"><div align="center">FECHA VENCIMIENTO</div></td>
            <td width="18%"  class="botones1"><div align="center">VALOR TOTAL</div></td>
			<td width="14%"  class="botones1"><div align="center">SALDO</div></td>
            </tr>
				<?
				$db = new Database ();
				$sql = "SELECT *,SUM(credito_dmov) AS deuda FROM m_movimientos
				INNER JOIN d_movimientos ON d_movimientos.cod_mov = m_movimientos.cod_mov
				INNER JOIN proveedor ON proveedor.cod_pro = d_movimientos.cod_ter
				WHERE fec_venci >='$fec_ini' AND fec_venci<='$fec_fin'
				GROUP BY d_movimientos.cod_mov";
				$db->query($sql);
				$estilo="formsleo";
				$total = 0;
				while($db->next_row()){					
				?>
                 <tr >
                   <td class="boton">
				   <? 
				   if($db->tipo_mov == 11){ 
				   		echo "Nomina"; 
				   } 
				   else { 
				   echo $db->nom_pro; }
				   ?>
                   </td>
                   <td class="boton"><?=$db->num_mov?></td>
                   <td class="boton"><div align="center">
                     <?=$db->fec_emi?>
                   </div></td>
                   <td class="boton"><div align="center">
                     <?=$db->fec_venci?>
                   </div>
                   <div align="center"></div></td>
                   <td class="boton"><div align="right"><?=number_format($db->deuda,0,".",".")?></div></td>
                   <td class="boton"><div align="right">
                     <?=number_format($db->deuda,0,".",".")?>
                   </div></td>
                 </tr>	        
               	<? 
				$total = $total + $db->deuda;
				} 				
				?>			 
				  <tr >
				    <td colspan="3" >&nbsp;</td>
				    <td class="boton">TOTAL</td>
				    <td class="boton"><div align="right"><?=number_format($total,0,".",".")?></div></td>
				    <td class="boton"><div align="right"><?=number_format($total,0,".",".")?></div></td>
		        </tr>
				  <tr >
				    <td colspan="6" >&nbsp;</td>
			    </tr>
		      </table></TD>
		  </TR>
			<TR>
			  <TD align="center">             </TD>
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
	