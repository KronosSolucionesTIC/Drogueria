<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<html>
<head>
<title><?=$nombre_aplicacion?></title>
<script type="text/javascript">
var tWorkPath="menus/data.files/";
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function enviar() 
{
  document.form1.submit();
}

function enviar1() 
{
  document.form2.submit();
}



</script>


<script language="javascript">
function imprimir(){
	document.getElementById('imp').style.display="none";
	document.getElementById('cer').style.display="none";
	window.print();
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
<style type="text/css">
<!--
.Estilo7 {color: #000000;}
.Estilo9 {color: #000000; font-weight: bold; }
-->
</style>
<link href="css/stylesforms.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #FFFFFF}
-->
</style>
</head>
<body  <?=$sis?>>
 <!--SELECCION PROVEEDOR Y FECHA-->
  <table width="952" align="center">
    <tr>
      <td width="946" height="21"><div align="center" class="subfongris">INFORME DE CLIENTES </div></td>
    </tr>
</table>
  <?  
 if ($fechas2==0) {

	  $fecha = "AND m_factura.fecha>='$fechas'  group by producto.cod_fry_pro,d_factura.cod_peso";

}

else{


	$fecha = "AND m_factura.fecha>='$fechas'AND m_factura.fecha<='$fechas2'group by producto.cod_fry_pro,d_factura.cod_peso";

}

  $aaa = split("\,",$arreglo_clientes);
for($g=0; $g<=$val_inicial; $g++)
{  
 $ttt = $aaa[$g]; 

 if($ttt!="") {  
 
 
 $sqls="SELECT nom_bod, cod_bod,ciu_bod
	FROM
		bodega1
	WHERE cod_bod='$ttt'";
$dbs= new  Database();
$dbs->query($sqls);
$dbs->next_row();
$nombre=$dbs->nom_bod;
$ciudad=$dbs->ciu_bod;
 

 
  
 ?>
  <table width="94%"border="1" align="center" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
    <tr>
      <td class="subfongris"><div align="left"><strong><span class="Estilo1">PERIODO</span></strong></div></td>
      <td class="subfongris"><div align="left"><strong><span class="Estilo1">FECHA INICIAL </span></strong></div></td>
      <td class="subfongris"><div align="left">
          <?=$fechas?>
      </div></td>
      <td class="subfongris"><div align="left"><strong><span class="Estilo1">FECHA FINAL </span></strong></div></td>
      <td colspan="3" class="subfongris"><div align="left">
          <?=$fechas2?>
      </div></td>
    </tr>
    <tr>
      <td class="subfongris"><div align="left">CLIENTE</div></td>
      <td colspan="6" class="subfongris"><div align="left">
          <?=$nombre?>
        </div>
          <div align="left"></div></td>
    </tr>
    <tr>
      <td class="subfongris"><div align="left">CIUDAD</div></td>
      <td colspan="6" class="subfongris"><div align="left">
          <?=$ciudad?>
      </div></td>
    </tr>
    <tr>
      <td width="8%" class="boton">CODIGO </td>
      <td width="21%" class="boton">NOMBRE </td>
      <td width="19%" class="boton">TIPO PRODUCTO</td>
      <td width="12%" class="boton">MARCA </td>
      <td width="8%" class="boton">TALLA</td>
      <td width="12%" class="boton">CANTIDAD</td>
      <td width="20%" class="boton">BODEGA </td>
    </tr>
    <?  
	
	$sqlr="SELECT 
 
  m_factura.cod_fac,
  m_factura.cod_cli,
  m_factura.cod_bod,
  m_factura.fecha,
  d_factura.cod_mfac,
  d_factura.cod_tpro,
  d_factura.cod_cat,
  bodega1.cod_bod,
  bodega1.nom_bod,
  d_factura.cod_pro,
  d_factura.cod_dfac,
  d_factura.cod_peso,
  sum(d_factura.cant_pro)as cant_pro,
  d_factura.cod_mfac,
  producto.cod_pro,
  tipo_producto.cod_tpro,
  marca.cod_mar,
  peso.cod_pes,
  peso.nom_pes,
  marca.nom_mar,
  producto.cod_fry_pro,
  producto.nom_pro,
  tipo_producto.nom_tpro,
  bodega.cod_bod AS bodega,
  bodega.nom_bod AS nombre_bodega
  
FROM

   m_factura
  INNER JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)
  INNER JOIN d_factura ON (m_factura.cod_fac = d_factura.cod_mfac)
  INNER JOIN producto ON (d_factura.cod_pro = producto.cod_pro)
  INNER JOIN peso ON (d_factura.cod_peso = peso.cod_pes)
  INNER JOIN marca ON (d_factura.cod_cat = marca.cod_mar)
  INNER JOIN tipo_producto ON (d_factura.cod_tpro = tipo_producto.cod_tpro) 
  INNER JOIN bodega ON (m_factura.cod_bod = bodega.cod_bod)
  WHERE m_factura.cod_cli='$ttt' $fecha ORDER BY cod_cat DESC "; 
 

$dbr= new  Database();
$dbr->query($sqlr);
$nombre=$dbr->nom_bod;
$code=$dbr->cod_bod;
$total_cantidad=0;
$i==1;
$a=1;

	
	
 while ($dbr->next_row()) {
 $total_cantidad=$total_cantidad+$dbr->cant_pro;	
       
if($i==1) {$color='#F2F4F7'; $color_est='#FFFFFF';} else {$color='#FFFFFF'; $color_est='#F2F4F7';}
	  ?>
    <tr bgcolor="<?=$color?>">
      <td class="textotabla1"><span class="textotabla01">
        <?=$dbr->cod_fry_pro?>
      </span></td>
      <td class="textotabla1"><div align="left">
          <?=$dbr->nom_pro?>
      </div></td>
      <td class="textotabla1"><div align="left"><span class="textotabla01">
          <?=$dbr->nom_tpro?>
      </span></div></td>
      <td align="center" class="textotabla1"><div align="left">
          <?=$dbr->nom_mar?>
      </div></td>
      <td align="center" class="textotabla1"><div align="center"><span class="textotabla01">
          <?=$dbr->nom_pes?>
      </span></div></td>
      <td align="center" class="textotabla1"><div align="center">
          <?=$dbr->cant_pro?>
      </div></td>
      <td align="center" class="textotabla1"><div align="left"><span class="textotabla01">
          <?=$dbr->nombre_bodega?>
      </span></div></td>
    </tr>
    <? if($i==1) { $i=2; }
		else {$i=1;	 }  
	$a++; } ?>
  </table>
  <table width="941"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
    <tr>
      <td width="745" class="textotabla01"><div align="right">TOTAL CANTIDAD </div></td>
      <td width="182"><div align="left"><span class="textotabla1">$<span class="textotabla01"><?=number_format($total_cantidad,0,".",".")?>
      </span> </span></div></td>
    </tr>
</table>  </br>

 <script>
function abrir(){
	window.print();
}
</script>

    
     <input type="hidden" name="clientes_abono" id="clientes_abono" value="<?=$clientes_abono?>">
   <input type="hidden" name="fechas" id="fechas" value="<?=$fechas?>">
     <input type="hidden" name="fechas2" id="fechas2" value="<?=$fechas2?>">
</form>
  <!--FIN SELECCION PROVEEDOR Y FECHA-->  
<!--SELECCION FECHA-->



  <script>
function abrir(){
	window.print();
}
</script>
  
  
  <? $gran_total_clientes=$gran_total_clientes+$total_cantidad; } }?>
   <input type="hidden" name="fechas" id="fechas" value="<?=$fechas?>">
     <input type="hidden" name="fechas2" id="fechas2" value="<?=$fechas2?>">
</form>
 <!--FIN SELECCION FECHA-->
 <table width="941"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
   <tr>
     <td width="745" class="textotabla01"><div align="right">TOTAL CANTIDAD CLIENTES </div></td>
     <td width="182"><div align="left"><span class="textotabla1">$
       <?=number_format($gran_total_clientes,0,".",".")?>
     </span></div></td>
   </tr>
</table>  </br>
 <table width="200" border="0" align="center">
   <tr>
     <td><div align="center"><span class="tituloproductos">
         <input name="button2" type="button" class="botones"  onClick="abrir()" value="Imprimir">
     </span></div></td>
   </tr>
 </table>
</body>
</html>