<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<?
if ($codigo!="") {
$sql ="SELECT * FROM rsocial WHERE cod_rso=$codigo";
$dbdatos= new  Database();
$dbdatos->query($sql);
$dbdatos->next_row();
}


if($guardar==1 and $codigo==0) { // RUTINA PARA  INSERTAR REGISTROS NUEVOS
	if($logo != NULL){ 
		$slashes = explode("\\",$logo);
		$nameLogo = $slashes[count($slashes) - 1];
		$pathLogo = stripslashes($logo);
		copy("$pathLogo","proyecto/$nameLogo");
		$compos="(nom_rso,nit_rso, logo_rso, desc1_rso, desc2_rso,reg_rso,tel_rso,dir_rso,num_fac_rso,num_fac_rso,email,estado_rsocial)";
		$valores="('".$nombres."','".$nit."','".$nameLogo."','".$leyenda1."','".$leyenda2."','".$regimenes."','".$tel."','".$dir."','".$num_factura."','".$email."','1')" ; 
		$error=insertar("rsocial",$compos,$valores); 
	}
	else {
		$compos="(nom_rso,nit_rso,desc1_rso, desc2_rso,reg_rso,tel_rso,dir_rso,num_fac_rso,email,estado_rsocial)";
		$valores="('".$nombres."','".$nit."','".$leyenda1."','".$leyenda2."','".$regimenes."','".$tel."','".$dir."','".$num_factura."','".$email."','1')" ;
		
		$error=insertar("rsocial",$compos,$valores); 
	}
	if ($error==1) {
		header("Location: con_rsocial.php?confirmacion=1&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
	}
	else
		echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>" ; 
}

if($guardar==1 and $codigo!=0) { // RUTINA PARA  editar REGISTROS NUEVOS
	if($logo != NULL){ 
		$slashes = explode("\\",$logo);
		$nameLogo = $slashes[count($slashes) - 1];
		$pathLogo = stripslashes($logo);
		copy("$pathLogo","proyecto/$nameLogo");
		$compos="nom_rso='".$nombres."', nit_rso='".$nit."',log_rso='".$nameLogo."',desc1_rso='".$leyenda1."', desc2_rso='".$leyenda2."',reg_rso='".$regimenes."',tel_rso='".$tel."',dir_rso='".$dir."',num_fac_rso='".$num_factura."',email='".$email."'";
		$error=editar("rsocial",$compos,'cod_rso',$codigo); 
	}	
	else {	
	$compos="nom_rso='".$nombres."', nit_rso='".$nit."',desc1_rso='".$leyenda1."', desc2_rso='".$leyenda2."',reg_rso='".$regimenes."',tel_rso='".$tel."',dir_rso='".$dir."',num_fac_rso='".$num_factura."',email='".$email."' ";
	$error=editar("rsocial",$compos,'cod_rso',$codigo); 
	}
	if ($error==1) {
		header("Location: con_rsocial.php?confirmacion=2&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
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
if (document.getElementById('nombres').value == ""   && document.getElementById('regimenes').value ==0 )
	return false;
else
	return true;
}
</script>

</head>
<body <?=$sis?>>
<form  name="forma" id="forma" action="man_rsocial.php"  method="post">
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
        <td width="20" ><img src="imagenes/icoguardar.png" alt="Nueno Registro" width="16" height="16" border="0"  onclick="cambio_guardar()" style="cursor:pointer"/></td>
        <td width="61" class="ctablaform">Guardar</td>
        <td width="21" class="ctablaform"><a href="con_rsocial.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/cancel.png" alt="Cancelar" width="16" height="16" border="0" /></a></td>
        <td width="65" class="ctablaform">Cancelar </td>
        <td width="22" class="ctablaform"><a href="con_rsocial.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/iconolupa.gif" alt="Buscar" width="16" height="16" border="0" /></a></td>
        <td width="70" class="ctablaform">Consultar</td>
        <td width="21" class="ctablaform"></td>
        <td width="60" class="ctablaform">&nbsp;</td>
        <td width="24" valign="middle" class="ctablaform">&nbsp;</td>
        <td width="193" valign="middle"><label>
          <input type="hidden" name="editar"   id="editar"   value="<?=$editar?>">
		  <input type="hidden" name="insertar" id="insertar" value="<?=$insertar?>">
		  <input type="hidden" name="eliminar" id="eliminar" value="<?=$eliminar?>">
          <input type="hidden" name="codigo"   id="codigo"   value="<?=$codigo?>" />
        </label></td>
        <td width="67" valign="middle">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="4" valign="bottom"><img src="imagenes/lineasup3.gif" alt="." width="100%" height="4" /></td>
  </tr>
  <tr>
    <td class="textotabla1 Estilo1">RAZON SOCIAL:</td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE" valign="top">
	<table width="629" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="textotabla1">Nombre:</td>
        <td><input name="nombres" id="nombres" type="text" class="textfield2"  value="<?=$dbdatos->nom_rso?>" /></td>
        <td><span class="textorojo">*</span></td>
        <td><span class="textotabla1">Nit:</span></td>
        <td><input name="nit" id="nit" type="text" class="textfield2"  value="<?=$dbdatos->nit_rso?>" /></td>
        <td class="textorojo">&nbsp;</td>
        <td class="textorojo">&nbsp;</td>
      </tr>
	  <tr>
        <td class="textotabla1">Telefono:</td>
        <td><input name="tel" id="tel" type="text" class="textfield2"  value="<?=$dbdatos->tel_rso?>" /></td>
        <td>&nbsp;</td>
        <td><span class="textotabla1">Direccion:</span></td>
        <td><input name="dir" id="dir" type="text" class="textfield2"  value="<?=$dbdatos->dir_rso?>" /></td>
        <td class="textorojo">&nbsp;</td>
        <td class="textorojo">&nbsp;</td>
      </tr>
	  
	  
	        <tr>
	          <td class="textotabla1">Correo:</td>
	          <td class="textfield2"><input name="email" id="email" type="text" class="textfield2"  value="<?=$dbdatos->email?>" /></td>
        <td width="8">&nbsp;</td>
        <td width="35" class="textotabla1">Num Factura </td>
        <td width="189"><input name="num_factura" id="num_factura" type="text" class="textfield2"  value="<?=$dbdatos->num_fac_rso?>" /></td>
        <td width="7" class="textorojo">&nbsp;</td>
        <td width="103" class="textorojo">&nbsp;</td>
      </tr>
	  
	  
	  
	        <tr>
        <td width="69" class="textotabla1">&nbsp;</td>
        <td width="218" class="textfield2">&nbsp;</td>
        <td width="8">&nbsp;</td>
        <td width="35" class="textotabla1">&nbsp;</td>
        <td width="189">&nbsp;</td>
        <td width="7" class="textorojo">&nbsp;</td>
        <td width="103" class="textorojo">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  
  <tr>
    <td><div align="center"><img src="imagenes/spacer.gif"  width="624" height="4" /></div></td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif" alt="." width="100%" height="4" /></td>
  </tr>
  <tr>
    <td  class="textotabla1 Estilo1">Leyendas:</td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif" alt="." width="100%" height="4" /></td>
  </tr>
  <tr>
   <!--LEYENDAS--> 
    <td bgcolor="#D1D8DE"><table width="629" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="textotabla1">Leyenda 1: </td>
        <td colspan="2"><textarea name="leyenda1" cols="45" rows="4" class="textfield02" ><?=$dbdatos->desc1_rso?></textarea></td>
        <td>&nbsp;</td>
        <td colspan="3">&nbsp;</td>
        </tr>
      <tr>
        <td class="textotabla1">Leyenda 2: </td>
        <td class="textfield2"><textarea name="leyenda2" cols="45" rows="4" class="textfield02" ><?=$dbdatos->desc2_rso?></textarea></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="textorojo">&nbsp;</td>
        <td class="textorojo">&nbsp;</td>
      </tr>
      <tr>
        <td width="69" class="textotabla1">Regimen</td>
        <td width="218" class="textfield2"><span class="textotabla1">
          <?   combo_evento("regimenes"," regimenes","nom_reg","nom_reg",$dbdatos->reg_rso,"  ", "nom_reg"); ?>
        </span></td>
        <td width="8">&nbsp;</td>
        <td width="35">&nbsp;</td>
        <td width="189">&nbsp;</td>
        <td width="7" class="textorojo">&nbsp;</td>
        <td width="103" class="textorojo">&nbsp;</td>
      </tr>
    </table></td>
	<!--FIN DE LEYENDAS-->
  </tr>
  <tr>
 
  <td><img src="imagenes/lineasup3.gif" alt="." width="100%" height="4" /></td>
  
  </tr>
  <tr>
    <td height="30"  > <input type="hidden" name="guardar" id="guardar" />	</td>
  </tr>
</table>
</form> 
</body>
</html>