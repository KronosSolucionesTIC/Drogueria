<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<?
if ($codigo!="") {
$sql ="SELECT *  FROM otros_pagos   WHERE cod_otro=$codigo";
$dbdatos= new  Database();
$dbdatos->query($sql);
$dbdatos->next_row();
$bos= $dbdatos->obs_otro;
$tipo_pago= $dbdatos->cod_tpag_otro;
$codigo_cliente=$dbdatos->cod_cli_otro;


}

if($guardar==1 and $codigo==0) { // RUTINA PARA  INSERTAT REGISTROS NUEVOS
	$compos="(cod_usu_otro,fec_otro,cod_cli_otro,obs_otro,val_otro,cod_tpag_otro )";
	$valores="('".$global[2]."','".$fec_otro."','".$cliente."','".$observaciones."','".$valor_otro."','".$tipo_pago_combo."')" ;

	$error=insertar("otros_pagos",$compos,$valores); 

	if ($error==1) {
		header("Location: con_otros.php?confirmacion=1&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
	}
	else
		echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>" ; 
}


if($guardar==1 and $codigo!=0) { // RUTINA PARA  editar REGISTROS NUEVOS
	//$compos="tipo_gas='".$tipo_gastos."',fec_res='".$fec_ini."',val_res='".$valor_gasto."',rso_res='".$empresa."',pun_res='".$Bodega."',resp_res='".$vendedor."'";
//	$error=editar("otros_pagos",$compos,'cod_otro',$codigo); 
//	if ($error==1) {
		header("Location: con_otros.php?confirmacion=2&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
	//}
	//else
	//	echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>" ; 
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />


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
function datos_completos()
{  


	if (document.getElementById('valor_otro').value == ""  ||  document.getElementById('cliente').value=="" )
	return false; 
else
	return true;
}





</script>

</head>
<body <?=$sis?>>
<form  name="forma" id="forma" action="man_otros.php"  method="post">
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
        <td width="21" class="ctablaform"><a href="con_otros.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/cancel.png" alt="Cancelar" width="16" height="16" border="0" /></a></td>
        <td width="65" class="ctablaform">Cancelar </td>
        <td width="22" class="ctablaform"><a href="con_otros.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/iconolupa.gif" alt="Buscar" width="16" height="16" border="0" /></a></td>
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
    <td class="textotabla1 Estilo1">OTROS PAGOS:</td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE" valign="top">
	<table width="629" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="textotabla1">Valor:</td>
        <td><input name="valor_otro" id="valor_otro" type="text" class="textfield2"  value="<?=$dbdatos->val_otro?>" /></td>
        <td><span class="textorojo">*</span></td>
        <td class="textotabla1">Cliente</td>
        <td><? 
			
		$sql="select distinct punto_venta.cod_bod as valor , nom_bod as nombre from punto_venta  inner join  bodega  on punto_venta.cod_bod=bodega.cod_bod where cod_ven=$global[2]";
		$dbdatos= new  Database();
		$dbdatos->query($sql);
		
		$where_cli="";
		while($dbdatos->next_row())
		{
			$where_cli .= "cod_bod_cli= ".$dbdatos->valor  ;
			$where_cli .= " or ";
		}
		$where_cli .= " cod_bod > 0 "; 
		$sql="select *,CONCAT(bodega1.nom_bod,apel_bod) as nombre from bodega1 where $where_cli  order  by nom_bod ";
		combo_sql("cliente","bodega1","cod_bod","nombre",$codigo_cliente,$sql); 
									
		//combo_evento("cliente","bodega1","cod_bod"," nom_bod ",$dbdatos_edi->cod_cli,"", "nom_bod");
		?></td>
        <td class="textorojo">&nbsp;</td>
        <td class="textorojo">&nbsp;</td>
      </tr>
      <tr>
        <td width="71" class="textotabla1">Fecha</td>
        <td width="144"><span class="ctablablanc">
          <input name="fec_otro" type="text" class="textotabla01" id="fec_ini" readonly="readonly"   value="<? echo date("Y-m-d");  ?>" />
        </span></td>
        <td width="16"><span class="textorojo">*</span></td>
        <td width="110" class="textotabla1">Observaciones</td>
        <td colspan="3" rowspan="2">
        <textarea name="observaciones" cols="45" rows="4" class="textfield02" ><?=$bos?></textarea>
        </td>
        </tr>
      
       <tr>
        <td width="71" class="textotabla1">Detalle pago</td>
        <td width="144"><? 
		
		$sql="select * from tipo_pago ";
		
		 combo_sql("tipo_pago_combo","tipo_pago","cod_tpag","nom_tpag",$tipo_pago,$sql); 
									
		//combo_evento("cliente","bodega1","cod_bod"," nom_bod ",$dbdatos_edi->cod_cli,"", "nom_bod");
		?></td>
        <td width="16">&nbsp;</td>
        <td width="110" class="textotabla1">&nbsp;</td>
        </tr>
      
       <tr>
        <td width="71" class="textotabla1">&nbsp;</td>
        <td width="144">&nbsp;</td>
        <td width="16">&nbsp;</td>
        <td width="110" class="textotabla1">&nbsp;</td>
        <td width="131">&nbsp;</td>
        <td width="12" class="textorojo">&nbsp;</td>
        <td width="145" class="textorojo">&nbsp;</td>
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
	</td>
  </tr>
</table>
</form> 
</body>   
</html>
