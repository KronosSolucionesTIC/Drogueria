<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<?

if ($codigo >0) { // BUSCAR DATOS
	 $sql =" SELECT * , (SELECT nom_bod FROM bodega WHERE cod_bod=cod_bod_sal_tras) AS bodega_salida, (SELECT nom_bod FROM bodega WHERE cod_bod=cod_bod_ent_tras) AS bodega_entrada FROM m_traslado WHERE cod_tras=$codigo";		
	$dbdatos= new  Database();
	$dbdatos->query($sql);
	$dbdatos->next_row();
	
	if(empty($bodega_salida) )
	$bodega_salida=$dbdatos->cod_bod_sal_tras;
}



if($guardar==1 and $codigo==0) 	{ // RUTINA PARA  INSERTAT REGISTROS NUEVOS
	$compos="(fec_tras,cod_bod_sal_tras,cod_bod_ent_tras,obs_tras,estado_m_traslado,cod_usu_tras)";
	$valores="('".$fecha."','".$bodega_salida."','".$bodega_entrada."','".$observaciones."','1','".$global[2]."')" ;
	$ins_id=insertar_maestro("m_traslado",$compos,$valores); 	
		
	if ($ins_id > 0) 
	{
		$compos="(cod_mtras_dtra,cod_tpro_dtra,cod_mar_dtra,cod_pes_dtra,cod_ref_dtra,cant_dtra)";
		
		for ($ii=1 ;  $ii <= $val_inicial + 1 ; $ii++) 
		{
			if($_POST["codigo_tipo_prodcuto_".$ii]!=NULL) 
			{
				$valores="('".$ins_id."','".$_POST["codigo_tipo_prodcuto_".$ii]."','".$_POST["codigo_marca_".$ii]."','".$_POST["codigo_peso_".$ii]."','".$_POST["codigo_referencia_".$ii]."','".$_POST["cantidad_ref_".$ii]."')" ;	
				$error=insertar("d_traslado",$compos,$valores); 

				kardex("resta",$_POST["codigo_referencia_".$ii],$bodega_salida,$_POST["cantidad_ref_".$ii],$_POST["costo_ref_".$ii],$_POST["codigo_peso_".$ii]);
				if($linea != 2){
					kardex("suma",$_POST["codigo_referencia_".$ii],$bodega_entrada,$_POST["cantidad_ref_".$ii],$_POST["costo_ref_".$ii],$_POST["codigo_peso_".$ii]);
				}
				$todocompra = $todocompra + $_POST["cantidad_ref_".$ii];
			}	
		
		}
		
		if($linea == 2){
			//INGRESO DE LA ENTRADA
			$campos="(fec_ment,fac_ment,obs_ment,cod_bod,total_ment,cod_prove_ment,usu_ment,estado_m_entrada)";
			$valores="('".date('Y-m-d')."','','CONVERSION TRASLADO No "."$ins_id','".$bodega_entrada."','".$todocompra."','','".$global[2]."','1')" ; 
			$ins_id=insertar_maestro("m_entrada",$campos,$valores); 
		
			$dbg = new Database();
			$sqlg = "SELECT * FROM grupo";
			$dbg->query($sqlg);
			while ($dbg->next_row()){
				$cantidad = 0;
				for ($ii=1 ;  $ii <= $val_inicial + 1 ; $ii++) 
				{
					$campos="(cod_ment_dent,cod_tpro_dent,cod_mar_dent,cod_pes_dent,cod_ref_dent,cant_dent,cos_dent)";
				
					if($_POST["cod_linea_".$ii]== $dbg->cod_grupo) 
					{
							$cantidad = $cantidad + $_POST["cantidad_ref_".$ii];
					}
				
				}	
					
					if($cantidad > 0){
						echo "<script language='javascript'> alert('ENTRO') </script>" ; 
						$dbp = new Database();
						$sqlp = "SELECT * FROM producto
						WHERE cod_pro = $dbg->cod_pro";
						$dbp->query($sqlp);
						$dbp->next_row();
						
						$tipo = $dbp->cod_tpro_pro;
						$marca = $dbp->cod_mar_pro;
						$cod_pro = $dbg->cod_pro;
						if($dbg->cod_grupo == 1){
							$cantidad = $cantidad * 12;
						}
						if($dbg->cod_grupo == 2){
							$cantidad = $cantidad / 2;
						}
						$valores="('".$ins_id."','".$tipo."','".$marca."','1','".$cod_pro."','".$cantidad."','".$cantidad."')" ;
						$error=insertar("d_entrada",$campos,$valores); 
						
						kardex("suma",$cod_pro,$bodega_entrada,$cantidad,0,1);
						
						$total_cant = $total_cant + $cantidad;
					}
				$compos="total_ment='".$total_cant."'";
				$error=editar("m_entrada",$compos,'cod_ment',$ins_id);
					
			}
		
		}
		header("Location: con_traslados.php?confirmacion=1&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
	}

else
	echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>" ; 		
}


if($guardar==1 and $codigo!=0) { // RUTINA PARA  editar REGISTROS 
	
	$compos="fec_tras='".$fecha."',  obs_tras='".$observaciones."' ";
	
	$error=editar("m_traslado",$compos,'cod_tras',$codigo); 
	
	$sql="select * from  d_traslado  where cod_mtras_dtra=$codigo ";
	$dbser= new  Database();	
	$dbser->query($sql);
	while($dbser->next_row())
	{			
		kardex("resta",$dbser->cod_ref_dtra,$bodega_entrada,$dbser->cant_dtra,"",$dbser->cod_pes_dtra);
		kardex("suma",$dbser->cod_ref_dtra,$bodega_salida,$dbser->cant_dtra,"",$dbser->cod_pes_dtra);
	}

	$sql="DELETE from  d_traslado  where cod_mtras_dtra=$codigo ";
	$dbser->query($sql);

	$compos="(cod_mtras_dtra,cod_tpro_dtra,cod_mar_dtra,cod_pes_dtra,cod_ref_dtra,cant_dtra)";		
	for ($ii=1 ;  $ii <= $val_inicial + 1 ; $ii++) 
	{
		if($_POST["codigo_tipo_prodcuto_".$ii]!=NULL) 
		{
			$valores="('".$codigo."','".$_POST["codigo_tipo_prodcuto_".$ii]."','".$_POST["codigo_marca_".$ii]."','".$_POST["codigo_peso_".$ii]."','".$_POST["codigo_referencia_".$ii]."','".$_POST["cantidad_ref_".$ii]."')" ;
			$error=insertar("d_traslado",$compos,$valores); 
			
			kardex("resta",$_POST["codigo_referencia_".$ii],$bodega_salida,$_POST["cantidad_ref_".$ii],$_POST["costo_ref_".$ii],$_POST["codigo_peso_".$ii]);
			kardex("suma",$_POST["codigo_referencia_".$ii],$bodega_entrada,$_POST["cantidad_ref_".$ii],$_POST["costo_ref_".$ii],$_POST["codigo_peso_".$ii]);
		}	
	}
		
	if ($error==1) {
		header("Location: con_traslados.php?confirmacion=2&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
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
		WHERE cant_ref_kar > 0 AND cod_bod_kar = $bodega_salida
		GROUP by  nom_tpro";		
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
		$sqlr =" SELECT DISTINCT kardex.cod_ref_kar,desc_ref,producto.cod_tpro_pro FROM   kardex
		LEFT OUTER JOIN producto ON (kardex.cod_ref_kar = producto.cod_pro)
		LEFT OUTER JOIN tipo_producto ON (producto.cod_tpro_pro = tipo_producto.cod_tpro)
		INNER JOIN referencia ON (referencia.cod_ref = producto.nom_pro)
		WHERE cant_ref_kar > 0 AND cod_bod_kar = $bodega_salida GROUP by  nom_pro";		
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
function cargar_codigo_talla(referencia,codigo,codigo_producto,cod_linea,bodega,talla,valor_lista){
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
var codigo_linea = "";
<?
		$sqlcod ="SELECT * FROM kardex
		INNER JOIN producto ON (kardex.cod_ref_kar = producto.cod_pro)";		
		$dbcod= new  Database();
		$dbcod->query($sqlcod);
		while($dbcod->next_row()){ 
		echo "if(document.getElementById(referencia).value==$dbcod->cod_ref_kar && document.getElementById(bodega).value==$dbcod->cod_bod_kar && $dbcod->cant_ref_kar > 0) {";	
		echo "codigo_articulo = '$dbcod->cod_pro'; ";
		echo "codigo_linea = '$dbcod->cod_gru_pro'; ";
		echo "item_articulo = '$dbcod->cod_fry_pro'; }";
		}
?>
document.getElementById(codigo_producto).value= codigo_articulo;
document.getElementById(codigo).value= item_articulo;
document.getElementById(cod_linea).value= codigo_linea;
}

function datos_completos()
{  

	if (document.getElementById('fecha').value == "" )
		return false;
	else
		return true;		

}

function  adicion() 
{
	
	if(document.getElementById('marca').value < 1 || document.getElementById('tipo_producto').value < 1 || document.getElementById('combo_referncia').value < 1 || document.getElementById('peso').value < 1  || document.getElementById('cantidad').value=="" ) 
	{
		alert("Datos Incompletos")
		return false;
	}
	

	if(buscar_datos_total('cantidad')==false)
		return false;
	
	var  vali_ref= anti_trampa(document.getElementById("combo_referncia").value, document.getElementById('peso').value);
	
	if(vali_ref==1)
	{
		alert("Esta Referencia Ya fue agregada  Si desea Modificar la Cantidad  Elimine el Registro y Agregue nuevamente")
		return false;
	}
	
	if(document.getElementById("cantidad").value>0 && document.getElementById("codigo_fry").value > ""  && document.getElementById('combo_referncia').value > 0  && document.getElementById('peso').value > 0  ) 
	{

		Agregar_html_traslado();					
		limpiar_combos();
		document.getElementById('marca').value=0;
		document.getElementById("codigo_fry").focus();
		return false;
		
	}
	else {
		alert("Ingrese una Referencia Valida junto con los demas Valores")
		document.getElementById("codigo_fry").focus();
	}
	
}




function enfocar(obj_ini,obj_fin){
if(window.event.keyCode==13)
{
  window.event.keyCode=0;
  document.getElementById(obj_fin).focus();
  return false;
}
}


function limpiar_combos()
{
	document.getElementById('tipo_producto').options.length=0;
	document.getElementById('combo_referncia').options.length=0;
	document.getElementById('codigo_fry').value="";
	document.getElementById('peso').options.length=0;
	document.getElementById('cantidad').value="";

return false;
}



function buscar_datos_total(opc) {
	var vec_datos_total = new Array;
	var vec_codigo=0;
	var vec_nombre=0; 
	var combo_llenar="";
	var xx=1 ;
	<?
	$dbdatos_total= new  Database();
	$sql=" SELECT distinct
  kardex.cod_ref_kar,
  kardex.cod_bod_kar,
  kardex.cant_ref_kar,
  producto.cod_fry_pro,
  producto.nom_pro,
  producto.iva_pro,
  producto.cod_tpro_pro,
  tipo_producto.nom_tpro,
  producto.cod_mar_pro,
  producto.cod_gru_pro,
  marca.nom_mar,
  kardex.cod_talla,
  peso.nom_pes,
  referencia.desc_ref
FROM
  kardex
  LEFT OUTER JOIN producto ON (kardex.cod_ref_kar = producto.cod_pro)
  LEFT OUTER JOIN referencia ON (referencia.cod_ref = producto.nom_pro)
  LEFT OUTER JOIN peso ON (kardex.cod_talla = peso.cod_pes)
  LEFT OUTER JOIN marca ON (producto.cod_mar_pro = marca.cod_mar)
  LEFT OUTER JOIN tipo_producto ON (producto.cod_tpro_pro = tipo_producto.cod_tpro)
WHERE
  cant_ref_kar > 0 AND   cod_bod_kar = $bodega_salida 

"; 

	$dbdatos_total->query($sql);
	$i = 0;
	while($dbdatos_total->next_row()){
		echo "vec_datos_total[$i]=  new Array('$dbdatos_total->cod_ref_kar'   ,'$dbdatos_total->cod_bod_kar' , '$dbdatos_total->cant_ref_kar' , '$dbdatos_total->cod_fry_pro' , \"$dbdatos_total->desc_ref\" , '$dbdatos_total->iva_pro' , '$dbdatos_total->cod_tpro_pro' , '$dbdatos_total->nom_tpro' , '$dbdatos_total->cod_mar_pro' , '$dbdatos_total->nom_mar' , '$dbdatos_total->cod_talla' , '$dbdatos_total->nom_pes' , '$dbdatos_total->cod_bod' , '$dbdatos_total->cod_lista' , '$dbdatos_total->nom_list' , '$dbdatos_total->cod_pro' , '$dbdatos_total->pre_list','$dbdatos_total->cod_gru_pro');\n";
		$i++;
	}
	?>

	if(opc=="marca") {
		limpiar_combos();
		vec_codigo=6;
		vec_nombre=7; 
		combo_llenar=document.getElementById('tipo_producto');
		combo_llenar.options.length=0;
		combo_llenar.options[0] = new Option('Seleccione','0'); 

		for (j=1; j<= vec_datos_total.length  ;j++)
		{
			if(document.getElementById('marca').value== vec_datos_total[j-1][8]) 
			{
				combo_llenar.options[xx] = new Option(vec_datos_total[j-1][vec_nombre],vec_datos_total[j-1][vec_codigo]);  
				xx++;
			}
		}
	}
	
	if(opc=="tipo_producto") {
		vec_codigo=0;
		vec_nombre=4; 
		combo_llenar=document.getElementById('combo_referncia');
		combo_llenar.options.length=0;
		combo_llenar.options[0] = new Option('Seleccione','0'); 

		for (j=1; j<= vec_datos_total.length  ;j++)
		{
			if(document.getElementById('tipo_producto').value== vec_datos_total[j-1][6]) 
			{
				combo_llenar.options[xx] = new Option(vec_datos_total[j-1][vec_nombre],vec_datos_total[j-1][vec_codigo]);  
				xx++;
			}
		}
	}	
	
	if(opc=="combo_referncia") {
		vec_codigo=10;
		vec_nombre=11; 
		combo_llenar=document.getElementById('peso');
		combo_llenar.options.length=0;
		combo_llenar.options[0] = new Option('Seleccione','0'); 
		
		for (j=1; j<= vec_datos_total.length  ;j++)
		{
			if(document.getElementById('combo_referncia').value== vec_datos_total[j-1][0]) 
			{
				document.getElementById('codigo_fry').value = vec_datos_total[j-1][3];
				document.getElementById('cod_linea').value=vec_datos_total[j-1][17];
			}
			
			if(document.getElementById('combo_referncia').value== vec_datos_total[j-1][0]) 
			{
				combo_llenar.options[xx] = new Option(vec_datos_total[j-1][vec_nombre],vec_datos_total[j-1][vec_codigo]);  
				xx++;
			}
		}
	}
	
	if(opc=="cantidad") {
		var cantidad_actu;
		for (j=1; j<= vec_datos_total.length  ;j++)
		{
			if(document.getElementById('combo_referncia').value== vec_datos_total[j-1][0] &&  document.getElementById('peso').value== vec_datos_total[j-1][10]) 
			{	
				if( parseInt(document.getElementById('cantidad').value) > parseInt(vec_datos_total[j-1][2])) {
					alert("La Cantidad No Corresponde")
					return false;
				}
				else 
					return true;
			}
		}
	}
	
	
	if(opc=="valor_lista") {
		for (j=1; j<= vec_datos_total.length  ;j++)
		{
			if(document.getElementById('combo_referncia').value== vec_datos_total[j-1][0] &&  vec_datos_total[j-1][10]>0) 
			{	
				document.getElementById('valor_lista').value=vec_datos_total[j-1][16];
			}
		}
		
		if(document.getElementById('valor_lista').value==""){
			alert("No hay Precio Asignado")
			return false;
		}
		else 
			return true;	
	}
	
	
	if(opc=="refe_codigo") {
		for (j=1; j<= vec_datos_total.length  ;j++)
		{
			if(document.getElementById('codigo_fry').value== vec_datos_total[j-1][3]) 
			{	
				document.getElementById('marca').value=vec_datos_total[j-1][8];
				
				document.getElementById('tipo_producto').options.length=0;
				document.getElementById('tipo_producto').options[0] = new Option(vec_datos_total[j-1][7],vec_datos_total[j-1][6]);  
				
				document.getElementById('combo_referncia').options.length=0;
				document.getElementById('combo_referncia').options[0] = new Option(vec_datos_total[j-1][4],vec_datos_total[j-1][0]);  
				
				buscar_datos_total('combo_referncia');
			}
		}
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
</script>

<script type="text/javascript" src="js/js.js"></script>
</head>
<body <?=$sis?>>
<div id="total">
<form  name="forma" id="forma" action="man_traslados.php"  method="post">
<table width="750" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
   <td bgcolor="#D1D8DE" >
   <table width="100%" height="30" border="0" cellspacing="0" cellpadding="0" align="center" > 
      <tr>
        <td width="5" height="30">&nbsp;</td>
        <td width="20" ><img src="imagenes/icoguardar.png" alt="Nuevo Registro" width="16" height="16" border="0" onClick="cambio_guardar()" style="cursor:pointer"/></td>
        <td width="61" class="ctablaform">Guardar</td>
        <td width="21" class="ctablaform"><a href="con_cargue.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/cancel.png" alt="Cancelar" width="16" height="16" border="0" /></a></td>
        <td width="65" class="ctablaform">Cancelar </td>
        <td width="21" class="ctablaform"></td>
        <td width="60" class="ctablaform">&nbsp;</td>
        <td width="24" valign="middle" class="ctablaform">&nbsp;</td>
        <td width="193" valign="middle">
          <input type="hidden" name="editar"   id="editar"   value="<?=$editar?>">
		  <input type="hidden" name="insertar" id="insertar" value="<?=$insertar?>">
		  <input type="hidden" name="eliminar" id="eliminar" value="<?=$eliminar?>">
          <input type="hidden" name="codigo" id="codigo" value="<?=$codigo?>" />
          <input type="hidden" name="bodega_salida" id="bodega_salida" value="<?=$bodega_salida?>" /></td>
        
        <td width="67" valign="middle">&nbsp;</td>
      </tr>
    </table>
	</td>
  </tr>
  <tr>
    <td height="4" valign="bottom"><img src="imagenes/lineasup3.gif" alt="." width="100%" height="4" /></td>
  </tr>
  <tr>
    <td class="textotabla01">TRASLADOS INVENTARIO :</td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
        <td width="50" class="textotabla1">Fecha:</td>
        <td width="164">
          <input name="fecha" type="text" class="fecha" id="fecha" readonly="1" value="<?=$dbdatos->fec_tras ?>" />
          <img src="imagenes/date.png" alt="Calendario" name="calendario" width="16" height="16" border="0" id="calendario" style="cursor:pointer"/></td>
        <td width="8" class="textorojo">*</td>
        <td width="93" rowspan="3" class="textotabla1" valign="top">Observaicones:</td>
        <td rowspan="3">
		  <textarea name="observaciones" cols="45" rows="4" class="textfield02"  ><?=$dbdatos->obs_tras?></textarea></td>
        </tr>
      
       <tr>
         <td class="textotabla1">Bodega Salida </td>
         <td><span class="textotabla1">
           <? 
		   $dbdatos333= new  Database();
		   
		 if ($codigo > 0 ) {
			echo "$dbdatos->bodega_salida <input name='bodega_salida' type='hidden' id='bodega_salida' value='$dbdatos->cod_bod_sal_tras' />";
			
			}
		 else {
			$sql ="select * from bodega where cod_bod=$bodega_salida "; 
			$dbdatos333->query($sql);
			while($dbdatos333->next_row()){
				echo "$dbdatos333->nom_bod <input name='bodega_salida' type='hidden' id='bodega_salida' value='$bodega_salida' />";
				}
			}
			 ?>
         </span></td>
         <td><span class="textorojo">*</span></td>
         </tr>
       
	         <tr>
         <td class="textotabla1">Bodega Entrada </td>
         <td class="textotabla1">
		 
		 <? 
		 if ($codigo > 0 ) 
		 	echo "$dbdatos->bodega_entrada<input name='bodega_entrada' type='hidden' id='bodega_entrada' value='$dbdatos->cod_bod_ent_tras'/>";
		 else   {
		 	$sql ="select * from bodega where cod_bod=$bodega_entrada "; 
			$dbdatos333->query($sql);
			while($dbdatos333->next_row()){
				$linea = $dbdatos333->cod_linea;
				echo "$dbdatos333->nom_bod  $linea <input name='linea' type='hidden' value='$linea' /><input name='bodega_entrada' type='hidden' id='bodega_salida' value='$bodega_entrada' />";
				}
			}
			?>
			</td>
         
		 
		 <td><span class="textorojo">*</span></td>
         </tr>
       
	   
	   <tr>
        <td colspan="5" class="textotabla1" >
		<table  width="99%" border="1">
         
          <tr>
            <td  class="ctablasup">Categoria</td>
            <td  class="ctablasup">Tipo Producto </td>
			<td  class="ctablasup">Referencia</td>
            <td  class="ctablasup">Codigo</td>
			<td colspan="2"  class="ctablasup">Talla</td>
            <td   class="ctablasup">Cantidad</td>
			<td width="4%" class="ctablasup" align="center">Agregar:</td>
          </tr>
          <tr >
            <td ><? 
  			$sql="SELECT cod_mar_pro,nom_mar  FROM kardex 
			INNER JOIN producto ON (kardex.cod_ref_kar = producto.cod_pro)  
 			INNER JOIN marca ON (producto.cod_mar_pro = marca.cod_mar)
			WHERE  cod_bod_kar = $bodega_salida AND cant_ref_kar > 0
			GROUP BY  kardex.cod_bod_kar, cod_mar_pro ";
			combo_sql_evento("marca","","cod_mar_pro","nom_mar","",$sql," onchange=\"cargar_tipo_producto('marca','tipo_producto','bodega_salida') \"");  ?></td>
			<td ><select size="1" id="tipo_producto" name="tipo_producto" class='SELECT' onchange="cargar_referencia('tipo_producto','combo_referncia','bodega_salida') " >
			  </select></td>
			<td align="center"><select size="1" id="combo_referncia" name="combo_referncia"  class='SELECT'  onchange="cargar_codigo_talla('combo_referncia','codigo_fry','codigo_producto','cod_linea','bodega_salida','peso','valor_lista')">
			  </select>			  <input name="text" type="hidden" id="codigo_producto" />
			  <input name="cod_linea" type="hidden" id="cod_linea" /></td>
			 <td align="center"><input name="codigo_fry" id="codigo_fry" type="text" class="caja_resalte1" onkeypress=" return valida_evento(this,'peso')"  onchange="buscar_datos_total('refe_codigo')" onblur="buscar_datos_total('refe_codigo')"></td>
			   <td colspan="2" align="center"><? combo_evento("peso","peso","cod_pes","nom_pes","","  ", "nom_pes"); ?>						</td>
			 <td align="center"><input name="cantidad" type="text" class="caja_resalte" id="cantidad" onkeypress="return validaInt_evento(this,'mas')"/></td>
			 <td align="center"> 
			 <input id="mas" type='button'  class='botones' value='  +  '  onclick="adicion()">			 </td>
          </tr>
		      
		  <tr >
		  <td  colspan="8">
		  
		  
		  
			  <table width="100%">
				<tr id="fila_0">

				<td  class="ctablasup">Tipo Producto </td>
				<td  class="ctablasup">Categoria</td>
				<td   class="ctablasup">Talla</td>
				<td  class="ctablasup">Referencia:</td>
				<td  class="ctablasup">Codigo</td>
				<td   class="ctablasup">Cantidad:              </td>
				
				<td width="4%" class="ctablasup" align="center">Borrar:</td>
				</tr>
				<?
				if ($codigo!="") { // BUSCAR DATOS
					$sql =" SELECT * FROM d_traslado LEFT JOIN tipo_producto ON tipo_producto.cod_tpro=d_traslado.cod_tpro_dtra LEFT JOIN marca ON marca.cod_mar=d_traslado.cod_mar_dtra LEFT JOIN peso ON peso.cod_pes=d_traslado.cod_pes_dtra LEFT JOIN producto ON producto.cod_pro=d_traslado.cod_ref_dtra WHERE cod_mtras_dtra=$codigo  ORDER BY cod_dtra ";//=		
					$dbdatos_1= new  Database();
					$dbdatos_1->query($sql);
					$jj=1;
					//echo "<table width='100%'>";
					while($dbdatos_1->next_row()){ 
						echo "<tr id='fila_$jj'>";
						//tipo de producto
						echo "<td><INPUT type='hidden'  name='codigo_tipo_prodcuto_$jj' value='$dbdatos_1->cod_tpro_dtra'><span  class='textfield01'> $dbdatos_1->nom_tpro </span> </td>";
						
						//cmarca
						echo "<td  ><INPUT type='hidden'  name='codigo_marca_$jj' value='$dbdatos_1->cod_mar_dtra'><span class='textfield01'> $dbdatos_1->nom_mar </span> </td>";	
						
						//peso empaque
						echo "<td   ><INPUT type='hidden'  name='codigo_peso_$jj' value='$dbdatos_1->cod_pes_dtra'><span  class='textfield01'> $dbdatos_1->nom_pes </span> </td>";
						
						//referencia
						echo "<td  ><INPUT type='hidden'  name='codigo_referencia_$jj' value='$dbdatos_1->cod_ref_dtra'><span  class='textfield01'> $dbdatos_1->nom_pro </span> </td>";
						
						//% codigo barra
						echo "<td ><INPUT type='hidden'  name='codigo_fry_$jj' value='$dbdatos_1->cod_fry_pro'><span  class='textfield01'> $dbdatos_1->cod_fry_pro </span> </td>";
						
						//cantidad
						echo "<td align='right'><INPUT type='hidden'  name='cantidad_ref_$jj' value='$dbdatos_1->cant_dtra'><span  class='textfield01'>".number_format($dbdatos_1->cant_dtra ,0,",",".")."  </span> </td>";	
						
						//boton q quita la fila
						echo "<td><div align='center'>	
<INPUT type='button' class='botones' value='  -  ' onclick='removerFila_entrada(\"fila_$jj\",\"val_inicial\",\"fila_\",\"0\");'>
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
		  <div style="display:none">
			  <table width="100%">
				<tr >
				<td  class="ctablasup"><div align="right">Resumen de Compra</div></td>
				</tr>
				<tr >
				<td ><div align="right" >Total  Compra:
				    <input name="todocompra" id="todocompra" type="text" class="textfield01" readonly="1" value="<? if($codigo !=0) echo $dbdatos->total_ment; else echo "0"; ?>"/>
                    <textarea name="tipo_referencias"  id="tipo_referencias"   cols="45" rows="4" style="display:inline" ></textarea>
				</div>				  </td>
				</tr>
				</table>			
		</div>
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


<script type="text/javascript">
			Calendar.setup(
				{
				inputField  : "fecha",      
				ifFormat    : "%Y-%m-%d",    
				button      : "calendario" ,  
				align       :"T3",
				singleClick :true
				}
			);
</script>
<div  id="relacion" align="center" style="display:none" >
<!-- <div  id="relacion" align="center" >-->
<table width="413" border="0" cellspacing="0" cellpadding="0" bgcolor="#D1D8DE" align="center">
   <tr id="pongale_0" >
    <td width="81" class="textotabla1"><strong>Referencia:</strong></td>
    <td width="332" class="textotabla1"><strong id="serial_nombre_"> </strong>
      <input type="hidden" name="textfield3"  id="ref_serial_"/>
	  <input type="hidden" name="textfield3"  id="campo_guardar"/>
	  </td>
   </tr>
   
   
   
   <tr>
     <td class="textotabla1" colspan="2"><div align="center">
       <input type="button" name="Submit" value="Guardar"  onclick="guardar_serial()"/>  
	    <input type="button" name="Submit" value="Cancelar"  onclick="limpiar()" id="cancelar" />  
       <input type="hidden" name="textfield32"  id="catidad_seriales" value="0"/>
     </div></td>
   </tr>
</table>
</div>

</body>

</html>
