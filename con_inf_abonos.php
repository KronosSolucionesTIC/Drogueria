<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=$nombre_aplicacion?></title>
<script type="text/javascript" src="js/funciones.js"></script>
<script type="text/javascript" src="js/js.js"></script>
 <link href="css/styles.css" rel="stylesheet" type="text/css">
 <link href="css/stylesforms.css" rel="stylesheet" type="text/css" />
<script language="javascript">

function agregar_cliente(){

	var cod_bodega = document.getElementById("clientes_abono").value;
	var cod_bodega = document.getElementById("clientes_abono").options[document.getElementById("clientes_abono").selectedIndex].value;
	//alert("cod_bodega" +cod_bodega); 
	if (cod_bodega=='0'){
		agregar_cliente_masivo();
	}
	else {    
	    
	    if (verifica_rep_bodega(cod_bodega) == 0) {
			
			var nombre = document.getElementById("clientes_abono").options[document.getElementById("clientes_abono").selectedIndex].text;
			var combo=document.getElementById("combo_clientes_consulta");
			var num = document.getElementById('val_inicial');	
			document.getElementById('val_inicial').value = parseInt(num.value) + 1;
			
			document.getElementById('arreglo_clientes').value += cod_bodega+',';
			combo.options[combo.options.length] = new Option(nombre,cod_bodega);
			
		}
		else
		   alert('Es repetido'); 
	}	
}


function agregar_cliente_masivo() {
	var combo=document.getElementById("combo_clientes_consulta");
	combo.options.length=0;
	var cant=0;
	var cod_cliente=0;
	var num = document.getElementById('val_inicial');	 	
	
	<? 
	  $db1 = new Database();	
	  $sql="SELECT distinct cod_bod,CONCAT(nom_bod,apel_bod) as nombre from bodega1 order by nom_bod ";
	  $db1->query($sql);
	  while($db1->next_row()){
	  $anadelo = true;

	  //echo "<script language='javascript'>  var cod = '$db->cod_bod; var anadelo = (verifica_rep(cod) != 1); \n";
	  //echo "$anadelo = anadelo; < /script>";

	   if ($anadelo) {
		   echo "combo.options[cant] = new Option(\"$db1->nombre\",'$db1->cod_bod');  \n";
		   echo  "cant++;  \n 	";
		   echo "cod_cliente = '$db1->cod_bod';";
		   echo "	document.getElementById('arreglo_clientes').value+=cod_cliente+',';";
		}
	  }
	?>	
	document.getElementById('val_inicial').value = combo.options.length + 1;
		
}

function verifica_rep_bodega(cod_bodega)
{
	var codigos = document.getElementById('arreglo_clientes').value;
	var repetido=0;
	if (codigos != '') {
		codigos = codigos.split(",");
		for(g=0; g<=codigos.length; g++){  
			if (cod_bodega==codigos[g]) { 
				alert ("Ya se agrego este cliente" +codigos[g]);
				repetido=1;
			}
		}
	}
	return repetido;
}

	
	
function borrar_cliente(){

	var num = document.getElementById('val_inicial');	
	var numE = document.getElementById('val_inicial').value;	

	num.value = parseInt(num.value) - 1;

	var combo=document.getElementById("combo_clientes_consulta");
	var cod_bodega = document.getElementById("combo_clientes_consulta").value;
	var combo1=document.getElementById("arreglo_clientes");
	
	var lugar=combo.selectedIndex;
	var datoo_eliminar=document.getElementById("combo_clientes_consulta").value+',';
	 var cadena=combo1.value;
	 
	while (cadena.indexOf(datoo_eliminar) != -1){ 

		 cadena = cadena.replace(datoo_eliminar,""); 

	 } 
	
	combo1.value=cadena;
	
	//alert (combo1.value);

	
	
	if (lugar>-1)
	
		combo.options[lugar]=null;
		cod_bodega.options[cod_bodega]=null;
	
}
	

function enviar_formulario(){ 
	if(document.getElementById('fechas').value ==''|| document.getElementById('fechas2').value =='' || document.getElementById('arreglo_clientes').value =='') {
		alert("Seleccione el cliente y la fecha inicial y fecha final de la consulta");
		return false;
	}
	
	else
 	document.forma_total.submit()
} 
</script> 
<? inicio() ?>
</head>
<body>
<table width="718" align="center">
<tr>
<td width="710" valign="top" >
<form id="forma_total" name="forma_total" method="post" action="man_inf_abonos.php" >
                  <table width="710" border="0" cellspacing="0" cellpadding="0" align="center" >
                    <tr>
                      <td width="710" bgcolor="#D1D8DE"><table width="100%" border="0" cellspacing="0" cellpadding="0"> <tr>
                          <td height="27" colspan="7"><div align="center" class="subfongris">INFORME ABONOS </div></td>
                        </tr>
                        <tr>
                          <td width="11" height="20"></td>
                          <td width="89" class="ctablaform">Clientes: </td>
                          <td colspan="4"><? 
						 
						  combo_evento("clientes_abono","bodega1", "cod_bod","CONCAT(nom_bod,apel_bod)", "", "","nombre"); ?>
						  <span class="textorojo">*
                            <label></label>
                            <label></label>
                            <input name="Submit" type="button" class="botones" onClick="agregar_cliente()" value="Agregar cliente">
                          </span></td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td class="ctablaform">&nbsp;</td>
                          <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td colspan="6"><p align="center">
                            <select name="combo_clientes_consulta" size="8"  id="combo_clientes_consulta">
                            </select>
                            </p>
                            <p align="center">
                              <input name="Submit2" type="button" class="botones" onClick="borrar_cliente()" value="Borrar cliente">
                              <input type="hidden" textarea  name="arreglo_clientes" id="arreglo_clientes" cols="40" rows="4" ></textarea>
							  <input type="hidden"  name="val_inicial" id="val_inicial" value="0" />   
                            </p></td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td>&nbsp;</td>
                          <td width="182">&nbsp;</td>
                          <td width="153">&nbsp;</td>
                          <td width="191">&nbsp;</td>
                          <td width="84" colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td><span class="ctablaform">&nbsp;Fecha Inicial:</span></td>
                          <td><input name="fechas" type="text" id="fechas" size="10" readonly />
                            <img src="imagenes/date.png" alt="Calendario" name="calendario" width="18" height="18" id="calendario" style="cursor:pointer"/>&nbsp;<span class="textorojo">*</span></td>
                          <td><span class="ctablaform">&nbsp;&nbsp;Fecha Final:</span></td>
                          <td><input name="fechas2" type="text" id="fechas2" size="10" readonly />
                            <img src="imagenes/date.png" alt="Calendario" name="calendario1" width="18" height="18" id="calendario1" style="cursor:pointer" /><span class="textorojo">*</span></td>
                          <td colspan="2"><img border="0" src="imagenes/lupa.png" alt="Buscar" width="16" height="16" style="cursor:pointer" onClick="enviar_formulario()" /></td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td>&nbsp;</td>
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
</script>
      </form>
</td>
</tr>
</table>						
</body>
</html>

