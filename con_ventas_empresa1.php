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
function agregar_ciudad(){

    var cod_ciudad = document.getElementById("ciudades_cliente").options[document.getElementById("ciudades_cliente").selectedIndex].value;
	//alert("CODCI" +cod_ciudad); 
	if (cod_ciudad=='0'){
		agregar_cliente_masivo();
	}
	else if (cod_ciudad!=''){    
	    if (verifica_rep(cod_ciudad) != 1) {
			var nombre = document.getElementById("ciudades_cliente").options[document.getElementById("ciudades_cliente").selectedIndex].text;
			var combo=document.getElementById('combo_ciudad_consulta');
			var num = document.getElementById('cantidad');	
			if (parseInt(cod_ciudad) >0){
				document.getElementById('cantidad').value = parseInt(num.value) + 1;
				document.getElementById('arreglo_ciudad').value += cod_ciudad+',';
				document.getElementById("combo_ciudad_consulta").options[combo.options.length] = new Option(nombre,cod_ciudad);
			}
		}
	}
}


function verifica_rep(cod_ciudad)
{
	var codigos = document.getElementById('arreglo_ciudad').value;
	var repetido=0;
	//alert(codigos);
	if (codigos != '') {
	    codigos = codigos.split(",");
		for(g=0; g<=codigos.length; g++){  
			if (cod_ciudad===codigos[g]) { 
				alert ("Ya se agregó esta ciudad"+codigos[g]);
				repetido=1;
			}
		}
	}
	return repetido;
}


function agregar_cliente_masivo() {
	var combo=document.getElementById("combo_ciudad_consulta");
	combo.options.length=0;
	var cant=0;
	var cod_ciudad=0;
	var num = document.getElementById('cantidad');	 	
	<? 
		$db = new Database();	
		$sql="SELECT distinct cod_ciudad, desc_ciudad from ciudad where cod_ciudad  in (select distinct CL.ciu_bod from bodega1 CL) order by desc_ciudad ";
		$db->query($sql);
		while($db->next_row()){
		$anadelo = true;

		//echo "<script language='javascript'>  var cod = '$db->cod_ciudad'; var anadelo = (verifica_rep(cod) != 1); \n";
		//echo "$anadelo = anadelo; < /script>";

		 if ($anadelo) {
			 echo "combo.options[cant] = new Option(\"$db->desc_ciudad\",'$db->cod_ciudad');  \n";
			 echo  "cant++;  \n 	";
			 echo "cod_ciudad = '$db->cod_ciudad';";
			 echo "	document.getElementById('arreglo_ciudad').value+=cod_ciudad+',';";
		  }
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

    var cod_empresa = document.getElementById("empresas_cliente").options[document.getElementById("empresas_cliente").selectedIndex].value;
	//alert("CODemp" +cod_empresa); 
	if (cod_empresa=='0'){
		agregar_empresa_masivo();
	}
	else if (cod_empresa!=''){    
	    if (verifica_rep_emp(cod_empresa) != 1) {
			var nombre = document.getElementById("empresas_cliente").options[document.getElementById("empresas_cliente").selectedIndex].text;
			var combo=document.getElementById("combo_empresa_consulta");
			var num = document.getElementById('cantidadEmp');	
			num.value = parseInt(num.value) + 1;
			document.getElementById('arreglo_empresa').value += cod_empresa+',';
			if (cod_empresa>0){
				combo.options[combo.options.length] = new Option(nombre,cod_empresa);
			}
		}
	}
}

function verifica_rep_emp(cod_empresa)
{
	var codigos = document.getElementById('arreglo_empresa').value;
	var repetido=0;
	//alert(codigos);
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
		$sql="SELECT distinct cod_rso, nom_rso from rsocial ";
		$db->query($sql);
		while($db->next_row()){
		$anadelo = true;

		//echo "<script language='javascript'>  var cod = '$db->cod_ciudad'; var anadelo = (verifica_rep(cod) != 1); \n";
		//echo "$anadelo = anadelo; < /script>";

		 if ($anadelo) {
			 echo "combo.options[cant] = new Option(\"$db->nom_rso\",'$db->cod_rso');  \n";
			 echo  "cant++;  \n 	";
			 echo "cod_rso = '$db->cod_rso';";
			 echo "	document.getElementById('arreglo_empresa').value=document.getElementById('arreglo_empresa').value+cod_rso+',';";
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
	if(document.getElementById('fechas').value == ""  || document.getElementById('fechas2').value == ""  ||  document.getElementById('arreglo_ciudad').value==""|| document.getElementById('arreglo_empresa').value =="" ) {
	 	alert('Datos Incompletos')
	}
	else 
	{
		var fechas = document.getElementById('fechas').value;
		var fechas2 = document.getElementById('fechas2').value;
		var arreglo_ciudad = document.getElementById('arreglo_ciudad').value;
		var val_inicial = document.getElementById('cantidad').value;
		var arreglo_empresa = document.getElementById('arreglo_empresa').value;
		imprimir_inf("inf_ventas_empresa1.php",'0&fechas='+fechas+'&fechas2='+fechas2+'&arreglo_ciudad='+arreglo_ciudad+'&val_inicial='+val_inicial+'&combo_empresa='+arreglo_empresa,'mediano');
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
                  <td height="27" colspan="7"><div align="center" class="subfongris">INFORME DE VENTAS POR CIUDADES Y EMPRESAS </div></td>
                </tr>
                <tr>
                  <td width="7" height="20"></td>
                  <td width="99" class="ctablaform">Ciudad</td>
                  <td colspan="1">
                    <?  
				  combo_evento_where1("ciudades_cliente","ciudad","cod_ciudad","desc_ciudad","", " where cod_ciudad  in (select distinct CL.ciu_bod from bodega1 CL LEFT JOIN m_factura F ON CL.cod_bod = F.cod_cli ) order by desc_ciudad "); 
				 
				  					?>
                  </td>
                  <td colspan="3"><span class="textorojo">*
                    <label></label>
                    <input name="Submit" type="button" class="botones" onClick="agregar_ciudad()" value="Agregar ciudad">
                    </span> </td>
                </tr>
                <tr>
                  <td height="20"></td>
                  <td colspan="6"><p align="center">
                      <select name="combo_ciudad_consulta" size="5"  id="combo_ciudad_consulta">
                      </select>
                    </p>
                    <p align="center">
                      <input name="Submit2" type="button" class="botones" onClick="borrar_ciudad()" value="Borrar ciudad">
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
                  <td width="160" class="ctablaform">
                    <? combo_evento_where("empresas_cliente"," rsocial","cod_rso","nom_rso","","", "  order by nom_rso"); ?>
                  </td>
                  <td width="125"><span class="textorojo">*
                    <input name="Submit" type="button" class="botones" onClick="agregar_empresa()" value="Agregar empresa">
                    </span> </td>
                  <td width="138">&nbsp;</td>
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
                  <td><span class="ctablaform">&nbsp;Fecha Inicial:</span></td>
                  <td><input name="fechas" type="text" id="fechas" size="10" readonly />
                    <img src="imagenes/date.png" alt="Calendario" name="calendario" width="18" height="18" id="calendario" style="cursor:pointer"/>&nbsp;<span class="textorojo">*</span></td>
                  <td><span class="ctablaform">&nbsp;&nbsp;Fecha Final:</span></td>
                  <td><input name="fechas2" type="text" id="fechas2" size="10" readonly />
                    <img src="imagenes/date.png" alt="Calendario" name="calendario1" width="18" height="18" id="calendario1" style="cursor:pointer" /><span class="textorojo">*</span></td>
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
					inputField  : "fechas",      
					ifFormat    : "%Y-%m-%d",    
					button      : "calendario" ,  
					align       :"T3",
					singleClick :true
				}
			);		

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