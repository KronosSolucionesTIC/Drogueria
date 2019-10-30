<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<?

if ($codigo != 0) {
	$sqlp =" SELECT * FROM m_proyeccion WHERE cod_proyeccion = $codigo";//=		
	$dbp= new  Database();
	$dbp->query($sqlp);
	$dbp->next_row();
}
if($guardar==1 and $codigo==0) 	{ // RUTINA PARA INSERTAR REGISTROS NUEVOS

	$campos="(valor_proyeccion,fecha_proyeccion,dias_habiles,estado_proyeccion)";
	$valores="('".$todocompra."','".$fecha."','".$dias."',1)" ; 
	$id = insertar_maestro("m_proyeccion",$campos,$valores); 	
	
		for ($ii=0; $ii<$contador;  $ii++){
			$campos="(cod_proyeccion,cod_vendedor,proyeccion_individual)";
			$valores="('".$id."','".$_POST["cod_vendedor".$ii]."','".$_POST["proyeccion_individual_".$ii]."')" ; 
			$error = insertar("d_proyeccion",$campos,$valores); 
		}
		
	if ($error==1) {
		header("Location: con_proyeccion.php?confirmacion=1&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
	}
	else
		echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>" ; 
}


if($guardar==1 and $codigo!=0) { // RUTINA PARA EDITAR REGISTROS 
	$campos="dias_habiles ='".$dias."',valor_proyeccion='".$todocompra."'";
	$error= editar("m_proyeccion",$campos,'cod_proyeccion',$codigo); 
	
	$error=eliminar("d_proyeccion",$codigo,"cod_proyeccion");
	for ($ii=0; $ii<$contador;  $ii++){
		$campos="(cod_proyeccion,cod_vendedor,proyeccion_individual)";
		$valores="('".$codigo."','".$_POST["cod_vendedor".$ii]."','".$_POST["proyeccion_individual_".$ii]."')" ; 
		$error = insertar("d_proyeccion",$campos,$valores); 
	}
	
	if ($error==1) {
		header("Location: con_proyeccion.php?confirmacion=2&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
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
function datos_completos()
{  
	if (document.getElementById('fecha').value == "" || document.getElementById('dias').value == "" || document.getElementById('todocompra').value == 0 )
		return false;
	else
		return true;		

}

function calcular_total(){
	var total = 0;
	var contador = document.getElementById('contador').value;
	for (i=0; i<=contador; i++){
		valor = document.getElementById('proyeccion_individual_'+i).value;
		if (valor == ""){
			valor = 0;
		}
		valor = parseInt(valor);
		total = total + valor;
		document.getElementById('todocompra').value = total;
	}
}
</script>

<script type="text/javascript" src="js/js.js"></script>
</head>
<body <?=$sis?>>
<div id="total">
<form  name="forma" id="forma" action="man_proyeccion.php"  method="post">
<table width="750" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
   <td bgcolor="#D1D8DE" >
   <table width="100%" height="30" border="0" cellspacing="0" cellpadding="0" align="center" > 
      <tr>
        <td width="5" height="30">&nbsp;</td>
        <td width="20" ><img src="imagenes/icoguardar.png" alt="Nuevo Registro" width="16" height="16" border="0" onClick="cambio_guardar()" style="cursor:pointer"/></td>
        <td width="61" class="ctablaform">Guardar</td>
        <td width="21" class="ctablaform"><a href="con_proyeccion.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"><img src="imagenes/cancel.png" alt="Cancelar" width="16" height="16" border="0" /></a></td>
        <td width="65" class="ctablaform">Cancelar </td>
        <td width="22" class="ctablaform"><a href="#"><img src="imagenes/iconolupa.gif" alt="Buscar" width="16" height="16" border="0" onclick="buscar_producto()" /></a><a href="con_proyeccion.php?confirmacion=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>"></a></td>
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
    <td class="textotabla01">PROYECCION :</td>
  </tr>
  <tr>
    <td><img src="imagenes/lineasup3.gif"  width="100%" height="4" /></td>
  </tr>
  <tr>
    <td bgcolor="#D1D8DE" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	   <tr>
	     <td width="750" class="textotabla1" >
	       <table  width="99%" border="1">
	         <? if ($codigo == 0) {?>
	         <? } ?>    
	         <tr >
	           <td width="4%">
	             <table width="100%">
	               <tr>
	                 <td width="13%" class='textfield01'><div align="left">Fecha:</div></td>
	                 <td width="35%"  class='textfield01'><div align="left"><? if ($codigo == 0) {?><input name="fecha" type="text" class="fecha" id="fecha" readonly="readonly" value="<?=$dbp->fecha_proyeccion?>" /><? } else {?><input name="fecha" type="text" class="fecha" id="fecha" readonly="readonly" disabled="disabled" value="<?=$dbp->fecha_proyeccion?>" /><? } ?>
	                   <img src="imagenes/date.png" alt="Calendario" name="calendario" width="16" height="16" border="0" id="calendario" style="cursor:pointer"/><span class="textorojo">*</span></div></td>
	                 <td class='textfield01'>&nbsp;</td>
	                 </tr>
	               <tr>
	                 <td class='textfield01'><div align="left">Dias habiles:</div></td>
	                 <td class='textfield01'><div align="left"><input name="dias" type="text" class="fecha" id="dias" value="<?=$dbp->dias_habiles?>" onkeypress="return validaInt_evento(this)"/>
	                   <span class="textorojo">*</span></div></td>
	                 <td class='textfield01'>&nbsp;</td>
	                 </tr>
	               <tr id="fila_0">
	                 <td colspan="2"  class="ctablasup">Vendedor</td>
	                 <td width="52%"  class="ctablasup">Valor</td>
	                 </tr>
                     <? if ($codigo == 0){ ?>
	               <?
					$sqlv =" SELECT * FROM vendedor WHERE estado_vendedor = 1";//=		
					$dbv= new  Database();
					$dbv->query($sqlv);
					$jj=0;
					//echo "<table width='100%'>";
					while($dbv->next_row()){ 
						echo "<tr>";
						
						//cmarca
						echo "<td colspan='2'><INPUT type='hidden'  name='cod_vendedor$jj' id='cod_vendedor$jj' value='$dbv->cod_ven'><span class='textfield01'>$dbv->nom_ven</span></td>";							
						//tipo de producto
						echo "<td><div align='center'><span  class='textfield01'><INPUT type='text' id='proyeccion_individual_$jj'  name='proyeccion_individual_$jj' onkeypress='return validaInt_evento(this)' onblur='calcular_total()'></span></div></td>";
						echo "</tr>";
						$jj++;
					}
				?>
                <? } ?>
                <? if ($codigo != 0){ ?>
	               <?
						$sqlv ="SELECT * FROM vendedor
						WHERE estado_vendedor = 1";//=		
						$dbv= new  Database();
						$dbv->query($sqlv);
						$jj=0;
						while($dbv->next_row()){
							$vendedor = $dbv->cod_ven;
							$sqldp ="SELECT * FROM d_proyeccion
							WHERE cod_proyeccion = $codigo AND cod_vendedor = $vendedor";//=		
							$dbdp= new  Database();
							$dbdp->query($sqldp);
							$dbdp->next_row();
							
						//cmarca
						echo "<td colspan='2'><INPUT type='hidden' name='cod_d_proyeccion$jj' id='cod_d_proyeccion$jj' value='$dbdp->cod_d_proyeccion'><INPUT type='hidden' name='cod_vendedor$jj' id='cod_vendedor$jj' value='$dbv->cod_ven'><span class='textfield01'>$dbv->nom_ven</span></td>";							
						//tipo de producto
						echo "<td><div align='center'><span class='textfield01'><INPUT type='text' id='proyeccion_individual_$jj'  name='proyeccion_individual_$jj' onkeypress='return validaInt_evento(this)' onblur='calcular_total()' value='$dbdp->proyeccion_individual'></span></div></td>";
						echo "</tr>";
						$jj++;
						}
					}
				?>
	               </table>			</td>
	           </tr>			
	         <tr >
	           <td>
	             <table width="100%">
	               <tr >
	                 <td  class="ctablasup"><div align="right"></div></td>
	                 </tr>
	               <tr >
	                 <td ><div align="right" >Total  Proyeccion:
	                   <input name="todocompra" id="todocompra" type="text" class="textfield01" readonly="1" value="<?=$dbp->valor_proyeccion?>"/>
	                   </div>				  </td>
	                 </tr>
	               </table>			</td>
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
	<input type="hidden" name="contador" id="contador" value="<? if($codigo==0) echo $jj; else echo $jj; ?>" />
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
    <td width="81" class="textotabla1"><strong>Referncia:</strong></td>
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
