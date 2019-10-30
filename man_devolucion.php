<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<?
	if($num_factura != 0){
				$db_edicion = new Database();
			    $sql = "select *  from m_factura WHERE cod_fac=$num_factura ";
			    $db_edicion->query($sql);	
				    if($db_edicion->next_row())
					{
					$fecha_venta = $db_edicion->fecha;
					$empresa = $db_edicion->cod_razon_fac;
					$cliente = $db_edicion->cod_cli;
					$bodega = $db_edicion->cod_bod;
					$codigo = $db_edicion->cod_fac;
					$total_fac = $db_edicion->tot_fac;
					$numero_fac = $db_edicion->num_fac;
					$var_edicion=0;
		            }
	}
?>
<?
if($guardar==1 and $codigo!=0) 	{ // RUTINA PARA  INSERTAT REGISTROS NUEVOS
	
	//INGRESO EN MAESTRA DE LA DEVOLUCION
	$campos="(fec_dev, cod_bod_dev, num_fac_dev ,val_del,cod_ven_dev, obs_dev)";
	$valores="('".date("Y-m-d")."','".$bodega_fac."','".$numero_factura."','".$todocompra."','".$global[2]."','".$observaciones."')" ; 
	$id=insertar_maestro("m_devolucion",$campos,$valores);

		$compos="(cod_mdev, cod_mfac_dev , cod_dfac_dev, cod_prod_ddev, cod_pes_ddev, cant_fac_dev, val_fac ,cant_ddev,total_ddev) ";
		for ($ii=1 ;  $ii <= $val_inicial + 1 ; $ii++) 
		{
			if($_POST["caja_cant_desc_".$ii] > 0)  
			{
				$val_total_dev_detalle=$_POST["costo_ref_".$ii] * $_POST["caja_cant_desc_".$ii];			
				$valores="('".$id."','".$codigo."','".$_POST["codigo_det_fac_".$ii]."','".$_POST["codigo_referencia_".$ii]."','".
				$_POST["codigo_peso_".$ii]."' ,'".$_POST["cantidad_ref_".$ii]."','".$_POST["costo_ref_".$ii]."','".$_POST[
				"caja_cant_desc_".$ii]."','".$val_total_dev_detalle."')";
				$error=insertar("d_devolucion",$compos,$valores); 
			}	
		}

	
	//DEVUELVE INVENTARIOS
	for ($ii=1 ;  $ii <= $val_inicial + 1 ; $ii++) 
	{	
		if($_POST["caja_cant_desc_".$ii] > 0)  

		{
			kardex("suma",$_POST["codigo_referencia_".$ii],$bodega_fac,$_POST["caja_cant_desc_".$ii],$_POST["cantidad_ref_".$ii],$_POST["codigo_peso_".$ii]);
		}
	}
	
	//DESCUENTO DE FACTURA O CARTERA
	$dbcar = new Database();
	$sqlcar = "SELECT * FROM m_factura
	WHERE cod_fac = $numero_factura";
	$dbcar->query($sqlcar);
	$dbcar->next_row();
	
	$devolucion=$dbcar->tot_dev_mfac;
	$valor_descuento = $todocompra + $devolucion;
	$campos="tot_dev_mfac='".$valor_descuento."'";
	editar("m_factura",$campos,"cod_fac",$numero_factura);
	
	//INGRESO DE LA ENTRADA
	$campos="(fec_ment,fac_ment,obs_ment,cod_bod,total_ment,cod_prove_ment,usu_ment,estado_m_entrada)";
	$valores="('".date('Y-m-d')."','','DEVOLUCION FACTURA No "."$fac_num','".$bodega_fac."','".$todocompra."','".$proveedor."','".$global[2]."','1')" ; 
	$ins_id=insertar_maestro("m_entrada",$campos,$valores); 	
	
	if ($ins_id > 0) 
	{
		$campos="(cod_ment_dent,cod_tpro_dent,cod_mar_dent,cod_pes_dent,cod_ref_dent,cant_dent,cos_dent)";
		
		for ($ii=1 ;  $ii <= $val_inicial + 1 ; $ii++) 
		{
			if($_POST["caja_cant_desc_".$ii] > 0) 
			{
				$valores="('".$ins_id."','".$_POST["codigo_tipo_prodcuto_".$ii]."','".$_POST["codigo_marca_".$ii]."','".$_POST["codigo_peso_".$ii]."','".$_POST["codigo_referencia_".$ii]."','".$_POST["caja_cant_desc_".$ii]."','".$_POST["caja_cant_desc_".$ii]."')" ;
				$error=insertar("d_entrada",$campos,$valores); 
			}	
		}
			
		header("Location: con_devolucion.php?confirmacion=1&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
	}

else
	echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>" ; 	
	
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.Estilo1 {font-size: 12px}
</style> 

<? inicio() ?>

<script language="javascript">
function crear_descuento(div_caja,caja,val_uni,span_desc,boton_crear, div_guardar){
document.getElementById(boton_crear).style.display='none';
document.getElementById(div_guardar).style.display='inline';
document.getElementById(span_desc).style.display='none';
document.getElementById(div_caja).style.display='inline';
//crear_descuento(\"div_caja_desc_$jj\",\"caja_cant_desc_$jj\",\"$dbdatos_1->val_uni\",\"span_crear_desc_$jj\",\"boton_desc_$jj\")
}

function guardar_descuento(div_caja,caja,val_uni,span_desc,boton_crear, div_guardar,cant_ori,letrero_span){

if ( parseInt(document.getElementById(cant_ori).value) < parseInt(document.getElementById(caja).value) ){
	alert('Cantidad No permitida, Verifique')
	return false;
}

document.getElementById("todocompra").value = parseInt(document.getElementById("todocompra").value) - parseInt((val_uni * document.getElementById(letrero_span).value) );
document.getElementById(boton_crear).style.display='inline';
document.getElementById(div_guardar).style.display='none';
document.getElementById(span_desc).style.display='inline';
document.getElementById(div_caja).style.display='none';
document.getElementById(letrero_span).value=document.getElementById(caja).value;

document.getElementById("todocompra").value = parseInt(document.getElementById("todocompra").value) + parseInt((val_uni * document.getElementById(letrero_span).value));
}

function datos_completos(){ 


	if( document.getElementById('todocompra').value < -1) {
		alert('No Valor en la Devolucion')
		return false;
	}
	
		
	if (document.getElementById('todocompra').value == ""){	
		return false;
	}
	
	else 
		return true;

}
</script>

<script type="text/javascript" src="js/js.js"></script>
<link href="css/stylesforms.css" rel="stylesheet" type="text/css" />
<link href="css/styles2.css" rel="stylesheet" type="text/css" />
<link href="informes/styles.css" rel="stylesheet" type="text/css" />
</head>
<body <?=$sis?>>
<div id="total">
<form  name="forma" id="forma" action="man_devolucion.php"  method="post">
<table width="750" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
   <td bgcolor="#D1D8DE" >
   <table width="100%" height="30" border="0" cellspacing="0" cellpadding="0" align="center" > 
      <tr>
        <td width="5" height="30">&nbsp;</td>
        <td width="20" ><img src="imagenes/icoguardar.png" alt="Nueno Registro" width="16" height="16" border="0" onClick="cambio_guardar()" style="cursor:pointer"/></td>
        <td width="61" class="ctablaform">Guardar</td>
        <td width="21" class="ctablaform"><a href="con_cargue.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/cancel.png" alt="Cancelar" width="16" height="16" border="0" /></a></td>
        <td width="65" class="ctablaform">Cancelar </td>
        <td width="22" class="ctablaform"><a href="con_cargue.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/iconolupa.gif" alt="Buscar" width="16" height="16" border="0" /></a></td>
        <td width="70" class="ctablaform">Consultar</td>
        <td width="21" class="ctablaform"></td>
        <td width="60" class="ctablaform">&nbsp;</td>
        <td width="24" valign="middle" class="ctablaform">&nbsp;</td>
        <td width="193" valign="middle">

          <input type="hidden" name="editar"   id="editar"   value="<?=$editar?>">
		  <input type="hidden" name="insertar" id="insertar" value="<?=$insertar?>">
		  <input type="hidden" name="eliminar" id="eliminar" value="<?=$eliminar?>">
          <input type="hidden" name="codigo" id="codigo" value="<?=$codigo?>" /> </td>
        
        <td width="67" valign="middle">&nbsp;</td>
      </tr>
    </table>
	</td>
  </tr>
  <tr>
    <td height="4" valign="bottom"><img src="imagenes/lineasup3.gif" alt="." width="100%" height="4" /></td>
  </tr>
  <tr>
    <td class="textotabla01">DEVOLUCION:<span class="subtitulosproductos">
      <input name="fac_num" id="fac_num" type="hidden" value="<?=$numero_fac?>"  />
    </span></td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td width="62" class="textotabla1">Fecha:</td>
        <td width="275" class="subtitulosproductos"><?=$fecha_venta?>
          <input name="fecha_fac" id="fecha_fac" type="hidden" value="<?=$fecha_venta?>"  /></td>
        <td width="20" class="textorojo">*</td>
        <td width="77" class="textotabla1">Vendedor:</td>
        <td width="145"  class="subtitulosproductos">
		<?
		 echo $global[3];
		
		?>
		<input name="vendedor" id="vendedor" type="hidden" value="<?=$global[2]?>"></td>		 
        <td width="171" class="textorojo">&nbsp;</td>
       </tr>
	   <tr>
        <td width="62" class="textotabla1">Empresa:</td>
        <td width="275" class="subtitulosproductos">
		<?
		 $sql ="SELECT * from rsocial where cod_rso=$empresa";
	$db->query($sql);
	while($db->next_row()){
		echo $db->nom_rso;
	}
		?>
		<input name="empresa" id="empresa" type="hidden" value="<?=$empresa?>">
        <input name="num_fac" id="num_fac" type="hidden" value="<?=$numero?>"></td>
        <td width="20" class="textorojo">*</td>
        <td width="77" class="textotabla1"> Bodega:</td>
        <td class="subtitulosproductos"><span class="textoproductos1">
          <?
		$sql ="SELECT * from bodega where cod_bod=$bodega";
	$db->query($sql);
	while($db->next_row()){
		echo $db->nom_bod;
	}
		?>
          <input name="bodega_fac" id="bodega_fac" type="hidden" value="<?=$bodega?>">
        </span></td>		 
        <td width="171" >
			<input name="numero_factura" id="numero_factura" type="hidden" value="<?=$num_factura?>" />
		</td>
       </tr>
	   <tr>
        <td width="62" class="textotabla1">Cliente:</td>
        <td width="275" class="subtitulosproductos">
		<?
	$sql ="SELECT * from bodega1 where cod_bod=$cliente";
	$db->query($sql);
	while($db->next_row())
	{
		echo $db->nom_bod;
		$cupo_covinoc=$db->cupo_au_covinoc;
		$regimen=$db->regimen_cli;
		$lista_p=$db->cod_lista ;
	}
	
	$sql ="SELECT SUM(((SELECT SUM(total_pro) FROM d_factura WHERE cod_mfac=m_factura.cod_fac)- valor_abono )) AS cartera 
	FROM m_factura
	INNER JOIN cartera_factura ON cartera_factura.cod_fac= m_factura.cod_fac
	WHERE  tipo_pago='Credito'   AND estado_car<>'CANCELADA' AND cod_cli=$cliente";
	$db->query($sql);
	while($db->next_row())
	{
		
		$cartera_ocupada=$db->cartera;
	}
	
	
	$cupo_covinoc=$cupo_covinoc-$cartera_ocupada;
		?>
		<input name="cliente_fac" id="cliente_fac" type="hidden" value="<?=$cliente?>">
		<input name="regimen_cli" id="regimen_cli" type="hidden" value="<?=$regimen?>">
			<input name="lista_p" id="lista_p" type="hidden" value="<?=$lista_p?>"></td>
        <td width="20" class="textorojo">*</td>
        <td width="77" class="textotabla1">&nbsp;</td>
        <td colspan="2"><div id="cupo" style="display:none">
          <span class="textotabla1">Cupo:<span class="textorojo">
          <input name="cupo_credito" id="cupo_credito" type="text" class="caja_resalte1"  readonly="-1"/>
          </span></span>		  </div>		  
		<span  id="div_credito" style="display:none" class="textoproductos1"> 
		$  <?=number_format($cupo_covinoc ,0,",",".")?>
		<input name="cupo_covinoc" type="hidden" id="cupo_covinoc"  value="<?=$cupo_covinoc?>" readonly="-1" align="right"/>
		</span></td>		 
        </tr>
	   <tr>
        <td colspan="7" class="textotabla1" >
		<table  width="100%" border="1">
		      
		  <tr >
		  <td width="4%">
			  <table width="100%">
				<tr id="fila_0">
				
				<td width="20%"  class="ctablasup">Referencia</td>
				<td width="13%"  class="ctablasup">Codigo</td>
				<td width="9%"   class="ctablasup">Talla</td>
				<td width="10%"  class="ctablasup">Cantidad</td>
				<td width="7%"  class="ctablasup">Valor</td>
				<td width="13%"   class="ctablasup">Opcion</td>
				<td width="21%"    class="ctablasup">Cantidad Devolucion</td>
				
				</tr>
				<?
				if ($codigo!="") { // BUSCAR DATOS
					$sql ="select * from d_factura 
					left join tipo_producto on d_factura.cod_tpro=tipo_producto.cod_tpro
					left join marca on d_factura.cod_cat=marca.cod_mar left join peso on d_factura.cod_peso= peso.cod_pes 
					left join producto  on d_factura.cod_pro= producto.cod_pro 
					left join referencia ON referencia.cod_ref = producto.nom_pro
					where cod_mfac =$codigo order by cod_dfac ";//=		
					$dbdatos_1= new  Database();
					$dbdatos_1->query($sql);
					$jj=1;
					//echo "<table width='100%'>";
					
					//busca el valor ya descontado en la devolucion
					$dbdatos_buscar= new  Database();
					//busca el valor ya descontado en la devolucion
					
					while($dbdatos_1->next_row()){ 
						echo "<tr id='fila_$jj'>";
						
						//referencia
						echo "<td> 
						<INPUT type='hidden'  name='codigo_mfac_$jj' id='codigo_mfac_$jj' value='$codigo'> 
						<INPUT type='hidden'  name='codigo_tipo_prodcuto_$jj' id='codigo_tipo_prodcuto_$jj' value='$dbdatos_1->cod_tpro'> 
						<INPUT type='hidden'  name='codigo_marca_$jj' id='codigo_marca_$jj' value='$dbdatos_1->cod_mar'>
						<INPUT type='hidden'  name='codigo_det_fac_$jj' id='codigo_det_fac_$jj' value='$dbdatos_1->cod_dfac'> 
						<INPUT type='hidden'  name='codigo_referencia_$jj'  id='codigo_referencia_$jj' value='$dbdatos_1->cod_pro'>
						<span  class='textfield01'> $dbdatos_1->desc_ref</span> </td>";
						
						
						
						//% codigo barra
						echo "<td ><span  class='textfield01'> $dbdatos_1->cod_fry_pro </span> </td>";
						
						//talla
						echo "<td>
						<INPUT type='hidden'  name='codigo_peso_$jj' id='codigo_peso_$jj' value='$dbdatos_1->cod_peso'>
						<span  class='textfield01'> $dbdatos_1->nom_pes </span> </td>";
						
						//cantidad
						echo "<td align='right'>
						<INPUT type='hidden'  name='cantidad_ref_$jj'  id='cantidad_ref_$jj' value='$dbdatos_1->cant_pro'>
						<span  class='textfield01'>".number_format($dbdatos_1->cant_pro ,0,",",".")."  </span> </td>";	
						
						//costo
						echo "<td align='right'>
						<INPUT type='hidden'  name='costo_ref_$jj' id='costo_ref_$jj' value='$dbdatos_1->val_uni'>
						<span  class='textfield01'>".number_format($dbdatos_1->total_pro ,0,",",".")."  </span> </td>";	
						
						echo "<td>
						<div align='center'>	
<INPUT type='button' class='botones' value='Descontar' id='boton_desc_$jj' onclick='crear_descuento(\"div_caja_desc_$jj\",\"caja_cant_desc_$jj\",\"$dbdatos_1->val_uni\",\"span_crear_desc_$jj\",\"boton_desc_$jj\",\"div_actua_$jj\" );'>
						</div>
						
						<div align='center' style='display:none' id='div_actua_$jj'>	
<INPUT type='button' class='botones' value='Actualizar' id='boton_actua_$jj' onclick='guardar_descuento(\"div_caja_desc_$jj\",\"caja_cant_desc_$jj\",\"$dbdatos_1->val_uni\",\"span_crear_desc_$jj\",\"boton_desc_$jj\",\"div_actua_$jj\" ,\"cantidad_ref_$jj\" ,\"valor_span_$jj\" );'> 
						</div>
						</td>";
						
						//cajita para el descuento
						if($var_edicion==1) {
							$sql="select cant_ddev from d_devolucion where cod_mfac_dev=$codigo  and cod_dfac_dev=$dbdatos_1->cod_dfac  and cod_prod_ddev=$dbdatos_1->cod_pro and  cod_pes_ddev=$dbdatos_1->cod_peso ";
							$dbdatos_edi->query($sql);		
							if($dbdatos_edi->next_row()){
								$valor_cantidad_dev=$dbdatos_edi->cant_ddev;	
							}
							
						}
						else  {
							$valor_cantidad_dev =0;
						}
						echo "<td>
								<table width='100%'>
									<tr>
										<td >
										 	<div style='display:none' id='div_caja_desc_$jj'>
										 		<input name='caja_cant_desc_$jj' id='caja_cant_desc_$jj' type='text' class='textfield01'  value='$valor_cantidad_dev'   onkeypress='return validaInt(this)' />
										 	</div>
										 </td>
										 <td  style='display:inline'>
										 	<div id='span_crear_desc_$jj' >
											<input name='valor_span_$jj' id='valor_span_$jj' type='text' class='botones'  value='$valor_cantidad_dev' readonly='-1'/>
											</div>
										 </td>
									</tr>
								</table>
						 </td>";
						 
						//boton q quita la fila
						echo "</tr>";
						$jj++;
					}
				}
				?>
				</table>			</td>
			</tr>			
		 <tr >
		  <td>
			  <table width="100%">
				<tr >
				<td  class="ctablasup"><div align="left">Observaciones:</div></td>
				<td  class="ctablasup"><div align="right">Resumen Venta </div></td>
				</tr>
				<tr >
				<td width="47%" ><div align="left" >
				  <textarea name="observaciones" cols="45" rows="3" class="textfield02"><?=$obs?></textarea>
				</div>				  </td>
				<td width="53%" ><div align="right"><span class="ctablasup">Total  Descuento:</span>
 <input name="todocompra" id="todocompra" type="text" class="textfield01" readonly="1"  value="<? if (empty($total_devolucion)) echo "0"; else echo $total_devolucion; ?> "/>
				  </div></td>
				</tr>
				</table>			  </td>
			</tr>
		</table>
		  </table>
		  
	    </td>
	  </tr>
	  <tr> 
		  <td colspan="8" >		  </td>
	  </tr>
    </table>
<tr>

  <tr>
    <td>
	<input type="hidden" name="val_inicial" id="val_inicial" value="<? if($codigo!=0) echo $jj-1; else echo "0"; ?>" />
	<input type="hidden" name="guardar" id="guardar" />
		 <?  if ($codigo!="") $valueInicial = $aa; else $valueInicial = "1";?>
	   <input type="hidden" id="valDoc_inicial" value="<?=$valueInicial?>"> 
	   <input type="hidden" name="cant_items" id="cant_items" value=" <?  if ($codigo!="") echo $aa; else echo "0"; ?>">
	</td>
  </tr>
  
</table>
</form> 
</div>
</body>
</html>
