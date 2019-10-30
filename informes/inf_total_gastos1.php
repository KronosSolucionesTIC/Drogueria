<? include("../lib/database.php"); 
//echo $grupo_gasto."   --".$tipo_gasto;
?>
<link href="../styles.css" rel="stylesheet" type="text/css" />
<link href="../css/stylesforms.css" rel="stylesheet" type="text/css" />
<title>Consulta Total Gastos</title>
<style type="text/css">
<!--
.Estilo1 {color: #FFFFFF}
-->
</style>
<TABLE width="725" border="1" align="center" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
  <TR>
    <TD colspan="7" class="titulogran"><div align="center">INFORME DE GASTOS </div></TD>
  </TR>
  <TR>
    <TD class="subfongris"><div align="left"><strong><span class="Estilo1">PERIODO</span></strong></div></TD>
    <TD class="subfongris"><div align="left"><strong><span class="Estilo1">FECHA INICIAL </span></strong></div></TD>
    <TD class="subfongris"><div align="left">
        <?=$fecha_ini?>
      </div></TD>
    <TD class="subfongris"><div align="left"><span class="textotabla01 Estilo1">FECHA FINAL</span></div></TD>
    <TD colspan="3" class="subfongris"><div align="left">
        <?=$fecha_fin?>
      </div></TD>
  </TR>
  <TR>
    <TD width="9%" class="subfongris">GRUPO</TD>
    <TD width="15%" class="subfongris">TIPO</TD>
    <TD width="13%" class="subfongris">USUARIO</TD>
    <TD width="15%" class="subfongris">BODEGA</TD>
    <TD width="15%" class="subfongris">FECHA</TD>
    <TD width="15%" class="subfongris">VALOR</TD>
    <TD width="19%" class="subfongris">OBSERVACIONES</TD>
  </TR>
  <?		
//	echo $producto."===";
//	echo $fecha_ini."===";
//	echo $fecha_fin."===";
	
	$where="";
	$pvez = true;
	if($grupo_gastos > 0){
		$where.="  where cod_gru_gas=$grupo_gastos";
		$pvez = false;
	}
	if($tipo_gasto > 0){
	     if ($pvez) {
		 	$where.=" where ";
			$pvez = false;
		 }	
		 else 
		 	$where.=" and ";   
		 $where.=" tipo_gas= $tipo_gasto ";
	}
	if($fecha_ini > 0 and $fecha_fin == 0 ){
	 	if ($pvez) {
		 	$where.=" where ";
			$pvez = false;
		 }	
		 else 
		 	$where.=" and ";  
		$where.="fec_res =  '$fecha_ini' ";
	}
	if($fecha_ini > 0 and $fecha_fin > 0){
	     if ($pvez) {
		 	$where.=" where ";
			$pvez = false;
		 }	
		 else 
		 	$where.=" and ";  
		$where.="  fec_res >=  '$fecha_ini' and fec_res <=  '$fecha_fin' ";
	}
	

	$sql = " SELECT grupo_gastos.nom_gru, tipo_gastos.nom_gas, usuario.nom_usu, bodega.nom_bod, 
	resumen_gastos.fec_res as fecha, resumen_gastos.val_res as total, resumen_gastos.obs_res
	FROM resumen_gastos
	
	INNER JOIN tipo_gastos ON (resumen_gastos.tipo_gas = tipo_gastos.cod_gas)
	LEFT JOIN bodega ON (resumen_gastos.pun_res = bodega.cod_bod)
	LEFT JOIN usuario ON (resumen_gastos.resp_res = usuario.cod_usu)
	LEFT JOIN grupo_gastos ON (grupo_gastos.cod_gru = tipo_gastos.cod_gru_gas  ) 
	$where  
	having total >0 
	order by grupo_gastos.nom_gru, tipo_gastos.nom_gas, usuario.nom_usu, bodega.nom_bod ";
  
  //group by grupo_gastos.nom_gru, tipo_gastos.nom_gas, usuario.nom_usu, bodega.nom_bod 
	
	$db->query($sql);
	while($db->next_row()){
		echo "<TR><TD class='txtablas' width='10%'>$db->nom_gru</TD>";	
		echo "<TD class='txtablas' align='center' width='15%'>$db->nom_gas</TD>";
		echo "<TD class='txtablas' align='center' width='15%'>$db->nom_usu</TD>";
		echo "<TD class='txtablas' align='center' width='15%'>$db->nom_bod</TD>";
		echo "<TD class='txtablas' align='center' width='15%'>$db->fecha</TD>";
		echo "<TD class='txtablas' align='center' width='15%'>".number_format($db->total,0,".",".")."</TD>";
		echo "<TD class='txtablas' align='center' width='15%'>$db->obs_res</TD>";			
		echo "<TR>";
		
		$total_gastos += $db->total;
	}
	$db->close();
	?>
  <TR>
    <TD colspan="4" class="subfongris"><div align="right">TOTAL GASTOS </div></TD>
    <TD colspan="3" class="subfongris"><?=number_format($total_gastos,0,".",".")?></TD>
  </TR>
  <FORM method="POST" action="">
    <TR>
      <TD align="center" colspan="8">
        <input name="button" type="button" class="botones" onClick="window.print()" value="Imprimir" />
        <INPUT type="button" value="Cerrar" class="botones" onClick="window.close()">
      </TD>
    </TR>
  </FORM>
</TABLE>