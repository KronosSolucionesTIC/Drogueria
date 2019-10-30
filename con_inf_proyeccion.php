<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>
<?=$nombre_aplicacion?>
</title>
<script type="text/javascript" src="js/funciones.js"></script>
<script type="text/javascript" src="js/js.js"></script>
<link href="css/styles.css" rel="stylesheet" type="text/css">
<link href="css/stylesforms.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function agregar_vendedor(){
    var cod_vendedor = document.getElementById("vendedor").options[document.getElementById("vendedor").selectedIndex].value; 
	if (cod_vendedor=='0'){
		agregar_vendedor_masivo();
	}
	else if (cod_vendedor!=''){
	    if (verifica_rep(cod_vendedor) != 1) {
			var nombre = document.getElementById("vendedor").options[document.getElementById("vendedor").selectedIndex].text;
			var combo=document.getElementById('combo_ciudad_consulta');
			var num = document.getElementById('cantidad');	
			if (parseInt(cod_vendedor) >0){
				document.getElementById('cantidad').value = parseInt(num.value) + 1;
				document.getElementById('arreglo_ciudad').value += cod_vendedor+',';
				document.getElementById("combo_ciudad_consulta").options[combo.options.length] = new Option(nombre,cod_vendedor);
			}
		}
	}
}


function verifica_rep(cod_vendedor)
{
	var codigos = document.getElementById('arreglo_ciudad').value;
	var repetido=0;
	if (codigos != '') {
	    codigos = codigos.split(",");
		for(g=0; g<=codigos.length; g++){  
			if (cod_vendedor===codigos[g]) { 
				alert ("Ya se agregó este vendedor "+codigos[g]);
				repetido=1;
			}
		}
	}
	return repetido;
}


function agregar_vendedor_masivo() {
	var combo=document.getElementById("combo_ciudad_consulta");
	combo.options.length=0;
	var cant=0;
	var cod_vendedor=0;
	var num = document.getElementById('cantidad');	 	
	<? 
		$db = new Database();	
		$sql="SELECT * FROM vendedor WHERE estado_vendedor = 1";
		$db->query($sql);
		while($db->next_row()){
			 echo "combo.options[cant] = new Option(\"$db->nom_ven\",'$db->cod_ven');";
			 echo "cant++;";
			 echo "cod_vendedor = '$db->cod_ven';";
			 echo "document.getElementById('arreglo_ciudad').value+=cod_vendedor+',';";
		}
	?>	
	num.value = combo.options.length + 1;
		
}
	
function borrar_ciudad(){

	var num = document.getElementById('cantidad');	
	num.value = parseInt(num.value) - 1;

	var combo=document.getElementById("combo_ciudad_consulta");
	var cod_ciudad = document.getElementById("combo_ciudad_consulta").value;
	var combo1=document.getElementById("arreglo_ciudad");
	var lugar=combo.selectedIndex;
	
	var datoo_eliminar=document.getElementById("combo_ciudad_consulta").value+',';
	var cadena=combo1.value;
	while (cadena.indexOf(datoo_eliminar) != -1){ 
		 cadena = cadena.replace(datoo_eliminar,""); 
	} 
	
	combo1.value=cadena;
	if (lugar>-1)
		combo.options[lugar]=null;
	cod_ciudad.options[cod_ciudad]=null;
	//alert(combo)
}

function agregar_empresa(){

    var cod_empresa = document.getElementById("empresa").options[document.getElementById("empresa").selectedIndex].value;
	if (cod_empresa =='0'){
		agregar_empresa_masivo();
	}
	else if (cod_empresa!=''){    
	    if (verifica_rep_emp(cod_empresa) != 1) {
			var nombre = document.getElementById("empresa").options[document.getElementById("empresa").selectedIndex].text;
			var combo = document.getElementById("combo_empresa_consulta");
			var num = document.getElementById('cantidadEmp');	
			num.value = parseInt(num.value) + 1;
			document.getElementById('arreglo_empresa').value += cod_empresa+',';
			if (cod_empresa > 0){
				combo.options[combo.options.length] = new Option(nombre,cod_empresa);
			}
		}
	}
}

function verifica_rep_emp(cod_empresa)
{
	var codigos = document.getElementById('arreglo_empresa').value;
	var repetido=0;
	if (codigos != '') {
	    codigos = codigos.split(",");
		for(g=0; g<=codigos.length; g++){  
			if (cod_empresa===codigos[g]) { 
				alert ("Ya se agregó esta empresa "+codigos[g]);
				repetido=1;
			}
		}
	}
	return repetido;
}

function agregar_empresa_masivo() {
	var combo=document.getElementById("combo_empresa_consulta");
	combo.options.length=0;
	var cant=0;
	var cod_empresa=0;
	var num = document.getElementById('cantidadEmp');	
	 	
	<? 
		$db = new Database();	
		$sql="SELECT * from rsocial WHERE estado_rsocial = 1";
		$db->query($sql);
		while($db->next_row()){
		$anadelo = true;

		 if ($anadelo) {
			 echo "combo.options[cant] = new Option(\"$db->nom_rso\",'$db->cod_rso');  \n";
			 echo  "cant++;  \n 	";
			 echo "cod_ruta = '$db->cod_rso';";
			 echo "	document.getElementById('arreglo_empresa').value=document.getElementById('arreglo_empresa').value+cod_empresa+',';";
		  }
		}
	?>	
	num.value = combo.options.length + 1;
		
}

function borrar_empresa(){

	var num = document.getElementById('cantidadEmp');	
	num.value = parseInt(num.value) - 1;

	var combo=document.getElementById("combo_empresa_consulta");
	var cod_empresa = document.getElementById("combo_empresa_consulta").value;
	var combo1=document.getElementById("arreglo_empresa");
	
	var datoo_eliminar=document.getElementById("combo_empresa_consulta").value+',';
	var cadena=combo1.value;
	while (cadena.indexOf(datoo_eliminar) != -1){ 
		 cadena = cadena.replace(datoo_eliminar,""); 
	} 
	combo1.value=cadena;
	var lugar=combo.selectedIndex;
	if (lugar>-1)
		combo.options[lugar]=null;
	cod_empresa.options[cod_empresa]=null;
	//alert(combo)
}

/******************** FIN COMBO CIUDADES y empresas***************************/


function abrir() {		
	if(document.getElementById('fechas2').value == ""  ||  document.getElementById('arreglo_ciudad').value==""|| document.getElementById('arreglo_empresa').value =="" ) {
	 	alert('Datos Incompletos')
	}
	else 
	{
		var fechas2 = document.getElementById('fechas2').value;
		var arreglo_ciudad = document.getElementById('arreglo_ciudad').value;
		var val_inicial = document.getElementById('cantidad').value;
		var arreglo_empresa = document.getElementById('arreglo_empresa').value;
		var dias = document.getElementById('dias').value;
		imprimir_inf("inf_proyeccion.php",'0&fechas2='+fechas2+'&combo_ciudad='+arreglo_ciudad+'&val_inicial='+val_inicial+'&dias='+dias+'&combo_empresa='+arreglo_empresa,'mediano');
	}
}

</script>
<script type="text/javascript" src="informes/inf.js"></script>
<? inicio() ?>
</head>
<body>
<table width="718" align="center">
  <tr>
    <td width="710" valign="top" >
        <table width="710" border="0" cellspacing="0" cellpadding="0" align="center" >
          <tr>
            <td width="710" bgcolor="#D1D8DE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="27" colspan="7"><div align="center" class="subfongris">INFORME DE VENTAS POR PROYECCION</div></td>
                </tr>
                <tr>
                  <td width="7" height="20"></td>
                  <td width="99" class="ctablaform">Vendedor</td>
                  <td colspan="1">
                    <? combo_evento_where("vendedor","vendedor","cod_ven","nom_ven","",""," where estado_vendedor = 1"); ?>
                  </td>
                  <td colspan="3"><span class="textorojo">*
                    <label></label>
                    <input name="Submit" type="button" class="botones" onClick="agregar_vendedor()" value="Agregar vendedor">
                    </span> </td>
                </tr>
                <tr>
                  <td height="20"></td>
                  <td colspan="6"><p align="center">
                      <select name="combo_ciudad_consulta" size="5"  id="combo_ciudad_consulta">
                      </select>
                    </p>
                    <p align="center">
                      <input name="Submit2" type="button" class="botones" onClick="borrar_ciudad()" value="Borrar vendedor">
                      <input type="hidden" textarea  name="arreglo_ciudad" id="arreglo_ciudad" cols="40" rows="4" >
                      </textarea>
                      <input type="hidden"  name="cantidad" id="cantidad" value="0" />
                    </p></td>
                </tr>
                <tr>
                  <td height="20"></td>
                  <td class="ctablaform">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td height="20"></td>
                  <td class="ctablaform">Empresa</td>
                  <td colspan="1"><? combo_evento_where("empresa","rsocial","cod_rso","nom_rso","",""," where estado_rsocial = 1"); ?></td>
                  <td width="125"><span class="textorojo">*
                    <input name="Submit" type="button" class="botones" onClick="agregar_empresa()" value="Agregar empresa">
                    </span> </td>
                  <td width="138"><input type="HIDDEN"  name="tipo_rutas" id="tipo_rutas" value="0" /></td>
                  <td width="181" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td height="20"></td>
                  <td colspan="6"><p align="center">
                      <select name="combo_empresa_consulta" size="5"  id="combo_empresa_consulta">
                      </select>
                    </p>
                    <p align="center">
                      <input name="Submit2" type="button" class="botones" onClick="borrar_empresa()" value="Borrar empresa">
                      <input type="hidden" textarea  name="arreglo_empresa" id="arreglo_empresa" cols="40" rows="4" >
                      </textarea>
                      <input type="hidden"  name="cantidadEmp" id="cantidadEmp" value="0" />
                    </p></td>
                </tr>
                <tr>
                  <td height="20"></td>
                  <td><span class="ctablaform">&nbsp;&nbsp;Fecha Final:</span></td>
                  <td><input name="fechas2" type="text" id="fechas2" size="10" readonly />
                    <img src="imagenes/date.png" alt="Calendario" name="calendario1" width="18" height="18" id="calendario1" style="cursor:pointer" /><span class="textorojo">*</span></td>
                  <td class="ctablaform">No de dia habil:</td>
                  <td><input name="dias" type="text" id="dias" size="10" onKeyPress="return validaInt_evento(this)"/>
                    <span class="textorojo">*</span></td>
                  <td colspan="2"><span class="ctablablanc">
                    <input type='hidden' name='codigo'>
                    <img src='imagenes/mirar.png' width='16' height='16'  style="cursor:pointer"  onClick=" abrir();"  />
						<!--<input type="button" onClick="abrir();" value="enviar" >-->
					</span></td>
                </tr>
                <tr>
                  <td height="20"></td>
                  <td class="ctablaform">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td colspan="2">&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td><img src="imagenes/lineasup3.gif" width="710" height="5" /></td>
          </tr>
          <tr>
            <td height="30" align="center" valign="bottom">&nbsp;</td>
          </tr>
        </table>
        <script type="text/javascript">	
			Calendar.setup(
				{
					inputField  : "fechas2",      
					ifFormat    : "%Y-%m-%d",    
					button      : "calendario1" ,  
					align       :"T3",
					singleClick :true
				}
			);			
</script></td>
  </tr>
</table>
</body>
</html>