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
</script>
</head>
<body>
<?
if($guardar_todo==1) {

	$texto=$contexto."<br>Observaciones".$comentario;
	$sql="select cod_usu, nom_usu from usuario ";
	$dbdatos= new  Database();
	$dbdatos->query($sql);
	if ($dbdatos->next_row())
		$nombre=$dbdatos->nom_usu;
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
			usuario.nom_usu as responsable,
			bodega1.nom_bod as cliente
		 FROM m_factura  
		 INNER JOIN bodega1 ON  (bodega1.cod_bod = m_factura.cod_cli) 
		 LEFT JOIN usuario ON ( usuario.cod_usu=m_factura.cod_usu) 
		 WHERE  ( fecha >='$fec_ini' AND fecha <='$fec_fin' )  
		 AND estado IS  NULL $where_cli  ";
	$db_ver->query($sql);	
	$cantidad_max=0;
	
	//echo $fec_ini_mas=$fec_ini;
	//echo $fec_fin_mas=$fec_fin;
?>
<table width="731" border="1" cellpadding="2" cellspacing="1"   class="textoproductos1" align="center">
  <tr>
    <td colspan="8" class="ctablasup" align="center">CUENTA CAJA</td>
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
		  <td width="32%" class="textoproductos1"><?=$db_ver->cliente; ?></td>
		  <td width="10%" colspan="3" class="textoproductos1" align="right"><?=number_format($valor,0,".",".")?></td>
		</tr>
		<?
		$cantidad_max += $valor;
		$total_fact_1 += $valor;
	} 	
	//echo $total_fact_1;
	?>
  </tbody>
  <tr>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos" align="right"><b>TOTAL FACTURACION: </b></td>
    <td colspan="3" class="subtitulosproductos" align="right"><b>
      <?=number_format($cantidad_max,0,".",".")?>
      </b></td>
  </tr>
  <tr>
    <td align="center" colspan="8"><input name="button2" type="button" class="botones1"  id="btn_fac" onClick="ver_facturacion('facturacion','btn_fac')" value="Ver Detalles" /></td>
  </tr>
  <?
	$sql = "SELECT  
	m_factura.num_fac, m_factura.fecha, m_devolucion.val_del,
	bodega1.nom_bod as cliente, 
	usuario.nom_usu 
	FROM m_devolucion 
	INNER JOIN m_factura ON m_devolucion.num_fac_dev = m_factura.cod_fac 
	INNER JOIN bodega1 ON m_factura.cod_cli = bodega1.cod_bod 
	LEFT JOIN usuario ON m_devolucion.cod_ven_dev = usuario.cod_usu 
	WHERE ( fecha >='$fec_ini' AND fecha <='$fec_fin' ) AND estado IS  NULL $where_cli  ";
	
	$db_ver->query($sql);	
?>
  <tr>
    <td colspan="7" class="ctablasup" align="center">DEVOLUCIONES</td>
  </tr>
   <tr>
    <td colspan="7" align="center"><br></td>
  </tr>
  <tbody colspan="7" id="facturacion1" style="display:none" width= "100%">
   <tr>
    <td class="subtitulosproductos">Responsable</td>
    <td width="17%" class="subtitulosproductos">Factura No. </td>
    <td class="subtitulosproductos">Fecha</td>
    <td class="subtitulosproductos">Cliente</td>
    <td colspan="3" class="subtitulosproductos">Valor</td>
  </tr>
    <? 
	$total_dev=0;
	$max_total=0;
	while($db_ver->next_row()){ 
		?>
		<tr>
		  <td class="textoproductos1"><?=$db_ver->nom_usu?></td>
		  <td class="textoproductos1"><?=$db_ver->num_fac?></td>
		  <td width="11%" class="textoproductos1"><?=$db_ver->fecha?></td>
		  <td width="32%" class="textoproductos1"><?=$db_ver->cliente?></td>
		  <td width="32%" colspan="3" class="textoproductos1" align="right"><?=number_format($db_ver->val_del,0,".",".")?></td>
		</tr>
		<?
		$total_dev += $db_ver->val_del;
		$max_total += $db_ver->val_del;
	} ?>
  </tbody>
  <tr>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos" align="right"><b>TOTAL DEVOLUCIONES:</b></td>
    <td colspan="3" class="subtitulosproductos" align="right"><b><?=number_format($total_dev,0,".",".")?></b></td>
  </tr>
   <tr>
    <td colspan="7" align="center"><br></td>
  </tr>
  <tr>
    <td align="center" colspan="8"><input name="button2" type="button" class="botones1"  id="btn_fac1" onClick="ver_facturacion('facturacion1','btn_fac1')" value="Ver Detalles" /></td>
  </tr> 
  <tbody  colspan="7" id="abonos" style="display:none"  width="100%">
   <tr>
    <td class="subtitulosproductos">Responsable</td>
    <td class="subtitulosproductos">Abono No. </td>
    <td class="subtitulosproductos">Fecha</td>
    <td class="subtitulosproductos">Cliente</td>
    <td colspan="3" class="subtitulosproductos">Valor</td>
  </tr>
    <? 
	$sql = "SELECT 
	abono.cod_abo, abono.fec_abo, abono.val_abo, 
	bodega1.nom_bod  AS bodega1, 
	usuario.nom_usu as responsable   
	FROM abono  
	INNER JOIN bodega1 ON abono.cod_bod_Abo = bodega1.cod_bod  
	INNER JOIN usuario ON usuario.cod_usu = abono.cod_usu_abo
	WHERE  (abono.fec_abo >='$fec_ini'   AND  abono.fec_abo <='$fec_fin')  $where_cli ";
	
	$db_ver->query($sql);
	$max_abono=0; 
 	while($db_ver->next_row()){ 
		?>
		<tr>
		  <td class="textoproductos1"><?=$db_ver->responsable?></td>
		  <td class="textoproductos1"><?=$db_ver->cod_abo?></td>
		  <td width="11%" class="textoproductos1"><?=$db_ver->fec_abo?></td>
		  <td width="32%" class="textoproductos1"><?=$db_ver->bodega1?></td>
		  <td width="32%" colspan="3" class="textoproductos1" align="right"><?=number_format($db_ver->val_abo,0,".",".")?></td>
		</tr>
		<?
		$max_abono += $db_ver->val_abo;
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
    <td colspan="7" align="center"><br></td>
  </tr>
  
  <tbody  colspan="7" id="abonos" style="display:none"  width="100%">
  <tr>
    <td colspan="2" class="subtitulosproductos">Responsable</td>
    <td class="subtitulosproductos">Fecha</td>
    <td class="subtitulosproductos">Cliente</td>
    <td colspan="3" class="subtitulosproductos">Valor</td>
  </tr>
    <? 
	$sql = "SELECT 
	  OP.fec_otro,
	  OP.val_otro,
	  bodega1.nom_bod,
	  usuario.nom_usu
	  FROM
	  otros_pagos  OP 
	  LEFT JOIN bodega1 ON (OP.cod_cli_otro = bodega1.cod_bod)
	  LEFT JOIN usuario ON (OP.cod_usu_otro = usuario.cod_usu)  
	  WHERE  ( OP.fec_otro >='$fec_ini'   AND OP.fec_otro <='$fec_fin' ) ";
	  
	$db_ver->query($sql);
	$val_otros=0; 
	while($db_ver->next_row()){ 
		?>
		<tr>
		  <td colspan="2" class="textoproductos1"><?=$db_ver->nom_usu?></td>
		  <td width="11%" class="textoproductos1"><?=$db_ver->fec_otro?></td>
		  <td width="32%" class="textoproductos1"><?=$db_ver->nom_bod?></td>
		  <td width="32%" colspan="3" class="textoproductos1" align="right"><?=number_format($db_ver->val_otro,0,".",".")?></td>
		</tr>
		<?
		$val_otros += $db_ver->val_otro;
  	} ?>
  </tbody>
  <tr>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos">&nbsp;</td>
    <td class="subtitulosproductos" align="right"><b>TOTAL OTROS PAGOS:</b></td>
    <td colspan="3" class="subtitulosproductos" align="right"><b><?=number_format($val_otros,0,".",".")?></b></td>
  </tr>
  <tr>
    <td align="center" colspan="8"><input name="button2" type="button" class="botones1"  id="btn_abo" onClick="ver_facturacion('abonos','btn_abo')" value="Ver Detalles" /></td>
  </tr>
  <tr>
    <td colspan="7" class="subtitulosproductos" align="center"><? 
	
	$sql = "SELECT sum(val_res) as total  FROM resumen_gastos  WHERE  ( fec_res >='$fec_ini' AND fec_res <='$fec_fin' )  and resp_res=$global[2]";
	$db_ver->query($sql);
	if ($db_ver->next_row()){ 
		$total_gastos=$db_ver->total;		
	}
	//echo $total_gastos."==========";
	
	$contexto.=$dato_total="Total  Facturación del período de  &nbsp; $fec_ini  &nbsp;  al     &nbsp;$fec_fin por:     &nbsp;&nbsp; $".number_format($total_fact_1,0,".",".");
	$contexto.="<br>";
	$contexto.="Total Facturacion Crédito: &nbsp;&nbsp;&nbsp;&nbsp;$".number_format($max_credito,0,".",".");
	$contexto.="<br>";
	$contexto.="Total Facturación Contado: &nbsp;&nbsp;&nbsp;$".number_format($max_contado,0,".",".");
	$contexto.="<br>";
	$contexto.="Total Abonos: &nbsp;&nbsp;&nbsp;$".number_format($max_abono,0,".",".");
	$contexto.="<br>";
	$contexto.="Total Otros pagos del Período: &nbsp;&nbsp;&nbsp;$".number_format($val_otros,0,".",".");
	$contexto.="<br>";
	$contexto.="Total Gastos del Período: &nbsp;&nbsp;&nbsp;$".number_format($total_gastos,0,".",".");
	$gran_total=($max_contado+ $max_abono)-($total_gastos)-($val_otros);
	$contexto.="<br>";
	$contexto.="Total Efectivo del Período:  &nbsp;&nbsp;&nbsp;$".number_format($gran_total,0,".",".");
	
	echo $contexto;
	
//echo "<input type='text' name='total_efectivo' value='".number_format($max_contado+ $max1,0,".",".")."'>";
//	echo $contexto;
//	echo $dato_total="Total  Facturacion del periodo de  &nbsp; $fec_ini  &nbsp;  al     &nbsp;$fec_fin por:     &nbsp;&nbsp; $".number_format($max,0,".",".");

	echo "<br>";
	
	//number_format($max_contado+$max1,0,".",".");
//	echo "<br>";
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
              <input type="hidden"  value="0" name="guardar_todo"/>
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
        <input name="button" type="button" class="botones1"  onClick="window.print()" value="Imprimir" />
        <input name="button2" type="button" class="botones1"  onClick="window.close()" value="Cerrar" />
        <input type="hidden" name="mapa" value="<?=$mapa?>"></td>
    </tr>
  </form>
</table>
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