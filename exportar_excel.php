<?
include "../lib/sesion.php";
include("../lib/database.php");
include("../conf/clave.php");				
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Exportar ventas</title>
<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
<script language="javascript">
$(document).ready(function() {
	$(".botonExcel").click(function(event) {
		document.forma.submit();
        
});
});

</script>
<style type="text/css">
.botonExcel{cursor:pointer;}
</style>
</head>
<body>
<form action="exportar.php" method="post" enctype="multipart/form-data" id="forma" name="forma">
<table width='1000' border='1' id='Exportar_a_Excel' bgcolor='#D1D8DE' class='textotabla1'>
<div align="right">
  <p>
    <?
	$db_ver = new Database();
	$sql = "select tot_dev_mfac,estado,cod_fac,num_fac,fecha,iden_bod,tot_fac,YEAR(fecha) as ano,MONTH(fecha) as mes,DAY(fecha) as dia,bodega1.nom_bod as nom_cliente from m_factura

left join rsocial on rsocial.cod_rso=m_factura.cod_razon_fac
left join usuario on usuario.cod_usu=m_factura.cod_usu
left join bodega1 on bodega1.cod_bod=m_factura.cod_cli 
left join bodega on bodega.cod_bod =m_factura.cod_bod WHERE cod_rso=$combo_empresa and fecha BETWEEN '$fechas' AND '$fechas2'";
	$db_ver->query($sql);	
	$i=1;
	$j=1;
	$k=0;
	$sumatoria_base = 0;
	$sumatoria_iva = 0;
	$sumatoria_total = 0;
	while($db_ver->next_row())
	{ 
		
		if ($db_ver->estado != 'anulado') {

		echo "<tr>";
    	echo "<td width='2%'>FV</td>";						//#1
    	echo "<td width='4%'>$db_ver->num_fac<input type = 'hidden' id='num_factura_$k' name='num_factura_$k' value='$db_ver->num_fac'></td>";		//#2
    	echo "<td width='4%'>01</td>";						//#3
    	echo "<td>$i<input type = 'hidden' id='valor_i_$k' name='valor_i_$k' value='$i'></td>";									//#4
    	echo "<td>$db_ver->dia-$db_ver->mes-$db_ver->ano<input type = 'hidden' id='valor_fecha_$k' name='valor_fecha_$k' value='$db_ver->dia-$db_ver->mes-$db_ver->ano'></td>";//#5
    	echo "<td>$db_ver->mes/$db_ver->ano<input type = 'hidden' id='valor_periodo_$k' name='valor_periodo_$k' value='$db_ver->mes/$db_ver->ano'></td>";			//#6
  		echo "<td></td>";									//#7
  		echo "<td>41352405</td>";							//#8
  		echo "<td>$db_ver->nom_cliente<input type = 'hidden' id='valor_cliente_$k' name='valor_cliente_$k' value='$db_ver->nom_cliente'></td>";				//#9
   		echo "<td></td>";									//#10
   		echo "<td></td>";									//#11
  		echo "<td></td>";									//#12
    	echo "<td>&nbsp;&nbsp;/&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;</td>";							//#13
   		echo "<td></td>";								//#14
		if ($db_ver->iden_bod==""){
		$iden = "11111";
		} else {
		$iden = $db_ver->iden_bod;
		}		
		echo "<td>$iden<input type = 'hidden' id='valor_iden_$k' name='valor_iden_$k' value='$iden'></td>";					//#15
  		echo "<td>0</td>";									//#16
  		echo "<td>0</td>";
		$devolucion = $db_ver->tot_dev_mfac;
		$total_fac = $db_ver->tot_fac;
		$total = $total_fac - $devolucion ;
		$base = 0;											//#17
		$base = ($total / 1.16);
		$base = round($base);
		$sumatoria_base = $base + $sumatoria_base;	
  		echo "<td>$base<input type = 'hidden' id='valor_base_$k' name='valor_base_$k' value='$base'></td>";								//#18
   		echo "<td></td>";									//#19
   	 	echo "<td></td>";									//#20
    	echo "<td>0</td>";									//#21
    	echo "<td>0</td>";									//#22
    	echo "<td>C</td>";									//#23
  		echo "</tr>";
		$i++;
		$k++;
		
		echo "<tr>";
    	echo "<td width='2%'>FV</td>";						//#1
    	echo "<td width='4%'>$db_ver->num_fac<input type = 'hidden' id='num_factura_$k' name='num_factura_$k' value='$db_ver->num_fac'></td>";		//#2
    	echo "<td width='4%'>01</td>";						//#3
    	echo "<td>$i<input type = 'hidden' id='valor_i_$k' name='valor_i_$k' value='$i'></td>";									//#4
    	echo "<td>$db_ver->dia-$db_ver->mes-$db_ver->ano<input type = 'hidden' id='valor_fecha_$k' name='valor_fecha_$k' value='$db_ver->dia-$db_ver->mes-$db_ver->ano'></td>";						//#5
    	echo "<td>$db_ver->mes/$db_ver->ano<input type = 'hidden' id='valor_periodo_$k' name='valor_periodo_$k' value='$db_ver->mes/$db_ver->ano'></td>";			//#6
  		echo "<td></td>";									//#7
  		echo "<td>24080105</td>";							//#8
  		echo "<td>$db_ver->nom_cliente<input type = 'hidden' id='valor_cliente_$k' name='valor_cliente_$k' value='$db_ver->nom_cliente'></td>";				//#9
   		echo "<td></td>";									//#10
   		echo "<td></td>";									//#11
  		echo "<td></td>";									//#12
    	echo "<td>&nbsp;&nbsp;/&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;</td>";							//#13
   		echo "<td></td>";									//#14
				if ($db_ver->iden_bod==""){
		$iden = "11111";
		} else {
		$iden = $db_ver->iden_bod;
		}		
		echo "<td>$iden<input type = 'hidden' id='valor_iden_$k' name='valor_iden_$k' value='$iden'></td>";					//#15
  		echo "<td>0</td>";									//#16
  		echo "<td>0</td>";									//#17
	    $base = ($total / 1.16);
		$iva = ($total - $base);
		$iva = round($iva);
		$sumatoria_iva = $iva + $sumatoria_iva ;				
  		echo "<td>$iva<input type = 'hidden' id='valor_iva_$k' name='valor_iva_$k' value='$iva'></td>";					//#18
   		echo "<td></td>";									//#19
   	 	echo "<td></td>";									//#20
    	echo "<td>0</td>";									//#21
    	echo "<td>0</td>";									//#22
    	echo "<td>C</td>";									//#23
  		echo "</tr>";
		$i++;
		$k++;
		
		echo "<tr>";
    	echo "<td width='2%'>FV</td>";						//#1
    	echo "<td width='4%'>$db_ver->num_fac<input type = 'hidden' id='num_factura_$k' name='num_factura_$k' value='$db_ver->num_fac'></td>";		//#2
    	echo "<td width='4%'>01</td>";						//#3
    	echo "<td>$i<input type = 'hidden' id='valor_i_$k' name='valor_i_$k' value='$i'></td>";									//#4
    	echo "<td>$db_ver->dia-$db_ver->mes-$db_ver->ano<input type = 'hidden' id='valor_fecha_$k' name='valor_fecha_$k' value='$db_ver->dia-$db_ver->mes-$db_ver->ano'></td>";						//#5
    	echo "<td>$db_ver->mes/$db_ver->ano<input type = 'hidden' id='valor_periodo_$k' name='valor_periodo_$k' value='$db_ver->mes/$db_ver->ano'></td>";			//#6
  		echo "<td></td>";									//#7
  		echo "<td>11050505</td>";							//#8
  		echo "<td>$db_ver->nom_cliente<input type = 'hidden' id='valor_cliente_$k' name='valor_cliente_$k' value='$db_ver->nom_cliente'></td>";				//#9
   		echo "<td></td>";									//#10
   		echo "<td></td>";									//#11
  		echo "<td></td>";									//#12
    	echo "<td>&nbsp;&nbsp;/&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;</td>";							//#13
   		echo "<td></td>";									//#14
		if ($db_ver->iden_bod==""){
		$iden = "11111";
		} else {
		$iden = $db_ver->iden_bod;
		}		
		echo "<td>$iden<input type = 'hidden' id='valor_iden_$k' name='valor_iden_$k' value='$iden'></td>";						//#15
  		echo "<td>0</td>";
		$sumatoria_total = $total + $sumatoria_total;								//#16
  		echo "<td>$total<input type = 'hidden' id='valor_total_$k' name='valor_total_$k' value='$total'></td>";					//#17
  		echo "<td>0</td>";									//#18
   		echo "<td></td>";									//#19
   	 	echo "<td></td>";									//#20
    	echo "<td>0</td>";									//#21
    	echo "<td>0</td>";									//#22
    	echo "<td>C</td>";									//#23
  		echo "</tr>";
		$i++;
		$k++;
		
		$i=1;
		$j++;
		}
		
	}
	echo "</table>";
?>
  </p>
  <table width="310" border="1">
    <tr>
      <td><input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
          <input type="hidden" id="contador2" name="contador2" value="<?=$j?>" />
        Total base:</td>
        <td><p align="right">
      
      $
      <?=number_format($sumatoria_base,0,".",".")?></p>      </td>
      </tr>
    <tr>
      <td>Total Iva: </td>
        <td><div align="right">$
          <?=number_format($sumatoria_iva,0,".",".")?>
        </div></td>
      </tr>
    <tr>
      <td>Total:</td>
        <td><div align="right">
          $
          <?=number_format($sumatoria_total,0,".",".")?>
        </div></td>
      </tr>
  </table>
</div>
<p>&nbsp;</p>
<p>Exportar a Excel  <img src="export_to_excel.gif" class="botonExcel" /></p>
<div align="right">
  <p>&nbsp;</p>
  </div>
</form>
</body>
</html>
