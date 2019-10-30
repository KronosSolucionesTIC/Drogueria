<?php include("lib/database.php");?>
<?php include("js/funciones.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Facturaciòn</title>
<script type="text/javascript" src="informes/inf.js"></script>
<?php inicio() ?>
<script language="javascript">
function enter_a_tab(obj, e) {
   var e = (typeof event != 'undefined') ? window.event : e; // IE : Moz 
    if (e.keyCode == 13) {
    	for(var j = 0; j <= 7; j++){
        	var ele = document.forms[j].elements;
       		for (var i = 0; i < ele.length; i++) {
            	var q = (i == ele.length - 1) ? 0 : i + 1; // if last element : if any other 
            	if (obj == ele[i]) {
                	ele[q].focus();
                	break
            	}
        	}
        }
    //return false;
    }   
}

//--INI--CARGA LA LISTA DEL CLIENTE
function cargar_lista(cliente,lista){
	<?php 
	$db = new Database();
	$sql = "SELECT cod_lista,cod_bod FROM bodega1";
	$db->query($sql);
	while ($db->next_row()){
		echo "if(document.getElementById(cliente).value==$db->cod_bod) {";
		echo "cod_lista = '$db->cod_lista';";
		echo "}"; 
	}
	?>
	document.getElementById(lista).value = cod_lista;
}
//--FIN--CARGA LA LISTA DEL CLIENTE

//--INI--LIMPIAR DATOS
function limpiar_datos(contador){
	document.getElementById('cant_pro_' + contador).value = 1;
	document.getElementById('cod_fry_' + contador).value = "";
	document.getElementById('cod_fry_' + contador).focus();
}
//--FIN--LIMPIAR DATOS

//--INI--ENVIA EL FORMULARIO
function enviar(contador){
	texto = 'form_' + contador;
	document.getElementById(texto).submit();
	limpiar_mesa(contador);
}
//--FIN--ENVIA EL FORMULARIO

//--INI--LIMPIAR MESA
function limpiar_mesa(contador){
	document.getElementById('tot_' + contador).value= 0;
	document.getElementById('val_inicial_' + contador).value = 0;
	
	//TOMA LA CANTIDAD DE ITEM Y BORRA LAS FILAS
	var num = document.getElementById('val_total_' + contador).value;

	for(i=1; i<=num; i++){
		if(document.getElementById('fila_' + contador + i)){
			var fila = document.getElementById('fila_' + contador + i);
			fila.parentNode.removeChild(fila);
		}
	}
}
//--FIN--LIMPIAR MESA

//--INI--CALCULAR TOTAL
function calcular_total(tot_fac,val_total,contador,linea,campo){
	if(document.getElementById('item_pago_' + contador + linea).checked == true){
		document.getElementById('contado_' +  contador).checked = 1;
		document.getElementById('pago_cont_' +  contador).disabled = false;
		tot = parseInt(document.getElementById(tot_fac).value) +  parseInt(val_total);
		document.getElementById(tot_fac).value =  tot ;		
		document.getElementById('pago_cont_' +  contador).value = tot;
	}
	else {
		document.getElementById('contado_' +  contador ).checked = 1;
		document.getElementById('pago_cont_' +  contador).disabled = false;
		tot = parseInt(document.getElementById(tot_fac).value) - parseInt(val_total);
		document.getElementById(tot_fac).value =  tot ;		
		document.getElementById('pago_cont_' +  contador).value = tot;
	}
}
//--FIN--CALCULAR TOTAL


//--INI--CARGA EL PRODUCTO
function adicion_producto(contador,cliente,lista,codigo,inicial,cant_pro,tot_fac){
	//--INI--VALIDA EL CAMPO CODIGO
	if(document.getElementById(codigo).value == "" || document.getElementById(codigo).value == 0){
		document.getElementById('paga_con_' + contador).focus();
	}//--FIN--VALIDA EL CAMPO CODIGO
	else{
		//--INI--VALIDA EL CAMPO CANTIDAD
		if(document.getElementById(cant_pro).value == "" || document.getElementById(cant_pro).value <= 0){
			alert('La cantidad debe ser mayor a 0');
			return false;
		}//--FIN--VALIDA EL CAMPO CANTIDAD
		
		else {
		
		//--INI--VALIDA QUE EXISTA EL PRODUCTO
		var codigo_fry = '';
		var codigo_pro = 0;
		var referencia = '';
		
		//--INI--CARGA EL CODIGO DE BARRAS, CODIGO, Y NOMBRE
	<?
	$db = new Database();
	$sql = "SELECT * FROM producto
	WHERE estado_producto = 1";
	$db->query($sql);
	while($db->next_row()){
		echo "if(document.getElementById(codigo).value == '$db->cod_fry_pro'){";
		echo "codigo_fry = '$db->cod_fry_pro';";
		echo "codigo_pro = '$db->cod_pro';";
		echo "referencia = '$db->nom_pro';";
		echo "cod_tpro = '$db->cod_tpro_pro';";
		echo "cod_mar = '$db->cod_mar_pro';";
		echo "cod_talla = '1';";
		echo "}";
	}
	?>
	
	}
	//--FIN--CARGA EL CODIGO DE BARRAS, CODIGO, Y NOMBRE
	
		
		if(codigo_fry == ''){
			alert('No existe el producto');
			<?
			echo 'document.getElementById(codigo).focus();'
			?>
			return false;
		}
		//--FIN--VALIDA QUE EXISTA EL PRODUCTO
		
	
	//--INI--VALIDA EL CAMPO DE CLIENTE
	if (document.getElementById(cliente).value == 0){
		alert('Seleccione un cliente para el valor del articulo');
		return false;
	}
	//--FIN--VALIDA EL CAMPO DE CLIENTE
	
	//--INI--CARGA LA LISTA DE PRECIOS
	else{
		valor_uni = 0;
		<?php 
		//CONSULTA LA LISTA
		$db = new Database();
		$sql = "SELECT * FROM listaprecio 
		INNER JOIN bodega1 ON  bodega1.cod_lista = listaprecio.cos_list";
		$db->query($sql);
		while($db->next_row()){
			echo "if(document.getElementById(cliente).value == $db->cod_bod){";
			echo "document.getElementById(lista).value = '$db->cos_list';";
			echo "}";
		}
		//

		$db = new Database();
		$sql = "SELECT * FROM det_lista";
		$db->query($sql);
		while($db->next_row()){
			echo "if(document.getElementById(lista).value == $db->cod_lista && codigo_pro == $db->cod_pro){";
			echo "valor_uni = '$db->pre_list';";
			echo "}";
		}
		?>
		
		//--INI--VALIDA QUE EL VALOR SEA MAYOR A 0
		if(valor_uni <= 0){
			alert('El articulo no tiene precio');
			return false;
		}
		//--FIN--VALIDA QUE EL VALOR SEA MAYOR A 0
		
	//--FIN--CARGA LA LISTA DE PRECIOS
	
		//--INI--VALIDA QUE LA REFERENCIA NO SE REPITA
		items = document.getElementById(inicial).value;
		for(j=1; j<=items; j++){
			if(document.getElementById('cod_pro_' + contador + j)){
				cod = document.getElementById('cod_pro_' + contador + j).value;
				if(cod == codigo_fry){
					alert('La referencia ya se agrego verifique el producto');
					return false;
				}
			}
		}
		//--FIN--VALIDA QUE LA REFERENCIA NO SE REPITA

		
		//--INI--DIBUJA LOS OBJETOS
		Agregar_html_item(contador,inicial,codigo_fry,codigo_pro,referencia,valor_uni,cant_pro,tot_fac,cod_tpro,cod_mar,cod_talla);
		//--FIN--DIBUJA LOS OBJETOS
		
		//--INI--LIMPIA LOS CAMPOS
		limpiar_datos(contador);
		//--FIN--LIMPIA LOS CAMPOS
		}
	}
}
//--FIN--CARGA EL PRODUCTO

//pone el foco en codigo
function foco(contador){
	document.getElementById('cod_fry_' + contador).focus();
}

function calcular_cambio(total,paga_con,cambio){
	paga = parseInt(document.getElementById(paga_con).value);
	compra = parseInt(document.getElementById(total).value);
	valor_cambio = paga - compra;
	if(valor_cambio < 0){
		alert('El pago debe ser igual o mayor');
		document.getElementById(cambio).value = 0;
		return false;
	}
	document.getElementById(cambio).value = valor_cambio;
	document.getElementById(cambio).focus();
}

function guarda(e,contador) {
   var e = (typeof event != 'undefined') ? window.event : e; // IE : Moz 
    if (e.which == 43) {	
    	document.getElementById('cod_fry_' + contador ).value = "";
    	document.getElementById('guarda_fac').focus();
    //return false;
    }
    if (e.which == 45){
    	document.getElementById('cod_fry_' + contador ).value = "";
    	document.getElementById('cant_pro_' + contador).focus();
    }    
}

function calcula(e){
   var e = (typeof event != 'undefined') ? window.event : e; // IE : Moz 
    if (e.keyCode == 13) {
    	contador = document.getElementById('conta').value;

    	calcular_cambio('tot_' + contador,'paga_con_' + contador,'cambio_' + contador);
    }   
}

function imprime(e){
   var e = (typeof event != 'undefined') ? window.event : e; // IE : Moz 
    if (e.keyCode == 13) {
    	contador = document.getElementById('conta').value;
    	foco(1);
    	enviar(contador);
    }   
}

function valida_existe(contador){

}
</script>
<script type="text/javascript" src="js/js.js"></script><link href="css/stylesforms.css" rel="stylesheet" type="text/css" />
<link href="css/styles2.css" rel="stylesheet" type="text/css" />
<link href="informes/styles.css" rel="stylesheet" type="text/css" />
<link href="css/styles1.css" rel="stylesheet" type="text/css" />
</head>
<body <?=$sis?> onload="foco(1);">
<div id="total">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left" >
  <tr>
    <td height="4" valign="bottom"><img src="imagenes/lineasup3.gif" alt="." width="100%" height="4" /></td>
  </tr>
  <tr>
    <td class="textotabla01">&nbsp;</td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4"/></td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	   <tr>
	     <td width="750" class="textotabla1" >
                    	 <?php 
			 //CONSULTO LAS MESAS
			if($codigo == 0){
				$db = new Database();
				$sql = "SELECT * FROM mesas 
				WHERE estado_mesa = 1";
				$db->query($sql);
				$i=1;
				while($db->next_row()){
			 ?>
             <div style="display:inline" id='<?php echo 'div_'.$i;?>'>
             <form  name='<?php echo 'form_'.$i;?>' id='<?php echo 'form_'.$i;?>' action="man_fact.php"  method="post" target="_blank">
	       <table  width="100%" border="1">
             <tr>
               <td colspan="2" class="ctablasup"><div align="center">
                 <?=$db->nom_mesa?>
               </div></td>
               </tr>
             <tr>
               <td width="28%" class="textotabla01"><div align="right">Cliente:
                 
               </div></td>
               <td width="72%" class="textotabla01"><div align="left">
                 <?php combo_evento_where2("cliente_".$i,"bodega1","cod_bod","nom_bod",$bd->cod_bod,"onkeypress=\"return enter_a_tab(cliente_$i,event)\" onchange=\"cargar_lista('cliente_$i','lista_$i') \""," where estado_bodega1 = 1 order by nom_bod"); ?>
               </div></td>
               </tr>
             <tr>
               <td class="textotabla01"><div align="right">Cantidad:</div></td>
               <td class="textotabla01"><div align="left">
                 <input name='<?php echo 'cant_pro_'.$i;?>' id="<?php echo 'cant_pro_'.$i;?>" type="text" class="caja_resalte1" onkeypress="<?php echo "return enter_a_tab(cant_pro_$i,event)"?>" value = '1'/>
               </div></td>
               </tr>
             <tr>
               <td height="48" class="textotabla01"><div align="right">Codigo producto:</div></td>
               <td height="48" class="textotabla01"><div align="left">
                 <input name='<?php echo 'cod_fry_'.$i;?>' id="<?php echo 'cod_fry_'.$i;?>" type="text" class="caja_resalte1" onkeypress="guarda(event,1);return enter_a_tab(this,event);" />
                 <input id="mas2" type='button'  class='botones' value='  +  '  onclick="<?php echo "adicion_producto('$i','cliente_$i','lista_$i','cod_fry_$i','val_inicial_$i','cant_pro_$i','tot_$i')";?>" />
               </div></td>
             </tr>
             <tr>
               <td height="48" class="textotabla01"><div align="right">Listado de productos:</div></td>
               <td height="48" class="textotabla01"><div align="center">
                 <table width="100%" border="1" class="textotabla01">
                   <tr id="<?php echo 'fila_'.$i.'0';?>">
                     <td class="textotabla01"><div align="center">CODIGO</div></td>
                     <td class="textotabla01"><div align="center">REFERENCIA</div></td>
                     <td class="textotabla01"><div align="center">CANTIDAD</div></td>
                     <td class="textotabla01"><div align="center">VALOR UNI</div></td>
                     <td class="textotabla01"><div align="center">VALOR TOT</div></td>
                     <td class="textotabla01"><div align="center">QUITAR</div></td>
                    </tr>
                 </table>
               </div></td>
             </tr>
              <tr>
                <td colspan="2" class="textotabla01"><div align="right"><span class="ctablasup">Total  Venta:</span>
                  <input name="<?php echo 'tot_'.$i;?>" id="<?php echo 'tot_'.$i;?>" type="text" class="textotabla01" readonly="readonly" value="0" />
                </div></td>
              </tr>
                            <tr>
                <td colspan="2" class="textotabla01"><div align="right"><span class="ctablasup">Paga con:</span>
                  <input name="<?php echo 'paga_con_'.$i;?>" id="<?php echo 'paga_con_'.$i;?>" type="text" class="textotabla01" onkeypress="calcula(event);" value="0" />
                </div></td>
              </tr>
                            <tr>
                <td colspan="2" class="textotabla01"><div align="right"><span class="ctablasup">Cambio:</span>
                  <input name="<?php echo 'cambio_'.$i;?>" id="<?php echo 'cambio_'.$i;?>" type="text" class="textotabla01" onkeypress="imprime(event);" readonly="readonly" value="0" />
                </div></td>
              </tr>

              <tr>
                <td colspan="2" class="textotabla01"><div align="center">
                  <input type="button" value="Guardar" id="guarda_fac" class="botones" onclick="<?php echo 'enviar('.$i.')';?>"/>
                  <input type="button" value="Limpiar" class="botones" onclick="<?php echo 'limpiar_mesa('.$i.')';?>"/>
                  <input type="hidden" name='<?php echo 'lista_'.$i;?>' id="<?php echo 'lista_'.$i;?>" />
                  <input type="hidden" name='<?php echo 'val_inicial_'.$i;?>' id='<?php echo 'val_inicial_'.$i;?>' value="<?php if($codigo!=0) echo $jj-1; else echo "0"; ?>" />
                  <input type="hidden" name='<?php echo 'val_total_'.$i;?>' id='<?php echo 'val_total_'.$i;?>' value="<?php if($codigo!=0) echo $jj-1; else echo "0"; ?>" />
                  <input type="hidden" name='conta' id='conta' value="<?php if($codigo!=0) echo $jj-1; else echo $i; ?>" />
                </div></td>
              </tr>
               <?
			   $i++; 
			   ?>
               </table>
               </form>
               </div>
                           <?
			   } 
			}
			   ?>
	     </table>
	    </td>
	  </tr>
    </table>
<tr>

  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</div>
</body>
</html>

