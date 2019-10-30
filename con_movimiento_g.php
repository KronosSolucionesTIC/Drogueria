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

/*combo agregar referencia *****/
function agregar_referencia(){

	var cod_referencia = document.getElementById("referencia").value;
	var yaesta=0;
	if(cod_referencia!='')
		yaesta=verica_rep(cod_referencia);
	
	if(yaesta==0){	
		var nombre = document.getElementById("referencia").options[document.getElementById("referencia").selectedIndex].text;
		var combo_sql=document.getElementById("combo_referencia_consulta");
		var cantidad=combo_sql.options.length;
		
		var num = document.getElementById('val_inicial');	
		num.value = parseInt(num.value) + 1;
		document.getElementById('arreglo_referencia').value=document.getElementById('arreglo_referencia').value+cod_referencia+',';
		
	//document.getElementById('arreglo_referencia').value=document.getElementById('arreglo_referencia').value+num.value+'|';
	
	
		if (cod_referencia>0){
			combo_sql.options[cantidad] = new Option(nombre,cod_referencia);
			
			
			}
	}
		
}


	
function verica_rep(cod_referencia)
{
var codigos = document.getElementById('arreglo_referencia').value.split(",");
var repetido=0;
for(g=0; g<=codigos.length; g++)
{  
	
	if (cod_referencia==codigos[g]) { 
		alert ("Ya se agrego esta Referencia");
		repetido=1;
	}
}
	
	return repetido;
}
	
function borrar_referencia(){

	var num = document.getElementById('val_inicial');	
	var numE = document.getElementById('val_inicial').value;	

	num.value = parseInt(num.value) - 1;

	var combo_sql=document.getElementById("combo_referencia_consulta");
	var cod_referencia = document.getElementById("combo_referencia_consulta").value;
	var combo_sql1=document.getElementById("arreglo_referencia");
	
	var lugar=combo_sql.selectedIndex;
	var datoo_eliminar=document.getElementById("combo_referencia_consulta").value+',';
	 var cadena=combo_sql1.value;
	 
	while (cadena.indexOf(datoo_eliminar) != -1){ 

		 cadena = cadena.replace(datoo_eliminar,""); 

	 } 
	
	combo_sql1.value=cadena;
	
	//alert (combo_sql1.value);

	
	
	if (lugar>-1)
	
		combo_sql.options[lugar]=null;
		cod_referencia.options[cod_referencia]=null;
	
}
	
/*function eliminar_proveedor(dato,ll){
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
	}*/


/******************** FIN COMBO REFERENCIA***************************/



function enviar_formulario(){ 

	if(document.getElementById('fechas').value ==''|| document.getElementById('fechas2').value =='' || document.getElementById('arreglo_referencia').value =='') {
		alert("Seleccione la refencia la fecha inicial y fecha final de la consulta");
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

<form id="forma_total" name="forma_total" method="post" action="informes/inf_movimiento_g.php" enctype="multipart/form-data">

                  <table width="710" border="0" cellspacing="0" cellpadding="0" align="center" >

                    <tr>

                      <td width="710" bgcolor="#D1D8DE"><table width="100%" border="0" cellspacing="0" cellpadding="0">


                        <tr>
                          <td height="27" colspan="7"><div align="center" class="subfongris">INFORME MOVIMIENTOS </div></td>
                        </tr>
                        <tr>

                          <td width="11" height="20"></td>

                          <td width="89" class="ctablaform">Referencia</td>

                          <td colspan="4">
                            <? 
			  
		    $sql=" select *, concat( cod_fry_pro, ' - ', desc_ref ) as nom_pro  from producto
			INNER JOIN referencia ON referencia.cod_ref = producto.nom_pro order by cod_fry_pro asc";
			combo_sql("referencia","producto","cod_pro","nom_pro",$dbdatose->cod_bod,$sql); 
			  
			  ?>                            
                            <span class="textorojo">*
                            <label></label>
                            <label></label>
                            <input name="Submit" type="button" class="botones" onClick="agregar_referencia()" value="Agregar referencia">
                            </span>                            
                          <div align="right"></div></td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td class="ctablaform">&nbsp;</td>
                          <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="20"></td>
                          <td colspan="6"><p align="center">
                            <select name="combo_referencia_consulta" size="8"  id="combo_referencia_consulta">
                            </select>
                            </p>
                            <p align="center">
                              <input name="Submit2" type="button" class="botones" onClick="borrar_referencia()" value="Borrar referencia">
                              <input type="hidden" textarea  name="arreglo_referencia" id="arreglo_referencia" cols="40" rows="4" ></textarea>
							  <input type="hidden"  name="val_inicial" id="val_inicial" value="<? if($codigo!=0) echo $jj-1; else echo "0"; ?>" />   
                            </p></td>
                        </tr>
                        
                        <tr>
                          <td height="20"></td>
                          <td class="ctablaform">&nbsp;</td>
                          <td width="182">&nbsp;</td>
                          <td width="153">&nbsp;</td>
                          <td width="191">&nbsp;</td>
                          <td width="336" colspan="2">&nbsp;</td>
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

