<?
include "../lib/sesion.php";
include("../lib/database.php");
include("../js/funciones.php");


calcular_saldos_total();

if(!empty($codigo_vendedor))
$w_where="WHERE cod_ven_sal=$codigo_vendedor";	

	
	
			
//echo $codigo;
$nombre_aplicacion="INFORME DE SALDO POR VENDEDOR ";
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
		<TABLE width="79%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

			<TR>
			  <TD width="100%" class='txtablas'>
			  <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  	<tr>
			  		<td width="16%" rowspan="2">
			  		<img src="distribuciones.jpg" width="139" height="60" /></td>
				    <td height="22" class="ctablaform"> <span class="textoproductos1"> &nbsp;&nbsp;&nbsp;&nbsp;INFORME DE SALDOS </span><span class="textoproductos1">&nbsp;&nbsp;</span></td>
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
				<td width="35%"  class="botones1">Empresa</td>	
                  <td width="20%"  class="botones1">Vendedor</td>
				   <td width="18%"  class="botones1">Ruta</td>
			       <td width="13%"  class="botones1">Ultimo Movimineto </td>
                   <td width="14%" align="center" class="botones1">Saldo</td>
			    </tr>
				
				<?
				$total=0;
				$db = new Database();
				
		    $sql = "SELECT cod_ven_sal, SUM(val_sal) AS saldo ,(SELECT MAX(fec_sal) FROM saldos WHERE cod_ven_sal=cod_ven_sal) as  ultima_fecha , nom_ven ,ruta.nom_rut , vendedor.cod_emp_ven ,(SELECT rsocial.nom_rso FROM rsocial WHERE rsocial.cod_rso=vendedor.cod_emp_ven) AS empresa  FROM saldos INNER JOIN vendedor ON vendedor.cod_ven=cod_ven_sal LEFT JOIN asignacion ON asignacion.cod_ven_asi=cod_ven_sal LEFT JOIN ruta ON ruta.cod_rut=asignacion.cod_rut_asi $w_where GROUP by cod_ven_sal order by vendedor.cod_emp_ven, nom_ven ASC ";
				$estilo="formsleo";
				$db->query($sql);	
				while($db->next_row()){ 
					$vendedor=$db->nom_ven;

				?>
                <tr  >
				<td  class="textotabla01"><?=$db->empresa?></td>
                  <td  class="textotabla01"><?=$db->nom_ven?></td>
				  <td  class="textotabla01"><?=$db->nom_rut?>&nbsp; </td>
                  <td  class="textotabla01"><div align="right"><?=$db->ultima_fecha?></div></td>
                  <td  class="textotabla01"><div align="right"><?=number_format($db->saldo,0,",",".")?></div></td>
                </tr>
				  
				<?
				
				  } 
				 
				 ?>
				 
				  <tr >
			  
                  <td colspan="4" >				  </td>
				  </tr>
				   <tr >
			  
                  <td colspan="5" >&nbsp;</td>
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
	