<? include("../lib/database.php")?>
<? include("../js/funciones.php")?>
<html>
<head>
<title>
<?=$nombre_aplicacion?>
</title>
<script type="text/javascript" src="js"></script>
<script type="text/javascript">

var tWorkPath="menus/data.files/";
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function enviar()  {
  document.form1.submit();
}

function enviar1() {
  document.form2.submit();
}
function abrir(dato){

var url="ver_factura_v.php?codigo="+dato;
window.open(url,"ventana","menubar=0,resizable=1,width=800,height=600,toolbar=0,scrollbars=yes")
 
}

function imprimir(){
	document.getElementById('imp').style.display="none";
	document.getElementById('cer').style.display="none";
	window.print();
}

function ver_ventas(obj,boton)
{
	if(document.getElementById(obj).style.display =="none")
	{
		document.getElementById(obj).style.display ="inline";
		document.getElementById(boton).value ="Ocultar";
	}
	else {
		document.getElementById(obj).style.display ="none";
		document.getElementById(boton).value ="Ver Detalles";
	}
}
</script>
<script type="text/javascript" src="js/funciones.js"></script>
<link href="css/styles.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body,td,th {
	color: #FFFFFF;
}
body {
	background-color: #FFFFFF;
}
-->
</style>
<link href="css/print.css" rel="stylesheet" type="text/css">
<link href="css/stylesforms.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #FFFFFF}
-->
</style>
<link href="../css/styles.css" rel="stylesheet" type="text/css">
<link href="../css/styles1.css" rel="stylesheet" type="text/css">
<link href="../css/styles2.css" rel="stylesheet" type="text/css">
<link href="../css/stylesforms.css" rel="stylesheet" type="text/css">
</head>
<body  <?=$sis?>>
<?  
if ($fechas2==0) {
	  $fecha = "AND m_factura.fecha>='$fechas'  ";
}
else{
	$fecha = "AND m_factura.fecha>='$fechas' AND m_factura.fecha<='$fechas2' ";
}
$gran_total_clientes = 0;
$total_dev = 0;
$cont = 0;
?>
<table width="644" border="1" align="center" cellpadding="2" cellspacing="1"   class="textoproductos1">
  <tr align="center" class="subfongris">
    <td width="100%" >INFORME DE VENTAS POR CIUDAD </td>
  </tr>
  <tr>
    <td  width="100%"><br></td>
  </tr>
  <tr>
    <td width="100%" ><?
$codciudades = split("\,",$arreglo_ciudad);  
for($g=0; $g<=count($codciudades); $g++)
{  
  $codCiudad = $codciudades[$g]; 
  if ($codCiudad!="") { 
	  $sqlg="SELECT distinct CL.ciu_bod, C.desc_ciudad FROM bodega1 CL INNER JOIN ciudad C ON  CL.ciu_bod  = C.cod_ciudad  WHERE CL.ciu_bod='$codCiudad' order by C.desc_ciudad";
	  $dbg= new  Database();
	  $dbg->query($sqlg);
	  while ($dbg->next_row()) { //inicio while de ciudades
	 
		  $ciudad=$dbg->ciu_bod;
		  $nomciudad=$dbg->desc_ciudad;
		  $total_ciudad=0;
		  $total_ciudad_dev = 0;
         ?>
<table width="644"border="1" align="center" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
  <tr>
    <td width="8%" class="subfongris"><div align="left">PERIODO</span></strong></div></td>
    <td width="16%" class="subfongris"><div align="left"><strong><span class="Estilo1">FECHA INICIAL </span></strong></div></td>
    <td width="15%" class="subfongris"><div align="left">
        <?=$fechas?>
      </div></td>
    <td width="16%" class="subfongris"><div align="left"><strong><span class="Estilo1">FECHA FINAL </span></strong></div></td>
    <td width="45%" colspan="3" class="subfongris"><div align="left">
        <?=$fechas2?>
      </div></td>
  </tr>
  <tr>
    <td class="subfongris"><div align="left">CIUDAD</div></td>
    <td colspan="6" class="subfongris"><div align="left">
        <?=$nomciudad?>
      </div></td>
  </tr>
  <tr>
    <td colspan="7" class="boton"><?
 		  $sqlt="SELECT distinct
		  bodega1.cod_bod as cliente,
		  bodega1.nom_bod
		  FROM  bodega1
		  INNER JOIN m_factura ON (bodega1.cod_bod = m_factura.cod_cli)
		  WHERE bodega1.ciu_bod='$ciudad'  $fecha  AND m_factura.estado IS NULL order by bodega1.cod_bod"; 

		  $dbt= new  Database();
		  $dbt->query($sqlt);

		  while ($dbt->next_row()) { //inicio while de clientes
				$nombre=$dbt->nom_bod;
				$code=$dbt->cliente;  
				$cont+=1; 
				$total_cliente=0; 
				$total_cliente_dev = 0;
 	  
	            ?>
      <table width="644" border="1" align="center" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
        <tr>
          <td colspan="5" class="subfongris"><div align="left">
              <?=$nombre?>
            </div></td>
        </tr>
        <tr>
          <td width="16%" class="boton"><div align="center">N. FACTURA </div></td>
          <td width="15%" class="boton"><div align="center">F. FACTURA </div></td>
          <td width="22%" class="boton"><div align="center">TOTAL FACTURA </div></td>
          <td class="boton"><div align="center">BODEGA </div></td>
          <td class="boton"><div align="center">DETALLE</div></td>
        </tr>
        <tbody id="<?= $cont?>" style="display:none">
          <?  
	
  			   $sqlr="SELECT distinct
			   m_factura.cod_fac,
			   m_factura.num_fac,
			   m_factura.fecha,
			   m_factura.tot_fac,
			    m_factura.tot_dev_mfac, 
			   bodega.nom_bod AS nombre_bodega
			  
			   FROM  m_factura
			  
			   left join bodega1 on (bodega1.cod_bod=m_factura.cod_cli) 
			   left join rsocial on (rsocial.cod_rso=m_factura.cod_razon_fac)
			   left join usuario on (usuario.cod_usu=m_factura.cod_usu)
			   left join bodega on (bodega.cod_bod =m_factura.cod_bod) 
			   left join ciudad on  bodega1.ciu_bod = ciudad.cod_ciudad
			   
			   WHERE m_factura.cod_cli='$code'  and bodega1.ciu_bod='$ciudad' AND m_factura.estado IS NULL $fecha
			   ORDER BY m_factura.fecha ASC "; 
 
			   $dbr= new  Database();
			   $dbr->query($sqlr);
			   $i==1;
			
				
			   while ($dbr->next_row()) { //inicio while facturas
				  	$total_cliente += $dbr->tot_fac;	
					$total_cliente_dev += $dbr->tot_dev_mfac; 
					if ($i==1) {$color='#F2F4F7'; $color_est='#FFFFFF';} else {$color='#FFFFFF'; $color_est='#F2F4F7';}
	  ?>
          <tr bgcolor="<?=$color?>">
            <td class="textotabla1"><div align="center">
                <?=$dbr->num_fac?>
              </div></td>
            <td class="textotabla1"><div align="center">
                <?=$dbr->fecha?>
              </div></td>
            <td align="center" class="textotabla1"><div align="center">
                <?=($dbr->tot_fac - $dbr->tot_dev_mfac)?>
              </div></td>
            <td width="33%" align="center" class="textotabla1"><div align="center"><span class="textotabla01">
                <?=$dbr->nombre_bodega?></span>
              </div></td>
            <td width="14%" align="center" class="textotabla1"><div align="center"><span class="ctablablanc"> <? echo "<img src='../imagenes/mirar.png' width='16' height='16'  style=\"cursor:pointer\"  onclick=\"abrir($dbr->cod_fac)\" />" ?></span></div></td>
          </tr>
          <tr bgcolor="<?=$color?>">
            <td colspan="5" class="textotabla1"><div align="center"></div></td>
          </tr>
          <? 	    if($i==1) { $i=2; }
					else {$i=1;	 } 
				
			   } // fin while facturas  ?>
      </table>
      <table width="644"  border="1" align="center" cellpadding="2" cellspacing="0"  bgcolor="#FFFFFF" class="textoproductos1">
        <tr>
          <td width="533" height="34" class="textotabla01"><div align="right"> TOTAL CANTIDAD CLIENTE </div></td>
          <td width="81"><div align="left" class="textotabla1">$<span class="textotabla01">
              <?=number_format($total_cliente - $total_cliente_dev, 0,".",".")?>
              </span> </div></td>
        </tr>
      </table>
      <div align="center">
        <input name="button" type="button" class="botones1"  id="btn_vent<?= $cont?>" onClick="ver_ventas('<?= $cont?>','btn_vent<?= $cont?>')" value="Ver Detalles" />
      </div>
          
		  <?    $total_ciudad+=$total_cliente; 
		        $total_ciudad_dev += $total_cliente_dev; 
		  } //fin de while de clientes ?>
      <table width="644"  border="1" align="center" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF"  class="textoproductos1">
        <tr>
          <td width="533" class="textotabla01"><div align="right">TOTAL CANTIDAD CIUDAD <? echo $nomciudad ?></div></td>
          <td width="81"><div align="left" class="textotabla1">$<span class="textotabla01">
              <?=number_format($total_ciudad - $total_ciudad_dev ,0,".",".")?>
              </span> </div></td>
        </tr>
      </table></td>
  </tr>
</table>
</tbody>
</br>
        <? $gran_total_clientes+=$total_ciudad; 
		   $total_dev +=  $total_ciudad_dev;
     } // fin del while de ciudades
  } // fin del if
} // fin del for
?>
    </td>
  </tr>
</table>
<table width="644"  border="0" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
  <tr  border="1">
    <td colspan="2"><table width="100%" border="1">
        <td width="533" class="textotabla01" border="1"><div align="right"><b>TOTAL</b></div></td>
          <td width="81" border="1"><div align="left" class="textotabla1"><b>$
              <?=number_format($gran_total_clientes - $total_dev,0,".",".")?>
              </b> </div></td>
      </table></td>
  </tr>
  <tr>
    <td colspan="2"></br></td>
  </tr>
  <tr>
    <td colspan="2" ><div align="center" class="tituloproductos">
        <input type="hidden" name="mapa" value="<?=$mapa?>" />
        <input name="button3" type="button" class="botones"  onclick="window.print()" value="Imprimir" />
      </div></td>
  </tr>
</table>
</body>
</html>