<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<html >
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
function agregar_bodega(){

    var cod_bodega = document.getElementById("clientes_abono").options[document.getElementById("clientes_abono").selectedIndex].value;
	//alert("CODBod" +cod_bodega); 
	if (cod_bodega=='0'){
		agregar_todas_bodegas();
	}
	else if (cod_bodega!=''){
	        if (verifica_rep(cod_bodega) != 1) {
				var nombre = document.getElementById("clientes_abono").options[document.getElementById("clientes_abono").selectedIndex].text;
				var combo=document.getElementById("combo_bodegas_consulta");
				var cantidad=combo.options.length;
				
				var num = document.getElementById('val_inicial');	
				num.value = parseInt(num.value) + 1;
				document.getElementById('arreglo_bodegas').value+=cod_bodega+',';
				if (cod_bodega>=0){
					combo.options[cantidad] = new Option(nombre,cod_bodega);
				}
			}
    }
}

function verifica_rep(cod_bodega)
{
	var codigos = document.getElementById('arreglo_bodegas').value.split(",");
	var repetido=0;
	for(g=0; g<= codigos.length; g++)
	{  
		if (cod_bodega==codigos[g]) { 
			alert ("Ya se agrego esta bodega");
			repetido=1;
		}
	}
	return repetido;
}
	
function agregar_todas_bodegas() {
	var combo=document.getElementById("combo_bodegas_consulta");
	combo.options.length=0;
	var cant=0;
	var cod_ciudad=0;
	var num = document.getElementById('val_inicial');	 	
	<? 
		$db = new Database();	
		$sql="SELECT distinct cod_bod, nom_bod from bodega where cod_bod  in (select distinct cod_bod from m_factura) order by nom_bod ";
		$db->query($sql);
		while($db->next_row()){
		$anadelo = true;

		 if ($anadelo) {
			 echo "combo.options[cant] = new Option(\"$db->nom_bod\",'$db->cod_bod');  \n";
			 echo  "cant++;  \n 	";
			 echo "cod_bod = '$db->cod_bod';";
			 echo "	document.getElementById('arreglo_bodegas').value+= cod_bod+',';";
		  }
		}
	?>	
	num.value = combo.options.length + 1;
		
}	
	
function borrar_bodega(){

	var num = document.getElementById('val_inicial');	
	var numE = document.getElementById('val_inicial').value;	

	num.value = parseInt(num.value) - 1;

	var combo=document.getElementById("combo_bodegas_consulta");
	var cod_bodega = document.getElementById("combo_bodegas_consulta").value;
	var combo1=document.getElementById("arreglo_bodegas");
	
	var lugar=combo.selectedIndex;
	var datoo_eliminar=document.getElementById("combo_bodegas_consulta").value+',';
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
	if (document.getElementById('arreglo_bodegas').value ==''||document.getElementById('combo_lista').value ==0) {
		alert("Seleccione la bodega y la lista de precios");
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
    <td width="710" valign="top" ><form id="forma_total" name="forma_total" method="post" action="informes/consulta_existencias_admin1.php" enctype="multipart/form-data">
        <table width="710" border="0" cellspacing="0" cellpadding="0" align="center" >
          <tr>
            <td width="710" bgcolor="#D1D8DE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="27" colspan="8"><div align="center" class="subfongris">INFORME VALORAR BODEGAS </div></td>
                </tr>
                <tr>
                  <td width="11" height="20"></td>
                  <td width="143" class="ctablaform">Bodegas:</td>
                  <td colspan="5"><? 
				  combo_evento_where("clientes_abono","bodega ","cod_bod","nom_bod","","", " where cod_bod in (select cod_bod from m_factura) order by nom_bod "); ?>
                    <span class="textorojo">*
                    <input name="Submit" type="button" class="botones" onClick="agregar_bodega()" value="Agregar Bodega">
                    </span></td>
                </tr>
                <tr>
                  <td height="20"></td>
                  <td class="ctablaform">&nbsp;</td>
                  <td colspan="6">&nbsp;</td>
                </tr>
                <tr>
                  <td height="20"></td>
                  <td colspan="7"><p align="center">
                      <select name="combo_bodegas_consulta" size="5"  id="combo_bodegas_consulta">
                      </select>
                    </p>
                    <p align="center">
                      <input name="Submit2" type="button" class="botones" onClick="borrar_bodega()" value="Borrar Bodega">
                      <input type="hidden" textarea  name="arreglo_bodegas" id="arreglo_bodegas" cols="40" rows="4" >
                      </textarea>
                      <input type="hidden"  name="val_inicial" id="val_inicial" value="<? if($codigo!=0) echo $jj-1; else echo "0"; ?>" />
                    </p></td>
                </tr>
                <tr>
                  <td height="20"></td>
                  <td>&nbsp;</td>
                  <td width="128">&nbsp;</td>
                  <td colspan="2">&nbsp;</td>
                  <td width="191">&nbsp;</td>
                  <td width="84" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td height="20"></td>
                  <td class="ctablaform">Lista de precios </td>
                  <td><? combo("combo_lista","listaprecio","cos_list","nom_list",$dbdatose->cos_list);?></td>
                  <td width="22">&nbsp;</td>
                  <td width="131"><img border="0" src="imagenes/lupa.png" alt="Buscar" width="16" height="16" style="cursor:pointer" onClick="enviar_formulario()" /></td>
                  <td>&nbsp;</td>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td height="20"></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td colspan="2">&nbsp;</td>
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
      </form></td>
  </tr>
</table>
</body>
</html>