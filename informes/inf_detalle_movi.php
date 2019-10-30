<?
include "../lib/sesion.php";
include("../lib/database.php");
include("../js/funciones.php");


	
			
//echo $codigo;
$nombre_aplicacion="INFORME DETALLADO DE MOVIMIENTOS POR VENDEDOR ";
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
		<TABLE width="94%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

			<TR>
			  <TD width="100%" class='txtablas'>
			  <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  	<tr>
			  		<td width="16%" rowspan="2">
			  		<img src="distribuciones.jpg" width="139" height="60" /></td>
				    <td height="22" class="ctablaform"> <span class="textoproductos1"> &nbsp;&nbsp;&nbsp;&nbsp;INFORME DE MOVIMIENTOS POR VENDEDOR </span><span class="textoproductos1"></span></td>
	  	        </tr>
			  	<tr>
			  	  <td  class="ctablaform"><span class="textoproductos1">&nbsp;&nbsp;</span></td>
			    </tr>
				</table>					</TD>
		  </TR>
			
			
			<TR>
			  <TD align="center">
			  <table width="99%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                <tr >
                  <td width="19%"  class="botones1">Empresa</td>
				  <td width="13%"  class="botones1">Vendedor</td>
                  <td width="8%"  class="botones1">Ruta</td>
                  <td width="10%"  class="botones1">Fecha</td>
				   <td width="10%" class="botones1">Saldo Anterior </td>
                   <td width="9%"  class="botones1">Tipo Movimineto </td>
                  <td width="10%"  class="botones1">Valor Debito </td>
                  <td width="10%"  align="center" class="botones1">Valor Credito</td>
				   <td width="11%"  align="center" class="botones1">Saldo Actual</td>
                </tr>
                <?
				$total=0;
				$total_saldo=0;
				$db = new Database();
		  		$sql = "SELECT *, (SELECT rsocial.nom_rso FROM rsocial WHERE rsocial.cod_rso=vendedor.cod_emp_ven) AS empresa FROM resumen_movimineto   INNER JOIN vendedor ON vendedor.cod_ven=resumen_movimineto.ven_rmov
LEFT JOIN asignacion ON asignacion.cod_ven_asi=vendedor.cod_ven LEFT JOIN ruta ON ruta.cod_rut=asignacion.cod_rut_asi WHERE ven_rmov=$vendedor AND  fec_rmov >='$fec_ini' AND fec_rmov <='$fec_fin' order by  vendedor.cod_emp_ven,cod_rmov  asc";
				$estilo="formsleo";
				//exit;
				$db->query($sql);	
				while($db->next_row()){ 
					$vendedor=$db->nom_ven;

				?>
                <tr  >
				<td  class="textotabla01"><?=$db->empresa?></td>				
                  <td  class="textotabla01"><?=$db->nom_ven?></td>
                  <td  class="textotabla01"><?=$db->nom_rut?></td>
				  <td  class="textotabla01"><?=$db->fec_rmov?></td>	
                  <td  class="textotabla01"><div align="right"><?=number_format($db->saldo_anterior,0,",",".")?></td>
                  <td  class="textotabla01"><?=$db->tipo_mov_rmov?></td>
                  <td  class="textotabla01"><div align="right"><?=number_format($db->valor_suma,0,",",".")?></div></td>
                  <td  class="textotabla01"><div align="right"><?=number_format($db->valor_resta,0,",",".")?></div></td>
				  <td  class="textotabla01"><div align="right"><?=number_format(($db->valor_suma+$db->saldo_anterior-$db->valor_resta),0,",",".")?></div></td>
                </tr>
                <?
				// $db->saldo_anterior + $db->valor_resta -$db->valor_resta
					$total_saldo=$total_saldo + $db->valor_suma;
					$total_saldo=$total_saldo - $db->valor_resta;
				  } 
				 
				 ?>
                <tr >
                  <td colspan="6" ></td>
                </tr>
                <tr >
                  <td colspan="9" ><div class="textotabla01">
                    <div align="center" class="subtitulosproductos">Total Saldo:  $ <?=number_format($total_saldo,0,",",".")?></div>
                  </div></td>
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
	