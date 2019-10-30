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
 <title><?=$nombre_aplicacion?> -- MOVIMIENTOS CONTABLES --</title>
 <style type="text/css">
<!--
.Estilo4 {font-size: 18px}
-->
 </style>
 <TABLE width="100%" border="0" cellspacing="0" cellpadding="0"   >
	
	<TR>
		<TD align="center">
		<TABLE width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

			<TR>
			  <TD colspan="2" class='txtablas'>
			  <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  	<tr>
			  		<td width="47%" height="22" class="textotabla01"><div align="center">MOVIMIENTOS CONTABLES</div></td>
			    </tr>
				</table>					</TD>
		  </TR>
			
			
			<TR>
			  <TD colspan="2" align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
<?  $dbv = new Database();
	$sqlv = "SELECT * FROM m_movimientos
	WHERE cod_mov = $codigo";
	$dbv->query($sqlv);
	$dbv->next_row();
?>
				  <tr >
				    <td class="textotabla01">Consecutivo:</td>
				    <td class="textotabla01"><?=$dbv->conse_mov?></td>
				    <td class="textotabla01">&nbsp;</td>
				    <td class="textotabla01">&nbsp;</td>
				    <td class="textotabla01">No factura:</td>
				    <td class="textotabla01"><?=$dbv->num_mov?></td>
			    </tr>
				  <tr >
				    <td class="textotabla01">Fecha de emision:</td>
				    <td class="textotabla01"><?=$dbv->fec_emi?></td>
				    <td class="textotabla01">&nbsp;</td>
				    <td class="textotabla01">&nbsp;</td>
				    <td class="textotabla01">Fecha de vencimiento:</td>
				    <td class="textotabla01"><?=$dbv->fec_venci?></td>
			    </tr>
				  <tr >
				    <td class="textotabla01">&nbsp;</td>
				    <td class="textotabla01">&nbsp;</td>
				    <td class="textotabla01">&nbsp;</td>
				    <td class="textotabla01">&nbsp;</td>
				    <td class="textotabla01">&nbsp;</td>
				    <td class="textotabla01">&nbsp;</td>
			    </tr>
				  <tr >
				    <td width="5%"  class="botones1">CENTRO</td>
				    <td width="5%"  class="botones1">TERCERO</td>
            <td width="5%"  class="botones1">CUENTA</td>
            <td width="5%"  class="botones1">CONCEPTO</td>
			<td width="5%"  class="botones1">DEBITO</td>
			 <td width="5%"   class="botones1">CREDITO</td>
            </tr>
            <?  $db = new Database();
	$sql = "SELECT *,CONCAT(cod_contable,'-',desc_cuenta) as cuenta FROM d_movimientos
	INNER JOIN proveedor ON proveedor.cod_pro = d_movimientos.cod_ter
	INNER JOIN cuenta ON cuenta.cod_cuenta = d_movimientos.cuenta_dmov
	INNER JOIN concepto ON concepto.cod_concepto = d_movimientos.concepto_dmov
	INNER JOIN centro_costos ON centro_costos.cod_centro = d_movimientos.centro_dmov
	WHERE cod_mov = $codigo AND nivel = 5" ;
	$db->query($sql);
	while($db->next_row()){
?>
                <tr id="fila_0"  >
                  <td  class="textotabla01"><?=$db->nom_centro?></td>
                  <td  class="textotabla01"><?=$db->nom_pro?></td>
                  <td  class="textotabla01"><?=$db->cuenta?></td>
				  <td  class="textotabla01"><div align="center"><?=$db->desc_concepto?></div></td>
                  <td  class="textotabla01"><div align="center"><?=$db->debito_dmov?></div></td>
                  <td  class="textotabla01"><div align="center"><?=$db->credito_dmov?></div></td>
                <? 
				 $total_debitos = $total_debitos + $db->debito_dmov;
				 $total_creditos = $total_creditos + $db->credito_dmov;
				} ?>
			    </tr>			 
				  <tr >
				    <td colspan="6" >&nbsp;</td>
			    </tr>
				  <tr >
				    <td colspan="5" ><strong><div align="right">Total debitos:</div></strong></td>
				    <td ><strong><div align="right"><?=number_format($total_debitos,0,".",".")?></div></strong></td>
		        </tr>
				  <tr >
			  
                  <td height="23" colspan="5" ><strong><div align="right">Total creditos:</div></strong></td>
                  <td ><strong><div align="right"><?=number_format($total_creditos,0,".",".")?></div></strong></td>
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
		        <?=$dbv->obs_mov?> 
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
	