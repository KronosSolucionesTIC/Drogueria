<?
include "../lib/sesion.php";
include("../lib/database.php");
			
//echo $codigo;
//echo $nombre_aplicacion;
//exit;
$db = new Database();
$db_ver = new Database();
$sql = "SELECT cod_ven_mven, fec_mven, cod_rut_mven, obs_mven, dec_cre_mven, obs_cre_mven, dec_cob_mven, obs_cob_mven, 
dec_che_mven, obs_che_mven, dec_gas_mven, obs_gas_mven, dec_efe_mven, obs_efe_mven, dec_cons_mven, obs_cons_mven  ,vendedor.nom_ven, ruta.nom_rut
FROM m_venta 
INNER JOIN vendedor ON m_venta.cod_ven_mven=vendedor.cod_ven
LEFT JOIN ruta ON m_venta.cod_rut_mven=ruta.cod_rut

 WHERE cod_mven=$codigo";
$db_ver->query($sql);	
if($db_ver->next_row()){ 
	$vendedor=$db_ver->nom_ven;
	$fecha=$db_ver->fec_mven;
	$obser=$db_ver->obs_mven;
	$ruta=$db_ver->nom_rut;
	
	$credito_dec=$db_ver->dec_cre_mven;
	$credito_obs=$db_ver->obs_cre_mven;
	
	$cobor_dec=$db_ver->dec_cob_mven;
	$cobro_obs=$db_ver->obs_cob_mven;
	
	$cheque_dec=$db_ver->dec_che_mven;
	$cheque_obs=$db_ver->obs_che_mven;
	
	$gasto_dec=$db_ver->dec_gas_mven;
	$gasto_obs=$db_ver->obs_gas_mven;
	
	$efectivo_dec=$db_ver->dec_efe_mven;
	$efectivo_obs=$db_ver->obs_efe_mven;
	
	$consignacion_dec=$db_ver->dec_cons_mven;
	$consignacion_obs=$db_ver->obs_cons_mven;
	
	
	
	
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
 <title><?=$nombre_aplicacion?> -- Venta --</title>
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0"   >
	
	<TR>
		<TD align="center">
		<TABLE width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

			<TR>
			  <TD colspan="3" class='txtablas'>
			  <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  	<tr>
			  		<td width="15%">
			  		<img src="distribuciones.jpg" width="139" height="60" /></td>
				    <td width="63%" height="22" class="ctablaform"> <span class="textoproductos1"> &nbsp;&nbsp;&nbsp;&nbsp;VENDEDOR:<span class="textotabla01">
                    <?=$vendedor?>
                    </span></span>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<span class="textoproductos1"> &nbsp;&nbsp;&nbsp;&nbsp;RUTA:<span class="textotabla01">
                    <?=$ruta?>
                    </span></span>				  </td>
			  	   
			  	    <td width="22%" class="ctablaform"><span class="textoproductos1">&nbsp;&nbsp;Fecha:</span><span class="textotabla01"> <?=$fecha?></span></td>
			  	</tr>
				</table>					</TD>
		  </TR>
			
			
			<TR>
				<!--  tabla de efectivo-->
			  <TD colspan="2"  align="center">
			  	<table width="98%">
				<tr id="fila_cre_0" >
				<td  class="ctablasup" colspan="3" >Venta Efectivo </td>
				</tr>
				
				<tr id="fila_cre_0">
				 <td  class="ctablasup" width="28%">Denominacion</td>
           		 <td   width="30%" class="ctablasup">Cantidad</td>
				 <td  class="ctablasup"  width="42%" >Valor</td>
				</tr>
				<?
		//busca las denominaciones\		
		$sql ="select cod_den,nom_den from denominacion order by nom_den,tipo_mon_den asc";//=		
		$dbdatos_1= new  Database();
		$dbdatos_sub= new  Database();
		$dbdatos_1->query($sql);
		$total_efe=0;
		$jj=1;
		while($dbdatos_1->next_row()){ 
		$codigos_den.=$dbdatos_1->cod_den."|";
		?> 
          	<tr >
				<td  class="ctablasup">
				<?
				$sql ="SELECT * FROM d_efectivo_venta WHERE cod_mven_dvef=$codigo AND cod_den_dvef=$dbdatos_1->cod_den";
				$dbdatos_sub->query($sql);
				if($dbdatos_sub->next_row()){ 
					$canti_efe=$dbdatos_sub->cant_dvef;
					$valor_efe=$dbdatos_sub->val_dvef;
					$total_efe=$total_efe + $valor_efe;
				}
				?>
				<input type="hidden" name="cod_denominacion_<?=$dbdatos_1->cod_den?>"  value="<?=$dbdatos_1->cod_den?>" />
				<input type="hidden" name="denominacion_<?=$dbdatos_1->cod_den?>"  value="<?=$dbdatos_1->nom_den?>" />
				<div align="right"> <?=number_format($dbdatos_1->nom_den,0,",",".")?></div></td>
				<td  >
					 
		        
		            <div align="right" class="textfield002">
                <span class="textfield01">
                <? if($canti_efe>0) echo $canti_efe; else echo "0" ?>
                </span></div></td>
				<td align="center">
					<div align="right"><span class="SELECT">
					  <? if($valor_efe>0) echo  number_format($valor_efe,0,".",".") ; else echo "0" ?>
			  </span></div>			  </td>
			</tr>
		<?
		}
		?>	
				</table>
				<table width="98%">
				<tr >
				<td width="29%" >
				<div align="left">
				<span class="ctablasup"> 
				Declarado: <?=number_format($efectivo_dec,0,".",".") ?>
				</span>				</div>				</td>
				<td width="71%" >
				<div align="right"><span class="ctablasup">Total efectivo: <?=number_format($total_efe,0,".",".")?>
				    
				  </span>				</div>		
			<tr >
			<td valign="top" colspan="2" >
			  <span class="ctablasup">Observacion Efectivo:  </span>		 
			 <span class="textfiel_caja"><?=$efectivo_obs?></span>			 </td> 
			</tr>
			  </table>			  </TD>
			  
			  <!--  tabla de efectivo-->
			  
			  <!--  tabla de consignacion-->
			  <TD width="50%"  align="center" valign="top">
			  	<table width="98%">
				<tr id="fila_cre_0" >
				<td  class="ctablasup" colspan="3" >Venta Consignacion</td>
				</tr>
				
				<tr id="fila_cre_0">
				<td  class="ctablasup" width="30%">Banco</td>
				<td  class="ctablasup" width="32%">Num Consignacion </td>
				<td  class="ctablasup" width="27%">Valor</td>
				</tr>
				<?
				$total_cosignacion=0;
				if ($codigo!="") { // BUSCAR DATOS
					$sql ="SELECT banco_dvcon,cod_doc_dvcon,val_consig_dvcon FROM d_consignacion_venta where cod_mven_dvcon = $codigo";//=		
					$dbdatos_1= new  Database();
					$dbdatos_1->query($sql);
					$jj=1;
					while($dbdatos_1->next_row()){ 
						echo "<tr id='fila_cons_$jj'>";
						//banco
						echo "<td><INPUT type='hidden'  name='banco_consignacion_$jj' value='$dbdatos_1->banco_dvcon'><span  class='textfield01'>$dbdatos_1->banco_dvcon </span></td>";
						
						//consignacion
						echo "<td><INPUT type='hidden'  name='num_consignacion_$jj' value='$dbdatos_1->cod_doc_dvcon'><span  class='textfield01'>$dbdatos_1->cod_doc_dvcon </span> </td>";
						
						//valor consignacion
						echo "<td align='right'><INPUT type='hidden'  name='valor_consignacion_$jj' value='$dbdatos_1->val_consig_dvcon'><span  class='textfield01'>".number_format($dbdatos_1->val_consig_dvcon,0,".",".")."</span></td>";
						
						
						//total del credito
						$total_consignacion= intval($total_consignacion + $dbdatos_1->val_consig_dvcon);
						//echo "<td><div align='center'><INPUT type='button' class='botones' value='  -  ' onclick='removerFila_consignacion(\"fila_cons_$jj\",\"val_inicial_cons\",\"fila_cons_\",\"$jj\",\"$dbdatos_1->val_consig_dvcon\");'>						</div></td>";
						echo "</tr>";
						$jj++;
						
					}
					$jj_consignacion=$jj;
				}
				?>
				</table>
				<table width="97%">
			<tr >
				<td >
				<div align="left">
				<span class="ctablasup">Valor Declarado:<?=number_format($consignacion_dec,0,".",".")?>	</span>				</div>				</td>
				<td  align="right">
				<span class="ctablasup">Total Consignacion: <? if ($codigo !=0) echo number_format($total_consignacion,0,".","."); else echo "0" ?>
				</span></td>
			</tr>
			<tr >
			<td valign="top" colspan="2" >
			<span class="ctablasup">Observacion de Consignacion: </span>	
			<span class="textfiel_caja"><?=$consignacion_obs?></span>	
			  </td> 
			</tr>
			<tr >
			<td valign="top" colspan="2" >&nbsp; </td> 
			</tr>				
			  </table>			  </TD>
			   <!--  tabla de consignacion-->
		  </TR>
		  
		  <TR>
			   <!--  tabla de gastos-->
			  <TD colspan="2"  align="center">
			  	<table width="98%">
				<tr id="fila_cre_0" >
				<td  class="ctablasup" colspan="3" >Venta Gastos </td>
				</tr>
				
				<tr id="fila_cre_0">
				<td  class="ctablasup" width="52%">Tipo de Gasto</td>
				<td  class="ctablasup" width="33%">Valor</td>
				</tr>
				<?
				$total_gasto=0;
				if ($codigo!="") { // BUSCAR DATOS
					$sql ="SELECT cod_doc_dvga,val_gas_dvga ,nom_doc FROM d_gastos_venta INNER JOIN documento ON cod_doc_dvga=cod_doc WHERE cod_mven_dvga = $codigo";//=		
					$dbdatos_1= new  Database();
					$dbdatos_1->query($sql);
					$jj=1;
					while($dbdatos_1->next_row()){ 
						echo "<tr id='fila_gas_$jj'>";
						//tipo documento
						echo "<td><INPUT type='hidden'  name='codigo_gasto_$jj' value='$dbdatos_1->cod_doc_dvga'><span  class='textfield01'>$dbdatos_1->nom_doc </span> </td>";
						
						//valor de gasto
						echo "<td align='right'><INPUT type='hidden'  name='valor_gasto_$jj' value='$dbdatos_1->val_gas_dvga'><span  class='textfield01'>".number_format($dbdatos_1->val_gas_dvga,0,".",".")."</span></td>";
						
						
						//total del credito
						$total_gasto= intval($total_gasto + $dbdatos_1->val_gas_dvga);
						//echo "<td><div align='center'><INPUT type='button' class='botones' value='  -  ' onclick='removerFila_gasto(\"fila_gas_$jj\",\"val_inicial_gas\",\"fila_gas_\",\"$jj\",\"$dbdatos_1->val_gas_dvga\");'>						</div></td>";
						echo "</tr>";
						$jj++;
						
					}
					$jj_gasro=$jj;
				}
				?>
				</table>
				<table width="98%">
			<tr >
				<td >
				<div align="left">
				<span class="ctablasup">Valor Declarado:<?=number_format($gasto_dec,0,".",".")?>				</span>				</div>				</td>
				<td  align="right"><span class="ctablasup">Total Gastos: <? if ($codigo !=0) echo number_format($total_gasto,0,".","."); else echo "0" ?>
				</span></td>
			</tr>
			<tr >
			<td valign="top" colspan="2" >
			<span class="ctablasup">Observacion de Gastos: </span>
			<span class="textfiel_caja"><?=$gasto_obs?></span>
						</td> 
			</tr>
			<tr >
			<td valign="top" colspan="2" >&nbsp; </td> 
			</tr>				
			  </table>			  </TD>
			  <!--  tabla de gastos-->
			  
			  <!--  tabla de credito-->
		    <TD  align="center" valign="top">
			  <table width="98%">
				<tr id="fila_cre_0" >
				<td  class="ctablasup" colspan="3" >Venta Credito</td>
				</tr>
				
				<tr id="fila_cre_0">
				<td  class="ctablasup" width="29%">Factura</td>
				<td  class="ctablasup" width="39%">Cliente</td>
				<td  class="ctablasup" width="20%">Valor</td>
				</tr>
				<?
				$total_credito=0;
				if ($codigo!="") { // BUSCAR DATOS
					$sql ="SELECT cod_mven_dvcr, cod_cli_dvcr, val_cred_dvcr, fac_cred_dvcr , nom_clic  FROM  d_credito_venta  INNER JOIN cliente_credito ON cod_cli_dvcr=cliente_credito.cod_clic  WHERE cod_mven_dvcr=$codigo";//=		
					$dbdatos_1= new  Database();
					$dbdatos_1->query($sql);
					$jj=1;
					while($dbdatos_1->next_row()){ 
						echo "<tr id='fila_cre_$jj'>";
						//numero de factura
						echo "<td><INPUT type='hidden'  name='num_factura_cre_$jj' value='$dbdatos_1->fac_cred_dvcr'><span  class='textfield01'>$dbdatos_1->fac_cred_dvcr </span> </td>";
						
						//codigo del cliente
						echo "<td><INPUT type='hidden'  name='cod_cli_cre_$jj' value='$dbdatos_1->cod_cli_dvcr'><span  class='textfield01'>$dbdatos_1->nom_clic </span> </td>";
						
						//valor de factura
						echo "<td align='right'><INPUT type='hidden'  name='val_factura_cre_$jj' value='$dbdatos_1->val_cred_dvcr'><span  class='textfield01'>".number_format($dbdatos_1->val_cred_dvcr,0,".",".")." </span> </td>";
						
						
						//total del credito
						$total_credito= intval($total_credito + $dbdatos_1->val_cred_dvcr);
					//	echo "<td><div align='center'><INPUT type='button' class='botones' value='  -  ' onclick='removerFila_credito(\"fila_cre_$jj\",\"val_inicial_cre\",\"fila_cre_\",\"$jj\",\"$dbdatos_1->val_cred_dvcr\");'>						</div></td>";
						echo "</tr>";
						$jj++;
						
					}
					$jj_credito=$jj;
				}
				?>
				</table>
			  <table width="98%">
			<tr >
				<td >
				<div align="left">
				<span class="ctablasup">Valor Declarado: <?=number_format($credito_dec,0,".",".")?>
				</span>				</div>				</td>
				<td  align="right"><span class="ctablasup">Total Credito: <? if ($codigo !=0) echo number_format($total_credito,0,".","."); else echo "0" ?>
				</span></td>
			</tr>
			<tr >
			<td valign="top" colspan="2" >
			<span class="ctablasup">Observacion de Creditos: </span>			
			<span class="textfiel_caja"><?=$credito_obs?></span>
			</td> 
			</tr>
			<tr >
			<td valign="top" colspan="2" >&nbsp; </td> 
			</tr>				
			  </table>		  	</TD>
			  <!--  tabla de credito-->
		  </TR>
		  
		  
		  <TR>
		  	<!--  tabla de cobros-->
			  <TD colspan="2"  align="center" valign="top">
			  	<table width="98%">
				<tr id="fila_cre_0" >
				<td  class="ctablasup" colspan="4" >Venta Cobros</td>
				</tr>
				
				<tr id="fila_cre_0">
				<td  class="ctablasup" width="21%">Factura</td>
				<td  class="ctablasup" width="41%">Cliente</td>
				<td  class="ctablasup" width="18%">Valor</td>
				<td  class="ctablasup" width="20%">Descuento</td>
				</tr>
			<?
				$total_cobro=0;
				if ($codigo!="") { // BUSCAR DATOS
					$sql ="SELECT cod_mven_dvco, cod_cli_dvco, val_cob_dvco,fac_cob_dvco , nom_clic ,val_desc_dvco  FROM  d_cobro_venta  INNER JOIN cliente_credito ON cod_cli_dvco=cliente_credito.cod_clic  WHERE cod_mven_dvco=$codigo";//=		
					$dbdatos_1= new  Database();
					$dbdatos_1->query($sql);
					$jj=1;
					while($dbdatos_1->next_row()){ 
						echo "<tr id='fila_cob_$jj'>";
						//numero de factura
						echo "<td><INPUT type='hidden'  name='num_factura_cob_$jj' value='$dbdatos_1->fac_cob_dvco'><span  class='textfield01'>$dbdatos_1->fac_cob_dvco </span> </td>";
						
						//codigo del cliente
						echo "<td><INPUT type='hidden'  name='cod_cli_cob_$jj' value='$dbdatos_1->cod_cli_dvco'><span  class='textfield01'>$dbdatos_1->nom_clic </span> </td>";
						
						//valor de factura
						echo "<td align='right'><INPUT type='hidden'  name='val_factura_cob_$jj' value='$dbdatos_1->val_cob_dvco'><span  class='textfield01'>".number_format($dbdatos_1->val_cob_dvco,0,".",".")." </span> </td>";
						
						//valor del descuento
						echo "<td align='right'><INPUT type='hidden'  name='valor_descuento_cob_$jj' value='$dbdatos_1->val_desc_dvco'><span  class='textfield01'>".number_format($dbdatos_1->val_desc_dvco,0,".",".")." </span> </td>";
						
						
						//total del credito
						$total_cobro= intval($total_cobro + $dbdatos_1->val_cob_dvco);
					//	echo "<td><div align='center'><INPUT type='button' class='botones' value='  -  ' onclick='removerFila_cobro(\"fila_cob_$jj\",\"val_inicial_cre\",\"fila_cre_\",\"$jj\",\"$dbdatos_1->val_cob_dvco\");'>				</div></td>";
						echo "</tr>";
						$jj++;
						
					}
					$jj_cobro=$jj;
				}
				?>
				</table>
				<table width="98%">
			<tr >
				<td >
				<div align="left">
				<span class="ctablasup">Valor Declarado: <?=number_format($cobor_dec,0,".",".")?>
				</span>				</div>				</td>
				<td  align="right"><span class="ctablasup">Total Credito: <? if ($codigo !=0) echo number_format($total_cobro,0,".",".") ; else echo "0" ?>
				</span></td>
			</tr>
			<tr >
			<td valign="top" colspan="2" >
			<span class="ctablasup">Observacion de Creditos: </span>	
			<span class="textfiel_caja"><?=$cobro_obs?></span>		</td> 
			</tr>
			<tr >
			<td valign="top" colspan="2" >&nbsp; </td> 
			</tr>				
			  </table>			  </TD>
			  <!--  tabla de cobros-->
			  
			  <!--  tabla de cheques-->
			  <TD  align="center">
			  	<table width="98%">
				<tr id="fila_cre_0" >
				<td  class="ctablasup" colspan="3" >Venta Cheques</td>
				</tr>
				
				<tr id="fila_cre_0">
				<td  class="ctablasup" width="29%">Numero de Cheque</td>
				<td  class="ctablasup" width="39%">Banco</td>
				<td  class="ctablasup" width="22%">Valor</td>
				</tr>
				<?
				$total_chero=0;
				
				if ($codigo!="") { // BUSCAR DATOS
					$sql ="SELECT  num_cheq_dvch , ban_cheq_dvch ,val_cheq_dvch from d_cheque_venta WHERE cod_mven_dvch=$codigo";//=		
					$dbdatos_1= new  Database();
					$dbdatos_1->query($sql);
					$jj=1;
					while($dbdatos_1->next_row()){ 
						echo "<tr id='fila_che_$jj'>";
						//numero de cheque
						echo "<td><INPUT type='hidden'  name='num_che_$jj' value='$dbdatos_1->num_cheq_dvch'><span  class='textfield01'>$dbdatos_1->num_cheq_dvch </span> </td>";
						
						//nombre de banco
						echo "<td><INPUT type='hidden'  name='nombre_banco_$jj' value='$dbdatos_1->ban_cheq_dvch'><span  class='textfield01'>$dbdatos_1->ban_cheq_dvch </span> </td>";
						
						//valor de cheque
						echo "<td align='right'><INPUT type='hidden'  name='valor_che_$jj' value='$dbdatos_1->val_cheq_dvch'><span  class='textfield01'>".number_format($dbdatos_1->val_cheq_dvch,0,".",".")." </span> </td>";
						
						
						//total del credito
						$total_chero= intval($total_chero + $dbdatos_1->val_cheq_dvch);
					//	echo "<td><div align='center'><INPUT type='button' class='botones' value='  -  ' onclick='removerFila_cheque(\"fila_che_$jj\",\"val_inicial_che\",\"fila_cre_\",\"$jj\",\"$dbdatos_1->val_cheq_dvch\");'>						</div></td>";
						echo "</tr>";
						$jj++;
						
					}
					$jj_chero=$jj;
				}
				?>
				</table>
				<table width="98%">
			<tr >
				<td >
				<div align="left">
				<span class="ctablasup">Valor Declarado: <?=number_format($dbdatos_1->val_cheq_dvch,0,".",".")?>
				</span>				</div>				</td>
				<td  align="right"><span class="ctablasup">Total Cheques: <? if ($codigo !=0) echo number_format($total_chero,0,".",".") ; else echo "0" ?>
				</span></td>
			</tr>
			<tr >
			<td valign="top" colspan="2" >
			<span class="ctablasup">Observacion de Cheques: </span>	
			<span class="textfiel_caja"><?=$cheque_obs?></span>
			</td> 
			</tr>
			<tr >
			<td valign="top" colspan="2" >&nbsp; </td> 
			</tr>				
			  </table>			  </TD>
			  <!--  tabla de cheques-->
		  </TR>
		  
		  
			<TR>
			
			  <TD colspan="3" align="center">             </TD>
		  </TR>
			
			<TR>
			
			
			
			  <TD width="10%" height="40" align="center" valign="top">
			  <div align="left" class="subtitulosproductos">Observaciones:</div>			  </TD>
	          <TD height="40" colspan="2" align="center" valign="top"><div align="left"><span class="textotabla01">
	            <?=$obser?>
              </span></div></TD>
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
	