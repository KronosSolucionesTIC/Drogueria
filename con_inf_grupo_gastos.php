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


function agregar_gastos(){

    var cod_gastos = document.getElementById("grupo_gastos").value;
	var yaesta=0;
	if(cod_gastos!='')
		yaesta=verica_rep(cod_gastos);
	
	if(yaesta==0){
	var nombre = document.getElementById("grupo_gastos").options[document.getElementById("grupo_gastos").selectedIndex].text;
	var combo=document.getElementById("combo_gastos_consulta");
	var cantidad=combo.options.length;
	
	var num = document.getElementById('val_inicial');	
	num.value = parseInt(num.value) + 1;
	document.getElementById('arreglo_gastos').value=document.getElementById('arreglo_gastos').value+cod_gastos+',';
	
//document.getElementById('arreglo_gastos').value=document.getElementById('arreglo_gastos').value+num.value+'|';


	if (cod_gastos>0){
		combo.options[cantidad] = new Option(nombre,cod_gastos);
		
		
		}
		
	}	
}



function verica_rep(cod_gastos)
{
var codigos = document.getElementById('arreglo_gastos').value.split(",");
var repetido=0;
for(g=0; g<=codigos.length; g++)
{  
	
	if (cod_gastos==codigos[g]) { 
		alert ("Ya se agrego este gasto");
		repetido=1;
	}
}
	
	return repetido;
}

	
	
function borrar_gastos(){

	var num = document.getElementById('val_inicial');	
	var numE = document.getElementById('val_inicial').value;	

	num.value = parseInt(num.value) - 1;

	var combo=document.getElementById("combo_gastos_consulta");
	var cod_gastos = document.getElementById("combo_gastos_consulta").value;
	var combo1=document.getElementById("arreglo_gastos");
	
	var lugar=combo.selectedIndex;
	var datoo_eliminar=document.getElementById("combo_gastos_consulta").value+',';
	 var cadena=combo1.value;
	 
	while (cadena.indexOf(datoo_eliminar) != -1){ 

		 cadena = cadena.replace(datoo_eliminar,""); 

	 } 
	
	combo1.value=cadena;
	
	//alert (combo1.value);

	
	
	if (lugar>-1)
	
		combo.options[lugar]=null;
		cod_gastos.options[cod_gastos]=null;
	
}
	
function eliminar_proveedor(dato,ll){
alert(dato)
alert(ll)
var aa=document.forma_total.bb.value
vertorCodigo=aa.split(',');
for(var i=0; i<=ll; i++){
if (vertorCodigo[i]==dato){
valorNum=dato.concat(",");
cadena=aa.replace(valorNum,"");
document.forma_total.bb.value=cadena;
}

}
	}

function enviar_formulario(){ 

	if(document.getElementById('fechas').value ==''|| document.getElementById('fechas2').value =='' || document.getElementById('arreglo_gastos').value =='') {
		alert("Seleccione el gasto y la fecha inicial y fecha final de la consulta");
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

<form id="forma_total" name="forma_total" method="post" action="informes/inf_grupo_gastos.php" enctype="multipart/form-data">

                  <table width="710" border="0" cellspacing="0" cellpadding="0" align="center" >

                    <tr>

                      <td width="710" bgcolor="#D1D8DE"><table width="100%" border="0" cellspacing="0" cellpadding="0">


                        <tr>
                          <td height="27" colspan="7"><div align="center" class="subfongris">INFORME GRUPO GASTOS </div></td>
                        </tr>
                        <tr>

                          <td width="11" height="20"></td>

                          <td width="89" class="ctablaform">GASTOS: </td>

                          <td colspan="4"><span class="textorojo"><span class="textotabla1">
                            <? combo_evento("grupo_gastos","grupo_gastos","cod_gru","nom_gru",""," onchange=\"busca_tipos()\" ", "nom_gru"); ?>
                          </span>*
                            <label></label>
                            <label></label>
                            <input name="Submit" type="button" class="botones" onClick="agregar_gastos()" value="Agregar gastos">
                          </span>                            <div align="right"></div></td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td class="ctablaform">&nbsp;</td>
                          <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td colspan="6"><p align="center">
                            <select name="combo_gastos_consulta" size="8"  id="combo_gastos_consulta">
                            </select>
                            </p>
                            <p align="center">
                              <input name="Submit2" type="button" class="botones" onClick="borrar_gastos()" value="Borrar gastos">
                              <input type="hidden" textarea  name="arreglo_gastos" id="arreglo_gastos" cols="40" rows="4" ></textarea>
							  <input type="hidden"  name="val_inicial" id="val_inicial" value="<? if($codigo!=0) echo $jj-1; else echo "0"; ?>" />   
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
                          <td><input name="fechas" type="text" id="fechas" size="10" readonly="1" />
                            <img src="imagenes/date.png" alt="Calendario" name="calendario" width="18" height="18" id="calendario" style="cursor:pointer"/>&nbsp;<span class="textorojo">*</span></td>
                          <td><span class="ctablaform">&nbsp;&nbsp;Fecha Final:</span></td>
                          <td><input name="fechas2" type="text" id="fechas2" size="10" readonly="1" />
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

