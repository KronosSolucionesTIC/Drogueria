<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<?
if ($codigo == 0) {
	    $sql ="SELECT * FROM bodega1
		INNER JOIN ruta ON ruta.cod_ruta = bodega1.cod_ruta
		WHERE cod_bod= $cliente";
		$db= new  Database();
		$db->query($sql);
		$db->next_row();
		if($db->rut == 1){
			$rut = 'SI';
		}
		else{
			$rut = 'NO';
		}
		
		$dbd = new Database();
		$sqld ="SELECT SUM(((SELECT SUM(total_pro) FROM d_factura WHERE cod_mfac=m_factura.cod_fac)- valor_abono )) -(SUM(tot_dev_mfac)) AS cartera FROM m_factura
		INNER JOIN cartera_factura ON cartera_factura.cod_fac= m_factura.cod_fac
		WHERE  tipo_pago='Credito'   AND estado_car<>'CANCELADA' AND cod_cli = $cliente";
		$dbd->query($sqld);
			while($dbd->next_row())
			{
				$cartera_ocupada=$dbd->cartera;
			}
	
	
		$cupo_covinoc=$db->cupo_au_covinoc - $cartera_ocupada;
}

if($guardar==1 and $codigo==0) { 
	// EDICION DEL CLIENTE
	$campos="nom_bod='".$nombres."',apel_bod='".$apellidos."',rsocial_bod='".$razon."',iden_bod='".$nit."',digito_bod='".$digito."',dir_bod='".$direccion."', dpto_cli='".$departamento."',ciu_bod='".$ciudad."', regimen_cli= '".$regimen."',  tel_bod='".$fijo."', cel_bod = '".$celular."', mail_bod='".$correo."'";
	$error=editar("bodega1",$campos,'cod_bod',$cliente); 
	// FIN EDICION DEL CLIENTE 
	
	$duracion = $hora.':'.$minuto.':'.$segundo;
	// INGRESO EN PRINCIPAL DE LLAMADAS
	$campos="(fecha_llamada,duracion_llamada,cli_llamada,obs_llamada)";
	$valores="('".$fecha."','".$duracion."','".$cliente."','".$observaciones."')" ;
	$id = insertar_maestro("m_llamadas",$campos,$valores);	
	// FIN PRINCIPAL DE LLAMADAS
	
	// INGRESO EN DETALLES DE LLAMADAS
	for ($ii=1; $ii<=$contador; $ii++){
		if ($_POST["result_".$ii].checked != checked) {
			$campos="(cod_llamada,cod_resultado,cod_respllamada)";
			$valores="('".$id."','".$_POST["cod_rllamada_".$ii]."','".$_POST["responsable_".$ii]."')" ;
			$error=insertar("d_llamadas",$campos,$valores); 
		}
	}
	// FIN DETALLES DE LLAMADAS
	
	if ($error==1) {
		
		header("Location: con_llamadas.php?confirmacion=2&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
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
if (document.getElementById('hora').value == 0 && document.getElementById('minuto').value == 0 && document.getElementById('segundo').value == 0)
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

function prueba(caja){
	if(document.getElementById('hora').value > 23){
		alert('Las horas de llamada debe ser menor a 24');
		document.getElementById(caja).value = 00;
	}
	if(document.getElementById('minuto').value > 59){
		alert('Los minutos de la llamada debe ser menor a 60');
		document.getElementById(caja).value = 00;
	}
	if(document.getElementById('segundo').value > 59){
		alert('Los segundos de la llamada debe ser menos a 60');
		document.getElementById(caja).value = 00;
	}
}
</script>

</head>
<body <?=$sis?>>
<form  name="forma" id="forma" action="man_llamadas.php"  method="post">
<table width="624" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
    <td colspan="3" bgcolor="#D1D8DE"><table width="100%" height="46" border="0" cellpadding="0" cellspacing="0">
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
        <td width="21" class="ctablaform"><a href="con_llamadas.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/cancel.png" alt="Cancelar" width="16" height="16" border="0" /></a></td>
        <td width="65" class="ctablaform">Cancelar </td>
        <td width="22" class="ctablaform"><a href="con_llamadas.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/iconolupa.gif" alt="Buscar" width="16" height="16" border="0" /></a></td>
        <td width="70" class="ctablaform">Consultar</td>
        <td width="21" class="ctablaform"></td>
        <td width="60" class="ctablaform">&nbsp;</td>
        <td width="24" valign="middle" class="ctablaform">&nbsp;</td>
        <td width="193" valign="middle"><label>
          <input type="hidden" name="editar"   id="editar"   value="<?=$editar?>">
		  <input type="hidden" name="insertar" id="insertar" value="<?=$insertar?>">
		  <input type="hidden" name="eliminar" id="eliminar" value="<?=$eliminar?>">
          <input type="hidden" name="codigo" id="codigo" value="<?=$codigo?>" />
          <input type="hidden" name="cliente" id="cliente" value="<?=$cliente?>" />
        </label></td>
        <td width="67" valign="middle">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="4" colspan="3" valign="bottom"><img src="imagenes/lineasup3.gif" alt="." width="100%" height="4" /></td>
  </tr>
  <tr>
    <td colspan="3" class="textotabla1 Estilo1">CLIENTES:</td>
  </tr>
  <tr>
    <td colspan="3"><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td colspan="3" valign="top" bgcolor="#D1D8DE"><table width="100%" border="0" bgcolor="#D1D8DE">
  <tr>
    <td class="ctablasup">NOMBRES</td>
    <td class="ctablasup">APELLIDOS</td>
    <td class="ctablasup">RAZON SOCIAL</td>
    <td class="ctablasup">NIT</td>
    <td class="ctablasup">DIGITO</td>
    <td class="ctablasup">DIRECCION</td>
    <td class="ctablasup">DEPARTAMENTO</td>
    <td class="ctablasup">CIUDAD</td>
    </tr>
  <tr>
    <td><input name="nombres" id="nombres" type="text" class="caja_resalte1" value="<?=$db->nom_bod;?>"/></td>
    <td><input name="apellidos" id="apellidos" type="text" class="caja_resalte1" value="<?=$db->apel_bod;?>"/></td>
    <td><input name="razon" id="razon" type="text" class="caja_resalte1" value="<?=$db->rsocial_bod;?>"/></td>
    <td><input name="nit" id="nit" type="text" class="caja_resalte1" value="<?=$db->iden_bod;?>"/></td>
    <td><input name="digito" id="digito" type="text" class="caja_resalte1" value="<?=$db->digito_bod;?>"/></td>
    <td><input name="direccion" id="direccion" type="text" class="caja_resalte1" value="<?=$db->dir_bod;?>"/></td>
    <td><? combo_evento("departamento","departamento","cod_departamento","desc_departamento",$db->dpto_cli,"onchange='cargar_ciudad(\"departamento\",\"ciudad\")'","desc_departamento"); ?></td>
    <td><? combo_evento_where("ciudad","ciudad","cod_ciudad","desc_ciudad",$db->ciu_bod,"","");?></td>
    </tr>
  <tr>
    <td class="ctablasup">REGIMEN</td>
    <td class="ctablasup">TELEFONO FIJO</td>
    <td class="ctablasup">CELULAR</td>
    <td class="ctablasup">CORREO</td>
    <td class="ctablasup">CUPO</td>
    <td class="ctablasup">DISPONIBLE</td>
    <td class="ctablasup">RUT</td>
    <td class="ctablasup">VENDEDOR</td>
    </tr>
  <tr>
    <td><input name="regimen" id="regimen" type="text" class="caja_resalte1" value="<?=$db->regimen_cli;?>"/></td>
    <td><input name="fijo" id="fijo" type="text" class="caja_resalte1" value="<?=$db->tel_bod;?>"/></td>
    <td><input name="celular" id="celular" type="text" class="caja_resalte1" value="<?=$db->cel_bod;?>"/></td>
    <td><input name="correo" id="correo" type="text" class="caja_resalte1" value="<?=$db->mail_bod;?>"/></td>
    <td><input name="cupo" id="cupo" type="text" class="caja_resalte1" value="<?=$db->cupo_au_covinoc;?>" readonly="readonly"/></td>
    <td><input name="disponible" id="disponible" type="text" class="caja_resalte1" value="<?=$db->cupo_au_covinoc;?>" readonly="readonly"/></td>
    <td><input name="rut" id="rut" type="text" class="caja_resalte1" value="<?=$rut;?>" readonly="readonly"/></td>
    <td><span class="textotabla1">
      <? 
		combo_evento("vendedor","vendedor","cod_ven","nom_ven",$db->cod_vendedor,"","nom_ven");
		?>
    </span></td>
    </tr>
    </table>

</td>
  </tr>
  
  <tr>
    <td colspan="3" bgcolor="#D1D8DE">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE">Resultado:</td>
    <td width="518" bgcolor="#D1D8DE">&nbsp;</td>
    <td width="210" bgcolor="#D1D8DE">&nbsp;</td>
  </tr>
<?
    $dbr = new Database();
	$sqlr = "SELECT * FROM r_llamadas order by desc_rllamada";
	$dbr->query($sqlr);
	$i = 1;
		while($dbr->next_row()){
			echo "<tr>";
    		echo "<td bgcolor='#D1D8DE'>&nbsp;</td>";
    		echo "<td width='323' bgcolor='#D1D8DE'>$dbr->desc_rllamada<input type='hidden' id='cod_rllamada_$i' name='cod_rllamada_$i' value='$dbr->cod_rllamada'></td>";
    		echo "<td width='1012' bgcolor='#D1D8DE'><input name='result_$i' type='checkbox' id='result_$i' />";
?>
			<? combo_evento('responsable_'.$i,'responsable','cod_respon','desc_respon','','','desc_respon'); ?>
            <?
            echo "</td>";
  			echo "</tr>";
       		$i++;
    	} 
?>  
    <td bgcolor="#D1D8DE">&nbsp;</td>
    <td bgcolor="#D1D8DE">&nbsp;</td>
    <td bgcolor="#D1D8DE"></td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE">Fecha:</td>
    <td bgcolor="#D1D8DE"><input name="fecha" id="fecha" type="text" class="caja_resalte1" value="<?=$fecha?>" readonly="readonly"/></td>
    <td bgcolor="#D1D8DE">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE">Duracion:</td>
    <td colspan="2" bgcolor="#D1D8DE"><input name="hora" id="hora" type="text" class="caja_resalte1" value="00" onkeypress="return validaInt_evento(this,'mas')" onchange="prueba('hora')"/>
      :
      <input name="minuto" id="minuto" type="text" class="caja_resalte1" value="00" onkeypress="return validaInt_evento(this,'mas')" onchange="prueba('minuto')"/>
      :
  <input name="segundo" id="segundo" type="text" class="caja_resalte1" value="00" onkeypress="return validaInt_evento(this,'mas')" onchange="prueba('segundo')"/></td>
    </tr>
  <tr>
    <td bgcolor="#D1D8DE">Observaciones:</td>
    <td bgcolor="#D1D8DE"><textarea name="observaciones" id="observaciones" cols="45" rows="3" class="textfield02"></textarea></td>
    <td bgcolor="#D1D8DE">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE">&nbsp;</td>
    <td bgcolor="#D1D8DE">&nbsp;</td>
    <td bgcolor="#D1D8DE">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td height="30" colspan="3"  > <input type="hidden" name="guardar" id="guardar" />
	  <input type="hidden" name="contador" id="contador" value="<?=$i - 1?>"/></td>
  </tr>
</table>
</form> 
</body>
</html>


