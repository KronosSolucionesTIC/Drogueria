<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<?

if($guardar==1 and $codigo==0) { // RUTINA PARA INSERTAR REGISTROS NUEVOS
	$campos="(num_mov,fec_emi,fec_venci,tipo_mov,cod_ter,cod_mov_pago,obs_mov)";
	$valores="('".$num_fac."','".$fecha_emision."','".$fecha_venci."','".$tipo_movimientos."','".$proveedor."','".$factura."','".$observaciones."')" ;
	$ins_id=insertar_maestro("m_movimientos",$campos,$valores); 
	
	$campos="(cod_mov,cuenta_dmov,concepto_dmov,debito_dmov,credito_dmov)";
	for ($ii=1 ;  $ii <= $val_inicial; $ii++) 
		{			
			$valores="('".$ins_id."','".$_POST["cod_auxiliar_".$ii]."','".$_POST["cod_concepto_".$ii]."','".$_POST["debito_".$ii]."','".$_POST["credito_".$ii]."')";	
			$error=insertar("d_movimientos",$campos,$valores);
			
			//NIVEL 4
			//TOMA LOS 6 PRIMEROS CARACTERES DEL CODIGO CONTABLE DE LA CUENTA
			$cod_cuenta = $_POST["cod_auxiliar_".$ii];
			$dbc = new Database();
			$sqlc = "SELECT substring(cod_contable,1,6) as cadena FROM cuenta
			WHERE cod_cuenta = $cod_cuenta";
			$dbc->query($sqlc);
			$dbc->next_row();
			$cod = $dbc->cadena;
			
			//BUSCA EL CODIGO DE LA CUENTA MAYOR
			$dbcm = new Database();
			$sqlcm = "SELECT cod_cuenta FROM cuenta
			WHERE cod_contable = $cod";
			$dbcm->query($sqlcm);
			$dbcm->next_row();
			$cuenta = $dbcm->cod_cuenta;
			
			//INGRESA REGISTROS DEL NIVEL 4
			$valores="('".$ins_id."','".$cuenta."','".$_POST["cod_concepto_".$ii]."','".$_POST["debito_".$ii]."','".$_POST["credito_".$ii]."')";					
			$error=insertar("d_movimientos",$campos,$valores);
			
			
			//NIVEL 3
			//TOMA LOS 4 PRIMEROS CARACTERES DEL CODIGO CONTABLE DE LA CUENTA
			$cod_cuenta = $_POST["cod_auxiliar_".$ii];
			$dbc = new Database();
			$sqlc = "SELECT substring(cod_contable,1,4) as cadena FROM cuenta
			WHERE cod_cuenta = $cod_cuenta";
			$dbc->query($sqlc);
			$dbc->next_row();
			$cod = $dbc->cadena;
			
			//BUSCA EL CODIGO DE LA CUENTA MAYOR
			$dbcm = new Database();
			$sqlcm = "SELECT cod_cuenta FROM cuenta
			WHERE cod_contable = $cod";
			$dbcm->query($sqlcm);
			$dbcm->next_row();
			$cuenta = $dbcm->cod_cuenta;
			
			//INGRESA REGISTROS DEL NIVEL 3
			$valores="('".$ins_id."','".$cuenta."','".$_POST["cod_concepto_".$ii]."','".$_POST["debito_".$ii]."','".$_POST["credito_".$ii]."')";					
			$error=insertar("d_movimientos",$campos,$valores);
			
			//NIVEL 2
			//TOMA LOS 2 PRIMEROS CARACTERES DEL CODIGO CONTABLE DE LA CUENTA
			$cod_cuenta = $_POST["cod_auxiliar_".$ii];
			$dbc = new Database();
			$sqlc = "SELECT substring(cod_contable,1,2) as cadena FROM cuenta
			WHERE cod_cuenta = $cod_cuenta";
			$dbc->query($sqlc);
			$dbc->next_row();
			$cod = $dbc->cadena;
			
			//BUSCA EL CODIGO DE LA CUENTA MAYOR
			$dbcm = new Database();
			$sqlcm = "SELECT cod_cuenta FROM cuenta
			WHERE cod_contable = $cod";
			$dbcm->query($sqlcm);
			$dbcm->next_row();
			$cuenta = $dbcm->cod_cuenta;
			
			//INGRESA REGISTROS DEL NIVEL 2
			$valores="('".$ins_id."','".$cuenta."','".$_POST["cod_concepto_".$ii]."','".$_POST["debito_".$ii]."','".$_POST["credito_".$ii]."')";					
			$error=insertar("d_movimientos",$campos,$valores);
			
			//NIVEL 1
			//TOMA EL 1 PRIMER CARACTER DEL CODIGO CONTABLE DE LA CUENTA
			$cod_cuenta = $_POST["cod_auxiliar_".$ii];
			$dbc = new Database();
			$sqlc = "SELECT substring(cod_contable,1,1) as cadena FROM cuenta
			WHERE cod_cuenta = $cod_cuenta";
			$dbc->query($sqlc);
			$dbc->next_row();
			$cod = $dbc->cadena;
			
			//BUSCA EL CODIGO DE LA CUENTA MAYOR
			$dbcm = new Database();
			$sqlcm = "SELECT cod_cuenta FROM cuenta
			WHERE cod_contable = $cod";
			$dbcm->query($sqlcm);
			$dbcm->next_row();
			$cuenta = $dbcm->cod_cuenta;
			
			//INGRESA REGISTROS DEL NIVEL 1
			$valores="('".$ins_id."','".$cuenta."','".$_POST["cod_concepto_".$ii]."','".$_POST["debito_".$ii]."','".$_POST["credito_".$ii]."')";					
			$error=insertar("d_movimientos",$campos,$valores);
			
		}
	
	if ($error==1) {
		header("Location: con_movimiento.php?confirmacion=1&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
	}
	else
		echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>" ; 
}

if($guardar==1 and $codigo!=0) { // RUTINA PARA EDITAR REGISTROS NUEVOS
	$campos="desc_cuenta='".$nombres."',cod_mayor='".$mayor."',cod_contable='".$cod_contable."'";
	$error=editar("cuenta",$campos,'cod_cuenta',$codigo); 
	if ($error==1) {
		header("Location: con_movimiento.php?confirmacion=2&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
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
function datos_completos(){ 

	if(document.getElementById("proveedor").value < 1 || document.getElementById("fecha_emision").value == ""  || document.getElementById("fecha_venci").value == "" ) {
		return false;
	}
	else if( document.getElementById("total_debito").value != document.getElementById("total_credito").value ){
		alert('El valor del debito debe ser igual al credito');
	}
	else
	{
		return true;	
	}
}

function limpiar_combos(){
	document.getElementById('auxiliar').value = 0 ;
	document.getElementById('concepto').value = 0 ;
	document.getElementById('debito').value = "";
	document.getElementById('credito').value= "";
}

function  adicion() 
{
	if(document.getElementById('auxiliar').value < 1 || document.getElementById('concepto').value < 1 || document.getElementById('debito').value=="" || document.getElementById('credito').value=="" ) 
	{
		alert("Datos Incompletos")
		return false;
	}
	
	{
		Agregar_html_cuenta();
		limpiar_combos();
	}
}

function cargar_facturas(proveedor,factura) {
var combo=document.getElementById(factura);
combo.options.length=0;
var cant=0;
combo.options[cant] = new Option('Seleccione...','0'); 
cant++;
<?
		$i=0;
		$sqlc ="SELECT * FROM `m_movimientos` ";		
		$dbc= new  Database();
		$dbc->query($sqlc);
		while($dbc->next_row()){ 
		echo "if(document.getElementById(proveedor).value==$dbc->cod_ter){ ";	
		echo "combo.options[cant] = new Option('$dbc->num_mov','$dbc->cod_mov'); ";	
		echo "cant++; } ";
		}
?>
}

</script>
<script type="text/javascript" src="js/funciones.js"></script>
<script type="text/javascript" src="js/js.js"></script>
</head>
<body <?=$sis?>>
<form  name="forma" id="forma" action="man_movimiento.php"  method="post">
<table width="624" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
    <td bgcolor="#D1D8DE"><table width="100%" height="46" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td bgcolor="#FFFFFF">&nbsp;</td>
        <td bgcolor="#FFFFFF">&nbsp;</td>
        <td bgcolor="#FFFFFF" >&nbsp;</td>
        <td bgcolor="#FFFFFF" >&nbsp;</td>
        <td bgcolor="#FFFFFF" >&nbsp;</td>
        <td bgcolor="#FFFFFF" >&nbsp;</td>
        <td bgcolor="#FFFFFF" >&nbsp;</td>
        <td bgcolor="#FFFFFF" >&nbsp;</td>
        <td bgcolor="#FFFFFF" >&nbsp;</td>
        <td valign="middle" bgcolor="#FFFFFF" >&nbsp;</td>
        <td valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
        <td valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
      </tr>
      <tr>
         <td width="5" height="19">&nbsp;</td>
        <td width="20" ><img src="imagenes/icoguardar.png" alt="Nuevo Registro" width="16" height="16" border="0"  onclick="cambio_guardar()" style="cursor:pointer"/></td>
        <td width="61" class="ctablaform">Guardar</td>
        <td width="21" class="ctablaform"><a href="con_movimiento.php?confirmacion=0&amp;editar=<?=$editar?>&amp;insertar=<?=$insertar?>&amp;eliminar=<?=$eliminar?>"><img src="imagenes/cancel.png" alt="Cancelar" width="16" height="16" border="0" /></a></td>
        <td width="65" class="ctablaform">Cancelar </td>
        <td width="22" class="ctablaform"><a href="con_movimiento.php?confirmacion=0&amp;editar=<?=$editar?>&amp;insertar=<?=$insertar?>&amp;eliminar=<?=$eliminar?>"><img src="imagenes/iconolupa.gif" alt="Buscar" width="16" height="16" border="0" /></a></td>
        <td width="70" class="ctablaform">Consultar</td>
        <td width="21" class="ctablaform"></td>
        <td width="60" class="ctablaform">&nbsp;</td>
        <td width="24" valign="middle" class="ctablaform">&nbsp;</td>
        <td width="193" valign="middle"><label>
          <input type="hidden" name="editar"   id="editar"   value="<?=$editar?>">
		  <input type="hidden" name="insertar" id="insertar" value="<?=$insertar?>">
		  <input type="hidden" name="eliminar" id="eliminar" value="<?=$eliminar?>">
          <input type="hidden" name="codigo" id="codigo" value="<?=$codigo?>" />
        </label></td>
        <td width="67" valign="middle">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="4" valign="bottom"><img src="imagenes/lineasup3.gif" alt="." width="100%" height="4" /></td>
  </tr>
  <tr>
    <td class="textotabla1 Estilo1">MOVIMIENTOS CONTABLE:</td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE" valign="top">
	<table width="719" border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td class="textotabla1">Proveedor:</td>
	    <td><? combo_evento_where("proveedor","proveedor","cod_pro","nom_pro",'',"onchange='cargar_facturas(\"proveedor\",\"factura\")'"," where estado_proveedor = 1 ORDER BY nom_pro");  ?></td>
	    <td><span class="textorojo">*</span></td>
	    <td class="textotabla1">No factura:</td>
	    <td><input name="num_fac" id="num_fac" type="text" class="textfield2" value="<?=$dbm->desc_cuenta?>" /></td>
	    <td>&nbsp;</td>
	    </tr>
	  <tr>
	    <td class="textotabla1">No factura pago:</td>
	    <td><? combo_evento("factura","m_movimientos","cod_mov","num_mov","","","num_mov");  ?></td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>
	  <tr>
	    <td class="textotabla1">Fecha de emision:</td>
	    <td><input name="fecha_emision" id="fecha_emision" type="text" class="textfield2" value="<?=$dbm->desc_cuenta?>" />
	      <span class="textorojo"><img src="imagenes/date.png" alt="Calendario1" name="calendario1" width="18" height="18" id="calendario1" style="cursor:pointer"/></span></td>
	    <td><span class="textorojo">*</span></td>
	    <td><span class="textotabla1">Fecha de vencimiento</span></td>
	    <td><input name="fecha_venci" id="fecha_venci" type="text" class="textfield2" value="<?=$dbm->desc_cuenta?>" />
	      <span class="textorojo"><img src="imagenes/date.png" alt="Calendario2" name="calendario2" width="18" height="18" id="calendario2" style="cursor:pointer"/></span></td>
	    <td><span class="textorojo">*</span></td>
	    </tr>
	  <tr>
	    <td class="textotabla1">Tipo movimiento:</td>
	    <td><? combo_evento("tipo_movimientos","tipo_movimientos","cod_tmov","nom_tmov","","","nom_tmov");  ?></td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>
	  <tr>
	    <td class="textotabla1">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>
	  <tr>
	    <td colspan="6" class="textotabla1"><table  width="100%" border="1">
	      <tr >
	        <td width="15%"  class="ctablasup">Cuenta</td>
	        <td width="20%"  class="ctablasup">Concepto</td>
	        <td colspan="2"  class="ctablasup">Debito</td>
	        <td width="26%"  class="ctablasup">Credito
	          <label></label></td>
	        <td width="7%" class="ctablasup" align="center">Agregar:</td>
	        </tr>
	      <tr >
	        <td ><? combo_evento_where("auxiliar","cuenta","cod_cuenta","CONCAT(cod_contable,'-',desc_cuenta)","",""," WHERE nivel = 5 AND estado_cuenta = 1 ORDER BY cod_contable");  ?></td>
	        <td ><? combo_evento("concepto","concepto","cod_concepto","desc_concepto","","","desc_concepto");  ?></td>
	        <td colspan="2" align="center"><input name="debito" id="debito" type="text" class="caja_resalte1" /></td>
	        <td align="center"><input name="credito" id="credito" type="text" class="caja_resalte1" /></td>
	        <td align="center"><input id="mas" type='button'  class='botones' value='  +  '  onclick="adicion()" /></td>
	        </tr>
	      <tr >
	        <td  colspan="6"><table width="100%">
	          <tr id="fila_0">
	            <td width="30%"  class="ctablasup">Cuenta</td>
	            <td width="28%"  class="ctablasup">Concepto</td>
	            <td width="18%"   class="ctablasup">Debito</td>
	            <td width="17%"  class="ctablasup">Credito</td>
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
	          </table></td>
	        </tr>
	      <tr >
	        <td  colspan="6"><table width="100%">
	          <tr >
	            <td  class="ctablasup"><div align="left">Observaciones:</div></td>
	            <td  class="ctablasup"><div align="right">Resumen Venta </div></td>
	            </tr>
	          <tr >
	            <td width="47%" ><div align="left" >
	              <textarea name="observaciones" cols="45" rows="3" class="textfield02"  onchange='buscar_rutas()' ><?=$dbdatos->obs_tras?>
        </textarea>
	              </div></td>
	            <td width="53%" ><div align="right">
	              <p><span class="ctablasup">Total  Debito:</span>
	                <input name="total_debito" id="total_debito" type="text" class="textfield01" readonly="readonly" value="<? if($codigo !=0) echo $dbdatos_edi->tot_fac; else echo "0"; ?>"/>
	                </p>
	              <p><span class="ctablasup">Total  Credito:</span>
	                <input name="total_credito" id="total_credito" type="text" class="textfield01" readonly="readonly" value="<? if($codigo !=0) echo $dbdatos_edi->tot_fac; else echo "0"; ?>"/>
	                </p>
	              </div></td>
	            </tr>
	          </table></td>
	        </tr>
	      </table></td>
	    </tr>
	  </table></td>
  </tr>
  
  <tr>
    <td><div align="center"><img src="imagenes/spacer.gif" alt="." width="624" height="4" /></div></td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td height="30"  > <input type="hidden" name="guardar" id="guardar" />
	  <input type="hidden" name="val_inicial" id="val_inicial" value="<? if($codigo!=0) echo $jj-1; else echo "0"; ?>" /></td>
  </tr>
</table>
</form> 
</body>
<script type="text/javascript">	

Calendar.setup(
				{
					inputField  : "fecha_emision",      
					ifFormat    : "%Y-%m-%d",    
					button      : "calendario1" ,  
					align       :"T3",
					singleClick :true
				}
			);		
			

Calendar.setup(
				{
					inputField  : "fecha_venci",      
					ifFormat    : "%Y-%m-%d",    
					button      : "calendario2" ,  
					align       :"T3",
					singleClick :true
				}
			);			
</script>
</html>
