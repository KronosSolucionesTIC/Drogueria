<?
/*include "../lib/sesion.php";
include("../lib/database.php");
include("../conf/clave.php");				

	$db = new Database();
	$db_ver = new Database();
	$sql = "select *, DATE_ADD(fecha ,interval 1 month) as fac_fecha_vence   from m_factura where cod_fac=$codigo";
	$db_ver->query($sql);	
	if($db_ver->next_row()){ 
		$rem_fac=$db_ver->rem_fac;
		$cod_razon=$db_ver->cod_razon_fac;
		$fac_numero=$db_ver->num_fac;
		$fac_fecha=$db_ver->fecha;
		$tipo_fac=$db_ver->tipo_fac;
		$codigo_bod=$db_ver->bod_cli_fac;
		$codigo_cli=$db_ver->cod_cli;
		$codigo_razon=$db_ver->cod_razon_fac;
		$tipo_pago=$db_ver->tipo_pago;
		$fac_fecha_vence=$db_ver->fac_fecha_vence;
		$estado_factura=$db_ver->estado;
		
	}

if($tipo_fac=="radiobutton_bodega")
	$codigo_salida=$codigo_bod;

if($tipo_fac=="radiobutton_cliente")
	$codigo_salida=$codigo_cli;


$db_fac = new Database();

$sql ='select * from razon where cod_razon='.$codigo_razon ;
$db_fac->query($sql);
if($db_fac->next_row()){ 
	$razon=$db_fac->nom_razon;
	$nit=$db_fac->nit_razon;
	$telefono=$db_fac->tel_razon;
	$direccion=$db_fac->dir_razon;
	$ciudad=$db_fac->ciu_razon;
	$leyenda=$db_fac->leyenda1;
	$leyenda2=$db_fac->leyenda2;
	$logo=$db_fac->logo;
}
*/



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
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0"   <? if($estado_factura=="Anulada")  echo'background="images/images/anulacion1.jpg"' ?>>


	<TR>
		<TD width="1%" background="images/bordefondo.jpg" style="background-repeat:repeat-y" rowspan="2"></TD>
		<TD bgcolor="#F4F4F4" class="pag_actual">&nbsp;</TD>
		<TD width="1%" background="images/bordefondo.jpg" style="background-repeat:repeat-y" rowspan="2"></TD>
	</TR>
	<TR>
		<TD align="center">
		<TABLE width="100%" border="0" cellpadding="2" cellspacing="0" >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

			<TR>
			  <TD colspan="2" class='txtablas'>
			  <table border="1" width="100%">
			  	<tr>
			  		<td width="16%">
			  		<img src="distribuciones.jpg" width="139" height="60" /></td>
				    <td width="84%"><? //include("factura_cabezote1.php") ?></td>
			  	</tr>
				</table>					</TD>
			  </TR>
			<TR>
			  <TD colspan="2" class='txtablas'> <? //include("factura_cliente1.php") ?>	</TD>
			  </TR>
			
			<TR>
			  <TD colspan="2" align="center">
			  <table width="100%" border="1" id="select_tablas" >
                <tr >
                  <td width="10%" class="botones1">Referencia</td>
				   <td width="29%" class="botones1">Descripcion</td>
                  <td width="8%" class="botones1">Cantidad</td>
                  <td width="19%" class="botones1">Valor</td>
                  <td colspan="2" class="botones1">Valor Total </td>
                </tr>
				
				<?
				$total=0;
				 $sql = "SELECT *,(SELECT nom_pro FROM producto WHERE producto.cod_pro=d_factura.cod_pro) AS nombre ,(SELECT des_pro FROM producto WHERE cod_pro=d_factura.cod_pro) AS des   FROM d_factura WHERE cod_mfac= $codigo";
					$db->query($sql);
					$estilo="formsleo";
					//while($db->next_row()){ 
						//$db->fec_ent;
						if($estilo=="formsleo")
							$estilo="formsleo1";
						else
							$estilo="formsleo";
				?>
                <tr id="fila_0"  >
                  <td width="10%" class="<?=$estilo?>"><?=$db->nombre?> </td>
				  <td width="29%" class="<?=$estilo?>"><? if ($db->des!="") echo $db->des; else echo "&nbsp;"; ?> </td>
                  <td colspan="1" class="<?=$estilo?>" ><?=$db->cant_pro?> </td>
                  <td width="19%" class="<?=$estilo?>" ><?=number_format($db->val_uni,0,".",".")?></td>
                  <td width="18%"  colspan="2" class="<?=$estilo?>"><?=number_format($db->total_pro,0,".",".")?></td>
                </tr>
				  
				<?
				$total++;
				//  } 
				  $total=20 - $total;
				  for($o;$o<=$total;$o++){
				  	if($estilo=="formsleo")
						$estilo="formsleo1";
					else
						$estilo="formsleo";
				  ?>  
				  <tr id="fila_0">
			  
                  <td width="10%" class="<?=$estilo?>" >&nbsp;</td>
				   <td width="29%" class="<?=$estilo?>">&nbsp;</td>
                  <td colspan="1" class="<?=$estilo?>" >&nbsp; </td>
                  <td width="19%" class="<?=$estilo?>" >&nbsp;</td>
                  <td width="18%"  colspan="2" class="<?=$estilo?>">&nbsp;</td>
                  </tr>
				  
				  <? } ?>
				   <tr >
			  
                  <td colspan="3" class="<?=$estilo?>" >&nbsp; </td>
				  <td colspan="1" class="<?=$estilo?>" align="right"><span class="tituloproductos"  >Total Factura:</span> </td>
				  <?
			$sql = "select *, (select concat(US_NOMBRE,' ',US_APELLIDO)  from usuarios where US_ID=m_factura.cod_usu) AS usuario FROM m_factura WHERE cod_fac= $codigo";
			$db->query($sql);
			if($db->next_row()){ 
				$usuario = $db->usuario;
				$obs = $db->obs;
			}
			$sql = "SELECT SUM(total_pro) as total FROM d_factura WHERE cod_mfac= $codigo";
			$db->query($sql);
			if($db->next_row()){ 
				$total = $db->total;
			}	
				
			
			?>
				   <td width="18%"  colspan="2" class="<?=$estilo?>" align="right"><span class="tituloproductos"><?=number_format($total,0,".",".")?>
                   </span></td>
                  </tr>
              </table></TD>
			  </TR>
			<TR>
			  <TD colspan="2" align="center">             </TD>
			  </TR>
			<TR>
			  <TD colspan="2" align="center"><div align="left" class="textoproductos1">El  Presente documento constituye una factura cambiaria de &nbsp;compra venta cuando no ha sido cancelada y es  aceptada por el adquiriente. Se asimila en sus efectos a la letra de cambio</div>
		      <p></TD>
		  </TR>
			<TR>
			
			
			
			  <TD width="50%" align="center"><div align="center" class="textoproductos1">
			    <div align="left" class="subtitulosproductos">Observaciones:			    </div>
			  </div></TD>
		      <TD width="50%" rowspan="2" align="center"><span class="subtitulosproductos">
                  <?//number_format($total,0,".",".")?>
                            </span></TD>
			</TR>
			<TR>
			  <TD align="center"><div align="left"><span class="textoproductos1">
			    <?=$obs?>
			   
		      </span></div></TD>
	      </TR>
			<TR><TD colspan="2" align="center">
			<table width="97%" class="subtitulosproductos">
                      <tr>
                        <td colspan="2" >Nota:<span class="forms1"><?=$leyenda2?></span>                          <p> </td>
                      </tr>
                      <tr>
                        <td width="484" class='formsleo'>Firma Entregado ________________
                        <p class="subtitulosproductos"><?=$usuario?></td>
                        <td width="477" class='formsleo'>
						<table width="477" border="0" class="forms1">
                          <tr class="SELECT">
                            <td colspan="2">Recibido Por:  </td>
                          </tr>
                          <tr class="SELECT">
                            <td>Firma:______________________</td>
                            <td>Nombre:_____________________</td>
                          </tr>
                          <tr class="SELECT">
                            <td>Cedula:____________________</td>
                            <td>Fecha:______________________</td>
                          </tr>
                        </table>                        </td>
                      </tr>
                    </table></TD>
					</TR>
				<TR><TD colspan="2" align="center">
					<INPUT type="button" value="Imprimr" id="imp"  class="botones" onClick="imprimir()">
					<INPUT type="button" value="Cerrar"  id="cer"  class="botones" onClick="window.close()"></TD>
					</TR>
</TABLE>

 
<TABLE width="70%" border="0" cellspacing="0" cellpadding="0">
	
	<TR><TD colspan="3" align="center"></TD>
	</TR>

	<TR>
		<TD width="1%" background="images/bordefondo.jpg" style="background-repeat:repeat-y" rowspan="2"></TD>
		<TD bgcolor="#F4F4F4" class="pag_actual">&nbsp;</TD>
		<TD width="1%" background="images/bordefondo.jpg" style="background-repeat:repeat-y" rowspan="2"></TD>
	</TR>
	<TR>
	  <TD align="center">
	