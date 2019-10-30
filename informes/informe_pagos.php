<?
include "../lib/sesion.php";
include("../lib/database.php");
include("../js/funciones.php");


calcular_saldos_total();

if(!empty($codigo_vendedor))
$w_where="WHERE cod_ven_sal=$codigo_vendedor";	

	
	
			
//echo $codigo;
$nombre_aplicacion="INFORME CARTERA PAGADA ";
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
		<TABLE width="71%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

			<TR>
			  <TD width="100%" class='txtablas'>
			  <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  	<tr>
			  		<td width="16%" rowspan="2">
			  		<img src="distribuciones.jpg" width="139" height="60" /></td>
				    <td height="22" class="ctablaform"> <span class="textoproductos1"> &nbsp;&nbsp;&nbsp;&nbsp;INFORME CARTERA PAGADA </span></td>
	  	        </tr>
			  	<tr>
			  	  <td  class="ctablaform"><span class="textoproductos1">&nbsp;&nbsp;</span></td>
			    </tr>
				</table>					</TD>
		  </TR>
			
			
			<TR>
			  <TD align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                <tr >
                  <td width="16%"  class="botones1">Fecha</td>
				   <td width="23%"  class="botones1">Cheque</td>
			       <td width="19%"  class="botones1">Valor</td>
                   <td width="42%" align="center" class="botones1">Observaciones</td>
			    </tr>
				
				<?
				$total=0;
				$db = new Database();
				
		    $sql = "SELECT  * from  m_pago_cartera where cod_pag=$codigo";
				$estilo="formsleo";
				$db->query($sql);	
				while($db->next_row()){ 
				?>
                <tr  >
                  <td  class="textotabla01"><?=$db->fec_pag?></td>
				  <td  class="textotabla01"><?=$db->che_pag?>&nbsp; </td>
				  <td  class="textotabla01"><div align="right"><?=number_format($db->val_pag,0,",",".")?></div></td>
                  <td  class="textotabla01"><div align="right"><?=$db->obs_pag?></div></td>
                  
                </tr>
				  
				<?
				
				  } 
				 
				 ?>
				 
				  <tr >
			  
                  <td colspan="4" >				  </td>
				  </tr>
				   <tr >
			  
                  <td colspan="4" >&nbsp;</td>
				  </tr>
              </table></TD>
		  </TR>
		  <TR>
			  <TD align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                <tr >
                  <td width="23%"  class="botones1">Fecha Cargue </td>
				   <td width="31%"  class="botones1">Vendedor</td>
			       <td width="20%"  class="botones1">Valor</td>
                   <td width="26%" align="center" class="botones1">Numero Factura </td>
			    </tr>
				
				<?
				$total=0;
				$db = new Database();
				
		    $sql = "SELECT cod_carg,cod_ven_carg,fec_carg,total_comp_carg,num_fac ,nom_ven FROM m_cargue INNER JOIN vendedor ON vendedor.cod_ven=cod_ven_carg WHERE pagado='1' and  cod_car_pag='$codigo' order by cod_carg ";
				$estilo="formsleo";
				$db->query($sql);	
				while($db->next_row()){
				?>
                <tr  >
                  <td  class="textotabla01"><?=$db->fec_carg?></td>
				  <td  class="textotabla01"><?=$db->nom_ven?>&nbsp; </td>
				  <td  class="textotabla01"><div align="right"><?=number_format($db->total_comp_carg,0,",",".")?></div></td>
                  <td  class="textotabla01"><div align="right"><?=$db->num_fac?></div></td>
                  
                </tr>
				  
				<?
				
				  } 
				 
				 ?>
				 
				  <tr >
			  
                  <td colspan="4" >				  </td>
				  </tr>
				   <tr >
			  
                  <td colspan="4" >&nbsp;</td>
				  </tr>
              </table>
			  </TD>
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
	