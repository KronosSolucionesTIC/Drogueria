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
<link href="css/stylesforms.css" rel="stylesheet" type="text/css">
<link href="informes/styles2.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #FFFFFF}
.Estilo11 {font-weight: bold}
-->
</style>
</head>
<body  <?=$sis?>>
 <!--SELECCION PROVEEDOR Y FECHA-->
  <table width="787" align="center">
    <tr>
      <td width="779" height="21"><div align="center" class="subfongris">INFORME DE ABONOS </span></div></td>
    </tr>
</table>
  <?  
 if ($fechas2==0) {

	  $fecha = "AND   abono.fec_abo>='$fechas'";

}

else{


	$fecha = "AND  abono.fec_abo>='$fechas' AND   abono.fec_abo<='$fechas2'";

}

  $aaa = split("\,",$arreglo_clientes);
for($g=0; $g<=$val_inicial; $g++)
{  
 $ttt = $aaa[$g]; 

 if($ttt!="") {  
 
 
 $sqls="SELECT CONCAT(nom_bod,apel_bod) as nombre,cod_bod
	FROM
		bodega1
	WHERE cod_bod='$ttt'";
$dbs= new  Database();
$dbs->query($sqls);
$dbs->next_row();
$nombre=$dbs->nombre;
 

 ?>



  <table width="92%" border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
    <tr>
      <td colspan="8"></td>
    </tr>
    
    <tr>
      <td width="17%" class="subfongris"><div align="left"><strong><span class="Estilo1">PERIODO</span></strong></div></td>
      <td width="13%" class="subfongris"><div align="left"><strong><span class="Estilo1">FECHA INICIAL </span></strong></div></td>
      <td width="16%" class="subfongris"><div align="left">
        <?=$fechas?>
      </div>      </td>
      <td width="17%" class="subfongris"><div align="left"><span class="Estilo1">FECHA FINAL</span></div></td>
      <td colspan="2" class="subfongris"><div align="left">
        <?=$fechas2?>
      </div>        <div align="left"></div></td>
    </tr>
    <tr>
      <td class="subfongris"><div align="left">Cliente</div></td>
      <td colspan="5" class="subfongris"><div align="left">
        <?=$nombre?>
      </div>      </td>
    </tr>
    
    <?  

	
 $sqlr="SELECT 
 
  abono.cod_abo,
  abono.cod_bod_Abo,
  bodega1.cod_bod,
  abono.anotacion,
  abono.val_abo,
  abono.fec_abo 
  
  
FROM

abono
  INNER JOIN bodega1 ON (abono.cod_bod_Abo = bodega1.cod_bod) WHERE abono.cod_bod_Abo='$ttt' $fecha"; 

$dbr= new  Database();
$dbr->query($sqlr);
$code=$dbr->cod_bod;
$total_abonado_cli=0;


$i==1;
$a=1;

 while ($dbr->next_row()) {
 $cantidadPrimer += $dbr->val_abo;
 $total_abonado_cli=$total_abonado_cli+$dbr->val_abo;
 		
      
if($i==1) {$color='#F2F4F7'; $color_est='#FFFFFF';} else {$color='#FFFFFF'; $color_est='#F2F4F7';}

	  ?>
    <tr bgcolor="<?=$color?>">
      <td colspan="6" class="textotabla1"><div align="center"><?=$dbr->anotacion?></div></td>
    </tr>
    <tr bgcolor="<?=$color?>">
      <td colspan="5" class="textotabla1"><div align="right">Abonado: </div></td>
      <td width="16%" class="textotabla1">$        <?=$dbr->val_abo?></td>
    </tr>
    
    <? if($i==1) { $i=2; }
		else {$i=1;	 }  
	$a++; } ?>
</table>

  <table width="919"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
    <tr>
      <td width="766" class="textotabla01"><div align="right">Total abonado </div></td>
      <td width="139"><span class="textotabla1">$
          <?=number_format($total_abonado_cli,0,".",".")?>
      </span></td>
    </tr>
</table>
  </br>
  <p>
    <script>
function abrir(){
	window.print();
}
 </script>
   <? $gran_total_abonado_cli=$gran_total_abonado_cli+$total_abonado_cli;}  } ?>
	
	

<? ?>
<table width="919"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
    <tr>
      <td width="643" class="textotabla01"><div align="right"> Total abonado clientes </div></td>
      <td width="117"><span class="textotabla1">$
          <?=number_format($gran_total_abonado_cli,0,".",".")?>
      </span></td>
    </tr>
</table>
    
    <input type="hidden" name="arreglo_clientes" id="arreglo_clientes" value="<?=$arreglo_clientes?>">
      <input type="hidden" name="fechas" id="fechas" value="<?=$fechas?>">
    <input type="hidden" name="fechas2" id="fechas2" value="<?=$fechas2?>">
    </form>
      <!--FIN SELECCION PROVEEDOR Y FECHA-->  
    
  </p>
  <table width="200" border="0" align="center">
    <tr>
      <td><div align="center"><span class="tituloproductos">
          <input name="button" type="button" class="botones"  onClick="abrir()" value="Imprimir">
      </span></div></td>
    </tr>
  </table>
</body>
</html>