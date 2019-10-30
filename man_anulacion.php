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
	if(document.getElementById('razon').value==0 || document.getElementById('observacion').value==''){
	  alert("Complete los campos obligatorios");
	  return false;
	}
	else {
		if(confirm("¿Esta seguro de Anular esta Factura?")) 
			{
				document.forma.submit();		
			}
	}
}

</script>
<?

if($anular==1 and $codigo>0) 	{ // RUTINA PARA  INSERTAT REGISTROS NUEVOS
	$db6 = new Database();
	$sql = "  update   m_factura  set  estado='anulado',razon_anulacion='$razon',obs_anulacion='$observacion'   WHERE  cod_fac=$codigo ";
	$db6->query($sql);	
	
	$sql = "  update   cartera_factura  set  estado_car='CANCELADA'   WHERE  cod_fac=$codigo ";
	$db6->query($sql);	
	
	$db7 = new Database();
	$sql2 = "  update   m_factura_esp  set  estado='anulado'   WHERE  cod_fac_m=$codigo ";
	$db7->query($sql2);	
	
	
	enviar_alerta("Alerta ANULACION FACTURA ", "Se ha Anulo una  Factura, Consultar Factura <a href=' http://www.globater.com/sistema/informes/ver_factura_v.php?codigo=$codigo'>Consultar Factura</a>");
	
	// parte de ajuste del kardex
	$sql = " select * ,(select cod_bod from m_factura where cod_fac=$codigo) as bodega from d_factura  where cod_mfac=$codigo ";
	$db6->query($sql);	
	while($db6->next_row()) {
		
		$bodega = $db6->bodega;
		kardex("suma",$db6->cod_pro ,$db6->bodega,$db6->cant_pro,$_POST["costo_ref_".$ii],$db6->cod_peso);
	}
	
	$db8 = new Database();
	$sql = " select sum(tot_fac-tot_dev_mfac) as total from m_factura  where cod_fac=$codigo ";
	$db8->query($sql);	
	$db8->next_row();
	$total = $db8->total;
	
	//INGRESO DE LA DEVOLUCION
	$campos="(fec_dev, cod_bod_dev, num_fac_dev ,val_del,cod_ven_dev, obs_dev)";
	$valores="('".date("Y-m-d")."','".$bodega."','".$codigo."','".$total."','".$global[2]."','".$observacion."')" ; 
	$id=insertar_maestro("m_devolucion",$campos,$valores);
	
	$compos="(cod_mdev, cod_mfac_dev , cod_dfac_dev, cod_prod_ddev, cod_pes_ddev, cant_fac_dev, val_fac ,cant_ddev,total_ddev) ";
	$db9 = new Database();
	$sql = "SELECT * FROM d_factura 
	INNER JOIN m_factura ON m_factura.cod_fac = d_factura.cod_mfac 
	WHERE cod_mfac = $codigo";
	$db9->query($sql);
	while($db9->next_row()){
		$cod_dfac = $db9->cod_dfac;	
		$cod_pro = $db9->cod_pro;	
		$cod_peso = $db9->cod_peso;
		$cant_pro = $db9->cant_pro;
		$val_uni = $db9->val_uni;
		$total_pro = $db9->total_pro;
		$num_fac = $db9->num_fac;
		$valores="('".$id."','".$codigo."','".$cod_dfac."','".$cod_pro."','".$cod_peso."' ,'".$cant_pro."','".$val_uni."','".$cant_pro."','".$total_pro."')";
		$error=insertar("d_devolucion",$compos,$valores); 	
	}
	
	//INGRESO DE LA ENTRADA
	$db9 = new Database();
	$sql = "SELECT * FROM d_factura 
	INNER JOIN m_factura ON m_factura.cod_fac = d_factura.cod_mfac 
	WHERE cod_mfac = $codigo";
	$db9->query($sql);
	$campos="(fec_ment,fac_ment,obs_ment,cod_bod,total_ment,cod_prove_ment,usu_ment,estado_m_entrada)";
	$valores="('".date('Y-m-d')."','','DEVOLUCION FACTURA No "."$num_fac','".$bodega."','".$total."','','".$global[2]."','1')" ; 
	$ins_id=insertar_maestro("m_entrada",$campos,$valores); 
	
	$campos="(cod_ment_dent,cod_tpro_dent,cod_mar_dent,cod_pes_dent,cod_ref_dent,cant_dent,cos_dent)";
	$db10 = new Database();
	$sql = "SELECT * FROM d_factura 
	INNER JOIN m_factura ON m_factura.cod_fac = d_factura.cod_mfac 
	WHERE cod_mfac = $codigo";
	$db10->query($sql);
	while($db10->next_row()){
		$cod_tpro = $db10->cod_tpro;	
		$cod_cat = $db10->cod_cat;	
		$cod_peso = $db10->cod_peso;
		$cod_pro = $db10->cod_pro;
		$cant_pro = $db10->cant_pro;
		$val_uni = $db10->val_uni;
		$total_pro = $db10->total_pro;
		$num_fac = $db10->num_fac;
		$valores="('".$ins_id."','".$cod_tpro."','".$cod_cat."','".$cod_peso."','".$cod_pro."' ,'".$cant_pro."','".$val_uni."')";
		$error=insertar("d_entrada",$campos,$valores); 	
	}	
	
	//ELIMINACION DEL OTRO PAGO
		eliminar("otros_pagos", $codigo,"cod_fac");
	
	echo " <script language='javascript'>window.location = 'con_anulacion.php?confirmacion=0&editar=$editar&insertar=$insertar&eliminar=$eliminar'; </script> "; 

}





if ($codigo!=0) {
	$dbdatos_edi= new  Database();
	$sql ="select *  from   m_factura   INNER JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)  left join cartera_factura on cartera_factura.cod_fac =m_factura.cod_fac where m_factura.cod_fac= $codigo";
	
	$dbdatos_edi->query($sql);
	$dbdatos_edi->next_row();
	$dbdatos_edi->tot_dev_mfac."===";
	$dbdatos_edi->valor_abono."---";
	if($dbdatos_edi->tot_dev_mfac >0 || $dbdatos_edi->valor_abono>0 ) {
		echo "<script language='javascript'>alert('Existen abonos o devoluciones para esta factura, No se puede Anular ')</script>";
		$no_anular=1;
	}
	
	
}

?>




<script type="text/javascript" src="js/js.js"></script>
<script type="text/javascript" src="js/funciones.js"></script>
<script type="text/javascript" src="informes/inf.js"></script>

</head>
<body <?=$sis?>>
<div id="total">
<form  name="forma" id="forma" action="man_anulacion.php"  method="post">
<table width="750" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
   <td bgcolor="#D1D8DE" >
   <table width="100%" height="30" border="0" cellspacing="0" cellpadding="0" align="center" > 
      <tr>
        <td width="5" height="30">&nbsp;</td>
        <td width="20" >
        <? if($no_anular!=1) { ?>
        <img src="imagenes/icoguardar.png" alt="Nueno Registro" width="16" height="16" border="0" onClick="datos_completos_sigue()" style="cursor:pointer"/>
        <? } ?>
        </td>
        <td width="61" class="ctablaform">Anular</td>
        <td width="21" class="ctablaform"><a href="con_anulacion.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/cancel.png" alt="Cancelar" width="16" height="16" border="0" /></a></td>
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
          <input type="hidden" name="codigo" id="codigo" value="<?=$codigo?>" /> 
		   <input type="hidden" name="anular" id="anular" value="1">
		  </td>
        
        <td width="67" valign="middle">&nbsp;</td>
      </tr>
    </table>
	</td>
  </tr>
  <tr>
    <td height="4" valign="bottom"><img src="imagenes/lineasup3.gif" alt="." width="100%" height="4" /></td>
  </tr>
  <tr>
    <td class="textotabla01">ANULACION DE FACTURA  :</td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
        <td width="147" class="textotabla1">No Factura:</td>
        <td width="119" class="textotabla1"><a href="#">
		
		<img src="imagenes/mirar.png" alt="Cancelar" width="16" height="16" border="0"   onclick="imprimir_inf('ver_factura_v.php',<?=$codigo?>,'grande')"/>
		
		</a>
         &nbsp;</td>
        <td width="17" class="textorojo">*</td>
        <td width="55" class="textotabla1" valign="top">Cliente</td>
        <td width="211"><span class="textotabla1">
          <?=$dbdatos_edi->nom_bod?>
        </span></td>
        <td width="201">&nbsp;</td>
       </tr>
       <tr>
         <td class="textotabla1">Razon:</td>
         <td><span class="textotabla1">
           <? combo_evento("razon","razon_anulacion","cod_razon","desc_razon",""," ", "desc_razon"); ?>
         </span></td>
         <td><span class="textorojo">*</span></td>
         <td width="55" class="textotabla1" valign="top">Valor</td>
         <td><span class="textotabla1">
           <?=$dbdatos_edi->tot_fac?>
         </span></td>
         <td>&nbsp;</td>
       </tr>
	   <tr>
         <td class="textotabla1" >Observacion:     	   
	     <td class="textotabla1" ><textarea name="observacion" id="observacion" cols="45" rows="3" class="textfield02"></textarea>         
	     <td class="textotabla1" ><span class="textorojo">*</span>                  
	     <td class="textotabla1" >         
	     <td class="textotabla1" >         
	     <td class="textotabla1" >         
	   </table>		  
	    </td>
	  </tr>
	  <tr> 
		<td colspan="8" >		  
		</td>
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