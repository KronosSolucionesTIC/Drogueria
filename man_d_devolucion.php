<? include("lib/database.php")?>
<? include("js/funciones.php")?>

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

function datos_completos_sigue()
{  

	if (document.getElementById('num_factura').value > 0 ) {
		var bandera = 0;
		<?
			$sqltp ="select * from m_devolucion";		
			$dbtp= new  Database();
			$dbtp->query($sqltp);
			while($dbtp->next_row()){ 
				echo "if(document.getElementById('num_factura').value==$dbtp->num_fac_dev) {";
				echo "bandera = 1;";
				echo "}";
			}
		?>
	
		if(bandera == 1){
			alert('Esta factura ya tiene devolucion');
		}
		else{
			document.forma.submit();
		}
	}
	else {
		alert('Complete el Formulario, Gracias')
	}

}


</script>

<script type="text/javascript" src="js/js.js"></script>
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
        <td width="20" ><img src="imagenes/siguiente.png" alt="Nueno Registro" width="16" height="16" border="0" onClick="datos_completos_sigue()" style="cursor:pointer"/></td>
        <td width="61" class="ctablaform">Continuar</td>
        <td width="21" class="ctablaform"><a href="con_venta.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/cancel.png" alt="Cancelar" width="16" height="16" border="0" /></a></td>
        <td width="65" class="ctablaform">Cancelar </td>
        <td width="22" class="ctablaform"><a href="con_cargue.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"></a></td>
        <td width="70" class="ctablaform">&nbsp;</td>
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
    <td class="textotabla01">DEVOLUCION DE FACTURA  :</td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
        <td width="84" class="textotabla1">No Factura:</td>
        <td width="182" class="textotabla1"><? combo_evento_where("num_factura","m_factura","cod_fac","CONCAT(num_fac,'-',fecha)","",""," where estado is null AND fecha >='2012-01-01'")?></td>
        <td width="17" class="textorojo">*</td>
        <td width="55" class="textotabla1" valign="top">&nbsp;</td>
        <td width="211">&nbsp;</td>
        <td width="201">&nbsp;</td>
       </tr>
      
       <tr>
         <td class="textotabla1">&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td width="55" class="textotabla1" valign="top">&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
       </tr>
       
	   
	   <tr>
         <td colspan="6" class="textotabla1" >
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
