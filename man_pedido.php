<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<?
if ($codigo!="") {
	$sql ="SELECT  cod_ped, fec_ped , cod_alm_ped , pri_ped ,   fec_ent_ped ,  cod_pro_ped ,  cod_proy_ped ,  obs_ped, cod_usu_ped FROM pedido  WHERE cod_ped=$codigo and cod_proy_ped=$global[0]";
	$dbdatos= new  Database();
	$dbdatos->query($sql);
	$dbdatos->next_row();
}

if($guardar==1 and $codigo==0) { // RUTINA PARA  INSERTAT REGISTROS NUEVOS
	$compos="(fec_ped , cod_alm_ped , pri_ped ,   fec_ent_ped ,  cod_pro_ped ,  cod_proy_ped ,  obs_ped, cod_usu_ped)";
	$valores="('".$fecha."','".$almacen."','".$prioridad."','".$fecha_ent."','".$proveedor."','".$global[0]."','".$observaciones."','".$global[2]."')" ;
	$ins_id=insertar_maestro("pedido",$compos,$valores); 	
	if ($ins_id > 0) {
		$compos="( cod_ped_dpe  , cod_det_dpe  , cant_dpe  ,   cos_dpe  ,  tot_dpe )";
		for ($ii=1 ;  $ii <= $cant_items + 1 ; $ii++) {
			if($_POST["codigo_".$ii]!=NULL) {
				$valores="('".$ins_id."','".$_POST["codigo_".$ii]."','".$_POST["cantidad".$ii]."','".$_POST["costo_".$ii]."','".$_POST["total".$ii]."')" ;
				$error=insertar("det_pedido",$compos,$valores); 
			}
		}	
		header("Location: con_pedido.php?confirmacion=1&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
	}
	else
		echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>" ; 
	
}

if($guardar==1 and $codigo!=0) { // RUTINA PARA  editar REGISTROS NUEVOS
	echo $compos="fec_ped='".$fecha."', cod_alm_ped='".$almacen."', pri_ped='".$prioridad."',  cod_pro_ped='".$proveedor."', obs_ped='".$observaciones."' ,fec_ent_ped='".$fecha_ent."'";
	$error=editar("pedido",$compos,'cod_ped',$codigo); 
	$sql ="delete  FROM det_pedido  WHERE cod_ped_dpe=$codigo ";
	$dbdatos= new  Database();	
	$dbdatos->query($sql);
	$compos="( cod_ped_dpe  , cod_det_dpe  , cant_dpe  ,   cos_dpe  ,  tot_dpe )";
		for ($ii=0 ;  $ii <= $cant_items + 1 ; $ii++) {
			if($_POST["codigo_".$ii]!=NULL) {
				$valores="('".$codigo."','".$_POST["codigo_".$ii]."','".$_POST["cantidad".$ii]."','".$_POST["costo_".$ii]."','".$_POST["total".$ii]."')" ;
				$error=insertar("det_pedido",$compos,$valores); 
			}
		}

	if ($error==1) {
		header("Location: con_pedido.php?confirmacion=2&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
	}
	else
		echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>" ; 
}


function buscar_detalles($codigo) {
	$sql ="SELECT  cod_ped_dpe ,  cod_det_dpe ,  cant_dpe ,  cos_dpe , tot_dpe  ,referencia.des_ref  as referenica FROM det_pedido INNER JOIN referencia
				ON det_pedido.cod_det_dpe=referencia.cod_ref     WHERE cod_ped_dpe=$codigo ";
	$dbdet= new  Database();
	$dbdet->query($sql);
	$aa=1;
	while($dbdet->next_row()){
	echo " <tr id='filaDoc_$aa' > ";
	echo " <td  > <input type='hidden' name='codigo_$aa'  value='$dbdet->cod_det_dpe'> $dbdet->referenica </td>";
	echo " <td > <input type='hidden' name='cantidad$aa'  value='$dbdet->cant_dpe'>".number_format($dbdet->cant_dpe,2)." </td>";
	echo " <td > <input type='hidden' name='costo_$aa'  value='$dbdet->cos_dpe'>".number_format($dbdet->cos_dpe,2)."</td>";
	echo " <td > <input type='hidden' name='total$aa'  value='$dbdet->cos_dpe'> ".number_format( $dbdet->tot_dpe,2)."</td> ";
	echo " <td align='center' width='14%'><input type='button'  class='botones' value=' - '  onclick=\"removeDocCmp('filaDoc_$aa')\"> </td>";
	echo "  </tr>";
	$aa++;
	}
return $aa -1  ;
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
if (document.getElementById('fecha').value == "" || document.getElementById('cant_items').value == 0   )
	return false;
else
	return true;
}
</script>

<script language="javascript">
function  adicion() {
	if (document.getElementById('referencia').value=="" || parseFloat(document.getElementById('cantidad').value)==0 || document.getElementById('cantidad').value=="") {
		alert("Complete los datos, Gracias")
	}
	else {	
		
		if (document.getElementById('costo').value == "" ) {
			document.getElementById('total').value=0;
			document.getElementById('costo').value=0;		
		}	
		else {
			 document.getElementById('total').value = parseFloat(document.getElementById('cantidad').value) * parseFloat(document.getElementById('costo').value);	
		}
		addDocCmp(document.getElementById('referencia').value, document.getElementById('referencia').options[parseInt(document.getElementById('referencia').selectedIndex)].text,document.getElementById('cantidad').value ,document.getElementById('total').value,document.getElementById('costo').value)
		document.getElementById('cantidad').value="";
		document.getElementById('total').value="";
		document.getElementById('costo').value="";
	}
}
</script>

<script type="text/javascript" src="js/js.js"></script>
</head>
<body <?=$sis?>>
<form  name="forma" id="forma" action="man_pedido.php"  method="post">
<table width="624" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
   <td bgcolor="#D1D8DE">
   <table width="100%" height="30" border="0" cellpadding="0" cellspacing="0"> 
      <tr>
        <td width="5" height="30">&nbsp;</td>
        <td width="20" ><img src="imagenes/icoguardar.png" alt="Nueno Registro" width="16" height="16" border="0" onclick="cambio_guardar()" style="cursor:pointer"/></td>
        <td width="61" class="ctablaform">Guardar</td>
        <td width="21" class="ctablaform"><a href="con_pedido.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/cancel.png" alt="Cancelar" width="16" height="16" border="0" /></a></td>
        <td width="65" class="ctablaform">Cancelar </td>
        <td width="22" class="ctablaform"><a href="con_pedido.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/iconolupa.gif" alt="Buscar" width="16" height="16" border="0" /></a></td>
        <td width="70" class="ctablaform">Consultar</td>
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
    <td class="textotabla01">PEDIDOS:</td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE" valign="top">
	<table width="629" border="0" cellspacing="0" cellpadding="0">
       <tr>
        <td width="100" class="textotabla1">Fecha:</td>
        <td width="170">
          <input name="fecha" type="text" class="fecha" id="fecha" readonly="1" value="<?=$dbdatos->fec_ped?>"/>
          <img src="imagenes/date.png" alt="Calendario" name="calendario" width="16" height="16" border="0" id="calendario"/></td>
        <td width="12" class="textorojo">*</td>
        <td width="106" class="textotabla1">Almacen:</td>
        <td width="84"><? combo_almacen("almacen","cod_alm","nom_alm",$dbdatos->cod_alm_ped,$global[0]); ?></td>
        <td width="4" class="textorojo">&nbsp;</td>
        <td width="153" class="textorojo">&nbsp;</td>
      </tr>
      <tr>
        <td class="textotabla1">Prioridad:</td>
        <td>
          <select name="prioridad" class="SELECT">
		  	<option value="Baja">Baja</option>
            <option value="Media">Media</option>
            <option value="Alta">Alta</option>
          </select>
        </td>
        <td class="textorojo">&nbsp;</td>
        <td class="textotabla1">Provedor:</td>
        <td><? combo("proveedor","proveedor","cod_pro","nom_pro",$dbdatos->cod_pro_ped); ?></td>
        <td class="textorojo">&nbsp;</td>
        <td class="textorojo">&nbsp;</td>
      </tr>
      <tr>
        <td class="textotabla1">Fecha Entrega :</td>
   		<td>
          <input name="fecha_ent" type="text" class="fecha" id="fecha_ent" readonly="1" value="<?=$dbdatos->fec_ent_ped?>"/>
        <a href="#"><img src="imagenes/date.png" alt="Calendario" name="calendario1" width="16" height="16" border="0" id="calendario"/></a>
		</td>
        <td>&nbsp;</td>
        <td class="textotabla1">Observaicones:</td>
       		<td colspan="3">
          <textarea name="observaciones" cols="35" rows="2" class="textfield02"  ><?=$dbdatos->obs_ped?></textarea> 
			</td>
        </tr>
	  <tr>
        <td colspan="7" class="textotabla1" >
		<table  width="85%" border="1">
          <tr>
            <td width="30%"  class="ctablasup">Referencia:</td>
            <td width="28%"  class="ctablasup">Cantidad:  </td>
			<td width="28%"  class="ctablasup">Costo:  </td>
            <td width="28%"  class="ctablasup">Total:     </td>
			<td width="14%" class="ctablasup" align="center">Agregar:</td>
          </tr>
          <tr>
            <td> <? combo11("referencia","referencia","cod_ref","des_ref",$dbdatos->cod_alm_ped); ?></td>
            <td align="center"><input name="cantidad" id="cantidad" type="text" class="textfield01" onchange="validaValue(this);" onkeypress=" return validaFloat(this)"/></td>
            <td align="center"> <input name="costo" id="costo" type="text" class="textfield01"  onchange="validaValue(this);" onkeypress=" return validaFloat(this)"/></td>
			 <td align="center"> <input name="total" id="total" type="text" class="textfield01" onchange="validaValue(this);" onkeypress=" return validaFloat(this)"/></td>
			<td align="center"> <input type='button'  class='botones' value='  +  '  onclick="adicion()">
			
			</td>
          </tr>
		  </table>
		  <table border="1" width="87%" >
			  <tr id="filaDoc_0" >
			   <td width='39%'> </td>
			  <td width='17%'> </td>
			  <td width='17%'> </td>
			  <td width='17%'> </td>
			  <td width='18%'> </td>
			  </tr>
	  		  <?  if ($codigo!="") $aa=buscar_detalles($codigo); ?>
			  </table>
		  </td>
		 </tr>
	  <tr> 
		  <td colspan="7" >
			  
		  </td>
		  </tr>
    </table>
<tr>
  <tr>
    <td><div align="center"><img src="imagenes/spacer.gif" alt="." width="624" height="4" /></div></td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td height="30"  > <input type="hidden" name="guardar" id="guardar" />
		 <?  if ($codigo!="") $valueInicial = $aa; else $valueInicial = "1";?>
	   <input type="hidden" id="valDoc_inicial" value="<?=$valueInicial?>"> 
	   <input type="hidden" name="cant_items" id="cant_items" value=" <?  if ($codigo!="") echo $aa; else echo "0"; ?>">
	</td>
  </tr>
</table>
</form> 
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
<script type="text/javascript">
			Calendar.setup(
				{
				inputField  : "fecha_ent",      
				ifFormat    : "%Y-%m-%d",    
				button      : "calendario1" ,  
				align       :"T3",
				singleClick :true
				}
			);
</script>

</body>
</html>
