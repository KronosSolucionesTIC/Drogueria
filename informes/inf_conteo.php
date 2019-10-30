<?
include "../lib/sesion.php";
include("../lib/database.php");
			
//echo $codigo;
//echo $nombre_aplicacion;
//exit;
$db = new Database();
$db_ver = new Database();
$sql = "SELECT cod_ven_inv,cod_rut_inv,fec_inv,total_saldo_inv,net_comp_inv,iva_comp_inv,total_comp_inv,
obs_inv,ini_cont_inv,nom_ven,nom_rut,cod_bod_ven,(SELECT nom_ven FROM vendedor  WHERE cod_ven =cod_res) AS supervisor,jorn_inv
FROM m_inventario 
INNER JOIN vendedor ON m_inventario.cod_ven_inv=vendedor.cod_ven
left JOIN ruta ON m_inventario.cod_rut_inv=ruta.cod_rut
INNER JOIN bodega ON bodega.cod_bod=vendedor.cod_bod_ven
 WHERE cod_inv=$codigo";
$db_ver->query($sql);	
if($db_ver->next_row()){ 
	$supervisor=$db_ver->supervisor;
	$vendedor=$db_ver->nom_ven;
	$fecha=$db_ver->fec_inv;
	$tipo_inv=$db_ver->ini_cont_inv;
	$obser=$db_ver->obs_inv;
	$jornada=$db_ver->jorn_inv;
	
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
 <title><?=$nombre_aplicacion?> -- Invetario --</title>
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0"   >
	
	<TR>
		<TD align="center">
		<TABLE width="94%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

			<TR>
			  <TD colspan="2" class='txtablas'>
			  <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  	<tr>
			  		<td width="14%" rowspan="2">
			  		<img src="distribuciones.jpg" width="139" height="60" /></td>
				    <td width="45%" height="22" class="ctablaform"> <span class="textoproductos1"> &nbsp;&nbsp;SUPERVISOR:</span><span class="textotabla01"> <?=$supervisor?></span></td>
			  	   
			  	    <td width="41%" class="ctablaform"><span class="textoproductos1">&nbsp;&nbsp;Fecha:</span><span class="textotabla01"> <?=$fecha?> Jornada: <?=$jornada?> </span></td>
			  	</tr>
			  	<tr>
			  	  <td  class="ctablaform"><span class="textoproductos1">&nbsp;&nbsp;VENDEDOR:<span class="textotabla01">
			  	    <?=$vendedor?>
			  	  </span></span></td>
				   <td width="29%" class="ctablaform"><span class="textoproductos1">&nbsp;&nbsp;Tipo de Inventario :</span><span class="textotabla01">
				     <?=$tipo_inv?>
				   </span></td>
		  	    </tr>
				</table>					</TD>
			  </TR>
			
			
			<TR>
			  <TD colspan="2" align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                <tr >
                  <td width="10%" class="botones1">Codigo</td>
				   <td width="41%" class="botones1">Referencia</td>
                  <td width="13%" class="botones1">Precio</td>
                  <td width="13%" class="botones1">Cantidad</td>
                  <td colspan="2" class="botones1">Valor Total </td>
                </tr>
				
				<?
				$total=0;
				 $sql = "SELECT cod_prod_dinv, nom_pro, cod_fry_pro, cant_dinv,pventa_dinv, round(((total_dinv/100)*iva_dinv)+total_dinv)  AS total  FROM d_invetario  INNER JOIN producto ON d_invetario.cod_prod_dinv=producto.cod_pro  WHERE cod_minv = $codigo";
					$db->query($sql);
					$estilo="formsleo";
					while($db->next_row()){ 	
						
				?>
                <tr id="fila_0"  >
                  <td width="10%" class="textotabla01"><?=$db->cod_fry_pro?></td>
				  <td width="41%" class="textotabla01"><?=$db->nom_pro?></td>
                  <td colspan="1" class="textotabla01"><div align="right">
                    <?=number_format($db->pventa_dinv,0,".",".")?>
                  </div></td>
                  <td width="13%" class="textotabla01"><div align="right">
                    <?=$db->cant_dinv?>
                  </div></td>
                  <td width="23%" class="textotabla01"><div align="right">
					<?=number_format($db->total,0,".",".")?>
                  </div></td>
                </tr>
				  
				<?
				$total+=$db->total;
				  } 
				 
				 ?>
				   <tr >
			  
                  <td colspan="4" class="subfongris"> <div align="right" >
                    <div align="right">Total Inventario:</div>
                  </div></td>
				  <td width="23%"  colspan="2" class="<?=$estilo?>" align="right"><span class="tituloproductos"><?=number_format($total,0,".",".")?>
                  </span></td>
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
	