<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<?
if($codigo==0) 
   $codigo=-10; 
if ($codigo!="") {
	    $sql ="SELECT * FROM bodega1 WHERE cod_bod= $codigo";
		$dbdatos= new  Database();
		$dbdatos->query($sql);
		$dbdatos->next_row();
}

if($guardar==1 and $codigo==-10) { // RUTINA PARA  INSERTAR REGISTROS NUEVOS
	$campos="(nom_bod, apel_bod,max_cos_bod, iden_bod,digito_bod,dir_bod,tel_bod, dpto_cli,ciu_bod,mail_bod, tipo_bod ,propia, cod_lista ,dias_traslado ,dias_credito,cod_covinoc, fec_covinoc, cupo_au_covinoc, cupo_traslados,cod_bod_cli,regimen_cli,cod_ruta,estado_bodega1)";
	
	 $valores="('".$nombresito."','".$apellidos."','".$max_traslado."', '".$identificacion."','".$digito."','".$direccion."',  '".$telefono."','".$departamento."','".$ciudad."', '".$correo."', '1','".$propia."','".$lista."', '".$ven_traslado."','".$ven_factura."','".$cod_covinoc."','".$fec_covinoc."','".$max_credito."','".$max_traslado."','".$bodega."','".$v_regimen."','".$ruta."','1')" ;  
	
	$error=1;
	$id_cli=insertar_maestro("bodega1",$campos,$valores); 
	
	//enviar_alerta("Alerta de Creacion de Clientes ", "Se han Modificado los datos del cliente: $nombresito <a href='http://www.globater.com/sistema/man_bodegas.php?codigo=$id_cli&editar=1&insertar=1&eliminar=1'>Consultar Formulario</a>");		
	
	if ($error==1) {
		header("Location: con_bodegas.php?confirmacion=1&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
	}
	else
		echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>" ; 
}

if($guardar==1 and $codigo!=0) { // RUTINA PARA EDITAR REGISTROS 
	$campos="nom_bod='".$nombresito."',apel_bod='".$apellidos."', max_cos_bod='".$max_traslado."', iden_bod='".$identificacion."',digito_bod='".$digito."',dir_bod='".$direccion."',  tel_bod='".$telefono."', dpto_cli='".$departamento."',ciu_bod='".$ciudad."', mail_bod='".$correo."', propia='".$propia."',  cod_lista='".$lista."', dias_traslado='".$ven_traslado."', dias_credito= '".$ven_factura."', cod_covinoc= '".$cod_covinoc."', fec_covinoc= '".$fec_covinoc."', cupo_au_covinoc= '".$max_credito."', cupo_traslados= '".$max_traslado."' , cod_bod_cli= '".$bodega."', regimen_cli= '".$v_regimen."', cod_ruta = '".$ruta."'";
	
	$error=editar("bodega1",$campos,'cod_bod',$codigo); 
	//enviar_alerta("Alerta de Cambio de Datos ", "Se han Modificado los datos del cliente: $nombresito <a href='http://www.globater.com/sistema/man_bodegas.php?codigo=$codigo&editar=1&insertar=1&eliminar=1'>Consultar Formulario</a>");
	if ($error==1) {
		
		header("Location: con_bodegas.php?confirmacion=2&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
	}
	else
		echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>" ; 
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link href="css/stylesforms.css" rel="stylesheet" type="text/css" />
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
if (document.getElementById('nombresito').value == "" || document.getElementById('ruta').value == 0 || document.getElementById('identificacion').value == ""  )
	return false;
else
	return true;
}

function cargar_ciudad(departamento,ciudad) {
var combo=document.getElementById(ciudad);
combo.options.length=0;
var cant=0;
combo.options[cant] = new Option('Seleccione...','0'); 
cant++;
<?
		$i=0;
		$sqlc ="SELECT * FROM `ciudad` ";		
		$dbc= new  Database();
		$dbc->query($sqlc);
		while($dbc->next_row()){ 
		echo "if(document.getElementById(departamento).value==$dbc->departamento){ ";	
		echo "combo.options[cant] = new Option('$dbc->desc_ciudad','$dbc->cod_ciudad'); ";	
		echo "cant++; } ";
		}
?>
}

</script>

</head>
<body <?=$sis?>>
<form  name="forma" id="forma" action="man_bodegas.php"  method="post">
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
        <td width="21" class="ctablaform"><a href="con_bodegas.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/cancel.png" alt="Cancelar" width="16" height="16" border="0" /></a></td>
        <td width="65" class="ctablaform">Cancelar </td>
        <td width="22" class="ctablaform"><a href="con_bodegas.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/iconolupa.gif" alt="Buscar" width="16" height="16" border="0" /></a></td>
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
    <td class="textotabla1 Estilo1">CLIENTES:</td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE" valign="top">
	<table width="629" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="105" class="textotabla1">Nombres:</td>
        <td width="159"><input name="nombresito" id="nombresito" type="text" class="textfield2"  value="<?=$dbdatos->nom_bod?>" />
          <span class="textorojo">*</span></td>
        <td width="20" align="left" class="textorojo">&nbsp;</td>
        <td width="84" class="textotabla1">Nit/CC:</td>
        <td width="220"><input name="identificacion" id="identificacion" type="text" class="textfield2" onkeypress="return validaInt_evento(this)" value="<?=$dbdatos->iden_bod?>"  />
          <input name="digito" id="digito" type="text" maxlength="1" class="textfield0010" onkeypress="return validaInt_evento(this)" value="<?=$dbdatos->digito_bod?>"  />
          <span class="textorojo">*</span></td>
        <td width="41" class="textorojo">&nbsp;</td>
        </tr>
      <tr>
        <td class="textotabla1">Apellidos:</td>
        <td><input name="apellidos" id="apellidos" type="text" class="textfield2"  value="<?=$dbdatos->apel_bod?>" /></td>
        <td class="textorojo">&nbsp;</td>
        <td class="textotabla1">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="textorojo">&nbsp;</td>
      </tr>
      <tr>
        <td class="textotabla1">Direccion:</td>
        <td><input name="direccion" id="direccion" type="text" class="textfield2"  value="<?=$dbdatos->dir_bod?>" /></td>
        <td class="textorojo">&nbsp;</td>
        <td class="textotabla1">Telefonos:</td>
        <td><input name="telefono" id="telefono" type="text" class="textfield2"  value="<?=$dbdatos->tel_bod?>" /></td>
        <td class="textorojo">&nbsp;</td>
        </tr>
      <tr>
        <td class="textotabla1">Departamento:</td>
        <td><? combo_evento("departamento","departamento","cod_departamento","desc_departamento",$dbdatos->dpto_cli,"onchange='cargar_ciudad(\"departamento\",\"ciudad\")'","desc_departamento"); ?></td>
        <td class="textorojo">&nbsp;</td>
        <td class="textotabla1">Ciudad:</td>
        <td><? 
		if ($codigo == -10) {
			combo_evento_where("ciudad","ciudad","cod_ciudad","desc_ciudad","","","");
		}
		else {
		combo_evento_where("ciudad","ciudad","cod_ciudad","desc_ciudad",$dbdatos->ciu_bod,""," where departamento = $dbdatos->dpto_cli ");
		}?></td>
        <td class="textorojo">&nbsp;</td>
      </tr>
      <tr>
        <td class="textotabla1">E-mail:</td>
        <td><input name="correo" id="correo" type="text" class="textfield2"  value="<?=$dbdatos->mail_bod?>" /></td>
        <td class="textorojo">&nbsp;</td>
        <td class="textotabla1">&nbsp;</td>
        <td>&nbsp;</td>
        <td class="textorojo">&nbsp;</td>
        </tr>
	  <tr>
	    <td class="textotabla1">Fecha consulta: </td>
	    <td><input name="fec_covinoc" id="fec_covinoc" type="text" class="textfield2" readonly="-1"  value="<?=$dbdatos->fec_covinoc?>" />
	      <img src="imagenes/date.png" alt="Calendario" name="calendario" width="18" height="18" id="calendario" style="cursor:pointer"/></td>
	    <td class="textorojo">&nbsp;</td>
	    <td class="textotabla1">Dias factura: </td>
	    <td><input name="ven_factura" id="ven_factura" onkeypress="return validaInt('%d', this,event)"  type="text" class="textfield2"  value="<?=$dbdatos->dias_credito?>" /></td>
	    <td class="textorojo">&nbsp;</td>
	    </tr>
	  <tr>
	    <td class="textotabla1">L.Precio:</td>
	    <td><? combo("lista","listaprecio","cos_list","nom_list",$dbdatos->cod_lista); ?></td>
	    <td class="textorojo">&nbsp;</td>
	    <td class="textotabla1">Valor credito: </td>
	    <td><input name="max_credito" id="max_credito" type="text" class="textfield2" onkeypress="return validaInt('%d', this,event)"  value="<?=$dbdatos->cupo_au_covinoc?>" /></td>
	    <td class="textorojo">&nbsp;</td>
	    </tr>
		 <tr>
	    <td class="textotabla1">Bodega:</td>
	    <td><? combo("bodega","bodega","cod_bod","nom_bod",$dbdatos->cod_bod_cli); ?></td>
	    <td class="textorojo">&nbsp;</td>
	    <td class="textotabla1">Ruta:</td>
	    <td class="textotabla1">
		<? 
		combo_evento_where("ruta","ruta","cod_ruta","desc_ruta",$dbdatos->cod_ruta,""," where estado_ruta = 1");
		?>
		<span class="textorojo">*</span></td>
	    <td class="textorojo">&nbsp;</td>
	    </tr>
		 <tr>
		   <td class="textotabla1"><div align="left">Condicion Tributaria:</div></td>
		   <td colspan="5" class="textotabla1"><table width="296" border="0" align="left" cellspacing="0" class="linea_gris">
               <tr>
                 <th width="96" scope="col"><div align="left" class="textotabla1">R&eacute;gimen Comun: </div></th>
                 <?
				if ($dbdatos->regimen_cli=="COMUN"){
$comun="checked='checked'";}
else {
$simplificado="checked='checked'";}
?>
                 <th width="38" bgcolor="f0f0f0" scope="col"><div align="center">
                     <? if ($codigo==0) { ?>
                     <input name="v_regimen" type="radio"  value="COMUN" onclick="marcado=true" />
                     <? } else {?>
                     <input name="v_regimen" type="radio"<?=$comun?>  value="COMUN" />
                     <? } ?>
                 </div></th>
                 <th width="112" scope="col"><label class="arial12">
                    <div align="center" class="textotabla1">R&eacute;gimen Simplificado: </div>
                    </label>
                  <label></label></th>
                 <th width="42" bgcolor="f0f0f0" scope="col"> <div align="center">
                     <? if ($codigo==0) { ?>
                     <input name="v_regimen" type="radio"  value="SIMPLIFICADO" onclick="marcado=true" />
                     <? } else {?>
                     <input name="v_regimen" type="radio"<?=$simplificado?>  value="SIMPLIFICADO" />
                     <? } ?>
                 </div></th>
               </tr>
                      </table></td>
	      </tr>
		
	  	  <tr>
        <td colspan="6" valign="bottom"></td>
        </tr>
    </table></td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td height="30"  > <input type="hidden" name="guardar" id="guardar" />
	</td>
  </tr>
</table>
</form> 

</body>
<script type="text/javascript">
			
			Calendar.setup(
				{
					inputField  : "fec_covinoc",      
					ifFormat    : "%Y-%m-%d",    
					button      : "calendario" ,  
					align       :"T3",
					singleClick :true
				}
			);
			
</script>
</html>


