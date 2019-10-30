<?
include "../lib/sesion.php";
include("../lib/database.php");
include("../conf/clave.php");		
//echo $codigo;
//echo $nombre_aplicacion;
//exit;
$db = new Database();
$db_ver = new Database();
 $sql =" SELECT m_factura.num_fac,cod_dev,
    m_devolucion.fec_dev,
  bodega.nom_bod as bodega,
  m_factura.num_fac,
  m_devolucion.val_del,
  usuario.nom_usu,
  m_factura.cod_cli,
  CONCAT(bodega1.nom_bod,' ',apel_bod) as cliente, obs_dev
FROM
  m_devolucion
  INNER JOIN usuario ON (m_devolucion.cod_ven_dev = usuario.cod_usu)
  INNER JOIN bodega ON (m_devolucion.cod_bod_dev = bodega.cod_bod)
  INNER JOIN m_factura ON (m_devolucion.num_fac_dev = m_factura.cod_fac)
  INNER JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)
   WHERE cod_dev=$codigo";
   
$db_ver->query($sql);	
if($db_ver->next_row()){ 
	$fecha=$db_ver->fec_dev;
	$usuario=$db_ver->nom_usu;
	$bodega=$db_ver->bodega;
	$num_fac=$db_ver->num_fac;
	$cliente=$db_ver->cliente;
	$obs_devolucion=$db_ver->obs_dev;
	$total_dev=$db_ver->val_del;
	$num_dev=$db_ver->cod_dev;	
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
 <title><?=$nombre_aplicacion?> -- Devoluciones --</title>
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0"   >
	
	<TR>
		<TD align="center">
		<TABLE width="94%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

			<TR>
			  <TD colspan="5" class='txtablas'>
			  <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  <tr>
			  		<td height="22" colspan="5"><div align="center"><strong>NOTA CREDITO No
			  		  <?=$num_dev?></strong>&nbsp;&nbsp;</div></td>
			    </tr>
			  
			  	<tr>
			  		<td width="14%"><span class="ctablaform"><span class="textoproductos1">Vendedor  :</span></span></td>
				    <td width="17%" height="22" class="ctablaform"> <span class="textoproductos1"> &nbsp;&nbsp;</span><span class="textotabla01"> <?=$usuario?></span></td>
			  	   
			  	    <td width="16%" class="ctablaform"><span class="textoproductos1">Bodega Recibe :</span></td>
			  	    <td width="22%" class="ctablaform"><span class="textoproductos1"><span class="textotabla01">
			  	      <?=$bodega?>
			  	    </span></span></td>
			  	    <td width="31%" class="ctablaform"><span class="textoproductos1">&nbsp;&nbsp;Fecha:</span><span class="textotabla01"> <?=$fecha?> </span></td>
			  	</tr>
			  	<tr>
			  	  <td width="14%"><span class="ctablaform"><span class="textoproductos1">No Factura: </span></span></td>
			  	  <td  class="ctablaform"><span class="textoproductos1">&nbsp;&nbsp;<span class="textotabla01">
			  	    <?=$num_fac?>
			  	  </span></span></td>
			      <td  class="ctablaform"><span class="textoproductos1">Cliente:</span></td>
			      <td  class="ctablaform"><span class="textotabla01">
			        <?=$cliente?>
			      </span></td>
			      <td  class="ctablaform">&nbsp;</td>
		  	    </tr>
				</table>					</TD>
			  </TR>
			
			
			<TR>
			  <TD colspan="5" align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                <tr >
                  <td width="14%" class="botones1">Codigo</td>
				   <td width="17%" class="botones1">Referencia</td>
                   <td width="16%" class="botones1">Talla</td>
                  <td width="22%" class="botones1">Cantidad</td>
                  <td colspan="2" class="botones1">Valor Total </td>
                </tr>
				
				<?
				$total=0;
				 $sql = " SELECT   desc_ref,d_devolucion.cod_prod_ddev,   producto.nom_pro,   producto.cod_fry_pro,   d_devolucion.cant_ddev,   d_devolucion.total_ddev,   d_devolucion.cod_pes_ddev,   peso.nom_pes FROM   d_devolucion   
				 INNER JOIN producto ON (d_devolucion.cod_prod_ddev = producto.cod_pro)
				 INNER JOIN referencia ON (referencia.cod_ref = producto.nom_pro)   
				 INNER JOIN peso ON (d_devolucion.cod_pes_ddev = peso.cod_pes)    
				 WHERE cod_mdev = $codigo order by cod_ddev";
					$db->query($sql);
					$estilo="formsleo";
					while($db->next_row()){ 	
						
				?>
                <tr id="fila_0"  >
                  <td width="14%" class="textotabla01"><?=$db->cod_fry_pro?></td>
				  <td width="17%" class="textotabla01"><?=$db->desc_ref?></td>
                  <td width="16%" class="textotabla01"><?=$db->nom_pes?></td>
                  <td width="22%" class="textotabla01"><div align="right"><?=$db->cant_ddev?></div></td>
                  <td width="31%" class="textotabla01"><div align="right"><?=number_format($db->total_ddev,0,".",".")?></div></td>
                </tr>
				<?
				//	$total+=$db->total;
				  } 
				 ?>
				   <tr >
			  
                  <td colspan="4" class="subfongris"> <div align="right" >
                    <div align="right">Total Devolucion:</div>
                  </div></td>
				  <td width="31%"  colspan="2" class="<?=$estilo?>" align="right"><span class="tituloproductos"><?=number_format($total_dev,0,".",".")?>
                  </span></td>
                  </tr>
              </table></TD>
			  </TR>
			<TR>
			  <TD colspan="5" align="center">             </TD>
			  </TR>
			<TR>
			  <TD colspan="5" align="center"><p></TD>
		  </TR>
			<TR>
			
			
			
			  <TD width="14%" height="40" align="center" valign="top"><div align="center" class="textoproductos1">
			    <div align="left" class="subtitulosproductos">Observaciones:			    </div>
			    <p>&nbsp;</p>
			  </div></TD>
		      <TD width="33%"  valign="top"><span class="textotabla01">
		        <?=$obs_devolucion?>
		      </span></TD>
		      <TD width="17%"  valign="top"><div align="center" class="textoproductos1">
		        <div align="left" class="subtitulosproductos">Elaborado: </div>
	          </div></TD>
		      <TD width="18%"  valign="top"><div align="center" class="textoproductos1">
		        <div align="left" class="subtitulosproductos">Revisado: </div>
	          </div></TD>
		      <TD width="18%"  valign="top"><div align="center" class="textoproductos1">
		        <div align="left" class="subtitulosproductos">Autorizado: </div>
	          </div></TD>
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
	