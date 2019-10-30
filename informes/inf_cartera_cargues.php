<?
include "../lib/sesion.php";
include("../lib/database.php");
			
//echo $codigo;
$nombre_aplicacion="INFORME DE CARTERA DE COMPRAS ";
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
 <title><?=$nombre_aplicacion?> -- Cargue --</title>
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0"   >
	
	<TR>
		<TD align="center">
		<TABLE width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

			<TR>
			  <TD width="100%" class='txtablas'>
			  <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  	<tr>
			  		<td width="16%" rowspan="2">
			  		<img src="distribuciones.jpg" width="139" height="60" /></td>
				    <td width="41%" height="22" class="ctablaform"> <span class="textoproductos1"> &nbsp;&nbsp;&nbsp;&nbsp;INFORME DE CARTERA DE COMPRAS  </span><span class="textoproductos1"></span>				  </td>
			  	   
			  	    <td width="43%" class="ctablaform"><span class="textoproductos1">&nbsp;&nbsp;Fecha Inical:
					<?=$fec_ini?> &nbsp;&nbsp;
				 Fecha Final: <?=$fec_fin?></span></td>
			  	</tr>
			  	<tr>
			  	  <td colspan="2"  class="ctablaform"><span class="textoproductos1">&nbsp;&nbsp;</span></td>
			    </tr>
				</table>					</TD>
		  </TR>
			
			
			<TR>
			  <TD align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                <tr >
					<td width="18%"  class="botones1">Empresa</td>
                  <td width="19%"  class="botones1">Vendedor</td>
				   <td width="10%"  class="botones1">Ruta</td>
			       <td width="9%"  class="botones1">Fecha</td>
                   <td width="13%" align="center" class="botones1">Factura</td>
				   <td width="10%" align="center"  class="botones1">Neto Compra </td>
                   <td width="10%" align="center"  class="botones1">I.V.A.</td>
                   <td width="11%"  colspan="2" align="center" class="botones1">Valor Factura</td>
                </tr>
				
				<?
				$total=0;
				$db = new Database();
				$sql = "SELECT nom_ven , nom_rut,fec_carg , net_comp_carg AS neto , iva_comp_carg AS iva ,
				 total_comp_carg AS total , num_fac  ,vendedor.cod_emp_ven ,(SELECT rsocial.nom_rso FROM rsocial WHERE rsocial.cod_rso=vendedor.cod_emp_ven) AS empresa  FROM m_cargue INNER JOIN vendedor ON vendedor.cod_ven=m_cargue.cod_ven_carg 
				INNER JOIN ruta ON ruta.cod_rut= m_cargue.cod_rut_carg 	WHERE fec_carg >='$fec_ini'  AND fec_carg <='$fec_fin'
				ORDER BY vendedor.cod_emp_ven,fec_carg desc";
				$estilo="formsleo";
				$db->query($sql);	
				while($db->next_row()){ 
					$vendedor=$db->nom_ven;

				?>
                <tr >
					<td  class="textotabla01"><?=$db->empresa?></td>
                  <td  class="textotabla01"><?=$db->nom_ven?></td>
				  <td  class="textotabla01"><?=$db->nom_rut?></td>
                  <td  class="textotabla01"><div align="right"><?=$db->fec_carg?></div></td>
                  <td  class="textotabla01"><div align="right"><?=$db->num_fac?></div></td>
                  <td  class="textotabla01"><div align="right">
					<?=number_format($db->neto,0,".",".")?>
                  </div></td>
				  
				   <td  class="textotabla01"><div align="right">
					<?=number_format($db->iva,0,".",".")?>
                  </div></td>
				  
				  <td  class="textotabla01"><div align="right">
					<?=number_format($db->total,0,".",".")?>
                  </div></td>
                </tr>
				  
				<?
				$total+=$db->total;
				  } 
				 
				 ?>
				 
				  <tr >
			  
                  <td colspan="7" >				  </td>
				  </tr>
				   <tr >
			  
                  <td colspan="8" >&nbsp;</td>
				  </tr>
				  
				   <tr >
			  
                  <td colspan="5" class="subfongris"> <div align="right" >
                    <div align="right">Total Cartera del Periodo:</div>
                  </div></td>
				  <td  colspan="3" class="<?=$estilo?>" align="right"><span class="tituloproductos">
				  <?=number_format($total,0,".",".")?>
                  </span></td>
                  </tr>
              </table></TD>
		  </TR>
			<TR>
			  <TD align="center">             </TD>
		  </TR>
			<TR>
			  <TD align="center"><p></TD>
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
	