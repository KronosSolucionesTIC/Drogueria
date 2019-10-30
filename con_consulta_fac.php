<? include("lib/database.php")?>
<? include("js/funciones.php")?>

<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" src="calendario/javascript/calendar.js"></script>
<script type="text/javascript" src="calendario/javascript/calendar-es.js"></script>
<script type="text/javascript" src="calendario/javascript/calendar-setup.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="calendario/styles/calendar-win2k-cold-1.css" title="win2k-cold-1" />  <script src="utilidades.js" type="text/javascript"> </script>
<title><?=$nombre_aplicacion?></title>
<script type="text/javascript">
var tWorkPath="menus/data.files/";
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function abrir() {		
	if(document.getElementById('fec_fin').value == ""  ||  document.getElementById('fec_ini').value=="") {
	 	alert('Seleccione las Fechas de consulta')
	}
	
	else 
	{
		var fec_ini = document.getElementById('fec_ini').value;
		var fec_fin = document.getElementById('fec_fin').value;
	//	var ruta ="consulta_caja_diara.php?fec_ini="+fec_ini+"&fec_fin="+fec_fin+"";
	//	window.open(ruta,"ventana","menubar=0,resizable=1,width=745,height=500,toolbar=0,scrollbars=yes")
		imprimir_inf("consulta_facturacion.php",'0&fec_ini='+fec_ini+'&fec_fin='+fec_fin,'mediano');
	}

	
}



		 
</script>
<script type="text/javascript" src="js/funciones.js"></script>
<script type="text/javascript" src="informes/inf.js"></script>
 <link href="css/styles.css" rel="stylesheet" type="text/css">
 
</head>
<body  <?=$sis?> >

<table align="center">
<tr>
<td valign="top" >
<form id="forma_total" name="forma_total" method="post" action="formatos/ver_traza.php">
                  <table width="624" border="0" cellspacing="0" cellpadding="0" align="center" >
                    <tr>
                      <td bgcolor="#D1D8DE"><table width="488" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="16" height="33"> </td>
                          <td width="19"> 
						  <? if ($insertar==1) {?>
					  	 <!-- <img src="imagenes/agregar.png" width="16" height="16"  alt="Nuevo Registro" style="cursor:pointer" onClick="location.href='man_traza_general.php?codigo=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>'"/>-->
					  	  <? } ?></td>
                          <td width="160"><span class="ctablaform">
                            <? if ($insertar==1) {?>
								<!--Agregar-->
							<? } ?>
                          </span></td>
                          <td width="20" class="ctablaform">&nbsp;</td>
                          <td width="53" class="ctablaform"><!--Buscar: --></td>
                          <td width="103"><label>
                            <!--<input name="text" type="text" class="textfield" size="12" id="texto" />-->
                          </label></td>
                          <td width="19" class="ctablaform"><!-- en--></td>
                          <td width="160" valign="middle"><!--<select name="campos" class="textfieldlista" id="campos" >
                            <option value="0">Seleccion</option>
                            <option value="nom_bod">Bodega</option>
                           	<option value="-1">Lista Completa</option>
                          </select>--></td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><table width="587" border="0"  cellpadding="0" align="center">
                        <tr>
                          <td colspan="4"  class="ctablasup" >CONSULTA FACTURACION </td>
                          <td  class="ctablasup"  width="101">INFORME</td>
                        </tr>
                        <? 
						echo "<tr style='display:none'><td ><form name='forma_0' action='man_traza_general.php'>";
						echo "  </form> </td></tr>  ";						  
						$estilo="ctablablanc";
						$estilo="ctablagris";
						//echo $sql;						
						if ($aux==0) { $estilo="ctablablanc"; $aux=1; $cambio_celda=$celda_blanca; }else { $estilo="ctablagris";  $cambio_celda=$celda_gris; $aux=0;}
							echo "<tr class='$estilo' $cambio_celda> ";
						?>
                        <tr>
                          <td width="91" class="ctablablanc" >Fecha Inicial </td>
                          <td width="155" class="ctablablanc" >
						  <input name="fecha" type="text" class="textotabla01" id="fec_ini" readonly="1"  />
                              <img src="imagenes/date.png" alt="Calendario" name="imageField" width="16" height="16" border="0" id="imageField" style="cursor:pointer"/></td>
                          <td width="73" class="ctablablanc" >Fecha Final </td>
                          <td width="155" class="ctablablanc" >
						  <input name="fec_fin" type="text" class="textotabla01" id="fec_fin" readonly="1"  />
                          <img src="imagenes/date.png" alt="Calendario" name="imageField1" width="16" height="16" border="0" id="imageField1" style="cursor:pointer"/></td>
                          <td aling='center' ><table width='100%' border='0' cellspacing='0' cellpadding='0'>
                              <tr>
                                <td align='center'><input type='hidden' name='codigo'></td>
                                <td align='center'><img src='imagenes/mirar.png' width='16' height='16'  style="cursor:pointer"  onclick="abrir()" /></td>
                              </tr>
                          </table></td>
                          <?
							echo "<input type='hidden' name='editar' value=".$editar."> <input type='hidden' name='insertar' value=".$insertar."> <input type='hidden' name='eliminar' value=".$eliminar.">";
							echo "  </tr>  ";
						 ?>
                      </table ></td>
                    </tr>
                    
                    <tr>
                      <td><img src="imagenes/lineasup3.gif" width="624" height="4" /></td>
                    </tr>
                    <tr>
                      <td height="30" align="center" valign="bottom"><table>
                        <tr>
                          <td> <span class="ctablaform" >  </span>
                         
                        </tr>
                      </table></td>
                    </tr>
                  </table>
      </form>
</td>
</tr>
</table>	
				
<form name="forma" method="post" action="con_traza_general.php">
  <input type="hidden" name="editar" id="editar" value="<?=$editar?>">
  <input type="hidden" name="insertar" id="insertar" value="<?=$insertar?>">
  <input type="hidden" name="eliminar" id="eliminar" value="<?=$eliminar?>">
  <input type="hidden" name="cant_pag"  id="cant_pag" value="<?=$cant_pag?>">
  <input type="hidden" name="act_pag"  id="act_pag" value="<? if(!empty($act_pag)) echo $act_pag; else echo $pagina;?>">
  <input type="hidden" name="busquedas" id="busquedas" value="<?=$busquedas?>">
   <input type="hidden" name="eliminacion" id="eliminacion" >
    <input type="hidden" name="eli_codigo" id="eli_codigo" >
</form>

    <script type="text/javascript">
Calendar.setup(
	{
	inputField  : "fec_ini",      // ID of the input field
	ifFormat    : "%Y-%m-%d",    // the date format
	button      : "imageField" ,   // ID of the button
	//align       :"T2",
	singleClick :true
	}
);

Calendar.setup(
	{
	inputField  : "fec_fin",      // ID of the input field
	ifFormat    : "%Y-%m-%d",    // the date format
	button      : "imageField1" ,   // ID of the button
	//align       :"T2",
	singleClick :true
	}
);
</script>
   

</body>

</html>



