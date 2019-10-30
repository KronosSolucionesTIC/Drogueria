<? include("../lib/database.php"); 

//echo $grupo_gasto."   --".$tipo_gasto;


?>

<link href="../styles.css" rel="stylesheet" type="text/css" />
<link href="../css/stylesforms.css" rel="stylesheet" type="text/css" />
<title>Consulta Gastos</title>
<style type="text/css">
<!--
.Estilo1 {color: #FFFFFF}
-->
</style>
<TABLE width="725" border="1" align="center" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
<TR>
  <TD colspan="8" class="titulogran"><div align="center">INFORME DE GASTOS </div></TD>
  </TR>
<TR>
  <TD class="subfongris"><div align="left"><strong><span class="Estilo1">PERIODO</span></strong></div></TD>
  <TD class="subfongris"><div align="left"><strong><span class="Estilo1">FECHA INICIAL </span></strong></div></TD>
  <TD class="subfongris"><div align="left">
    <?=$fecha_ini?>
  </div></TD>
  <TD class="subfongris"><div align="left"><span class="textotabla01 Estilo1">FECHA FINAL</span></div></TD>
  <TD colspan="2" class="subfongris"><div align="left">
    <?=$fecha_fin?>
  </div>
    </TD>
  </TR>
<TR>
		<TD width="9%" class="subfongris">GRUPO</TD>
		<TD width="21%" class="subfongris">TIPO</TD>
		<TD width="13%" class="subfongris">USUARIO</TD>
    <TD width="23%" class="subfongris">BODEGA</TD>
      <TD width="15%" class="subfongris">VALOR</TD>
      <TD width="19%" class="subfongris">OBSERVACIONES</TD>
</TR>
	
	<?		
//	echo $producto."===";
//	echo $fecha_ini."===";
//	echo $fecha_fin."===";
	
	
	
	
		
	
	$where=" where val_res > 0 ";
	
	 if($grupo_gastos > 0){
		$where.=" and cod_gru_gas=$grupo_gastos";
	}
	
	if($tipo_gasto > 0){
		$where.=" and tipo_gas= $tipo_gasto ";
	}
	
	//--------------
	
	
	if($fecha_ini > 0 and $fecha_fin > 0){
		$where.=" and fec_res >=  '$fecha_ini' and fec_res <=  '$fecha_fin' ";
	}
	
	/*if($fecha_fin > 0){
		$where.=" and fec_res <=  '$fecha_fin' ";
	}*/
	
	if($fecha_ini > 0 and $fecha_fin == 0 ){
		$where.=" and fec_res =  '$fecha_ini' ";
	}
	/*$where=" where val_res > 0 ";
	
	if  ($grupo_gastos > 0){
		$where.=" and cod_gru_gas= $grupo_gastos ";
	}
	
	if($tipo_gasto > 0){
		$where.=" and tipo_gas= $tipo_gasto ";
	}
	
	//--------------
	
	
	if($fecha_ini > 0){
		$where.=" and fec_res >=  '$fecha_ini' ";
	}
	
	if($fecha_fin > 0){
		$where.=" and fec_res <=  '$fecha_fin' ";
	}*/

	
	//---------------
	

	
  $sql = " SELECT 
* ,sum(val_res) as total
FROM
  resumen_gastos
  
  INNER JOIN tipo_gastos ON (resumen_gastos.tipo_gas = tipo_gastos.cod_gas)
  INNER JOIN bodega ON (resumen_gastos.pun_res = bodega.cod_bod)
  INNER JOIN usuario ON (resumen_gastos.resp_res = usuario.cod_usu)
  INNER JOIN grupo_gastos ON (grupo_gastos.cod_gru = tipo_gastos.cod_gru_gas  ) 
  $where  group by tipo_gas  ";
  
/*
ASI ESTABA
 $sql = " SELECT 
* ,sum(val_res) as total
FROM
  resumen_gastos
  
  INNER JOIN tipo_gastos ON (resumen_gastos.tipo_gas = tipo_gastos.cod_gas)
  INNER JOIN rsocial ON (resumen_gastos.rso_res = rsocial.cod_rso)
  INNER JOIN bodega ON (resumen_gastos.pun_res = bodega.cod_bod)
  INNER JOIN usuario ON (resumen_gastos.resp_res = usuario.cod_usu)
  INNER JOIN grupo_gastos ON (grupo_gastos.cod_gru = tipo_gastos.cod_gru_gas  ) 
  $where  group by tipo_gas  ";*/

		$db->query($sql);
		while($db->next_row()){
			echo "<FORM action='agr_traslado.php' method='POST'> ";
			echo "<TR><TD class='txtablas' width='10%'>$db->nom_gru</TD>";	
			echo "<TD class='txtablas' align='center' width='15%'>$db->nom_gas</TD>";
			//echo "<TD class='txtablas' align='center' width='15%'>$db->nom_pro</TD>";
				
			echo "<TD class='txtablas' align='center' width='15%'>$db->nom_usu</TD>";
			
			echo "<TD class='txtablas' align='center' width='15%'>$db->nom_bod</TD>";
			
			echo "<TD class='txtablas' align='center' width='15%'>".number_format($db->total,0,".",".")."</TD>";
			echo "<TD class='txtablas' align='center' width='15%'>$db->obs_res</TD>";			
			
			echo "</TR></FORM>";
			
		$total_gastos=	$total_gastos +$db->total;
		}
	?>

</TR>
<TR>
		<TD colspan="4" class="subfongris"><div align="right">TOTAL GASTOS </div></TD>
	<TD colspan="2" class="subfongris"><?=number_format($total_gastos,0,".",".")?></TD>
  </TR>


<script>
function ver_documento(codigo,mapa)
{
	 window.open("ver_factura.php?codigo="+codigo,"ventana","menubar=0,resizable=1,width=700,height=400,toolbar=0,scrollbars=yes")
	// window.open("ver_traslado.php?codigo="+codigo,"ventana") 
} 
</script>


	<FORM method="POST" action="../agr_prin_factura.php">
	<TR><TD align="center" colspan="8">	
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<input name="button" type="button" class="botones" onClick="window.print()" value="Imprimir" />
			<INPUT type="button" value="Cerrar" class="botones" onClick="window.close()">
	</TD></TR></FORM>
</TABLE>
