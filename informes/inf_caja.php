<?
include("../lib/database.php");
include("../js/funciones.php");
?>
<html>
<head>
<link href="styles.css" rel="stylesheet" type="text/css" />
<link href="../css/styles.css" rel="stylesheet" type="text/css">
<title>INFORME CIERRE DE CAJA</title>
<script language="javascript">
function ver_facturacion(obj,boton)
{
	if(document.getElementById(obj).style.display =="none")
	{
		document.getElementById(obj).style.display ="inline";
		document.getElementById(boton).value ="Ocultar";
	}
	else {
		document.getElementById(obj).style.display ="none";
		document.getElementById(boton).value ="Ver Detalles";
	}
}

function imprimir(){
	document.getElementById('aper').value = 1;
	document.forma.submit();
	window.open("ver_cierre.php?fec_ini=<?=$fec_ini?>&fec_fin=<?=$fec_fin?>&valor=<?=$valor?>");
}
</script>
</head>
<body>
<?
if (!isset($global[2])) {
   $codigo_usuario = $_SESSION['global_2'];
}
else
	$codigo_usuario = $global[2];


if($guardar_todo==1) {

	$texto=$contexto."<br>Observaciones".$comentario;
	$sql="select cod_usu, nom_usu from usuario  where cod_usu=$codigo_usuario";
	$dbdatos= new  Database();
	$dbdatos->query($sql);
	if ($dbdatos->next_row())
		$nombre=$dbdatos->nom_usu;
		echo "Enviando alerta de cierre a usuario ".$nombre;
		enviar_alerta("Alerta Cierre de $nombre " , "  $texto  ");
	}
	
	$db_ver = new Database();
	$sql="select distinct punto_venta.cod_bod as valor , nom_bod as nombre 
	from punto_venta  inner join  bodega  on punto_venta.cod_bod=bodega.cod_bod 
	WHERE cod_ven = $global[2]";
	$dbdatos= new  Database();
	$dbdatos->query($sql);
	$where_cli="";	
	$primeravez = true;
	while($dbdatos->next_row())
	{
		
		$valor = $dbdatos->valor;
		if ($primeravez) {
		   $where_cli=" and bodega1.cod_bod_cli in (";
		   $where_cli .= "'".$dbdatos->valor."'";
		   $primeravez = false;
		}
		else
		   $where_cli .= ", '".$dbdatos->valor."'";
	}	
	if ($primeravez == false) {	
		$where_cli.=" )";
	}

	$busqueda = "bodega.cod_bod = $valor";

	$busqueda2 = "centro_costos.cod_bod = $valor";


	$sql = "SELECT 
			m_factura.num_fac,
			m_factura.fecha,
			m_factura.tipo_pago,
			m_factura.tot_fac, 
			m_factura.tot_dev_mfac,
			bodega.cod_bod,
			bodega.nom_bod,
			usuario.nom_usu as responsable,
			CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente
		 FROM m_factura  
		 INNER JOIN bodega1 ON  (bodega1.cod_bod = m_factura.cod_cli) 
		 INNER JOIN ruta ON ruta.cod_ruta = bodega1.cod_ruta
		 LEFT JOIN usuario ON ( usuario.cod_usu=m_factura.cod_usu)
		 INNER JOIN bodega ON bodega.cod_bod = m_factura.cod_bod 
		 WHERE  ( fecha >='$fec_ini' AND fecha <='$fec_fin' ) AND $busqueda
		 AND estado IS  NULL $where_cli  ";
	$db_ver->query($sql);	
	$cantidad_max=0;;
?>
<table width="731" border="1" cellpadding="2" cellspacing="1"   class="textoproductos1" align="center">
  <tr>
  <? 
  $dbp = new Database();
  $sqlp = "SELECT * FROM bodega
  WHERE cod_bod = $valor";
  $dbp->query($sqlp);
  $dbp->next_row();
  ?>
    <td colspan="8" class="ctablasup" align="center">CUENTA CAJA <?=$dbp->nom_bod?></td>
  </tr>
  <tr>
    <td colspan="8" align="center"><br></td>
  </tr>
  <tbody colspan="8" id="facturacion" style="display:none" width="100%">
   <tr>
    <td class="subtitulosproductos">Responsable</td>
    <td width="17%" class="subtitulosproductos">Factura No. </td>
    <td class="subtitulosproductos">Fecha</td>
    <td class="subtitulosproductos">Tipo Pago</td>
    <td class="subtitulosproductos">Cliente</td>
    <td colspan="3" class="subtitulosproductos">Valor</td>
  </tr>
    <? 
	$total_fact_1=0;
	$max_credito = 0;
	$max_contado = 0;  
	while($db_ver->next_row()){ 
	    $valor = $db_ver->tot_fac - $db_ver->tot_dev_mfac;
		if($db_ver->tipo_pago=='Credito')
			$max_credito += $valor;	
		if($db_ver->tipo_pago=='Contado')
			$max_contado += $valor;
	    ?>
		<tr>
		  <td class="textoproductos1"><?=$db_ver->responsable?></td>
		  <td class="textoproductos1"><?=$db_ver->num_fac?></td>
		  <td width="11%" class="textoproductos1"><?=$db_ver->fecha?></td>
		  <td width="32%" class="textoproductos1"><?=$db_ver->tipo_pago?></td>
		  <td width="32%" class="textoproductos1"><?=$db_ver->cliente?></td>
		  <td width="10%" colspan="3" class="textoproductos1" align="right"><?=number_format($valor,0,".",".")?></td>
		</tr>
		<?
		$cantidad_max += $valor;
		$total_fact_1 += $valor;
	} 	
	?>
  </tbody>
  <tr>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos" align="right"><b>TOTAL FACTURACION:</b></td>
    <td colspan="3" class="subtitulosproductos" align="right"><b>
      <?=number_format($cantidad_max,0,".",".")?>
      </b></td>
  </tr>
  <tr>
    <td align="center" colspan="8"><input name="button2" type="button" class="botones1"  id="btn_fac" onClick="ver_facturacion('facturacion','btn_fac')" value="Ver Detalles" /></td>
  </tr>
  <tr>
    <td colspan="8" class="ctablasup" align="center">ANULACIONES</td>
  </tr>
  <tr>
    <td colspan="8" align="center"><br></td>
  </tr>
  <tbody colspan="8" id="facturacion3" style="display:none" width="100%">
  <tr>
    <td class="subtitulosproductos">Responsable</td>
    <td class="subtitulosproductos">Factura No. </td>
    <td class="subtitulosproductos">Fecha</td>
    <td class="subtitulosproductos">Tipo Pago</td>
    <td class="subtitulosproductos">Cliente</td>
    <td colspan="3" class="subtitulosproductos">Valor</td>
  </tr>
  <?
  $dba = new Database();
  $sqla = "SELECT *,CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) AS cliente FROM m_factura 
  INNER JOIN usuario ON usuario.cod_usu = m_factura.cod_usu
  INNER JOIN bodega1 ON bodega1.cod_bod = m_factura.cod_cli
  INNER JOIN bodega ON bodega.cod_bod = m_factura.cod_bod
  WHERE(fecha >='$fec_ini' AND fecha <='$fec_fin') AND estado='anulado' AND $busqueda";
  $dba->query($sqla);
  	while($dba->next_row()){
		$valor_anul = $dba->tot_fac - $dba->tot_dev_mfac;
  ?>
  <tr>
    <td class="textoproductos1"><?=$dba->nom_usu?></td>
    <td class="textoproductos1"><?=$dba->num_fac?></td>
    <td class="textoproductos1"><?=$dba->fecha?></td>
    <td class="textoproductos1"><?=$dba->tipo_pago?></td>
    <td class="textoproductos1"><?=$dba->cliente?></td>
    <td width="10%" colspan="3" class="textoproductos1" align="right"><?=number_format($valor_anul,0,".",".")?></td>
  </tr>
  <tr>
    <td colspan="4" class="subtitulosproductos">&nbsp;</td>
    <? 
	$dbra = new Database();
	$sqlra = "SELECT * FROM razon_anulacion
	WHERE cod_razon = '$dba->razon_anulacion'";
	$dbra->query($sqlra);
	$dbra->next_row();
	?>
    <br>
    <td colspan="3" class="subtitulosproductos" align="right"><?=$dbra->desc_razon?>; <?=$dba->obs_anulacion?></td>
  </tr>
    <? 
		$total_anul += $valor_anul;
	} 
	?>
   </tbody>
  <tr>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos" align="right"><b>TOTAL ANULACIONES: </b></td>
    <td colspan="3" class="subtitulosproductos" align="right"><b>
      <?=number_format($total_anul,0,".",".")?>
    </b></td>
  </tr>

  <tr>
    <td align="center" colspan="8"><input name="btn_fac3" type="button" class="botones1"  id="btn_fac3" onClick="ver_facturacion('facturacion3','btn_fac3')" value="Ver Detalles" /></td>
  </tr>
      <tr>
    <td colspan="7" class="ctablasup" align="center">ABONOS</td>
  </tr> 
  <tbody  colspan="7" id="abonos" style="display:none"  width="100%">
   <tr>
    <td class="subtitulosproductos">Responsable</td>
    <td class="subtitulosproductos">Abono No. </td>
    <td class="subtitulosproductos">Fecha</td>
    <td class="subtitulosproductos">Tipo Pago</td>
    <td colspan="2" class="subtitulosproductos">Cliente</td>
    <td class="subtitulosproductos">Valor</td>
   </tr>
    <? 
	$sql = "SELECT 
	abono.cod_abo, abono.fec_abo, abono.val_abo,abono.tipo_pago, 
	CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente, 
	 ( select usuario.nom_usu  from usuario where   usuario.cod_usu = abono.cod_usu_abo) as responsable
	FROM abono  
	INNER JOIN bodega1 ON abono.cod_bod_Abo = bodega1.cod_bod 
	INNER JOIN bodega ON bodega.cod_bod = bodega1.cod_bod_cli
	WHERE (abono.fec_abo >='$fec_ini'   AND  abono.fec_abo <='$fec_fin') AND $busqueda";
	
	$db_ver->query($sql);
	$max_abono=0; 
 	while($db_ver->next_row()){ 
		?>
		<tr>
                            <?
				$dbtp = new Database();
				$sqltp = "SELECT * FROM tipo_pago 
				WHERE cod_tpag = '$db_ver->tipo_pago'"; 
				$dbtp->query($sqltp);
				$dbtp->next_row();
			?>
		  <td class="textoproductos1"><?=$db_ver->responsable?></td>
		  <td class="textoproductos1"><?=$db_ver->cod_abo?></td>
		  <td width="11%" class="textoproductos1"><?=$db_ver->fec_abo?></td>
		  <td width="32%" class="textoproductos1"><?=$dbtp->nom_tpag?></td>
		  <td width="16%" colspan="2" class="textoproductos1"><?=$db_ver->cliente?></td>
		  <td width="16%" class="textoproductos1" align="right"><?=number_format($db_ver->val_abo,0,".",".")?></td>
		</tr>
		<?
		$max_abono += $db_ver->val_abo;
		if($dbtp->cod_tpag == 4){
			$consig_abonos += $db_ver->val_abo ;	
		}
		if($dbtp->cod_tpag == 3){
			$cheques_abonos += $db_ver->val_abo ;	
		}
		if($dbtp->cod_tpag == 5){
			$data_abonos += $db_ver->val_abo;	
		}
		if($dbtp->cod_tpag == 7){
			$nomina_abonos += $db_ver->val_abo;	
		}
		if(($dbtp->cod_tpag == 2)){
			$max_efectivo += $db_ver->val_abo; 
		}
		if(($dbtp->cod_tpag == 6)){
			$net_abonos += $db_ver->val_abo; 
		}
	} ?>
  </tbody>
  <tr>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos" align="right"><b>TOTAL ABONOS: </b></td>
    <td colspan="3" class="subtitulosproductos" align="right"><b><?=number_format($max_abono,0,".",".")?></b></td>
  </tr>
   <tr>
    <td colspan="7" align="center"><br>
     <input name="btn_abo" type="button" class="botones1"  id="btn_abo" onClick="ver_facturacion('abonos','btn_abo')" value="Ver Detalles" /></td>
  </tr>
   <tr>
    <td colspan="7" class="ctablasup" align="center">CONSIGNACIONES</td>
  </tr> 
  <? $val_otros = 0 ?>
  <tbody  colspan="7" id="consignacion" style="display:none"  width="100%">
  <tr>
    <td class="subtitulosproductos">Responsable</td>
    <td class="subtitulosproductos">Factura No. </td>
    <td class="subtitulosproductos">Fecha</td>
    <td class="subtitulosproductos">Cliente</td>
    <td colspan="3" class="subtitulosproductos">Valor</td>
  </tr>
    <? 
	$sql = "SELECT 
	  OP.fec_otro,
	  OP.val_otro,
	  OP.cod_fac,
	  CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente,
	  usuario.nom_usu
	  FROM
	  otros_pagos  OP 
	  LEFT JOIN bodega1 ON (OP.cod_cli_otro = bodega1.cod_bod)
	  INNER JOIN bodega ON bodega.cod_bod = bodega1.cod_bod_cli
	  LEFT JOIN usuario ON (OP.cod_usu_otro = usuario.cod_usu)  
	  WHERE  ( OP.fec_otro >='$fec_ini'   AND OP.fec_otro <='$fec_fin' ) AND cod_tpag_otro = 4 AND $busqueda";
	  
	$db_ver->query($sql);
	$val_con=0; 
	while($db_ver->next_row()){ 
		?>
		<tr>
                    <?
				$dbf = new Database();
				$sqlf = "SELECT * FROM m_factura 
				WHERE cod_fac = '$db_ver->cod_fac'"; 
				$dbf->query($sqlf);
				$dbf->next_row();
					if($dbf->tipo_pago == 'Credito'){
						$deducciones_credito += $db_ver->val_otro;
					}
			?>
		  <td class="textoproductos1"><?=$db_ver->nom_usu?></td>
		  <td class="textoproductos1"><?=$dbf->num_fac?></td>
		  <td width="11%" class="textoproductos1"><?=$db_ver->fec_otro?></td>
		  <td width="32%" class="textoproductos1"><?=$db_ver->cliente?></td>
		  <td width="32%" colspan="3" class="textoproductos1" align="right"><?=number_format($db_ver->val_otro,0,".",".")?></td>
		</tr>
		<?
		$val_con += $db_ver->val_otro;
  	} ?>
  </tbody>
  <tr>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos" align="right"><b>TOTAL CONSIGNACIONES:</b></td>
    <td colspan="3" class="subtitulosproductos" align="right"><b><?=number_format($val_con,0,".",".")?></b></td>
  </tr>
  <tr>
    <td align="center" colspan="8"><input name="btn_con" type="button" class="botones1"  id="btn_con" onClick="ver_facturacion('consignacion','btn_con')" value="Ver Detalles" /></td>
  </tr>
    <tr>
    <td colspan="7" class="ctablasup" align="center">CHEQUES</td>
  </tr> 
  <tbody  colspan="7" id="cheques" style="display:none"  width="100%">
   <tr>
    <td class="subtitulosproductos">Responsable</td>
    <td class="subtitulosproductos">Factura No. </td>
    <td class="subtitulosproductos">Fecha</td>
    <td class="subtitulosproductos">Cliente</td>
    <td colspan="3" class="subtitulosproductos">Valor</td>
  </tr>
    <? 
	$sql = "SELECT 
	  OP.fec_otro,
	  OP.val_otro,
	  OP.cod_fac,
	  CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente,
	  usuario.nom_usu
	  FROM
	  otros_pagos  OP 
	  LEFT JOIN bodega1 ON (OP.cod_cli_otro = bodega1.cod_bod)
	  INNER JOIN bodega ON bodega.cod_bod = bodega1.cod_bod_cli
	  LEFT JOIN usuario ON (OP.cod_usu_otro = usuario.cod_usu)  
	  WHERE  ( OP.fec_otro >='$fec_ini'   AND OP.fec_otro <='$fec_fin' ) AND cod_tpag_otro = 3 AND $busqueda";
	
	$db_ver->query($sql);
	$val_che=0; 
 	while($db_ver->next_row()){ 
		?>
		<tr>
             <?
				$dbf = new Database();
				$sqlf = "SELECT * FROM m_factura 
				WHERE cod_fac = '$db_ver->cod_fac'"; 
				$dbf->query($sqlf);
				$dbf->next_row();
				if($dbf->tipo_pago == 'Credito'){
					$deducciones_credito += $db_ver->val_otro;
				}
			?>
		   <td class="textoproductos1"><?=$db_ver->nom_usu?></td>
		  <td class="textoproductos1"><?=$dbf->num_fac?></td>
		  <td width="11%" class="textoproductos1"><?=$db_ver->fec_otro?></td>
		  <td width="32%" class="textoproductos1"><?=$db_ver->cliente?></td>
		  <td width="32%" colspan="3" class="textoproductos1" align="right"><?=number_format($db_ver->val_otro,0,".",".")?></td>
		<?
		$val_che += $db_ver->val_otro;
	} ?>
  </tbody>
  <tr>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos" align="right"><b>TOTAL CHEQUES: </b></td>
    <td colspan="3" class="subtitulosproductos" align="right"><b>
      <?=number_format($val_che,0,".",".")?>
    </b></td>
  </tr>
   <tr>
    <td colspan="7" align="center"><br>
     <input name="btn_che" type="button" class="botones1"  id="btn_che" onClick="ver_facturacion('cheques','btn_che')" value="Ver Detalles" /></td>
  </tr>
   <tr>
    <td colspan="7" class="ctablasup" align="center">DATAFONO</td>
  </tr> 
  <tbody  colspan="7" id="datafono" style="display:none"  width="100%">
   <tr>
    <td class="subtitulosproductos">Responsable</td>
    <td class="subtitulosproductos">Factura No. </td>
    <td class="subtitulosproductos">Fecha</td>
    <td class="subtitulosproductos">Cliente</td>
    <td colspan="3" class="subtitulosproductos">Valor</td>
  </tr>
    <? 
	$sql = "SELECT 
	  OP.fec_otro,
	  OP.val_otro,
	  OP.cod_fac,
	  CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente,
	  usuario.nom_usu
	  FROM
	  otros_pagos  OP 
	  LEFT JOIN bodega1 ON (OP.cod_cli_otro = bodega1.cod_bod)
	  INNER JOIN bodega ON bodega.cod_bod = bodega1.cod_bod_cli
	  LEFT JOIN usuario ON (OP.cod_usu_otro = usuario.cod_usu)  
	  WHERE  ( OP.fec_otro >='$fec_ini'   AND OP.fec_otro <='$fec_fin' ) AND cod_tpag_otro = 5 AND $busqueda";
	
	$db_ver->query($sql);
	$val_data=0; 
 	while($db_ver->next_row()){ 
		?>
		<tr>
                            <?
				$dbf = new Database();
				$sqlf = "SELECT * FROM m_factura 
				WHERE cod_fac = '$db_ver->cod_fac'"; 
				$dbf->query($sqlf);
				$dbf->next_row();
				if($dbf->tipo_pago == 'Credito'){
					$deducciones_credito += $db_ver->val_otro;
				}
			?>
		 <td class="textoproductos1"><?=$db_ver->nom_usu?></td>
		  <td class="textoproductos1"><?=$dbf->num_fac?></td>
		  <td width="11%" class="textoproductos1"><?=$db_ver->fec_otro?></td>
		  <td width="32%" class="textoproductos1"><?=$db_ver->cliente?></td>
		  <td width="32%" colspan="3" class="textoproductos1" align="right"><?=number_format($db_ver->val_otro,0,".",".")?></td>
		</tr>
		<?
		$val_data += $db_ver->val_otro;
	} ?>
  </tbody>
  <tr>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos" align="right"><b>TOTAL DATAFONO: </b></td>
    <td colspan="3" class="subtitulosproductos" align="right"><b><?=number_format($val_data,0,".",".")?></b></td>
  </tr>
   <tr>
    <td colspan="7" align="center"><br>
     <input name="btn_data" type="button" class="botones1"  id="btn_data" onClick="ver_facturacion('datafono','btn_data')" value="Ver Detalles" /></td>
  </tr>
  <tbody  colspan="7" id="cheques" style="display:none"  width="100%">
		<tr>
  </tbody>
   <tr>
    <td colspan="7" class="subtitulosproductos" align="center"><? 
	
	$val_otros = $val_con + $val_che + $val_data;
	$sql = "SELECT sum(credito_dmov) as total  FROM d_movimientos  
	INNER JOIN m_movimientos ON m_movimientos.cod_mov = d_movimientos.cod_mov
	INNER JOIN cuenta ON cuenta.cod_cuenta = d_movimientos.cuenta_dmov
	INNER JOIN centro_costos ON centro_costos.cod_centro = d_movimientos.centro_dmov
	WHERE  ( fec_emi >='$fec_ini' AND fec_emi <='$fec_fin' )  AND tipo_mov = 4 AND nivel = 5 AND estado_mov = 1 AND $busqueda2";
	$db_ver->query($sql);
	if ($db_ver->next_row()){ 
		$total_gastos= (double) $db_ver->total;		
	}
		
	//echo $total_gastos."==========";
	$total_con = $val_con + $consig_abonos;
	$total_che = $val_che + $cheques_abonos;
	$total_data = $val_data + $data_abonos;
	$total_credito = $max_credito - $deducciones_credito;
	$contexto.=$dato_total="Total  Facturación del período de  &nbsp; $fec_ini  &nbsp;  al     &nbsp;$fec_fin por:     &nbsp;&nbsp; $".number_format($total_fact_1,0,".",".");
	$contexto.="<br>";
	$contexto.="Total venta neta credito: &nbsp;&nbsp;&nbsp;$".number_format($total_credito,0,".",".");
	$contexto.="<br>";
	$contexto.="Total abonos: &nbsp;&nbsp;&nbsp;$".number_format($max_abono,0,".",".");
	$contexto.="<br>";
	$contexto.="Total consignaciones: &nbsp;&nbsp;&nbsp;$".number_format($total_con,0,".",".");
	$contexto.="<br>";
	$contexto.="Total cheques: &nbsp;&nbsp;&nbsp;$".number_format($total_che,0,".",".");
	$contexto.="<br>";
	$contexto.="Total datafonos: &nbsp;&nbsp;&nbsp;$".number_format($total_data,0,".",".");
	$contexto.="<br>";
	$contexto.="Total descuentos por nomina: &nbsp;&nbsp;&nbsp;$".number_format($nomina_abonos,0,".",".");
	$contexto.="<br>";
	$contexto.="Total abonos NET: &nbsp;&nbsp;&nbsp;$".number_format($net_abonos,0,".",".");
	$contexto.="<br>";
	$efectivo =($total_fact_1 + $max_abono)-($total_credito)-($total_con)-($total_che)-($total_data)-($nomina_abonos)-($net_abonos);
	$contexto.="<br>";
	$contexto.="Efectivo del Período:  &nbsp;&nbsp;&nbsp;$".number_format($efectivo,0,".",".");
	
	echo $contexto;
	

	echo "<br>";
	echo "<br>";
	?></td>
  </tr>
  <form method="POST" action="consulta_caja_diara1.php" name="enviar_correo">
    <tr>
      <td align="center" colspan="8"><table width="0" border="1">
          <tr>
            <td class="subtitulosproductos">Observaciones</td>
            <td><textarea name="comentario" cols="30" rows="4"><?=$comentario?>
</textarea>
              <input type="hidden"  value="<?=$dato_total?>" name="total_cierre"/>
              <input type="hidden"  value="0" name="guardar_todo" id="guardar_todo"/>
              <input type="hidden"   name="fec_ini" value="<?=$fec_ini?>"/>
              <input type="hidden"   name="fec_fin" value="<?=$fec_fin?>"/>
              <textarea name="contexto" cols="30" rows="4" style="display:none"><?=$contexto?>
</textarea>
            </td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td align="center" colspan="8"><input name="button3" type="button" class="botones1"  onclick="guardar_correo()" value="Enviar" />
        <input name="button" type="button" class="botones1"  onClick="imprimir()" value="Imprimir" />
        <input name="button2" type="button" class="botones1"  onClick="window.close()" value="Cerrar" />
        <input type="hidden" name="mapa" value="<?=$mapa?>">
        <input type="visible" name="aper" id="aper" /></td>
    </tr>
  </form>
  <form id="forma" name="forma">
  <input name="fec_ini" id="fec_ini" type="hidden" value="<?=$fec_ini?>"/>
  </form>
</table>
 <?
if (!isset($global[2])) {
   $codigo_usuario = $_SESSION['global_2'];
}
else
	$codigo_usuario = $global[2];


if($guardar_todo==1) {

	$texto=$contexto."<br>Observaciones".$comentario;
	$sql="select cod_usu, nom_usu from usuario  where cod_usu=$codigo_usuario";
	$dbdatos= new  Database();
	$dbdatos->query($sql);
	if ($dbdatos->next_row())
		$nombre=$dbdatos->nom_usu;
		echo "Enviando alerta de cierre a usuario ".$nombre;
		enviar_alerta("Alerta Cierre de $nombre " , "  $texto  ");
	}
	
	$db_ver = new Database();
	$sql="select distinct punto_venta.cod_bod as valor , nom_bod as nombre 
	from punto_venta  inner join  bodega  on punto_venta.cod_bod=bodega.cod_bod ";
	$dbdatos= new  Database();
	$dbdatos->query($sql);
	$where_cli="";	
	$primeravez = true;
	while($dbdatos->next_row())
	{
		if ($primeravez) {
		   $where_cli=" and bodega1.cod_bod_cli in (";
		   $where_cli .= "'".$dbdatos->valor."'";
		   $primeravez = false;
		}
		else
		   $where_cli .= ", '".$dbdatos->valor."'";
	}	
	if ($primeravez == false) {	
		$where_cli.=" )";
	}

	$sql = "SELECT 
			m_factura.num_fac,
			m_factura.fecha,
			m_factura.tipo_pago,
			m_factura.tot_fac, 
			m_factura.tot_dev_mfac,
			bodega.cod_bod,
			bodega.nom_bod,
			usuario.nom_usu as responsable,
			CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente
		 FROM m_factura  
		 INNER JOIN bodega1 ON  (bodega1.cod_bod = m_factura.cod_cli) 
		 INNER JOIN ruta ON ruta.cod_ruta = bodega1.cod_ruta
		 LEFT JOIN usuario ON ( usuario.cod_usu=m_factura.cod_usu)
		 INNER JOIN bodega ON bodega.cod_bod = m_factura.cod_bod 
		 WHERE  ( fecha >='2014-12-04' AND fecha <='$fec_fin' ) AND $busqueda
		 AND estado IS  NULL $where_cli  ";
	$db_ver->query($sql);	
	$cantidad_max=0;
?>
</p>
<table width="731" border="1" cellpadding="2" cellspacing="1"   class="textoproductos1" align="center">
    <? 
	$deducciones_credito = 0;
	$total_fact_2 = 0;
	$max_credito = 0;
	$max_contado = 0;  
	while($db_ver->next_row()){ 
	    $valor = $db_ver->tot_fac - $db_ver->tot_dev_mfac;
		if($db_ver->tipo_pago=='Credito')
			$max_credito += $valor;	
		if($db_ver->tipo_pago=='Contado')
			$max_contado += $valor;
			$cantidad_max += $valor;
			$total_fact_2 += $valor;
	} 	


  	$dba = new Database();
  	$sqla = "SELECT *,CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) AS cliente FROM m_factura 
  	INNER JOIN usuario ON usuario.cod_usu = m_factura.cod_usu
  	INNER JOIN bodega1 ON bodega1.cod_bod = m_factura.cod_cli
	INNER JOIN bodega ON bodega.cod_bod = bodega1.cod_bod_cli
  	WHERE (fecha >= '2014-12-04' AND fecha <= '$fec_fin') AND estado='anulado' AND $busqueda";
  	$dba->query($sqla);
  		while($dba->next_row()){
			$valor_anul = $dba->tot_fac - $dba->tot_dev_mfac;
			$dbra = new Database();
			$sqlra = "SELECT * FROM razon_anulacion
			WHERE cod_razon = '$dba->razon_anulacion'";
			$dbra->query($sqlra);
			$dbra->next_row();
		
			$total_anul += $valor_anul;
		} 

	$sql = "SELECT  
	m_factura.num_fac, m_factura.fecha, m_devolucion.val_del,
	CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente, 
	usuario.nom_usu 
	FROM m_devolucion 
	INNER JOIN m_factura ON m_devolucion.num_fac_dev = m_factura.cod_fac 
	INNER JOIN bodega1 ON m_factura.cod_cli = bodega1.cod_bod 
	INNER JOIN bodega ON bodega.cod_bod = bodega1.cod_bod_cli
	LEFT JOIN usuario ON m_devolucion.cod_ven_dev = usuario.cod_usu 
	WHERE ( (fecha >= '2014-12-04' AND fecha <= '$fec_fin')) AND estado IS  NULL $where_cli AND $busqueda";
	$db_ver->query($sql);	
	$total_dev=0;
	$max_total=0;
	while($db_ver->next_row()){ 
		$total_dev += $db_ver->val_del;
		$max_total += $db_ver->val_del;
	} 
	
	$sql = "SELECT 
	abono.cod_abo, abono.fec_abo, abono.val_abo,abono.tipo_pago, 
	CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente, 
	 ( select usuario.nom_usu  from usuario where   usuario.cod_usu = abono.cod_usu_abo) as responsable
	FROM abono  
	INNER JOIN bodega1 ON abono.cod_bod_Abo = bodega1.cod_bod 
	INNER JOIN bodega ON bodega.cod_bod = bodega1.cod_bod_cli 
	WHERE  (abono.fec_abo >= '2014-12-04' AND abono.fec_abo <= '$fec_fin')   $where_cli AND $busqueda";
	
	$db_ver->query($sql);
	$max_abono=0; 
 	while($db_ver->next_row()){ 
		$dbtp = new Database();
		$sqltp = "SELECT * FROM tipo_pago 
		WHERE cod_tpag = '$db_ver->tipo_pago'"; 
		$dbtp->query($sqltp);
		$dbtp->next_row();

		$max_abono += $db_ver->val_abo;
		if($dbtp->cod_tpag == 4){
			$consig_abonos2 += $db_ver->val_abo ;	
		}
		if($dbtp->cod_tpag == 3){
			$cheques_abonos2 += $db_ver->val_abo ;	
		}
		if($dbtp->cod_tpag == 5){
			$data_abonos2 += $db_ver->val_abo;	
		}
		if($dbtp->cod_tpag == 7){
			$nomina_abonos2 += $db_ver->val_abo;	
		}
		if(($dbtp->cod_tpag == 2)){
			$max_efectivo2 += $db_ver->val_abo; 
		}
		if(($dbtp->cod_tpag == 6)){
			$net_abonos2 += $db_ver->val_abo; 
		}
	} 
	$val_otros = 0 ;
	$sql = "SELECT 
	  OP.fec_otro,
	  OP.val_otro,
	  OP.cod_fac,
	  CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente,
	  usuario.nom_usu
	  FROM
	  otros_pagos  OP 
	  LEFT JOIN bodega1 ON (OP.cod_cli_otro = bodega1.cod_bod)
	  INNER JOIN bodega ON bodega.cod_bod = bodega1.cod_bod_cli
	  LEFT JOIN usuario ON (OP.cod_usu_otro = usuario.cod_usu)  
	  WHERE  ( OP.fec_otro >='2014-12-04'   AND OP.fec_otro <='$fec_fin' ) AND cod_tpag_otro = 4 AND $busqueda";
	  
	$db_ver->query($sql);
	$val_con2=0; 
	while($db_ver->next_row()){ 
		$dbf = new Database();
		$sqlf = "SELECT * FROM m_factura 
		WHERE cod_fac = '$db_ver->cod_fac'"; 
		$dbf->query($sqlf);
		$dbf->next_row();
			if($dbf->tipo_pago == 'Credito'){
				$deducciones_credito += $db_ver->val_otro;
			}
			
		$val_con2 += $db_ver->val_otro;
  	} 
	 
	$sql = "SELECT 
	  OP.fec_otro,
	  OP.val_otro,
	  OP.cod_fac,
	  CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente,
	  usuario.nom_usu
	  FROM
	  otros_pagos  OP 
	  LEFT JOIN bodega1 ON (OP.cod_cli_otro = bodega1.cod_bod)
	  INNER JOIN bodega ON bodega.cod_bod = bodega1.cod_bod_cli
	  LEFT JOIN usuario ON (OP.cod_usu_otro = usuario.cod_usu)  
	  WHERE ( OP.fec_otro >= '2014-12-04' AND OP.fec_otro <= '$fec_fin' )  AND cod_tpag_otro = 3 AND $busqueda";
	
	$db_ver->query($sql);
	$val_che2=0; 
 	while($db_ver->next_row()){ 
		$dbf = new Database();
		$sqlf = "SELECT * FROM m_factura 
		WHERE cod_fac = '$db_ver->cod_fac'"; 
		$dbf->query($sqlf);
		$dbf->next_row();
		if($dbf->tipo_pago == 'Credito'){
			$deducciones_credito += $db_ver->val_otro;
		}
		$val_che2 += $db_ver->val_otro;
	} 
	
	$sql = "SELECT 
	  OP.fec_otro,
	  OP.val_otro,
	  OP.cod_fac,
	  CONCAT(bodega1.nom_bod,' ',bodega1.apel_bod) as cliente,
	  usuario.nom_usu
	  FROM
	  otros_pagos  OP 
	  LEFT JOIN bodega1 ON (OP.cod_cli_otro = bodega1.cod_bod)
	  INNER JOIN bodega ON bodega.cod_bod = bodega1.cod_bod_cli
	  LEFT JOIN usuario ON (OP.cod_usu_otro = usuario.cod_usu)  
	  WHERE ( OP.fec_otro >= '2014-12-04' AND OP.fec_otro <= '$fec_fin' ) AND cod_tpag_otro = 5 AND $busqueda";
	
	$db_ver->query($sql);
	$val_data2=0; 
 	while($db_ver->next_row()){ 
		$dbf = new Database();
		$sqlf = "SELECT * FROM m_factura 
		WHERE cod_fac = '$db_ver->cod_fac'"; 
		$dbf->query($sqlf);
		$dbf->next_row();
		if($dbf->tipo_pago == 'Credito'){
			$deducciones_credito += $db_ver->val_otro;
		}
		$val_data2 += $db_ver->val_otro;
	} ?>
  <tr>
    <td colspan="7" class="subtitulosproductos" align="center"><? 
	
	$val_otros = $val_con2 + $val_che + $val_data;
	$sql = "SELECT sum(credito_dmov) as total  FROM d_movimientos  
	INNER JOIN m_movimientos ON m_movimientos.cod_mov = d_movimientos.cod_mov
	INNER JOIN cuenta ON cuenta.cod_cuenta = d_movimientos.cuenta_dmov
	INNER JOIN centro_costos ON centro_costos.cod_centro = d_movimientos.centro_dmov
	WHERE  ( fec_emi >='2014-12-04' AND fec_emi <='$fec_fin' )  AND tipo_mov = 4 AND nivel = 5 AND estado_mov = 1 AND $busqueda2";
	$db_ver->query($sql);
	if ($db_ver->next_row()){ 
		$total_gastos2 = (double) $db_ver->total;		
	}
	
	$dbs = new Database();
	$sqls = "SELECT sum(credito_dmov) as total  FROM d_movimientos  
	INNER JOIN m_movimientos ON m_movimientos.cod_mov = d_movimientos.cod_mov
	INNER JOIN cuenta ON cuenta.cod_cuenta = d_movimientos.cuenta_dmov
	INNER JOIN centro_costos ON centro_costos.cod_centro = d_movimientos.centro_dmov
	WHERE  ( fec_emi >= '$fec_ini' AND fec_emi <= '$fec_fin' ) AND tipo_mov = 10 AND nivel = 5 AND estado_mov = 1 AND $busqueda2";
	$dbs->query($sqls);
	if ($dbs->next_row()){ 
		$total_saldos = (double) $dbs->total;		
	}
	
	$dbs = new Database();
	$sqls = "SELECT sum(credito_dmov) as total  FROM d_movimientos  
	INNER JOIN m_movimientos ON m_movimientos.cod_mov = d_movimientos.cod_mov
	INNER JOIN cuenta ON cuenta.cod_cuenta = d_movimientos.cuenta_dmov
	INNER JOIN centro_costos ON centro_costos.cod_centro = d_movimientos.centro_dmov
	WHERE  ( fec_emi >= '2014-12-04' AND fec_emi <= '$fec_fin' ) AND tipo_mov = 10 AND nivel = 5 AND estado_mov = 1 AND $busqueda2";
	$dbs->query($sqls);
	if ($dbs->next_row()){ 
		$total_saldos2 = (double) $dbs->total;		
	}
	
	//echo $total_gastos."==========";
	$total_con2 = $val_con2 + $consig_abonos2;
	$total_che2 = $val_che2 + $cheques_abonos2;
	$total_data2 = $val_data2 + $data_abonos2;
	$total_credito = $max_credito - $deducciones_credito;
	$efectivo2 =($total_fact_2 + $max_abono)-($total_credito)-($total_con2)-($total_che2)-($total_data2)-($nomina_abonos2)-($net_abonos2);
	$anterior = $efectivo2 - $efectivo;
	$contexto3.="<br>";
	$contexto3.="Saldo anterior:  &nbsp;&nbsp;&nbsp;$".number_format($anterior,0,".",".");
	$contexto3.="<br>";
	$contexto3.="Efectivo del periodo:  &nbsp;&nbsp;&nbsp;$".number_format($efectivo,0,".",".");
	$contexto3.="<br>";
	$contexto3.="Efectivo acumulado:  &nbsp;&nbsp;&nbsp;$".number_format($efectivo2,0,".",".");
	$contexto3.="<br>";
	$contexto3.="Gastos del Período: &nbsp;&nbsp;&nbsp;$".number_format($total_gastos,0,".",".");
	$contexto3.="<br>";
	$contexto3.="Gastos acumulados: &nbsp;&nbsp;&nbsp;$".number_format($total_gastos2,0,".",".");
	$contexto3.="<br>";
	$contexto3.="Saldos consignados del periodo: &nbsp;&nbsp;&nbsp;$".number_format($total_saldos,0,".",".");
	$contexto3.="<br>";
	$contexto3.="Saldos consignados acumulados:  &nbsp;&nbsp;&nbsp;$".number_format($total_saldos2,0,".",".");
	$contexto3.="<br>";
	$nuevo_saldo = $efectivo2 - $total_saldos2 - $total_gastos2;
	$contexto3.="Nuevo saldo:  &nbsp;&nbsp;&nbsp;$".number_format($nuevo_saldo,0,".",".");
	
	echo $contexto3;
	

	echo "<br>";
	echo "<br>";
	?></td>
  </tr>
</table>
<p>&nbsp; </p>
<script language="javascript">
function guardar_correo(){
	document.getElementById('guardar_todo').value=1
	document.enviar_correo.submit();
}
function abrir(id){
	window.open("ver_abono.php?codigo="+id,"ventana","menubar=0,resizable=1,width=700,height=400,toolbar=0,scrollbars=yes")
}
  </script>
</body>
</html>