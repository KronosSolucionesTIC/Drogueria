<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<?	
$empresa = 19;
if($guardar==1 and $codigo==0) 
	
	{ // RUTINA PARA  INSERTAR REGISTROS NUEVOS	
	$db6 = new Database();
	$sql = "select num_fac_rso + 1  as  num_factura from rsocial WHERE cod_rso=$empresa ";
	$db6->query($sql);
	
	if($db6->next_row())
	$num_factura = $db6->num_factura;
	
	//validacion de existencia del numero de factura
	$db7 = new Database();
	$sql = "select cod_fac from m_factura WHERE num_fac = $num_factura and cod_razon_fac=$empresa ";
	$db7->query($sql);
	
	if ($db7->num_rows() >0) {
		$num_factura +=1;
	}
		
	//ACTUALIZA LA ULTIMA FACTURA
	$db2 = new Database();
	$sql = "UPDATE rsocial SET num_fac_rso = $num_factura  WHERE  cod_rso=$empresa";
	$db2->query($sql);	
		
	if(($Credito=="")and($cheque=="")) $tipo_pago="Contado"; else  $tipo_pago="Credito";
 
	$compos="(cod_usu, cod_cli,fecha,num_fac,cod_razon_fac,tipo_pago,obs,tot_fac,cod_bod)";
	$valores="('".$global[2]."','".$_POST["cliente"]."','".date('Y-m-d')."','".$num_factura."','".$empresa."','".$tipo_pago."','".$observaciones."','".$total_val."','225')" ;
	
	$ins_id=insertar_maestro("m_factura",$compos,$valores); 
	
	
// copia espejo

    $cliente_facs = $_POST["cliente"];  	
	
	$sql ="SELECT cod_lista from bodega1 where cod_bod=$cliente_facs";
	$dbdatos_cli= new  Database();
	$dbdatos_cli->query($sql);
	$dbdatos_cli->next_row();
	$lista=$dbdatos_cli->cod_lista;

    if ($regimen_cli=='COMUN')
    {
	$ins_ides=insertar_maestro("m_factura_esp",$compos,$valores); 
	$db88 = new Database();
	$sql88 = "UPDATE m_factura_esp SET cod_fac_m=$ins_id  WHERE  cod_fac=$ins_ides";
	$db88->query($sql88);
	
	}
		
	if ($regimen_cli=='SIMPLIFICADO' && $lista==21)
    {
	$ins_ido=insertar_maestro("m_factura_esp",$compos,$valores); 
		$db88 = new Database();
	    $sql88 = "UPDATE m_factura_esp SET cod_fac_m=$ins_id  WHERE  cod_fac=$ins_ido";
	    $db88->query($sql88);
	}
	
    if ($regimen_cli=='SIMPLIFICADO' && $lista!=21)
    { 	         
	$ins_idet=insertar_maestro("m_factura_esp",$compos,$valores);
		$db88 = new Database();
		$sql88 = "UPDATE m_factura_esp SET cod_fac_m=$ins_id  WHERE  cod_fac=$ins_idet";
		$db88->query($sql88);	
    }      
 
		
	if($tipo_pago != 'Contado' ) 
	{
		$sql = "INSERT INTO cartera_factura ( fec_car_fac, cod_fac) VALUES( '$fecha_fac', '$ins_id');";
		$db2->query($sql);	
	}
		
		//INSERCION DE LOS OTROS PAGOS
		for ($i=1; $i<=5; $i++){
			if(($i != 1)and($i != 2)){
				$valor_otro = $_POST["pago_".$i];
				if($i == 4){
				$cod_cuenta = $_POST["cuenta"];
				$dbc = new Database();
				$sqlc = "SELECT desc_cuenta FROM cuenta 
				WHERE cod_cuenta = '$cod_cuenta'";
				$dbc->query($sqlc);
				$dbc->next_row();
				$obs = "$dbc->desc_cuenta";
				}
				if($valor_otro > 0){
					$compos="(cod_usu_otro,fec_otro,cod_cli_otro,obs_otro,val_otro,cod_tpag_otro,cod_fac)";
					$valores="('".$global[2]."','".date('Y-m-d')."','".$_POST["cliente"]."','".$observaciones."','".$valor_otro."','".$i."','".$ins_id."')" ;

					$error=insertar("otros_pagos",$compos,$valores); 
				}
			}
		}
	
	
	
		//INGRESO EN DETALLE DE FACTURA	
		$campos="(cod_mfac,cod_tpro,cod_cat,cod_peso,cod_pro,cant_pro,val_uni,total_pro) ";
		
		for ($i=0; $i<= $_POST["inicial"]-1; $i++){
			if($_POST["cant_".$i]>0){
				$total_pro = $_POST["cant_".$i] * $_POST["prec_".$i];
				$valores="('".$ins_id."','".$_POST["tpro_".$i]."','".$_POST["mpro_".$i]."','1','".$_POST["cod_".$i]."','".$_POST["cant_".$i]."','".$_POST["prec_".$i]."','".$total_pro."')" ;
				$error=insertar("d_factura",$campos,$valores); 
				kardex("resta",$_POST["cod_".$i],$dbb->cod_bod,$_POST["cant_".$i],$_POST["cant_".$i],1);
			}
	
	if($ins_id > 0){
		header("Location: blaus/informes/ver_factura_v.php?codigo=$ins_id"); 
	}
	
	else
		echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>"; 	

    }
}

/*}//fin espejo*/


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Facturaciòn</title>
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
<?
$dbdatos_cliente= new  Database();
$sql=" select cod_lista from bodega1  where cod_bod=230"; 
$dbdatos_cliente->query($sql);
if($dbdatos_cliente->next_row()){
	$codigo_lista_cliente=$dbdatos_cliente->cod_lista;
}
?>

<script language="javascript">
function aumenta(contador){
	if(parseInt(document.getElementById('cant_'+contador).value) < parseInt(document.getElementById('exist_'+contador).value)){
		num = document.getElementById('cant_'+contador).value;
		precio = document.getElementById('prec_'+contador).value;
		precio = parseInt(precio);
		num = parseInt(num);
		num = num + 1;
		total_uni = 1 * precio;
		total_uni_cant = num * precio;
		tot = document.getElementById('total_val').value;
		tot = parseInt(tot);
		total_fac = tot + total_uni;
		document.getElementById('cant_'+contador).value = num;
		var divContainerFac = document.getElementById('div_fac_'+contador);
		divContainerFac.innerHTML = num;
		var divContainerVal = document.getElementById('div_val_'+contador);
		divContainerVal.innerHTML = total_uni_cant ;
		document.getElementById('total_val').value = total_fac;
	}
	else{
		alert('La cantidad supera las existencias');
		return false;
	}
}

function disminuya(contador){
	if(parseInt(document.getElementById('cant_'+contador).value) != 0){
		num = document.getElementById('cant_'+contador).value;
		num = parseInt(num);
		precio = document.getElementById('prec_'+contador).value;
		precio = parseInt(precio);
		num = num - 1;
		total_uni = 1 * precio;
		total_uni_cant = num * precio;
		tot = document.getElementById('total_val').value;
		tot = parseInt(tot);
		total_fac = tot - total_uni;
		document.getElementById('cant_'+contador).value = num;
		var divContainerFac = document.getElementById('div_fac_'+contador);
		divContainerFac.innerHTML = num;
		var divContainerVal = document.getElementById('div_val_'+contador);
		divContainerVal.innerHTML = total_uni_cant ;
		document.getElementById('total_val').value = total_fac;
	}
}

//CARGA EL CREDITO DEL CLIENTE
function cargar_credito(cliente){
<?
		$sqltp ="SELECT cod_bod,cupo_au_covinoc FROM bodega1";		
		$dbtp= new  Database();
		$dbtp->query($sqltp);
		while($dbtp->next_row()){ 
		echo "if(document.getElementById(cliente).value==$dbtp->cod_bod) {";
		echo "cupo = '$dbtp->cupo_au_covinoc' ;";
		echo "} ";
		}		
?>

<?
		$db= new  Database();
		$sql ="SELECT cod_cli,SUM(total_pro) credito FROM d_factura 
		INNER JOIN m_factura ON m_factura.cod_fac = d_factura.cod_mfac
		INNER JOIN cartera_factura ON cartera_factura.cod_fac= m_factura.cod_fac
		WHERE  tipo_pago='Credito' AND estado_car<>'CANCELADA'
		GROUP BY cod_cli";
		$db->query($sql);
		while($db->next_row()){
		echo "if(document.getElementById(cliente).value==$db->cod_cli){";
		echo "credito = '$db->credito' ;";
		echo "} ";
		}
?>

<?
		$dba= new  Database();
		$sqla ="SELECT cod_cli,valor_abono AS abono FROM d_factura 
		INNER JOIN m_factura ON m_factura.cod_fac = d_factura.cod_mfac
		INNER JOIN cartera_factura ON cartera_factura.cod_fac= m_factura.cod_fac
		WHERE  tipo_pago='Credito' AND estado_car<>'CANCELADA'
		GROUP BY cod_cli";
		$dba->query($sqla);
		while($dba->next_row()){
		echo "if(document.getElementById(cliente).value==$dba->cod_cli){";
		echo "abono = '$dba->abono' ;";
		echo "} ";
		}
?>

<?
		$dbd= new  Database();
		$sqld ="SELECT cod_cli,SUM(tot_dev_mfac) AS devolucion FROM d_factura 
		INNER JOIN m_factura ON m_factura.cod_fac = d_factura.cod_mfac
		INNER JOIN cartera_factura ON cartera_factura.cod_fac= m_factura.cod_fac
		WHERE  tipo_pago='Credito' AND estado_car<>'CANCELADA'
		GROUP BY cod_cli";
		$dbd->query($sqld);
		while($dbd->next_row()){
		echo "if(document.getElementById(cliente).value==$dbd->cod_cli){";
		echo "devolucion = '$dbd->devolucion' ;";
		echo "} ";
		}
?>
	cupo = parseInt(cupo);
	credito = parseInt(credito);
	abono = parseInt(abono);
	devolucion = parseInt(devolucion);
	dispo = cupo - (credito - abono) - devolucion;
	document.getElementById('cupo_covinoc').value = dispo ;
}

function datos_completos(){ 

	if(document.getElementById("Credito").checked==true &&  parseInt(document.getElementById('cupo_covinoc').value) <= parseInt(document.getElementById('total_val').value) ) {
		alert('No hay Cupo para esta Compra')
		return false;
	}
	
	if(document.getElementById("Credito").checked==false && document.getElementById("efectivo").checked==false && document.getElementById("cheque").checked==false && document.getElementById("consignacion").checked==false && document.getElementById("datafono").checked==false) {
		alert('Seleccione un tipo de pago')
		return false;
	}
			
	if (document.getElementById('total_val').value == ""){	
		return false;
	}
	else 
	
	total_suma = 0;
	for(i=1; i<=5; i++){
		valor = parseInt(document.getElementById('pago_'+i).value);
		if (valor == ""){
			valor = 0;
		}
		total_suma = total_suma + valor;
	}
	
	if(total_suma != parseInt(document.getElementById('total_val').value)){
		alert('El pago debe ser igual al total de la compra');
		return false;
	}
	
		return true;

}


function verificar_credito()
{
	if (document.getElementById("Credito").checked==false)
	{
		document.getElementById("div_credito").style.display="none";
		document.getElementById("pago_1").disabled = true;
		document.getElementById("pago_1").value = 0;
	}
	else 
	{
		if(document.getElementById("cupo_covinoc").value>0) 
		{
			document.getElementById("div_credito").style.display="inline";
			if (document.getElementById("Credito").checked == true){
				document.getElementById("pago_1").disabled = false;
				document.getElementById("pago_1").value = parseInt(document.getElementById('total_val').value) ;
			}
		}
		else 
		{
			alert("Este Cliente No tiene Credito")
			document.getElementById("Credito").checked=false;
		}
	}
}


function resalte (declarado, sumado){
if(document.getElementById(declarado).value!=document.getElementById(sumado).value) {
	document.getElementById(declarado).style.color="#FF0000";
}
else 
	document.getElementById(declarado).style.color="#000000";
}


function activa_casilla_efec(){
	if (document.getElementById("efectivo").checked == true){
		document.getElementById("pago_2").disabled = false;
		document.getElementById("pago_2").value = parseInt(document.getElementById('total_val').value) ;
	}
	else {
		document.getElementById("pago_2").value = 0;
		document.getElementById("pago_2").disabled = true;
	}
}

function activa_casilla_che(){
	if (document.getElementById("cheque").checked == true){
		document.getElementById("pago_3").disabled = false;
		document.getElementById("pago_3").value = parseInt(document.getElementById('total_val').value) ;
	}
	else {
		document.getElementById("pago_3").value = 0;
		document.getElementById("pago_3").disabled = true;
	}
}

function activa_casilla_con(){
	if (document.getElementById("consignacion").checked == true){
		document.getElementById("pago_4").disabled = false;
		document.getElementById("pago_4").value = parseInt(document.getElementById('total_val').value) ;
		document.getElementById("cuentas").style.display="inline";
	}
	else {
		document.getElementById("pago_4").value = 0;
		document.getElementById("pago_4").disabled = true;
		document.getElementById("cuentas").style.display="none";
	}
}

function activa_casilla_data(){
	if (document.getElementById("datafono").checked == true){
		document.getElementById("pago_5").disabled = false;
		document.getElementById("pago_5").value = parseInt(document.getElementById('total_val').value) ;
	}
	else {
		document.getElementById("pago_5").value = 0;
		document.getElementById("pago_5").disabled = true;
	}
}
</script><script type="text/javascript" src="js/js.js"></script><script type="text/javascript" src="js/js.js"></script><script type="text/javascript" src="js/js.js"></script><link href="css/stylesforms.css" rel="stylesheet" type="text/css" />
<link href="css/styles2.css" rel="stylesheet" type="text/css" />
<link href="informes/styles.css" rel="stylesheet" type="text/css" />
</head>
<body <?=$sis?>>
<div id="total">
<form  name="forma" id="forma" action="con_mostrador.php"  method="post">
<span class="textotabla01">

</span>
<table width="750" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
   <td bgcolor="#D1D8DE" >
   <table width="100%" height="30" border="0" cellspacing="0" cellpadding="0" align="center" > 
      <tr>
        <td width="5" height="30">&nbsp;</td>
        <td width="20" ><img src="imagenes/icoguardar.png" alt="Nueno Registro" width="16" height="16" border="0" onClick="cambio_guardar()" style="cursor:pointer"/></td>
        <td width="61" class="ctablaform">Guardar</td>
        <td width="21" class="ctablaform"><a href="con_venta.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/cancel.png" alt="Cancelar" width="16" height="16" border="0" /></a></td>
        <td width="65" class="ctablaform">Cancelar </td>
        <td width="22" class="ctablaform"><a href="#"><img src="imagenes/iconolupa.gif" alt="Buscar" width="16" height="16" border="0" onclick="buscar_producto()" /></a></td>
        <td width="70" class="ctablaform">Consultar</td>
        <td width="21" class="ctablaform"></td>
        <td width="60" class="ctablaform">&nbsp;</td>
        <td width="24" valign="middle" class="ctablaform">&nbsp;</td>
        <td width="193" valign="middle">

          <input type="hidden" name="editar"   id="editar"   value="<?=$editar?>">
		  <input type="hidden" name="insertar" id="insertar" value="<?=$insertar?>">
		  <input type="hidden" name="eliminar" id="eliminar" value="<?=$eliminar?>">
          <input type="hidden" name="bodega" id="bodega" value="<?=$bodega?>">
          <input type="hidden" name="codigo_lista_cliente" id="codigo_lista_cliente" value="<?=$codigo_lista_cliente?>">
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
    <td class="textotabla01"> <?
	$db6 = new Database();
	$sql = "select num_fac_rso + 1  as  num_factura from rsocial WHERE cod_rso=19 ";
	$db6->query($sql);
	
	if($db6->next_row())
	$num_factura = $db6->num_factura;
	?>
    FACTURA DE VENTA  Nro.      <?=$num_factura?></td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4"/></td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="84" class="textotabla1">Fecha:</td>
          <td width="148" class="subtitulosproductos"><?=date('Y-m-d')?>
            <input name="fecha_fac" id="fecha_fac" type="hidden" value="<?=date('Y-m-d')?>"  /></td>
          <td width="5" class="subtitulosproductos">&nbsp;</td>
          <td width="64" class="textotabla1">Vendedor:</td>
          <td width="144"  class="subtitulosproductos"><?
		if ($codigo!=0) echo $dbdatos_edi->nom_usu;
		else  echo $global[3];
		
		?>
            <input name="vendedor" id="vendedor" type="hidden" value="<?=$global[2]?>" /></td>
          <td width="5"  class="subtitulosproductos">&nbsp;</td>
          <td width="59" height="24" class="textotabla1">Bodega:</td>
          <td width="144" class="subtitulosproductos"><span class="textoproductos1">
            <?
		$sql ="SELECT nom_bod from bodega where cod_bod=225";
	$db->query($sql);
	while($db->next_row()){
		echo $db->nom_bod;
	}
		?>
            <input name="bodega_fac" id="bodega_fac" type="hidden" value="<?=$bodega?>" />
            <input name="precio_lista" id="precio_lista" type="hidden" class="subtitulosproductos" />
          </span></td>
          <td width="5" class="subtitulosproductos"><span class="textorojo">*</span></td>
          <td class="textotabla1">&nbsp;</td>
          <td class="subtitulosproductos">&nbsp;</td>
          <td class="subtitulosproductos">&nbsp;</td>
        </tr>
	    <tr>
	      <td class="textotabla1">Cliente:</td>
	      <td colspan="10" class="textotabla1"><span class="textoproductos1">
	        <? combo_evento("cliente","bodega1","cod_bod","CONCAT(nom_bod,' ',apel_bod,' ',rsocial_bod)",$dbdatos_edi->cod_bod,"onchange=\"cargar_credito('cliente') \"","nombre"); ?>
            <input name="cliente_fac" id="cliente_fac" type="hidden" value="<?=$cliente?>" />
          </span></td>
	      <td>&nbsp;</td>
	      </tr>
	    <tr>
	     <td class="textotabla1"> Credito:</td>
	     <td><input name="Credito" id="Credito" type="checkbox"  value="Credito" onclick="verificar_credito()" />
	       <div id="cupo" style="display:none"> <span class="textotabla1">Cupo:<span class="textorojo">
	         <input name="cupo_credito" id="cupo_credito" type="hidden" class="caja_resalte1"  readonly="readonly"/>
	         </span></span></div>
	       <span  id="div_credito" style="display:none" class="textoproductos1"> $
	         <?=number_format($cupo_covinoc ,0,",",".")?>
	         <input name="cupo_covinoc" type="visible" id="cupo_covinoc"  value="<?=$cupo_covinoc?>" readonly="readonly" align="right"/>
	         </span>
	       <textarea name="tipo_referencias"  id="tipo_referencias"   cols="45" rows="4"  style="display:none"></textarea>
	       <span class="textotabla1">
	         <input name="pago_1" id="pago_1" type="text" class="caja_resalte1" onkeypress="return validaInt_evento(this,'mas')" disabled="disabled" value="0"/>
	         </span></td>
	     <td class="textotabla1">&nbsp;</td>
	     <td class="textotabla1"> Efectivo:</td>
	     <td><input name="efectivo" id="efectivo" type="checkbox"  value="efectivo" onclick="activa_casilla_efec('this',pago_2)" />
	       <div id="cupo" style="display:none"> <span class="textotabla1">Cupo:<span class="textorojo">
	         <input name="cupo_credito" id="cupo_credito" type="hidden" class="caja_resalte1"  readonly="readonly"/>
	         </span></span></div>
	       <textarea name="tipo_referencias"  id="tipo_referencias"   cols="45" rows="4"  style="display:none"></textarea>
	       <span class="textotabla1">
	         <input name="pago_2" id="pago_2" type="text" class="caja_resalte1" onkeypress="return validaInt_evento(this,'mas')" disabled="disabled" value="0"/>
	         </span></td>
	     <td class="textotabla1">&nbsp;</td>
	     <td class="textotabla1">Datafono:</td>
	     <td><input name="datafono" id="datafono" type="checkbox"  value="datafono" onclick="activa_casilla_data()" />
            <textarea name="tipo_referencias2"  id="tipo_referencias2"   cols="45" rows="4"  style="display:none"></textarea>
	       <span class="textotabla1">
	         <input name="pago_5" id="pago_5" type="text" class="caja_resalte1" onkeypress="return validaInt_evento(this,'mas')" disabled="disabled" value="0"/>
	         </span></td>
	     <td class="textotabla1">&nbsp;</td>
	     <td class="textotabla1">Cheque:</td>
	     <td><input name="cheque" id="cheque" type="checkbox"  value="cheque" onclick="activa_casilla_che()"/>
	       <textarea name="tipo_referencias"  id="tipo_referencias"   cols="45" rows="4"  style="display:none"></textarea>
	       <span class="textotabla1">
	         <input name="pago_3" id="pago_3" type="text" class="caja_resalte1" onkeypress="return validaInt_evento(this,'mas')" disabled="disabled" value="0"/>
	         </span></td>
	     <td>&nbsp;</td>
	     </tr>
	   <tr>
	     <td class="textotabla1"> Consignacion:</td>
	     <td><input name="consignacion" id="consignacion" type="checkbox"  value="consignacion" onclick="activa_casilla_con()"/>
	       <textarea name="tipo_referencias"  id="tipo_referencias"   cols="45" rows="4"  style="display:none"></textarea>
	       <span class="textotabla1">
	         <input name="pago_4" id="pago_4" type="text" class="caja_resalte1" onkeypress="return validaInt_evento(this,'mas')" disabled="disabled" value="0"/>
	         </span></td>
	     <td>&nbsp;</td>
	     <td class="textorojo">&nbsp;</td>
	     <td class="textotabla1">&nbsp;</td>
	     <td colspan="7"><span class="textorojo">
	       <div id="cuentas" style="display:none">
	         <? 
			combo_evento_where("cuenta","cuenta","cod_cuenta","desc_cuenta","",""," where cod_cuenta = 448 or cod_cuenta = 15 or cod_cuenta = 14 or cod_cuenta = 11");  ?>
	         
	         </div>
	       </span>
	       </td>
	     </tr>
	   <tr>
	     <td class="subtitulosproductos">TOTAL:</td>
	     <td colspan="2"><input name="total_val" id="total_val" class="caja_resalte1" value="0" /></td>
	     <td class="textorojo">&nbsp;</td>
	     <td>Observaciones:</td>
	     <td colspan="7"><span class="textotabla1">
	       <textarea name="observaciones" cols="45" rows="3" class="textfield02"  onchange='buscar_rutas()' ><?=$dbdatos->obs_tras?>
	       </textarea>
	       </span></td>
	     </tr>
	   <tr>
        <td colspan="13" class="textotabla1" >
		<table  width="100%" border="1">
		      
		  <tr >
		    <td width="4%">
		      <table width="100%">
		        <? 
				//CONSULTA LOS PRODUCTOS DE ESA BODEGA
				$dbpr = new Database();
				$sqlpr ="SELECT desc_ref,cod_ref_kar,cod_tpro_pro,cod_mar_pro,SUM(cant_ref_kar) AS cantidad,COUNT(*) AS total FROM kardex
				INNER JOIN producto ON producto.cod_pro = cod_ref_kar
				INNER JOIN referencia ON referencia.cod_ref = producto.nom_pro
				WHERE cod_bod_kar = 225
				GROUP BY cod_ref_kar
				HAVING cantidad > 0
				ORDER BY desc_ref DESC";
				$dbpr->query($sqlpr);
				$j = 1;
				$k = 0;
				?>
		        <?
				while($dbpr->next_row()){
					if($k == 10){
						echo "<tr>";
						$k = 0;
					}
					echo "<td>$dbpr->desc_ref";
					echo "<input name='cant_$j' id='cant_$j' type='hidden' value='0'/>";
    				echo "<input name='cod_$j' id='cod_$j' type='hidden' value=$dbpr->cod_ref_kar />";
    				echo "<input name='tpro_$j' id='tpro_$j' type='hidden' value=$dbpr->cod_tpro_pro />";
    				echo "<input name='mpro_$j' id='mpro_$j' type='hidden' value=$dbpr->cod_mar_pro />";
					echo "<input name='exist_$j' id='exist_$j' type='hidden' value=$dbpr->cantidad />";
					echo "<div align='right' id='div_fac_$j' class='ctablasup'>0</div>";
					echo "<div align='right' id='div_val_$j' class='ctablasup'>0</div>";
					echo "<input value='+' type='button' onClick='aumenta(\"$j\")' >";
					echo "<input value='-' type='button' onClick='disminuya(\"$j\")'>";
					
					//CONSULTO LA LISTA DE PRECIO
					$dbp = new Database();
					$sqlp = "SELECT pre_list FROM det_lista
					WHERE cod_pro = $dbpr->cod_ref_kar AND cod_lista = 12";
					$dbp->query($sqlp);
					$dbp->next_row();
	
					echo "<input name='prec_$j' id='prec_$j' type='hidden' value=$dbp->pre_list />";
					echo "</td>";
					$k++;
					$j++; 
				}
				?>    
		        </table>
		      </td>
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
<input type="hidden" name="inicial" id="inicial" value="<?=$j?>"/>
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
