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
//CARGA EL TIPO DEL PRODUCTO
function cargar_tipos(categoria,tipo_producto){
var combo=document.getElementById(tipo_producto);
combo.options.length=0;
var cant=0;
combo.options[cant] = new Option('Seleccione','0'); 
cant++;
<?
		$sqltp ="SELECT * FROM tipo_producto";		
		$dbtp= new  Database();
		$dbtp->query($sqltp);
		while($dbtp->next_row()){ 
		echo "if(document.getElementById(categoria).value==$dbtp->cod_marca) {";
		echo "combo.options[cant] = new Option('$dbtp->nom_tpro','$dbtp->cod_tpro');";
		echo "cant++; } ";
		}
?> 
}

function validaInt_1(){
	if (event.keyCode>47 & event.keyCode<58) {
		return true;
		}
	else{
		return false;
		}
}






var tWorkPath="menus/data.files/";
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function abrir() {		
	if(document.getElementById('fec_fin').value == ""  ||document.getElementById('fec_ini').value == "") {
	 	alert('Seleccione las Fechas')
	}
	
	else 
	{
		var fec_ini = document.getElementById('fec_ini').value;
		var fec_fin = document.getElementById('fec_fin').value;
		var categoria = document.getElementById('categoria').value;
		var tipo_producto = document.getElementById('tipo_producto').value;
		imprimir_inf("inf_ventas_cantidad.php",'0&fec_ini='+fec_ini+'&fec_fin='+fec_fin+'&categoria='+categoria+'&tipo_producto='+tipo_producto,'mediano');
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
                          <td width="16" height="36"> </td>
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
                          <td colspan="5"  class="ctablasup" >INFORME DE VENTAS EN CANTIDADES</td>
                        </tr>
                    
                        <tr>
                          <td colspan="5" class="ctablablanc" ><div align="center">FECHA DE ANALISIS  </div></td>
                        <tr>
                          <td colspan="5" class="ctablablanc" >&nbsp;</td>
                        <tr>
                          <td width="91" class="ctablablanc" >Fecha Inicial </td>
                          <td width="149" class="ctablablanc" ><input name="fec_ini" type="text" class="textotabla01" id="fec_ini" readonly  />
                          <img src="imagenes/date.png" alt="Calendario" name="imageField" width="16" height="16" border="0" id="imageField" style="cursor:pointer"/></td>
                          <td width="136" class="ctablablanc" >&nbsp;</td>
                          <td width="98" class="ctablablanc" >Fecha Final </td>
                          <td class="ctablablanc" ><input name="fec_fin" type="text" class="textotabla01" id="fec_fin" readonly  />
                          <img src="imagenes/date.png" alt="Calendario" name="imageField1" width="16" height="16" border="0" id="imageField1" style="cursor:pointer"/></td>
                        <tr>
                          <td class="ctablablanc" >&nbsp;</td>
                          <td class="ctablablanc" >&nbsp;</td>
                          <td class="ctablablanc" >&nbsp;</td>
                          <td class="ctablablanc" >&nbsp;</td>
                          <td width="101" aling='center' >&nbsp;</td>
                        <tr>
                          <td class="ctablablanc" >&nbsp;</td>
                          <td class="ctablablanc" >&nbsp;</td>
                          <td class="ctablablanc" ><div align="center">CATEGORIA</div></td>
                          <td class="ctablablanc" >&nbsp;</td>
                          <td aling='center' >&nbsp;</td>
                        <tr>
                          <td class="ctablablanc" >&nbsp;</td>
                          <td class="ctablablanc" >&nbsp;</td>
                          <td class="ctablablanc" ><div align="center">
                            <? combo_evento("categoria","marca","cod_mar","nom_mar","","onchange=cargar_tipos('categoria','tipo_producto')","nom_mar"); ?>
                          </div></td>
                          <td class="ctablablanc" >&nbsp;</td>
                          <td aling='center' >&nbsp;</td>
                        <tr>
                          <td class="ctablablanc" >&nbsp;</td>
                          <td class="ctablablanc" >&nbsp;</td>
                          <td class="ctablablanc" ><div align="center">TIPO DE PRODUCTO</div></td>
                          <td class="ctablablanc" >&nbsp;</td>
                          <td aling='center' >&nbsp;</td>
                        <tr>
                          <td class="ctablablanc" >&nbsp;</td>
                          <td class="ctablablanc" >&nbsp;</td>
                          <td class="ctablablanc" ><div align="center">
                            <? combo_evento("tipo_producto","tipo_producto","cod_tpro","nom_tpro","","","nom_tpro");?>
                          </div></td>
                          <td class="ctablablanc" ><input type='hidden' name='codigo'>
                          <img src='imagenes/mirar.png' alt="" width='16' height='16'  style="cursor:pointer"  onclick="abrir()" /></td>
                          <td aling='center' >&nbsp;</td>
                        </table ></td>	
                    </tr>
                    
                    <tr>
                      <td><img src="imagenes/lineasup3.gif" width="624" height="4" /></td>
                    </tr>
                    <tr>
                      <td height="30" align="center" valign="bottom">&nbsp;</td>
                    </tr>
                  </table>
      </form>
</td>
</tr>
</table>	
				
<form name="forma" method="post" action="semaforo.php">
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



