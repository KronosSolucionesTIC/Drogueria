<? include("../lib/database.php")?>
<? include("../js/funciones.php")?>
<html>
<head>
<title>
<?=$nombre_aplicacion?>
</title>
<script type="text/javascript" src="js/js.js"></script>
<script type="text/javascript">
var tWorkPath="menus/data.files/";
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function abrir(dato){
var url="ver_factura_v.php?codigo="+dato;
window.open(url,"ventana","menubar=0,resizable=1,width=800,height=600,toolbar=0,scrollbars=yes")
}

</script>
<script language="javascript">
function imprimir(){
	document.getElementById('imp').style.display="none";
	window.print();
}

function ver_ventas(obj,boton)
{
	if(document.getElementById(obj).style.display =="none")  {
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
body, td, th {
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
.Estilo1 {
	color: #FFFFFF
}
-->
</style>
<link href="../css/styles.css" rel="stylesheet" type="text/css">
<link href="../css/styles1.css" rel="stylesheet" type="text/css">
<link href="../css/styles2.css" rel="stylesheet" type="text/css">
<link href="../css/stylesforms.css" rel="stylesheet" type="text/css">
</head>
<body  <?=$sis?>>
<?  
$usuario = $_GET["usuario"];
$fechas = $_GET["fechas"];
$fechas2 = $_GET["fechas2"];

echo 'usuario'.$usuario;

if ($fechas2==0) {
	$fecha = "AND m_factura.fecha>='$fechas' ";
}
else{
	$fecha = "AND m_factura.fecha>='$fechas' AND m_factura.fecha<='$fechas2' ";
}

$sql2="SELECT R.cod_rut, R.cod_ruta_ciu, U.nom_usu 
FROM ruta R 
INNER JOIN usuario U ON  (R.usuario =  U.cod_usu)
WHERE R.usuario='$usuario'";
$db2= new  Database();
$db2->query($sql2);
$db2->next_row();
$ruta_ciudad=$db2->cod_ruta_ciu;

?>
<table width="644"  border="1" align="center" cellpadding="2" cellspacing="1"  >
  <tr class="subfongris" >
    <td colspan="7" width= '100%' ><b>INFORME DE VENTAS POR USUARIO SEGUN RUTA</b></td>
  </tr>
  <tr class="textoproductos1">
    <td width="30%" colspan="2" class="subfongris"><div align="left">USUARIO</span></strong></div></td>
    <td width="70%"  colspan="5" class="subfongris"><div align="left">
        <?=$db2->nom_usu ?>
      </div></td>
  </tr>
  <?

$ciudades = split("\,",$ruta_ciudad);
//print_r($ciudades);
for ($cod=0; $cod<=count($ciudades); $cod++) {  
	$ciudad = $ciudades[$cod];	 
	if  ($ciudad !="")  {  
		$total_ciudad = 0; 
		//busqueda de los clientes de esa ciudad que tienen facturas que cumplen la condicion
		
		$sqlg1="SELECT distinct nom_bod, cod_bod, ciu_bod, desc_ciudad
		FROM   bodega1
		INNER JOIN ciudad ON (bodega1.ciu_bod = ciudad.cod_ciudad)
		WHERE ciu_bod='$ciudad'  
		and bodega1.cod_bod in (select distinct cod_cli from m_factura  where estado IS NULL $fecha  order by m_factura.cod_cli )
		order by nom_bod"; 
	    $dbg= new  Database();
	    $dbg->query($sqlg1);
		if ($dbg->num_rows() >0 ) {
		  $dbg->next_row();	
		  $nom_ciudad = $dbg->desc_ciudad ;
		  ?>
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
        <?=$ciudad?>
        &nbsp;&nbsp;
        <?=$nom_ciudad?>
      </div></td>
  </tr>
  <tr>
    <td colspan="7" class="boton"><?php 
				$dbg->query($sqlg1);
				$total_cliente=0;
				$cliente = "";
				$cont = 1;
				if ($dbg->num_rows() >0 ) {
				while ($dbg->next_row())  {
				  if ($cliente != $dbg->cod_bod) { 
					  $cliente = $dbg->cod_bod;
					  $nomCliente = $dbg->nom_bod;
					  $total_cliente=0;
					  ?>
      <table width="99%"border="1" align="center" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
        <tr>
          <td colspan="5" class="subfongris"><div align="left">
              <?=$cliente?>
              -
              <?=$nomCliente?>
            </div></td>
        </tr>
        <tr>
          <td width="16%" class="boton"><div align="center">N. FACTURA </div></td>
          <td width="15%" class="boton"><div align="center">F. FACTURA </div></td>
          <td width="22%" class="boton"><div align="center">TOTAL FACTURA </div></td>
          <td class="boton"><div align="center">BODEGA</div></td>
          <td class="boton"><div align="center">DETALLE</div></td>
        </tr>
        <tbody id="<?= $cont?>" style="display:none">
          <?  
				  } // del if cambio de cliente
				  
				  $sqlt="SELECT 
				  m_factura.cod_fac,
				  m_factura.cod_cli,
				  m_factura.cod_bod,
				  m_factura.num_fac,
				  m_factura.fecha,
				  m_factura.tot_fac,
				   m_factura.tot_dev_mfac,  
				  bodega.cod_bod AS bodega,
				  bodega.nom_bod AS nombre_bodega 
				  FROM m_factura 
				  INNER JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)
				  INNER JOIN bodega ON (m_factura.cod_bod = bodega.cod_bod)
				 
				  WHERE bodega1.cod_bod_cli = bodega.cod_bod and  bodega1.cod_bod='$cliente' and  bodega1.ciu_bod = '$ciudad' 
				  AND estado IS NULL $fecha order by m_factura.cod_cli"; 
				  $dbr= new  Database();
				  $dbr->query($sqlt);
				 
				  while ($dbr->next_row()) {
					
					$i=1;
					$total_cliente += $dbr->tot_fac - $dbr->tot_dev_mfac; 	
				   
					if($i==1) {$color='#F2F4F7'; $color_est='#FFFFFF';} else {$color='#FFFFFF'; $color_est='#F2F4F7';}
				  ?>
          <tr bgcolor="<?=$color?>">
            <td class="textotabla1"><div align="center">
                <?=$dbr->num_fac?>
              </div></td>
            <td class="textotabla1"><div align="center">
                <?=$dbr->fecha?>
              </div></td>
            <td align="center" class="textotabla1"><div align="center">
                <?=($dbr->tot_fac - $dbr->tot_dev_mfac)  ?>
              </div></td>
            <td width="33%" align="center" class="textotabla1"><div align="center"><span class="textotabla01">
                <?=$dbr->nombre_bodega?>
                </span></div></td>
            <td width="14%" align="center" class="textotabla1"><div align="center"><span class="ctablablanc"> <? echo "<img src='../imagenes/mirar.png' width='16' height='16'  style=\"cursor:pointer\"  onclick=\"abrir($dbr->cod_fac)\" />" ?></span></div></td>
          </tr>
          <tr bgcolor="<?=$color?>">
            <td colspan="5" class="textotabla1"><div align="center"></div></td>
          </tr>
          <? 
					  if($i==1) { $i=2; }
					  else {$i=1;	 } 
					 
				  } // del while de facturas?>
        </tbody>
      </table>
      <table width="628"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
        <tr>
          <td width="533" height="34" class="textotabla01"><div align="right"> TOTAL CLIENTE </div></td>
          <td width="81"><div align="left" class="textotabla1">$<span class="textotabla01">
              <?=number_format($total_cliente,0,".",".")?>
              </span> </div></td>
        </tr>
      </table>
      <div align="center">
        <input name="button" type="button" class="botones1"  id="btn_vent<?= $cont?>" onClick="ver_ventas('<?= $cont?>','btn_vent<?= $cont?>')" value="Ver Detalles" />
      </div>
      <?           $cont=$cont+1; 
				   $total_ciudad += $total_cliente; 
				 } //de while clientes
				} //del if ?>
      <table width="628"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
        <tr>
          <td width="533" class="textotabla01"><div align="right">TOTAL CIUDAD </div></td>
          <td width="81"><div align="left" class="textotabla1">$<span class="textotabla01">
              <?=number_format($total_ciudad,0,".",".")?>
              </span> </div></td>
        </tr>
      </table>
      <?  
		  $gran_total_clientes+= $total_ciudad;  ?></td>
  </tr>
  <?
        } // del if hay filas
    } //del if ciudad no nula
} //del for de ciudades ?>
</table>
</br>
<input type="hidden" name="fechas" id="fechas" value="<?=$fechas?>">
<input type="hidden" name="fechas2" id="fechas2" value="<?=$fechas2?>">
<table width="644"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
  <tr>
    <td width="533" class="textotabla01"><div align="right">TOTAL VENTAS DEL USUARIO</div></td>
    <td width="97"><div align="left" class="textotabla1">$
        <?=number_format($gran_total_clientes,0,".",".")?>
      </div></td>
  </tr>
</table>
</br>
<table width="200" border="0" align="center">
  <tr>
    <td><div id="imp" align="center" class="tituloproductos">
        <input name="button3" type="button" class="botones"  onclick="imprimir()" value="Imprimir" />
      </div></td>
  </tr>
</table>
</body>
</html>