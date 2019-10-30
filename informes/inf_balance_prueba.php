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
 <title><?=$nombre_aplicacion?> -- BALANCE DE PRUEBA --</title>
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
			  		<td width="47%" height="26"><div align="center"><span class="Estilo4">BALANCE DE PRUEBA DE <?=$fec_ini?> HASTA <?=$fec_fin?></span></div></td>
			    </tr>
				</table>					</TD>
		  </TR>
			
			
			<TR>
			  <TD align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                
				  <tr >
            <td width="9%"  class="botones1"><div align="center">CUENTA</div></td>
            <td width="34%"  class="botones1"><div align="center">NOMBRE DE LA CUENTA</div></td>
            <td width="16%"  class="botones1"><div align="center">SALDO INICIAL</div></td>
            <td width="14%"  class="botones1"><div align="center">DEBITOS</div></td>
            <td width="13%"  class="botones1"><div align="center">CREDITOS</div></td>
			<td width="14%"  class="botones1"><div align="center">NUEVO SALDO</div></td>
            </tr>
				<?
				$db = new Database ();
				$sql = "SELECT * FROM cuenta
				INNER JOIN d_movimientos ON d_movimientos.cuenta_dmov = cuenta.cod_cuenta
				INNER JOIN m_movimientos ON m_movimientos.cod_mov = d_movimientos.cod_mov
				WHERE (fec_emi >='$fec_ini') AND (fec_emi <='$fec_fin') AND(cod_contable LIKE '1%')or(cod_contable LIKE '5%')or(cod_contable LIKE '6%') AND estado_mov = 1
				GROUP BY cod_cuenta
				ORDER BY  `cuenta`.`cod_contable` ASC";
				$db->query($sql);
				$estilo="formsleo";
				$total_saldo = 0;
				$total_debito = 0;
				$total_credito = 0;
				$total_nuevo_saldo = 0;
				while($db->next_row()){					
				?>
                <tr id="fila_0"  >
                  <td  class="textotabla01"><?=$db->cod_contable?></td>
				  <td  class="textotabla01"><?=$db->desc_cuenta?></td>
                <?
                $dbsi = new Database ();
				$sqlsi = "SELECT SUM(debito_dmov) AS suma_debito,SUM(credito_dmov) AS suma_credito FROM `d_movimientos`
				INNER JOIN m_movimientos ON m_movimientos.cod_mov = d_movimientos.cod_mov
				where cuenta_dmov = $db->cod_cuenta AND (fec_emi >='$fec_ini') AND concepto_dmov = 35 AND estado_mov = 1";
				$dbsi->query($sqlsi);
				$dbsi->next_row();	

				$saldo = $dbsi->suma_debito - $dbsi->suma_credito;
				
				if ($db->nivel == 1){
					$total_saldo = $total_saldo + $saldo;
				}				
				?>
				  <td class="textotabla01"><div align="right"><?=$saldo?></div></td>
				<?
                $dbb = new Database ();
				$sqlb = "SELECT sum(debito_dmov) as debito FROM `d_movimientos`
				INNER JOIN m_movimientos ON m_movimientos.cod_mov = d_movimientos.cod_mov
				where cuenta_dmov = $db->cod_cuenta AND ((fec_emi >='$fec_ini')AND(fec_emi <='$fec_fin')) AND concepto_dmov != 35 AND estado_mov = 1";
				$dbb->query($sqlb);
				$dbb->next_row();						
				if($dbb->debito == ""){
					$debito = 0;	
				}
				else {
					$debito = $dbb->debito;
				};
				if ($db->nivel == 1){
					$total_debito = $total_debito + $debito;
				}
				?>
				  <td class="textotabla01">
			      <div align="right"><?=number_format($debito,0,".",".")?></div>
                  </td>
                <?
				$dbc = new Database ();
				$sqlc = "SELECT sum(credito_dmov) as credito FROM `d_movimientos`
				INNER JOIN m_movimientos ON m_movimientos.cod_mov = d_movimientos.cod_mov
				where cuenta_dmov = $db->cod_cuenta AND ((fec_emi >='$fec_ini')AND(fec_emi <='$fec_fin')) AND estado_mov = 1";
				$dbc->query($sqlc);
				$dbc->next_row();		
				if($dbc->credito == ""){
					$credito = 0;	
				}
				else {
					$credito = $dbc->credito;
				};
				if ($db->nivel == 1){
					$total_credito = $total_credito + $credito;
				}				
				?>               
				 <td class="textotabla01">
			     <div align="right"><?=number_format($credito,0,".",".")?></div>
                 </td>
                 <? 
				 $nuevo_saldo = $saldo + $debito - $credito;
				 
				 if ($db->nivel == 1){
					$total_nuevo_saldo = $total_nuevo_saldo + $nuevo_saldo;
				 }
				 ?>
				 <td colspan="1" class="textotabla01"><div align="right">
				   <?=number_format($nuevo_saldo,0,".",".")?>				 </div></td>
                </tr>
                <? } ?>
                 <tr >
                   <td colspan="6" >&nbsp;</td>
                 </tr>
                 <tr >
                   <td colspan="2" ><div align="right"><strong>Total activo y egresos:</strong></div></td>
                   <td ><strong><div align="right">
                     <?=number_format($total_saldo,0,".",".")?>
                   </div></strong></td>
                   <td ><strong><div align="right">
                     <?=number_format($total_debito,0,".",".")?>
                   </div></strong></td>
                   <td ><strong><div align="right">
                     <?=number_format($total_credito,0,".",".")?>
                   </div></strong></td>
                   <td ><strong><div align="right">
                     <?=number_format($total_nuevo_saldo,0,".",".")?>
                   </div></strong></td>
                 </tr>
                 <tr >
                   <td colspan="6" >&nbsp;</td>
                 </tr>
                 <tr >
                   <td colspan="6" >&nbsp;</td>
                 </tr>
                 <tr >
            <td width="9%"  class="botones1"><div align="center">CUENTA</div></td>
            <td width="34%"  class="botones1"><div align="center">NOMBRE DE LA CUENTA</div></td>
            <td width="16%"  class="botones1"><div align="center">SALDO INICIAL</div></td>
            <td width="14%"  class="botones1"><div align="center">DEBITOS</div></td>
            <td width="13%"  class="botones1"><div align="center">CREDITOS</div></td>
			<td width="14%"  class="botones1"><div align="center">NUEVO SALDO</div></td>
            </tr>
				<?
				$db = new Database ();
				$sql = "SELECT * FROM cuenta
				INNER JOIN d_movimientos ON d_movimientos.cuenta_dmov = cuenta.cod_cuenta
				INNER JOIN m_movimientos ON m_movimientos.cod_mov = d_movimientos.cod_mov
				WHERE (fec_emi >='$fec_ini') AND (fec_emi <='$fec_fin') AND ((cod_contable LIKE '2%')or(cod_contable LIKE '3%') or(cod_contable LIKE '4%')) AND estado_mov = 1
				GROUP BY cod_cuenta
				ORDER BY  `cuenta`.`cod_contable` ASC";
				$db->query($sql);
				$estilo="formsleo";
				$total_saldo = 0;
				$total_debito = 0;
				$total_credito = 0;
				$total_nuevo_saldo = 0;
				while($db->next_row()){						
				?>
                <tr id="fila_0"  >
                  <td  class="textotabla01"><?=$db->cod_contable?></td>
				  <td  class="textotabla01"><?=$db->desc_cuenta?></td>
                <?
                $dbsi = new Database ();
				$sqlsi = "SELECT SUM(debito_dmov) AS suma_debito,SUM(credito_dmov) AS suma_credito FROM `d_movimientos`
				INNER JOIN m_movimientos ON m_movimientos.cod_mov = d_movimientos.cod_mov
				where cuenta_dmov = $db->cod_cuenta AND (fec_emi >='$fec_ini') AND concepto_dmov = 35 AND estado_mov = 1";
				$dbsi->query($sqlsi);
				$dbsi->next_row();
					
				$saldo = $dbsi->suma_credito - $dbsi->suma_debito;
				
				if ($db->nivel == 1){
					$total_saldo = $total_saldo + $saldo;
				}					
				?>
				  <td class="textotabla01"><div align="right"><?=number_format($saldo,0,".",".")?></div></td>
               <?
				$dbb = new Database ();
				$sqlb = "SELECT sum(debito_dmov) as debito FROM `d_movimientos`
				INNER JOIN m_movimientos ON m_movimientos.cod_mov = d_movimientos.cod_mov
				where cuenta_dmov = $db->cod_cuenta AND ((fec_emi >='$fec_ini')AND(fec_emi <='$fec_fin')) AND estado_mov = 1";
				$dbb->query($sqlb);
				$dbb->next_row();
				if($dbb->debito == ""){
					$debito = 0;	
				}
				else {
					$debito = $dbb->debito;
				};		
				if ($db->nivel == 1){
					$total_debito = $total_debito + $debito;
				}				
				?>
				  <td class="textotabla01">
			      <div align="right"><?=number_format($debito,0,".",".")?></div></td>
                <?
				$dbc = new Database ();
				$sqlc = "SELECT sum(credito_dmov) as credito FROM `d_movimientos`
				INNER JOIN m_movimientos ON m_movimientos.cod_mov = d_movimientos.cod_mov
				where cuenta_dmov = $db->cod_cuenta AND ((fec_emi >='$fec_ini')AND(fec_emi <='$fec_fin'))  AND concepto_dmov != 35 AND estado_mov = 1";
				$dbc->query($sqlc);
				$dbc->next_row();	
				if($dbc->credito == ""){
					$credito = 0;	
				}
				else {
					$credito = $dbc->credito;
				};		
				if ($db->nivel == 1){
					$total_credito = $total_credito + $credito;
				}						
				?>                
				  <td class="textotabla01">
			      <div align="right"><?=number_format($credito,0,".",".")?></div></td>
                  <? 
				  $nuevo_saldo = $saldo + $credito - $debito;				  				 
				  if ($db->nivel == 1){
					$total_nuevo_saldo = $total_nuevo_saldo + $nuevo_saldo;
				  }
				  ?>
				 <td colspan="1" class="textotabla01"><div align="right">
				   <?=number_format($nuevo_saldo,0,".",".")?>
				 </div></td>
                </tr>
				  
				<?
				  } 
				 ?>
				 
				  <tr >
				    <td colspan="6" >&nbsp;</td>
			    </tr>
				  <tr >
				    <td colspan="2" ><div align="right"><strong>Total pasivo patrimonio e ingresos:</strong></div></td>
				    <td ><strong><div align="right">
				      <?=number_format($total_saldo,0,".",".")?>
			        </div></strong></td>
				    <td ><strong><div align="right">
				      <?=number_format($total_debito,0,".",".")?>
			        </div></strong></td>
				    <td ><strong><div align="right">
				      <?=number_format($total_credito,0,".",".")?>
			        </div></strong></td>
				    <td ><strong><div align="right">
				      <?=number_format($total_nuevo_saldo,0,".",".")?>
			        </div></strong></td>
			    </tr>
				  <tr >
				    <td colspan="5" >&nbsp;</td>
				    <td >&nbsp;</td>
			    </tr>
				  <tr >
			  
                  <td colspan="6" >
				  <table  width="100%"  > 
				  <tr>
				    </tr>
				  </table>				  </td>
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
	