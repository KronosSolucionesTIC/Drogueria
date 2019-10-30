<?
include "../lib/sesion.php";
include("../lib/database.php");		
//echo $codigo;
//echo $nombre_aplicacion;
//exit;
$db = new Database();
$db_ver = new Database();
 $sql ="SELECT * , (SELECT nom_bod FROM bodega WHERE cod_bod=cod_bod_sal_tras) AS bodega_salida, (SELECT nom_bod FROM bodega WHERE cod_bod=cod_bod_ent_tras) AS bodega_entrada FROM m_traslado WHERE cod_tras=$codigo";
$db_ver->query($sql);	
if($db_ver->next_row()){ 
	$ven_entrega=$db_ver->bodega_salida;
	$ven_recibe=$db_ver->bodega_entrada;
	$fecha=$db_ver->fec_tras;
	$obs_tras=$db_ver->obs_tras;
	$numero_tras=$db_ver->cod_tras;	
	$cod_bodega_recibe=$db_ver->cod_bod_ent_tras;
	
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
 <link href="../css/styles.css" rel="stylesheet" type="text/css" />
 <link href="../css/stylesforms.css" rel="stylesheet" type="text/css" />
 <title><?=$nombre_aplicacion?> -- Traslado de Mercancia --</title>
 <style type="text/css">
<!--
.Estilo7 {font-size: 10px}
.Estilo8 {font-size: 16px}
.Estilo9 {font-size: 24px}
-->
 </style>
 <TABLE width="100%" border="0" cellspacing="0" cellpadding="0"   >
	
	<TR>
		<TD align="center">
		<TABLE width="94%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

		<?
		
		$db_1 = new Database();
		$sql22="SELECT    bodega.cod_bod,   bodega.nom_bod,   bodega.cod_rso_bod,   rsocial.cod_rso,   rsocial.nom_rso, tel_rso,  dir_rso, rsocial.nit_rso FROM   bodega  INNER JOIN rsocial ON (bodega.cod_rso_bod = rsocial.cod_rso)   where    bodega.cod_bod=$cod_bodega_recibe";
		$db_1->query($sql22);	
		
		if($db_1->next_row())
		{ 
		 	$nom_rsocial=$db_1->nom_rso;
			$nit_rsocial=$db_1->nit_rso;
			$tel_rsocial=$db_1->tel_rso;
			$dir_rsocial=$db_1->dir_rso;
		}

	
		?>

			<TR>
			  <TD colspan="2" class='txtablas'>
			  <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  	<tr>
			  		<td width="45%"><span class="Estilo8"><?=$nom_rsocial?></span></td>
			        <td width="16%"><span class="Estilo7">
			          Nit: <?=$nit_rsocial?>
			        </span></td>
			  	    <td width="19%"><span class="Estilo7">Direccion</span>: <span class="Estilo7">
			  	      <?=$dir_rsocial?>
			  	    </span></td>
			  	    <td width="20%"><span class="Estilo7">Telefono:
			  	      <?=$tel_rsocial?>
			  	    </span></td>
			  	</tr>
				<tr>
			  		<td colspan="4">&nbsp;</td>
		        </tr>
				</table>
				<table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  	<tr>
			  		<td width="46%" rowspan="2"><span class="Estilo9">DESPACHO DE BODEGA </span></td>
				    <td width="26%" height="22" class="ctablaform"> <span class="textoproductos1">&nbsp;Bodega Entrega :</span><span class="textotabla01"> <?=$ven_entrega?></span></td>
			  	   
			  	    <td width="28%" class="ctablaform"><span class="textoproductos1">&nbsp;&nbsp;Fecha:</span><span class="textotabla01"> <?=$fecha?> </span></td>
			  	</tr>
			  	<tr>
			  	  <td  class="ctablaform"><span class="textoproductos1"> &nbsp;Bodega  Recibe :<span class="textotabla01">
			  	    <?=$ven_recibe?>
			  	  </span></span><span class="textoproductos1">&nbsp;&nbsp;</span></td>
			      <td  class="ctablaform"> <span class="textoproductos1">&nbsp;&nbsp;Documento No:&nbsp;&nbsp;<?=$numero_tras?> </span></td>
			  	</tr>
				</table>
				
			  </TD>
			  </TR>
			
			
			<TR>
			  <TD colspan="2" align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                
				  <tr >
            <td  class="botones1">Tipo Producto </td>
            <td  class="botones1">Proveedor</td>
			<td  class="botones1">Referencia</td>
            <td  class="botones1">Codigo</td>
            <td   class="botones1">Cantidad</td>
	
          </tr>
				<?
				$total=0;
			 $sql = " SELECT * 
FROM d_traslado 
LEFT JOIN producto ON producto.cod_pro=d_traslado.cod_ref_dtra
LEFT JOIN tipo_producto ON tipo_producto.cod_tpro=producto.`cod_tpro_pro`
LEFT JOIN marca ON marca.cod_mar=producto.`cod_mar_pro`
WHERE cod_mtras_dtra=$codigo  ORDER BY cod_dtra ";
					$db->query($sql);
					$estilo="formsleo";
					while($db->next_row()){ 	
						
				?>
                <tr id="fila_0"  >
                  <td  class="textotabla01"><?=$db->nom_tpro?></td>
				  <td  class="textotabla01"><?=$db->nom_mar?></td>
                  <td  class="textotabla01"><div align="right"><?=$db->nom_pro?></div></td>
                  <td  class="textotabla01"><div align="right"><?=$db->cod_fry_pro?></div></td>
				   <td class="textotabla01"><div align="right"><?=number_format($db->cant_dtra,0,".",".")?></div></td>

                </tr>
				  
				<?
				$total = $total + $db->cant_dtra;
				  } 
				 
				 ?>
				 
				  <tr >
			  
                  <td colspan="7" >
				   </td>
				  </tr>
              </table>
			  </TD>
			  </TR>
			<TR>
			  <TD colspan="2" align="center">             </TD>
			  </TR>
			<?
			$dbt = new Database();
            $sqlt = " SELECT SUM(cant_dtra) AS suma,nom_mar FROM d_traslado 
			LEFT JOIN producto ON producto.cod_pro=d_traslado.cod_ref_dtra
			INNER JOIN referencia ON referencia.cod_ref = producto.nom_pro
			LEFT JOIN tipo_producto ON tipo_producto.cod_tpro=producto.`cod_tpro_pro`
			LEFT JOIN marca ON marca.cod_mar=producto.`cod_mar_pro`
			LEFT JOIN peso ON peso.cod_pes=d_traslado.cod_pes_dtra  WHERE cod_mtras_dtra=$codigo 
			GROUP BY cod_mar ORDER BY cod_dtra";
			$dbt->query($sqlt);
			while($dbt->next_row()){ 
			?>
            <TR>            
			  <TD colspan="2" class="textotabla01" align="center"><p align="right">TOTAL
              <?=$dbt->nom_mar?> : 
			    <?=number_format($dbt->suma,0,".",".")?>
              </TD>
		  	</TR>
            <? } ?>
			<TR>
			
			
			
			  <TD width="13%" height="40" align="center" valign="top"><div align="center" class="textoproductos1">
			    <div align="left" class="subtitulosproductos">Observaciones:			    </div>
			  </div></TD>
		      <TD width="87%"  valign="top"><span class="textotabla01">
		        <?=$obs_tras?>
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
	