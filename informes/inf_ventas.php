<? include("../lib/database.php")?>
<? include("../js/funciones.php")?>
<html>
<head>
<title><?=$nombre_aplicacion?></title>
<script type="text/javascript" src="js"></script>
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
function abrir(dato){

var url="ver_factura_v.php?codigo="+dato;
window.open(url,"ventana","menubar=0,resizable=1,width=800,height=600,toolbar=0,scrollbars=yes")
 
}




</script>


<script language="javascript">
function imprimir(){
	document.getElementById('imp').style.display="none";
	document.getElementById('cer').style.display="none";
	window.print();
}


</script>


<script language="javascript">
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
 <!--SELECCION PROVEEDOR Y FECHA-->
  <table width="643" align="center" class="subfongris">
    <tr>
      <td width="202" height="21">&nbsp;</td>
      <td width="229">INFORME DE VENTAS </td>
      <td width="196">&nbsp;</td>
    </tr>
</table>
  <?  
 if ($fechas2==0) {

	  $fecha = "AND m_factura.fecha>='$fechas' 
	  ";

}

else{


	$fecha = "AND m_factura.fecha>='$fechas' AND m_factura.fecha<='$fechas2' 
	";

}

 $aaa = split("\,",$arreglo_ciudad);
for($g=0; $g<=$val_inicial; $g++)
{  
  $ttt = $aaa[$g]; 

 if($ttt!="") {  
 
  $sqlg="SELECT nom_bod,cod_bod,ciu_bod
	FROM
		bodega1
	WHERE cod_bod='$ttt'";
$dbg= new  Database();
$dbg->query($sqlg);
 while ($dbg->next_row()) {
 
 $ciudad=$dbg->ciu_bod;

 
  
  ?>

  <table width="644"border="1" align="center" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
    
    <tr>
      <td width="8%" class="subfongris"><div align="left">PERIODO</span></strong></div></td>
      <td width="16%" class="subfongris"><div align="left"><strong><span class="Estilo1">FECHA INICIAL </span></strong></div></td>
      <td width="15%" class="subfongris"><div align="left">
        <?=$fechas?>
      </div>      </td>
      <td width="16%" class="subfongris"><div align="left"><strong><span class="Estilo1">FECHA FINAL </span></strong></div></td>
      <td width="45%" colspan="3" class="subfongris"><div align="left">
        <?=$fechas2?>
      </div></td>
    </tr>
    
    <tr>
      <td class="subfongris"><div align="left">CIUDAD</div></td>
      <td colspan="6" class="subfongris"><div align="left">
        <div align="left">
          <?=$ciudad?></div>
      </div>      </td>
    </tr>
    <tr>
      <td colspan="7" class="boton">
	  
	
	
	  <?
  $sqlt="SELECT 
  bodega1.cod_bod as cliente,
  bodega1.nom_bod,
  bodega1.ciu_bod,
  m_factura.cod_fac,
  m_factura.fecha,
  m_factura.cod_cli,
  m_factura.cod_bod as bodega
FROM
  bodega1
  INNER JOIN m_factura ON (bodega1.cod_bod = m_factura.cod_cli)
WHERE bodega1.ciu_bod='$ciudad'  $fecha  AND estado IS NULL group by m_factura.cod_cli"; 
 

$dbt= new  Database();
$dbt->query($sqlt);
$total_c=0;
 while ($dbt->next_row()) {
$nombre=$dbt->nom_bod;
$code=$dbt->cliente;  
 $cont=$cont+1;  
 	  
	?> 
	
	
	  <table width="99%"border="1" align="center" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
        
        <tr>
          <td colspan="5" class="subfongris"><div align="left">
              <?=$nombre?>
            </div>
              <div align="left"></div></td>
        </tr>
        
        <tr>
          <td width="16%" class="boton"><div align="center">N. FACTURA </div></td>
          <td width="15%" class="boton"><div align="center">F. FACTURA </div></td>
          <td width="22%" class="boton"><div align="center">TOTAL FACTURA </div>
          </span></td>
          <td class="boton"><div align="center">BODEGA </span></div></td>
          <td class="boton"><div align="center">DETALLE</div></td>
        </tr>
		
		<tbody id="<?= $cont?>" style="display:none">
		
		
        <?  
	
   $sqlr="SELECT 
 
  m_factura.cod_fac,
  m_factura.cod_cli,
  m_factura.cod_bod,
  m_factura.num_fac,
  m_factura.fecha,
  m_factura.tot_fac,
  bodega1.cod_bod,
  bodega1.nom_bod,
  bodega.cod_bod AS bodega,
  bodega.nom_bod AS nombre_bodega
  
FROM

   m_factura
  INNER JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)
   INNER JOIN bodega ON (m_factura.cod_bod = bodega.cod_bod)
  WHERE m_factura.cod_cli='$code'  $fecha "; 
 

$dbr= new  Database();
$dbr->query($sqlr);
$total_cantidad=0;

$i==1;
$a=1;

 while ($dbr->next_row()) {
  $total_cantidad+=$dbr->tot_fac."<br>";	
       
if($i==1) {$color='#F2F4F7'; $color_est='#FFFFFF';} else {$color='#FFFFFF'; $color_est='#F2F4F7';}
	  ?>
        <tr bgcolor="<?=$color?>">
          <td class="textotabla1">
            <div align="center">
              <?=$dbr->num_fac?>
            </div></td>
          <td class="textotabla1">
            <div align="center">
              <?=$dbr->fecha?>
            </div></td>
          <td align="center" class="textotabla1">            <div align="center">
            <?=$dbr->tot_fac?>
          </div></td>
          <td width="33%" align="center" class="textotabla1"><div align="center"><span class="textotabla01">
            <?=$dbr->nombre_bodega?>
          </span></div></td>
          <td width="14%" align="center" class="textotabla1"><div align="center"><span class="ctablablanc">
            <? echo "<img src='../imagenes/mirar.png' width='16' height='16'  style=\"cursor:pointer\"  onclick=\"abrir($dbr->cod_fac)\" />" ?></span></div></td>
        </tr>
        <tr bgcolor="<?=$color?>">
          <td colspan="5" class="textotabla1"><div align="center"></div></td>
          </tr>
		
		
        <? if($i==1) { $i=2; }
		else {$i=1;	 } 
		
		 
	$a++; } ?>
      </table>
	 
	  
	   <table width="628"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
         
         <tr>
           <td width="533" height="34" class="textotabla01"><div align="right">
             TOTAL CANTIDAD </div></td>
           <td width="81"><div align="left" class="textotabla1">$<span class="textotabla01">
             <?=number_format($total_cantidad,0,".",".")?>
           </span> </div></td>
         </tr>
       </table>   
	
	     
	   <div align="center">
	   
	   <input name="button" type="button" class="botones1"  id="btn_vent<?= $cont?>" onClick="ver_ventas('<?= $cont?>','btn_vent<?= $cont?>')" value="Ver Detalles" />
	     
	     <?    $total_c+=$total_cantidad; }?>
	     
	   </div>
	   <table width="628"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
         <tr>
           <td width="533" class="textotabla01"><div align="right">TOTAL CANTIDAD </div></td>
           <td width="81"><div align="left" class="textotabla1">$<span class="textotabla01">
             <?=number_format($total_c,0,".",".")?>
           </span> </div></td>
         </tr>
       </table>  
	  
      </td>
    </tr>
</table>
  </tbody>
  </br>


    
     <input type="hidden" name="clientes_abono" id="clientes_abono" value="<?=$clientes_abono?>">
   <input type="hidden" name="fechas" id="fechas" value="<?=$fechas?>">
     <input type="hidden" name="fechas2" id="fechas2" value="<?=$fechas2?>">
</form>
  <!--FIN SELECCION PROVEEDOR Y FECHA-->  
<!--SELECCION FECHA-->



  
  
  <? $gran_total_clientes+=$total_c; }} }?>
   <input type="hidden" name="fechas" id="fechas" value="<?=$fechas?>">
     <input type="hidden" name="fechas2" id="fechas2" value="<?=$fechas2?>">
</form>
 <!--FIN SELECCION FECHA-->
 <table width="628"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
   <tr>
     <td width="533" class="textotabla01"><div align="right">TOTAL CANTIDAD CLIENTES </div></td>
     <td width="81"><div align="left" class="textotabla1">$
       <?=number_format($gran_total_clientes,0,".",".")?>
     </div></td>
   </tr>
</table>  </br>
 <table width="200" border="0" align="center">
   <tr>
     <td><div align="center" class="tituloproductos">
         <input name="button2" type="button" class="botones"  onClick="abrir()" value="Imprimir">
     </div></td>
   </tr>
 </table>
</body>
</html>