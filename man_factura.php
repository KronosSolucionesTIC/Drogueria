<? include("lib/database.php")?>
<? include("js/funciones.php")?>
 <?

 $fecha = date('Y-m-d');
 
function microtime_float(){ 
list($usec, $sec) = explode(" ", microtime()); 
return ((float)$usec + (float)$sec); 
} 

$time_start = microtime_float(); 

//SELECCIONA EL CODIGO DE LA BODEGA
$sql="select distinct punto_venta.cod_bod as valor , nom_bod as nombre from punto_venta  inner join  bodega  on punto_venta.cod_bod=bodega.cod_bod where cod_ven=$global[2]";
$db= new  Database();
$db->query($sql);	
while($db->next_row()){
	$cod = $db->valor ;
}	

//SELECCIONA EL CLIENTE POR DEFECTO
$sql="SELECT * FROM bodega1
WHERE cod_bod_cli = $cod AND defecto = 1";
$db= new  Database();
$db->query($sql);	
while($db->next_row()){
	$cliente = $db->cod_bod;
}

//SELECCIONA LA LISTA DE PRECIOS DEL CLIENTE
$db = new  Database();
$sql ="SELECT * FROM bodega1  
WHERE cod_bod=$cliente"; 
$db->query($sql);
if($db->next_row()){
	$codigo_lista_cliente=$db->cod_lista;
}
	
	$db = new Database();
	$sql = "select num_fac_rso + 1  as  num_factura from rsocial WHERE cod_bod = $cod AND defecto = 1 ";
	$db->query($sql);
	
	if($db->next_row())
	$num_factura = $db->num_factura;
	
	?>
<?	




if($guardar==1 and $codigo==0) 
	{ // RUTINA PARA  INSERTAR REGISTROS NUEVOS	
	$db = new Database();
	$sql = "select *,num_fac_rso + 1  as  num_factura from rsocial WHERE cod_bod = $cod ";
	$db->query($sql);
	
	if($db->next_row())
	$num_factura = $db->num_factura;
	$empresa = $db->cod_rso;
	//validacion de existencia del numero de factura
	
	$db7 = new Database();
	$sql = "select *  from m_factura WHERE num_fac = $num_factura and cod_razon_fac=$empresa ";
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
	$valores="('".$global[2]."','".$cliente_fac."','".$fecha_fac."','".$num_factura."','".$empresa."','".$tipo_pago."','".$observaciones."','".$todocompra."','".$bodega_fac."')" ;
	
	$ins_id=insertar_maestro("m_factura",$compos,$valores); 
	
	
// copia espejo

    $cliente_facs=$cliente_fac;  	
	
	$sql ="SELECT * from bodega1 where cod_bod=$cod";
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
				$sqlc = "SELECT * FROM cuenta 
				WHERE cod_cuenta = '$cod_cuenta'";
				$dbc->query($sqlc);
				$dbc->next_row();
				$obs = "$dbc->desc_cuenta";
				}
				if($valor_otro > 0){
					$compos="(cod_usu_otro,fec_otro,cod_cli_otro,obs_otro,val_otro,cod_tpag_otro,cod_fac)";
					$valores="('".$global[2]."','".$fecha_fac."','".$cliente_fac."','".$obs."','".$valor_otro."','".$i."','".$ins_id."')" ;

					$error=insertar("otros_pagos",$compos,$valores); 
				}
			}
		}
	
	
	
	if ($ins_id > 0) 
	{
		//insercion del credito
		$compos="(cod_mfac,cod_tpro,cod_cat,cod_peso, cod_pro, cant_pro, val_uni, total_pro) ";
		for ($ii=1 ;  $ii <= $val_inicial + 1 ; $ii++) 
		{
			if($_POST["codigo_referencia_".$ii]!=NULL) 
			{
						
			$val_uni=$_POST["costo_ref_".$ii] / $_POST["cantidad_ref_".$ii];				
			$valores="('".$ins_id."','".$_POST["codigo_tipo_prodcuto_".$ii]."','".$_POST["codigo_marca_".$ii]."','".$_POST[            "codigo_peso_".$ii]."','".$_POST["codigo_referencia_".$ii]."','".$_POST["cantidad_ref_".$ii]."','".$_POST[            "costo_ref_uni_".$ii]."','".$_POST["costo_ref_total_".$ii]."')";
			
			$error=insertar("d_factura",$compos,$valores);
			
			
			if ($regimen_cli=='COMUN')
			{
			
			$val_uni=$_POST["costo_ref_".$ii] / $_POST["cantidad_ref_".$ii];				
			$valores="('".$ins_ides."','".$_POST["codigo_tipo_prodcuto_".$ii]."','".$_POST["codigo_marca_".$ii]."','".$_POST[            "codigo_peso_".$ii]."','".$_POST["codigo_referencia_".$ii]."','".$_POST["cantidad_ref_".$ii]."','".$_POST[            "costo_ref_uni_".$ii]."','".$_POST["costo_ref_total_".$ii]."')";
										 
			 $error=insertar("d_factura_esp",$compos,$valores);
				
			} 			
				
	          else 
			  { 
				   
				   if ($regimen_cli=='SIMPLIFICADO' && $lista==21)
				   { 	
							
					 $val_uni=$_POST["costo_ref_".$ii] / $_POST["cantidad_ref_".$ii];				
					 $valores="('".$ins_ido."','".$_POST["codigo_tipo_prodcuto_".$ii]."','".$_POST["codigo_marca_".$ii]."','".                     $_POST["codigo_peso_".$ii]."','".$_POST["codigo_referencia_".$ii]."','".$_POST["cantidad_ref_".$ii]."','".                     $_POST["costo_ref_uni_".$ii]."','".$_POST["costo_ref_total_".$ii]."')";
									
					 $error=insertar("d_factura_esp",$compos,$valores);
			
					}
				 		 
			    }
								
					kardex("resta",$_POST["codigo_referencia_".$ii],$bodega_fac,$_POST["cantidad_ref_".$ii],$_POST["costo_ref_".$ii],                    $_POST["codigo_peso_".$ii]);
			}	
		}
		
					 if ($regimen_cli=='SIMPLIFICADO' && $lista!=21)
                     { 	
		         
					  $sql =" select
					  d_factura.cod_dfac,
					  d_factura.cod_mfac,
					  d_factura.cod_pro as l,
					  producto.cod_pro ,
					  producto.cod_mar_pro,
					  producto.cod_tpro_pro,
					  d_factura.cod_tpro,
					  tipo_producto.cod_tpro,
					  tipo_producto.nom_tpro,
					  d_factura.cod_peso,
					  producto.cod_pes_pro,
					  marca.cod_mar,
					  marca.nom_mar,
					  m_factura.cod_fac,
					  bodega1.cod_bod,
					  m_factura.cod_cli
					  
					  FROM
					  d_factura
					  INNER JOIN producto ON (d_factura.cod_pro = producto.cod_pro)
					  INNER JOIN tipo_producto ON (producto.cod_tpro_pro = tipo_producto.cod_tpro)
					  INNER JOIN marca ON (producto.cod_mar_pro = marca.cod_mar)
					  INNER JOIN m_factura ON (d_factura.cod_mfac = m_factura.cod_fac)
					  INNER JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)
										
					  where d_factura.cod_mfac=$ins_id AND m_factura.cod_cli=$cliente_facs ";
									
					  $dbdatos_pro= new  Database();
					  $dbdatos_pro->query($sql);
						
					  while($dbdatos_pro->next_row())
					  { 
						
						$productoss=$dbdatos_pro->l;
						
						  $sqll ="select 
						  listaprecio.cos_list,
						  listaprecio.nom_list,
						  det_lista.cod_pro,
						  det_lista.pre_reg,
						  det_lista.pre_list,
						  det_lista.cod_lista,
						  d_factura.cod_dfac,
						  d_factura.cod_mfac,
						  d_factura.cod_pro,
						  d_factura.cant_pro,
					  	  producto.cod_pro,
						  producto.cod_mar_pro,
						  producto.cod_tpro_pro,
						  d_factura.cod_tpro,
						  tipo_producto.cod_tpro,
						  tipo_producto.nom_tpro,
						  d_factura.cod_peso,
						  producto.cod_pes_pro,
						  marca.cod_mar,
						  marca.nom_mar
						  
					      FROM
						  listaprecio
						  INNER JOIN det_lista ON (listaprecio.cos_list = det_lista.cod_lista)
						  INNER JOIN d_factura ON (det_lista.cod_pro = d_factura.cod_pro)
						  INNER JOIN producto ON (d_factura.cod_pro = producto.cod_pro)
						  INNER JOIN tipo_producto ON (producto.cod_tpro_pro = tipo_producto.cod_tpro)
						  INNER JOIN marca ON (producto.cod_mar_pro = marca.cod_mar)
						  where listaprecio.cos_list=21 and det_lista.cod_pro=$productoss and  d_factura.cod_mfac=$ins_id ";
									
						  $dbdatos_list= new  Database();
						  $dbdatos_list->query($sqll);
						  $dbdatos_list->next_row();
						  $dbdatos_pro->total_pro;
						
						  $total_prod=($dbdatos_list->cant_pro * $dbdatos_list->pre_list);
						  $total_prodfact+=($dbdatos_list->cant_pro * $dbdatos_list->pre_list);
						  $valor_unitario=($dbdatos_list->pre_list);		
														
						  $composd="(cod_mfac,cod_tpro,cod_cat,cod_peso,cod_pro,cant_pro,val_uni,total_pro) ";
						  $valoresf="('".$ins_idet."','".$dbdatos_list->cod_tpro."','".$dbdatos_list->cod_mar."','".$dbdatos_list->                          cod_peso."','".$dbdatos_list->cod_pro."','".$dbdatos_list->cant_pro."','".$valor_unitario."','".$total_prod.                          "')";
						   
						  $error=insertar("d_factura_esp",$composd,$valoresf);
	 					 
	                    }
		 
						 // RUTINA PARA  editar REGISTROS
						  $compos="tot_fac='".$total_prodfact."'";
						  $error=editar("m_factura_esp",$compos,'cod_fac',$ins_idet); 
						
                     }
					 	
		echo "<script language='javascript'> alert('Se guardo') </script>"; 	
	
	}
	
	else
		echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>"; 	

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
<script language="javascript">
function prueba(){
	alert('entoro');
}
//CARGA EL TIPO DEL PRODUCTO
function cargar_tipo_producto(categoria,tipo_producto,bodega){
var combo=document.getElementById(tipo_producto);
combo.options.length=0;
var cant=0;
combo.options[cant] = new Option('Seleccione','0'); 
cant++;
<?
		$sqltp ="SELECT DISTINCT producto.cod_tpro_pro,tipo_producto.nom_tpro,producto.cod_mar_pro,kardex.cod_bod_kar FROM kardex
		LEFT OUTER JOIN producto ON (kardex.cod_ref_kar = producto.cod_pro)
		LEFT OUTER JOIN tipo_producto ON (producto.cod_tpro_pro = tipo_producto.cod_tpro)
		WHERE cant_ref_kar > 0 AND cod_bod_kar = $cod
		GROUP by  cod_ref_kar
		ORDER BY  nom_tpro ASC
		";		
		$dbtp= new  Database();
		$dbtp->query($sqltp);
		while($dbtp->next_row()){ 
		echo "if(document.getElementById(categoria).value==$dbtp->cod_mar_pro) {";
		echo "combo.options[cant] = new Option('$dbtp->nom_tpro','$dbtp->cod_tpro_pro');";
		echo "cant++; } ";
		}
?> 
}

//CARGA LA REFERENCIA DEL PRODUCTO
function cargar_referencia(tipo_producto,referencia,bodega){
var combo=document.getElementById(referencia);
combo.options.length=0;
var cant=0;
combo.options[cant] = new Option('Seleccione','0'); 
cant++;
<?
		$sqlr ="SELECT DISTINCT kardex.cod_ref_kar,desc_ref,producto.cod_tpro_pro FROM   kardex
		LEFT OUTER JOIN producto ON (kardex.cod_ref_kar = producto.cod_pro)
		LEFT OUTER JOIN tipo_producto ON (producto.cod_tpro_pro = tipo_producto.cod_tpro)
		INNER JOIN referencia ON (referencia.cod_ref = producto.nom_pro)
		WHERE cant_ref_kar > 0 AND cod_bod_kar = $cod 
		GROUP by  nom_pro
		ORDER BY  desc_ref ASC";		
		$dbr= new  Database();
		$dbr->query($sqlr);
		while($dbr->next_row()){ 
		echo "if(document.getElementById(tipo_producto).value==$dbr->cod_tpro_pro) {";
		echo "combo.options[cant] = new Option('$dbr->desc_ref','$dbr->cod_ref_kar');";
		echo "cant++; } ";
		}
?> 
}


//CARGA EL ITEM, EL CODIGO DEL PRODUCTO Y LA TALLA
function cargar_codigo_talla(referencia,codigo,codigo_producto,bodega,talla,valor_lista){
//CARGA LA TALLA DEL PRODUCTO
var combo=document.getElementById(talla);
combo.options.length=0;
var cant=0;
combo.options[cant] = new Option('Seleccione','0'); 
cant++;
<?
		$sqlt ="SELECT * FROM kardex
		INNER JOIN peso ON (kardex.cod_talla = peso.cod_pes)";		
		$dbt= new  Database();
		$dbt->query($sqlt);
		while($dbt->next_row()){ 
		echo "if(document.getElementById(referencia).value==$dbt->cod_ref_kar && document.getElementById(bodega).value==$dbt->cod_bod_kar && $dbt->cant_ref_kar > 0) {";	
		echo "combo.options[cant] = new Option('$dbt->nom_pes','$dbt->cod_pes');";
		echo "cant++; } ";
		}
?>

//CARGA EL ITEM Y EL CODIGO DEL PRODUCTO
var item_articulo = "";
var codigo_articulo = "";
<?
		$sqlcod ="SELECT * FROM kardex
		INNER JOIN producto ON (kardex.cod_ref_kar = producto.cod_pro)";		
		$dbcod= new  Database();
		$dbcod->query($sqlcod);
		while($dbcod->next_row()){ 
		echo "if(document.getElementById(referencia).value==$dbcod->cod_ref_kar && document.getElementById(bodega).value==$dbcod->cod_bod_kar && $dbcod->cant_ref_kar > 0) {";	
		echo "codigo_articulo = '$dbcod->cod_pro'; ";
		echo "item_articulo = '$dbcod->cod_fry_pro'; }";
		}
?>
document.getElementById(codigo_producto).value= codigo_articulo;
document.getElementById(codigo).value= item_articulo;
}


//CARGA EL VALOR DEL PRODUCTO
function cargar_valor(valor_lista,codigo_producto){
var valor=0;
<?
	$sqlv =" SELECT * FROM det_lista WHERE cod_lista = $codigo_lista_cliente";		
	$dbv= new  Database();
	$dbv->query($sqlv);
	while($dbv->next_row()){ 
		echo "if(document.getElementById(codigo_producto).value==$dbv->cod_pro) {";
		echo "valor = '$dbv->pre_list'; }";
	}
?>
document.getElementById(valor_lista).value= valor;
}


//VALIDA QUE LA CANTIDAD NO SEA MAYOR A LA EXISTENTE
function validar_cantidad(){
var cantidad_existente = 0;
var cantidad_pedido = 0;
<?
	$sqlcan =" SELECT * FROM kardex";		
	$dbcan= new  Database();
	$dbcan->query($sqlcan);
	while($dbcan->next_row()){ 
		echo "if(document.getElementById('bodega').value==$dbcan->cod_bod_kar &&  document.getElementById('codigo_producto').value==$dbcan->cod_ref_kar && document.getElementById('peso').value==$dbcan->cod_talla)";
		echo "cantidad_existente = '$dbcan->cant_ref_kar';";
	}
	$dbcan->close();
?>
cantidad_pedido = parseInt(document.getElementById('cantidad').value);
cantidad_existente = parseInt(cantidad_existente);
	if (cantidad_pedido > cantidad_existente) {
 		alert('Cantidad '+ cantidad_pedido +' mayor a la existente '+cantidad_existente);
		return false;	
	}
	else
	 	return true;
}

//CARGA TODA LA INFORMACION CON EL ITEM
function cargar_articulo(categoria,tipo_producto,referencia,codigo,codigo_producto,bodega,peso,valor_lista){
	
//CARGA LA CATEGORIA
var combo=document.getElementById(categoria);
<?
		$sqltd ="SELECT * FROM `producto`
		INNER JOIN marca ON (marca.cod_mar = producto.cod_mar_pro)";		
		$dbtd= new  Database();
		$dbtd->query($sqltd);
		while($dbtd->next_row()){ 
		echo "if(document.getElementById(codigo).value =='$dbtd->cod_fry_pro'){";
		echo "valor = '$dbtd->cod_mar';}";	
		}
?>
var cant = combo.options.length; 
for (i=0; i<=cant; i++){
if(combo[i].value == valor){
combo[i].selected = true;
	
	//CARGA EL TIPO DEL PRODUCTO
	cargar_tipo_producto(categoria,tipo_producto,bodega)	
	var combo=document.getElementById(tipo_producto);
	<?
		$sqltp ="SELECT * FROM `producto`
		INNER JOIN tipo_producto ON (tipo_producto.cod_tpro = producto.cod_tpro_pro)";		
		$dbtp= new  Database();
		$dbtp->query($sqltp);
		while($dbtp->next_row()){ 	
		echo "if(document.getElementById(codigo).value=='$dbtp->cod_fry_pro') {";
		echo "valor = '$dbtp->cod_tpro';}";
		}
	?>
	var cant = combo.options.length;
	for (i=0; i<=cant; i++){
	if(combo[i].value == valor){
	combo[i].selected = true;
		
		//CARGA LA REFERENCIA
		cargar_referencia(tipo_producto,referencia,bodega)
		var combo=document.getElementById(referencia);
		<?
			$sqlr ="SELECT * FROM `producto`";		
			$dbr= new  Database();
			$dbr->query($sqlr);
			while($dbr->next_row()){ 	
			echo "if(document.getElementById(codigo).value=='$dbr->cod_fry_pro') {";
			echo "valor = '$dbr->cod_pro';}";
			}
		?>
		var cant = combo.options.length;
		for (i=0; i<=cant; i++){
		if(combo[i].value == valor){
		combo[i].selected = true;
		cargar_codigo_talla(referencia,codigo,codigo_producto,bodega,peso,valor_lista)
		return true;
		}
		}
	}
	}
}
}
}



function buscar_datos_total(opc) {
if(opc=="valor_lista") {
	if(document.getElementById('valor_lista').value==""){
		alert("No hay Precio Asignado")
		return false;
	}
	else 
		return true;	
	}
}


function limpiar_combos()
{
	document.getElementById('tipo_producto').options.length=0;
	document.getElementById('combo_referncia').options.length=0;
	document.getElementById('codigo_fry').value="";
	document.getElementById('peso').options.length=0;
	document.getElementById('valor_lista').value="";
	document.getElementById('cantidad').value="";	
	
}

function datos_completos(){ 

	if(document.getElementById("Credito").checked==true &&  parseInt(document.getElementById('cupo_covinoc').value) <= parseInt(document.getElementById('todocompra').value) ) {
		alert('No hay Cupo para esta Compra')
		return false;
	}
	
	if(document.getElementById("Credito").checked==false && document.getElementById("efectivo").checked==false && document.getElementById("cheque").checked==false && document.getElementById("consignacion").checked==false && document.getElementById("datafono").checked==false) {
		alert('Seleccione un tipo de pago')
		return false;
	}
			
	if (document.getElementById('todocompra').value == ""){	
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
	
	if(total_suma != parseInt(document.getElementById('todocompra').value)){
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
				document.getElementById("pago_1").value = parseInt(document.getElementById('todocompra').value) ;
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


function  adicion() 
{

	if(document.getElementById('marca').value < 1 || document.getElementById('tipo_producto').value < 1 || document.getElementById('combo_referncia').value < 1 || document.getElementById('peso').value < 1  || document.getElementById('cantidad').value=="" ) 
	{
		alert("Datos Incompletos")
		return false;
	}
	
	if(validar_cantidad()==false)
		return false;
		
	if(buscar_datos_total('valor_lista')==false)
		return false;
	
	
	var  vali_ref= anti_trampa(document.getElementById("combo_referncia").value, document.getElementById('peso').value);
	
	if(vali_ref==1)
	{
		alert("Esta Referencia Ya fue agregada  Si desea Modificar la Cantidad  Elimine el Registro y Agregue nuevamente")
		return false;
	}

	if(document.getElementById("marca").value>0  && document.getElementById("tipo_producto").value > "" && document.getElementById('combo_referncia').value > 0 && document.getElementById('peso').value > 0 && document.getElementById("cantidad").value>0 ) 
	{
		Agregar_html_venta();						
		limpiar_combos();
		document.getElementById('marca').value=0;
		document.getElementById("codigo_fry").focus();
		return false;
	}
	
	else 
	{
		alert("Ingrese una Referencia Valida junto con los demas Valores")
		document.getElementById("codigo_fry").focus();
	}
}



function buscar_producto(){
var ruta="con_consulta_inf.php";
var tamano="recibo";
var ancho=0;
var alto=0;
	
if(tamano=="mediano") {
	ancho=900;
	alto=600;
}

if(tamano=="grande") {
	ancho=900;
	alto=700;
}

if(tamano=="recibo") {
	ancho=700;
	alto=500;
}



var marginleft = (screen.width - ancho) / 2;
var margintop = (screen.height - alto) / 2;
propiedades = 'menubar=0,resizable=1,height='+alto+',width='+ancho+',top='+margintop+',left='+marginleft+',toolbar=0,scrollbars=yes';
window.open(""+ruta+"?codigo=0","Busqueda",propiedades)

}


document.onkeydown = function(e) 
{ 
	
	
	if(e) 
	document.onkeypress = function(){return true;} 


	var evt = e?e:event; 
	if(evt.keyCode==120 || evt.keyCode==121) 
	{ 
		if(evt.keyCode==120)
		buscar_producto();
		if(evt.keyCode==121){
		calcula_cambio();
		cambio_guardar();		
		}
	
		if(e) 
		document.onkeypress = function(){return false;} 
		else 
		{ 
		evt.keyCode = 0; 
		evt.returnValue = false; 
		} 
	} 
} 


function anti_trampa(cod_ref_comp,peso_comp)
{	
	var myString =document.getElementById("tipo_referencias").value;
	var mySplitResult = myString.split("@");
	var myString_sub;
	var mySplitResult_sub ;
	var validador=0;
		
	
	for(i = 1; i < mySplitResult.length; i++)
	{		

		myString_sub=mySplitResult[i];
		mySplitResult_sub = myString_sub.split(",");
		
		if(mySplitResult_sub[1]== cod_ref_comp &&  mySplitResult_sub[0]== peso_comp) 
		{
			validador=1;
		}

	}
	
	return validador;
}

function activa_casilla_efec(){
	if (document.getElementById("efectivo").checked == true){
		document.getElementById("pago_2").disabled = false;
		document.getElementById("pago_2").value = parseInt(document.getElementById('todocompra').value) ;
	}
	else {
		document.getElementById("pago_2").value = 0;
		document.getElementById("pago_2").disabled = true;
	}
}

function activa_casilla_che(){
	if (document.getElementById("cheque").checked == true){
		document.getElementById("pago_3").disabled = false;
		document.getElementById("pago_3").value = parseInt(document.getElementById('todocompra').value) ;
	}
	else {
		document.getElementById("pago_3").value = 0;
		document.getElementById("pago_3").disabled = true;
	}
}

function activa_casilla_con(){
	if (document.getElementById("consignacion").checked == true){
		document.getElementById("pago_4").disabled = false;
		document.getElementById("pago_4").value = parseInt(document.getElementById('todocompra').value) ;
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
		document.getElementById("pago_5").value = parseInt(document.getElementById('todocompra').value) ;
	}
	else {
		document.getElementById("pago_5").value = 0;
		document.getElementById("pago_5").disabled = true;
	}
}
</script><script type="text/javascript" src="js/js.js"></script><script type="text/javascript" src="js/js.js"></script><script type="text/javascript" src="js/js.js"></script>
<script type="text/javascript" src="informes/inf.js"></script>
<link href="css/stylesforms.css" rel="stylesheet" type="text/css" />
<link href="css/styles2.css" rel="stylesheet" type="text/css" />
<link href="informes/styles.css" rel="stylesheet" type="text/css" />
</head>
<body <?=$sis?>>
<div id="total">
<form  name="forma" id="forma" action="man_factura.php"  method="post">
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
          <input type="hidden" name="bodega" id="bodega" value="<?=$cod?>">
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
    <td class="textotabla01">
    FACTURA   Nro.    <?=$num_factura?></td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4"/></td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td width="62" class="textotabla1">Fecha:</td>
        <td width="275" colspan="2" class="subtitulosproductos"><?=date('Y-m-d');?>
          <input name="fecha_fac" id="fecha_fac" type="hidden" value="<?=$fecha?>"  /></td>
        <td width="20" class="textorojo">*</td>
        <td width="77" class="textotabla1">Vendedor:</td>
        <td width="145"  class="subtitulosproductos">
		<?
		if ($codigo!=0) echo $dbdatos_edi->nom_usu;
		else  echo $global[3];
		
		?>
		<input name="vendedor" id="vendedor" type="hidden" value="<?=$global[2]?>"></td>		 
        <td width="171" class="textorojo">&nbsp;</td>
       </tr>
	   <tr>
        <td width="62" height="24" class="textotabla1">Empresa:</td>
        <td width="275" colspan="2" class="subtitulosproductos">
		<?
		$sql ="SELECT * from rsocial where cod_bod = $cod";
	$db->query($sql);
	while($db->next_row()){
		echo $db->nom_rso;
	}
		?>
		<input name="empresa" id="empresa" type="hidden" value="<?=$empresa?>"></td>
        <td width="20" class="textorojo">*</td>
        <td width="77" class="textotabla1"> Bodega:</td>
        <td class="subtitulosproductos"><span class="textoproductos1">
          <?
		$sql ="SELECT * from bodega where cod_bod = $cod";
		$db->query($sql);
		while($db->next_row()){
			echo $db->nom_bod;
		}
		?>
          <input name="bodega_fac" id="bodega_fac" type="hidden" value="<?=$cod?>">
        </span></td>		 
        <td width="171" >
			<input name="precio_lista" id="precio_lista" type="hidden" class="subtitulosproductos" />		</td>
       </tr>
	   <tr>
	     <td height="24" class="textotabla1">Cliente:</td>
	     <td colspan="2" class="subtitulosproductos"><?
	$sql ="SELECT * from bodega1 where cod_bod=$cliente";
	$db->query($sql);
	while($db->next_row())
	{
		echo $db->nom_bod." ".$db->apel_bod;
		
		$cupo_covinoc=$db->cupo_au_covinoc;
	}
	
	 $sql ="SELECT SUM(((SELECT SUM(total_pro) FROM d_factura WHERE cod_mfac=m_factura.cod_fac)- valor_abono )) -(SUM(tot_dev_mfac)) AS cartera 
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
	       <input name="cliente_fac" id="cliente_fac" type="hidden" value="<?=$cliente?>" /></td>
	     <td class="textorojo">&nbsp;</td>
	     <td class="textotabla1">Regimen</td>
	     <td class="subtitulosproductos">
           <?
	$sql ="SELECT * from bodega1 where cod_bod=$cliente";
	$db->query($sql);
	$db->next_row();
		
		echo $db->regimen_cli;
	
		 
		?>
           <input name="regimen_cli" id="regimen_cli" type="hidden" value="<?=$db->regimen_cli?>" /></td>
	     <td >&nbsp;</td>
	     </tr>
	   <tr>
	     <td class="textotabla1">Crear cliente:</td>
	     <td colspan="2">&nbsp;</td>
	     <td class="textotabla1">&nbsp;</td>
	     <td class="textotabla1">&nbsp;</td>
	     <td colspan="2">&nbsp;</td>
	     </tr>
	   <tr>
	     <td class="textotabla1"> Credito:</td>
	     <td colspan="2"><input name="Credito" id="Credito" type="checkbox"  value="Credito" onclick="verificar_credito()" />
	       <div id="cupo" style="display:none"> <span class="textotabla1">Cupo:<span class="textorojo">
	         <input name="cupo_credito" id="cupo_credito" type="hidden" class="caja_resalte1"  readonly="readonly"/>
	         </span></span></div>
	       <span  id="div_credito" style="display:none" class="textoproductos1"> $
	         <?=number_format($cupo_covinoc ,0,",",".")?>
	         <input name="cupo_covinoc" type="hidden" id="cupo_covinoc"  value="<?=$cupo_covinoc?>" readonly="readonly" align="right"/>
	         </span>
	       <textarea name="tipo_referencias"  id="tipo_referencias"   cols="45" rows="4"  style="display:none"></textarea>
	       <span class="textotabla1">
	       <input name="pago_1" id="pago_1" type="text" class="caja_resalte1" onkeypress="return validaInt_evento(this,'mas')" disabled="disabled" value="0"/>
	       </span></td>
        <td class="textotabla1">&nbsp;</td>
        <td class="textotabla1">&nbsp;</td>
        
        <td colspan="2">&nbsp;</td>		 
        </tr>
	   <tr>
	     <td class="textotabla1"> Efectivo:</td>
	     <td colspan="2"><input name="efectivo" id="efectivo" type="checkbox"  value="efectivo" onclick="activa_casilla_efec('this',pago_2)" />
	       <div id="cupo" style="display:none"> <span class="textotabla1">Cupo:<span class="textorojo">
	         <input name="cupo_credito" id="cupo_credito" type="hidden" class="caja_resalte1"  readonly="readonly"/>
	         </span></span></div>
	       <span  id="div_credito" style="display:none" class="textoproductos1"> $
	         <?=number_format($cupo_covinoc ,0,",",".")?>
	         <input name="cupo_covinoc" type="hidden" id="cupo_covinoc"  value="<?=$cupo_covinoc?>" readonly="readonly" align="right"/>
	         </span>
	       <textarea name="tipo_referencias"  id="tipo_referencias"   cols="45" rows="4"  style="display:none"></textarea>
	       <span class="textotabla1">
	       <input name="pago_2" id="pago_2" type="text" class="caja_resalte1" onkeypress="return validaInt_evento(this,'mas')" disabled="disabled" value="0"/>
	       </span></td>
	     <td class="textorojo">&nbsp;</td>
	     <td class="textotabla1">&nbsp;</td>
	     <td colspan="2">&nbsp;</td>
	     </tr>
	   <tr>
	     <td class="textotabla1">Cheque:</td>
	     <td colspan="2"><input name="cheque" id="cheque" type="checkbox"  value="cheque" onclick="activa_casilla_che()"/>
	       <div id="cupo" style="display:none"> <span class="textotabla1">Cupo:<span class="textorojo">
	         <input name="cupo_credito" id="cupo_credito" type="hidden" class="caja_resalte1"  readonly="readonly"/>
	         </span></span></div>
	       <span  id="div_credito" style="display:none" class="textoproductos1"> $
	         <?=number_format($cupo_covinoc ,0,",",".")?>
	         <input name="cupo_covinoc" type="hidden" id="cupo_covinoc"  value="<?=$cupo_covinoc?>" readonly="readonly" align="right"/>
	         </span>
	       <textarea name="tipo_referencias"  id="tipo_referencias"   cols="45" rows="4"  style="display:none"></textarea>
	       <span class="textotabla1">
	       <input name="pago_3" id="pago_3" type="text" class="caja_resalte1" onkeypress="return validaInt_evento(this,'mas')" disabled="disabled" value="0"/>
	       </span></td>
	     <td class="textorojo">&nbsp;</td>
	     <td class="textotabla1">&nbsp;</td>
	     <td colspan="2">&nbsp;</td>
	     </tr>
	   <tr>
	     <td class="textotabla1"> Consignacion:</td>
	     <td colspan="2"><input name="consignacion" id="consignacion" type="checkbox"  value="consignacion" onclick="activa_casilla_con()"/>
	       <div id="cupo" style="display:none"> <span class="textotabla1">Cupo:<span class="textorojo">
	         <input name="cupo_credito" id="cupo_credito" type="hidden" class="caja_resalte1"  readonly="readonly"/>
	         </span></span></div>
	       <span  id="div_credito" style="display:none" class="textoproductos1"> $
	         <?=number_format($cupo_covinoc ,0,",",".")?>
	         <input name="cupo_covinoc" type="hidden" id="cupo_covinoc"  value="<?=$cupo_covinoc?>" readonly="readonly" align="right"/>
	         </span>
	       <textarea name="tipo_referencias"  id="tipo_referencias"   cols="45" rows="4"  style="display:none"></textarea>
	       <span class="textotabla1">
	       <input name="pago_4" id="pago_4" type="text" class="caja_resalte1" onkeypress="return validaInt_evento(this,'mas')" disabled="disabled" value="0"/>
	       </span></td>
	     <td class="textorojo">&nbsp;</td>
	     <td class="textotabla1">&nbsp;</td>
	     <td colspan="2"><span class="textorojo">
         <div id="cuentas" style="display:none">
	       <? 
			combo_evento_where("cuenta","cuenta","cod_cuenta","desc_cuenta","",""," where cod_cuenta = 448 or cod_cuenta = 15 or cod_cuenta = 14 or cod_cuenta = 11");  ?>
	     
         </div>
         </span>
         </td>
	     </tr>
	   <tr>
	     <td class="textotabla1">Datafono:</td>
	     <td colspan="2"><input name="datafono" id="datafono" type="checkbox"  value="datafono" onclick="activa_casilla_data()" />
	       <div id="cupo2" style="display:none"> <span class="textotabla1">Cupo:<span class="textorojo">
	         <input name="cupo_credito2" id="cupo_credito2" type="hidden" class="caja_resalte1"  readonly="readonly"/>
	         </span></span></div>
	       <span  id="div_credito2" style="display:none" class="textoproductos1"> $
	         <?=number_format($cupo_covinoc ,0,",",".")?>
	         <input name="cupo_covinoc2" type="hidden" id="cupo_covinoc2"  value="<?=$cupo_covinoc?>" readonly="readonly" align="right"/>
	         </span>
	       <textarea name="tipo_referencias2"  id="tipo_referencias2"   cols="45" rows="4"  style="display:none"></textarea>
	       <span class="textotabla1">
	       <input name="pago_5" id="pago_5" type="text" class="caja_resalte1" onkeypress="return validaInt_evento(this,'mas')" disabled="disabled" value="0"/>
	       </span></td>
	     <td class="textorojo"></td>
	     <td class="textotabla1">&nbsp;</td>
	     <td colspan="2">&nbsp;</td>
	     </tr>
	   <tr>
	     <td class="textotabla1">&nbsp;</td>
	     <td class="subtitulosproductos">&nbsp;</td>
	     <td class="subtitulosproductos">&nbsp;</td>
	     <td class="textorojo">&nbsp;</td>
	     <td class="textotabla1">&nbsp;</td>
	     <td colspan="2">&nbsp;</td>
	     </tr>
	   <tr>
        <td colspan="8" class="textotabla1" >
		<table  width="100%" border="1">         
          <tr >
            
            <td  class="ctablasup">Categoria</td>
			<td  class="ctablasup">Tipo Producto </td>
            <td colspan="2"  class="ctablasup">Referencia</td>
			<td  class="ctablasup">&nbsp;Codigo
			  <label></label></td>
            <td  class="ctablasup">Talla</td>
            <td   class="ctablasup">Cantidad</td>
			<td width="4%" class="ctablasup" align="center">Agregar:</td>
          </tr>
          <tr >
            <td >
			<? 
  			$sql="SELECT cod_mar_pro,nom_mar  FROM kardex 
			INNER JOIN producto ON (kardex.cod_ref_kar = producto.cod_pro)  
 			INNER JOIN marca ON (producto.cod_mar_pro = marca.cod_mar)
			WHERE  cod_bod_kar = $cod AND cant_ref_kar > 0
			GROUP BY  kardex.cod_bod_kar, cod_mar_pro
			ORDER BY  `marca`.`nom_mar` ASC";
			combo_sql_evento("marca","","cod_mar_pro","nom_mar","",$sql," onchange=\"cargar_tipo_producto('marca','tipo_producto','cod') \"");  ?>			</td>
			<td >
			<select size="1" id="tipo_producto" name="tipo_producto" class='SELECT' onchange="cargar_referencia('tipo_producto','combo_referncia','cod') " >
            </select>			</td>
            <td colspan="2" align="center">
		<select size="1" id="combo_referncia" name="combo_referncia"  class='SELECT'  onchange="cargar_codigo_talla('combo_referncia','codigo_fry','codigo_producto','bodega','peso','valor_lista')">
            </select>
			
              <input name="text" type="hidden" id="codigo_producto" /></td>
            <td align="center">
			<input name="codigo_fry" id="codigo_fry" type="text" class="caja_resalte1" onkeypress=" return valida_evento(this,'peso')"  onchange="cargar_articulo('marca','tipo_producto','combo_referncia','codigo_fry','codigo_producto','cod','peso','valor_lista')" >			</td>
			 <td align="center">
			 <select size="1" id="peso" name="peso"  class='SELECT'  onChange="cargar_valor('valor_lista','codigo_producto')">
            </select>				</td>
			 <td align="center">
			 <input name="cantidad" type="text" class="caja_resalte" id="cantidad" onkeypress="return validaInt_evento(this,'mas')"/>
			 <input name="valor_lista" type="hidden" id="valor_lista" />	 			    </td>
			 
			 
		    <td align="center"> 
			 <input id="mas" type='button'  class='botones' value='  +  '  onclick="adicion()">			 </td>
          </tr>
		      
		  <tr >
		  <td  colspan="8">
			  <table width="100%">
				<tr id="fila_0">
				
				<td width="14%"  class="ctablasup">Categoria</td>
				<td width="21%"  class="ctablasup">Tipo Producto </td>
				<td width="20%"   class="ctablasup">Referencia</td>
				<td width="9%"  class="ctablasup">Codigo</td>
				<td width="8%"  class="ctablasup">Talla</td>
				<td width="8%"   class="ctablasup">Cantidad</td>
				<td width="13%"    class="ctablasup">Valor</td>
				<td width="7%" class="ctablasup" align="center">Borrar</td>
				</tr>
				<?
				if ($codigo!="") { // BUSCAR DATOS
					$sql =" select * from d_factura inner join tipo_producto on d_factura.cod_tpro=tipo_producto.cod_tpro
inner join marca on d_factura.cod_cat=marca.cod_mar inner join peso on d_factura.cod_peso= peso.cod_pes inner join producto  on d_factura.cod_pro= producto.cod_pro where cod_mfac =$codigo order by cod_dfac ";//=		
					$dbdatos_1= new  Database();
					$dbdatos_1->query($sql);
					$jj=1;
					//echo "<table width='100%'>";
					while($dbdatos_1->next_row()){ 
						echo "<tr id='fila_$jj'>";
						//cmarca
						echo "<td  ><INPUT type='hidden'  name='codigo_marca_$jj' value='$dbdatos_1->cod_cat'><span class='textfield01'> $dbdatos_1->nom_mar </span> </td>";	
						
						//tipo de producto
						echo "<td><INPUT type='hidden'  name='codigo_tipo_prodcuto_$jj' value='$dbdatos_1->cod_tpro'><span  class='textfield01'> $dbdatos_1->nom_tpro </span> </td>";
						
						//referencia
						echo "<td  ><INPUT type='hidden'  name='codigo_referencia_$jj' value='$dbdatos_1->cod_pro_1'><span  class='textfield01'> $dbdatos_1->nom_pro </span> </td>";
						
						//% codigo barra
						echo "<td ><INPUT type='hidden'  name='codigo_fry_$jj' value='$dbdatos_1->cod_fry_pro'><span  class='textfield01'> $dbdatos_1->cod_fry_pro </span> </td>";
						
						//talla
						echo "<td   ><INPUT type='hidden'  name='codigo_peso_$jj' value='$dbdatos_1->cod_peso'><span  class='textfield01'> $dbdatos_1->nom_pes </span> </td>";
						
						//cantidad
						echo "<td align='right'><INPUT type='hidden'  name='cantidad_ref_$jj' value='$dbdatos_1->cant_pro'><span  class='textfield01'>".number_format($dbdatos_1->cant_pro ,0,",",".")."  </span> </td>";	
						
						//costo
						echo "<td align='right'><INPUT type='hidden'  name='costo_ref_$jj' value='$dbdatos_1->total_pro'><span  class='textfield01'>".number_format($dbdatos_1->total_pro ,0,",",".")."  </span> </td>";	
						
						//boton q quita la fila
						echo "<td><div align='center'>	
<INPUT type='button' class='botones' value='  -  ' onclick='removerFila_entrada(\"fila_$jj\",\"val_inicial\",\"fila_\",\"$dbdatos_1->total_pro\");'>
						</div></td>";
						echo "</tr>";
						$jj++;
					}
				}
				?>
				</table>			</td>
			</tr>			
		 <tr >
		  <td  colspan="8">
			  <table width="100%">
				<tr >
				<td  class="ctablasup"><div align="left">Observaciones:</div></td>
				<td  class="ctablasup"><div align="right">Resumen Venta </div></td>
				</tr>
				<tr >
				<td width="47%" ><div align="left" >
				  <textarea name="observaciones" cols="45" rows="3" class="textfield02"  onchange='buscar_rutas()' ><?=$dbdatos->obs_tras?></textarea>
				</div>				  </td>
				<td width="53%" ><div align="right"><span class="ctablasup">Total  Venta:</span>
				    <input name="todocompra" id="todocompra" type="text" class="textfield01" readonly="1" value="<? if($codigo !=0) echo $dbdatos_edi->tot_fac; else echo "0"; ?>"/>
				  </div>
                                  <?php 
$time_end = microtime_float(); 
$time = number_format($time_end - $time_start, 5); 

echo "$time"; 
?>  </td>
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

