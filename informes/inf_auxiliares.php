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
 <title><?=$nombre_aplicacion?> -- AUXILIAR --</title>
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
			  		<td width="47%" height="26"><div align="center"><span class="Estilo4">INFORME DE AUXILIARES DE
	  		      <?=$fec_ini?> HASTA <?=$fec_fin?></span></div></td>
			    </tr>
				</table>					</TD>
		  </TR>
			
			
			<TR>
			  <TD align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                
				  <tr >
            <td width="12%"  class="botones1"><div align="center">FECHA EMISION</div></td>
            <td width="11%"  class="botones1"><div align="center">CONSECUTIVO</div></td>
            <td width="38%"  class="botones1"><div align="center">TERCERO</div></td>
            <td width="18%"  class="botones1"><div align="center">DEBITO</div></td>
            <td width="21%"  class="botones1"><div align="center">CREDITO</div></td>
			</tr>
				<?
				$db = new Database ();
				$sql = "SELECT * FROM d_movimientos
				INNER JOIN cuenta ON cuenta.cod_cuenta = d_movimientos.cuenta_dmov
				INNER JOIN m_movimientos ON m_movimientos.cod_mov = d_movimientos.cod_mov
				INNER JOIN proveedor ON proveedor.cod_pro = d_movimientos.cod_ter
				WHERE nivel = 5 AND cod_cuenta = $auxiliar AND fec_venci >='$fec_ini' AND fec_venci<='$fec_fin' and estado_mov = 1
				ORDER BY fec_emi";
				$db->query($sql);
				$estilo="formsleo";
				$total_debito = 0;
				$total_credito = 0;
				while($db->next_row()){					
				?>
                 <tr >
                   <td class="boton"><div align="center"><?=$db->fec_emi?></div></td>
                   <td class="boton"><div align="center"><?=$db->conse_mov?></div></td>
                   <td class="boton"><div align="left"><?=$db->nom_pro?></div></td>
                   <td class="boton"><div align="right"><?=number_format($db->debito_dmov,0,".",".")?></div>
                   <div align="center"></div></td>
                   <td class="boton"><div align="right"><?=number_format($db->credito_dmov,0,".",".")?></div></td>
                 </tr>	        
               	<? 
				$total_debito = $total_debito + $db->debito_dmov;
				$total_credito = $total_credito + $db->credito_dmov;
				} 				
				?>			 
				  <tr >
				    <td colspan="3" ><div align="right"><span class="boton">TOTAL</span></div></td>
				    <td class="boton"><div align="right">
				      <?=number_format($total_debito,0,".",".")?>
			        </div></td>
				    <td class="boton"><div align="right"><?=number_format($total_credito,0,".",".")?></div></td>
			    </tr>
				  <tr >
				    <td colspan="5" >&nbsp;</td>
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

	