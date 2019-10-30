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

function imprimir(){
	document.getElementById('imp').style.display="none";
	window.print();
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

$fecha_actual = $_GET["fechas2"];
$mes = "";
$dia = "";
$ano = "";
//echo 'FECHA'.$fecha_actual;

$sql="SELECT  DAY('$fecha_actual') as diaActual,   MONTH('$fecha_actual') as mesActual,  YEAR('$fecha_actual') as anoActual,  meta, dias_laborales from historico_metas
             where   mes =MONTH('$fecha_actual') and ano = YEAR('$fecha_actual') ";
$db2= new  Database();
$db2->query($sql);
if ($db2->next_row()) {
	$dia = $db2->diaActual;
	$mes = $db2->mesActual;
	$ano = $db2->anoActual;
	$meta_mes = $db2->meta;
	$dias_laborales = $db2->dias_laborales;
}

//echo $dia."  ".$mes."  ".$dia;

//echo 'usuario'.$usuario;
if (($mes!='')  && ($ano != '') && ($dia != '')) {
	$fecha = "AND MONTH(m_factura.fecha) ='$mes' AND YEAR(m_factura.fecha) ='$ano' ";

?>
<table width="644"  border="1" align="center" cellpadding="2" cellspacing="1"  >
  <tr>
    <td colspan="7" class="subfongris"><b>INFORME NACIONAL DE VENTAS</b></td>
  </tr>
  <tr>
    <td width="14%" class="subfongris"><div align="left">PERIODO</span></strong></div></td>
    <td width="14%" class="subfongris"><div align="left"><strong><span class="Estilo1">DIA: </span></strong></div></td>
    <td width="14%" class="subfongris"><div align="left">
        <?=$dia?>
      </div></td>
    <td width="14%" class="subfongris"><div align="left"><strong><span class="Estilo1">MES: </span></strong></div></td>
    <td width="14%" class="subfongris"><div align="left">
        <?=$mes?>
      </div></td>
    <td width="14%" class="subfongris"><div align="left"><strong><span class="Estilo1">ANO: </span></strong></div></td>
    <td width="14%"  class="subfongris"><div align="left">
        <?=$ano?>
      </div></td>
  </tr>
    <tr>
    <td width="100%" colspan="7" class="boton"><?php
	$cont = 0;
	?>
      <table width="99%"border="1" align="center" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
        <tr>
          <td width="14%" class="boton"><div align="center">USUARIO</div></td>
          <td width="14%" class="boton"><div align="center">META DEL MES</div></td>
          <td width="14%" class="boton"><div align="center">META DIARIA</div></td>
          <td width="14%" class="boton"><div align="center">VENTA REAL DIA</div></td>
          <td  width="14%"class="boton"><div align="center">% PROMEDIO</div></td>
          <td width="14%" class="boton"><div align="center">VENTA ACUM. al <?=$fecha_actual ?></div></td>
          <td  width="14%"class="boton"><div align="center">% PROMEDIO MES</div></td>
        </tr>
<?
$sql2="SELECT distinct  R.cod_rut, R.cod_ruta_ciu, U.cod_usu,  U.nom_usu 
FROM ruta R 
INNER JOIN usuario U ON  (R.usuario =  U.cod_usu) 
order by U.nom_usu";
$dbruta= new  Database();
$dbruta->query($sql2);
while ($dbruta->next_row())  {
   $ruta_ciudad=$dbruta->cod_ruta_ciu;
   $usuario = $dbruta->cod_usu;
   //echo $usuario. "  ";
   //echo $meta_mes. "  ";
   //echo $dias_laborales. " ";
   //$meta_dia = $meta_mes/$dias_laborales;
	//				   echo $meta_dia."  "; 
	
?>
      <tbody id="<?= $cont?>" >
      <? 
	  /* 
     $sqlt1="SELECT  sum( m_factura.tot_fac) as total_ventas_mes, sum(m_factura.tot_dev_mfac) as total_devuelto_mes
	  FROM m_factura 
	  LEFT JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)
	  LEFT JOIN bodega ON (m_factura.cod_bod = bodega.cod_bod)
      WHERE  m_factura.estado IS NULL and bodega1.ciu_bod in ($ruta_ciudad) $fecha "; 
      $dbg1= new Database();
	  $dbg1->query($sqlt1);
	  echo "$sqlt1";
	  echo "totales=".$dbg1->total_ventas_mes." ".$dbg1->total_devuelto_mes."<br>";
	  */
      $sqlt="SELECT  m_factura.tot_fac, m_factura.tot_dev_mfac
	  FROM m_factura 
	  LEFT JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)
	  LEFT JOIN bodega ON (m_factura.cod_bod = bodega.cod_bod)
      WHERE  m_factura.estado IS NULL and bodega1.ciu_bod in ($ruta_ciudad) $fecha "; 
      $dbg= new Database();
	  $dbg->query($sqlt);

      $total_ventas_mes = 0;
	  $total_devuelto_mes = 0;
	  while ($dbg->next_row()) {
		 if ($dbg->tot_fac != NULL) { 
		  $total_ventas_mes += $dbg->tot_fac;
		 }
		 if ($dbg->tot_dev_mfac != NULL) { 
	      $total_devuelto_mes += $dbg->tot_dev_mfac;
		 }
	  }
	  $total_ventas_mes = $total_ventas_mes - $total_devuelto_mes;
     //echo "totales individuales=".$total_ventas_mes." ".$total_devuelto_mes."<br>";
	  
	  $sqlt="SELECT  m_factura.tot_fac, m_factura.tot_dev_mfac
	  FROM m_factura 
	  LEFT JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)
	  LEFT JOIN bodega ON (m_factura.cod_bod = bodega.cod_bod)
      WHERE  m_factura.estado IS NULL and bodega1.ciu_bod in ($ruta_ciudad) $fecha and m_factura.fecha <= '$fecha_actual'"; 
      $dbg= new Database();
	  $dbg->query($sqlt);

      $acum_ventas_mes = 0;
	  $acum_devuelto_mes = 0;
	  while ($dbg->next_row()) {
		 if ($dbg->tot_fac != NULL) { 
		  $acum_ventas_mes += $dbg->tot_fac;
		 }
		 if ($dbg->tot_dev_mfac != NULL) { 
	      $acum_devuelto_mes += $dbg->tot_dev_mfac;
		 }		 
	  }
	  $acum_ventas_mes = $acum_ventas_mes - $acum_devuelto_mes;
 	  /*
	  $sqlt="SELECT  sum( m_factura.tot_fac) as total_ventas_dia, sum(m_factura.tot_dev_mfac) as total_devuelto_dia
	  FROM m_factura 
	  LEFT JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)
	  LEFT JOIN bodega ON (m_factura.cod_bod = bodega.cod_bod)
      WHERE   DAY(m_factura.fecha) = $dia AND m_factura.estado IS NULL and bodega1.ciu_bod in ($ruta_ciudad)  $fecha ";
	   
      $dbr1= new  Database();
	  $dbr1->query($sqlt);
	  echo "$sqlt";
	  echo "totales diarios =".$dbr1->total_ventas_dia." ".$dbr1->total_devuelto_dia."<br>";
	  */
	  $sqlt="SELECT  m_factura.tot_fac, m_factura.tot_dev_mfac
	  FROM m_factura 
	  LEFT JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)
	  LEFT JOIN bodega ON (m_factura.cod_bod = bodega.cod_bod)
      WHERE   m_factura.estado IS NULL and bodega1.ciu_bod in ($ruta_ciudad) AND ( (DAY(m_factura.fecha) = $dia $fecha )  or m_factura.fecha = '$fecha_actual') ";
	  $cont=$cont+1;   
      $dbr= new  Database();
	  $dbr->query($sqlt);
	  //echo "$sqlt";
	   
	  $total_ventas_dia = 0;
	  $total_devuelto_dia = 0;
	  while ($dbr->next_row()) {
		 if ($dbr->tot_fac != NULL) { 
		  	$total_ventas_dia += $dbr->tot_fac;
		 }
		 if ($dbr->tot_dev_mfac != NULL) { 
	      	$total_devuelto_dia += $dbr->tot_dev_mfac;
		 }	
		 
	  } 
	  $total_ventas_dia = $total_ventas_dia - $total_devuelto_dia;
	  //echo "$sqlt";
	  //echo "totales diarios individuales =".$total_ventas_dia." ".$total_devuelto_dia."<br>";
	  	
	  $i=1;
	  if($i==1) {$color='#F2F4F7'; $color_est='#FFFFFF';} else {$color='#FFFFFF'; $color_est='#F2F4F7';}
		  ?>
          <tr bgcolor="<?=$color?>">
            <td width="14%" class="textotabla1"><div align="center">
                <?=$dbruta->nom_usu?>
              </div></td>
            <td width="14%" class="textotabla1"><div align="center">
                <?=$meta_mes?>
              </div></td>
            <td  width="14%" class="textotabla1"><div align="center">
                <? $meta_dia = $meta_mes/$dias_laborales;
					   echo number_format($meta_dia,0,'.','.' ); 
					?>
              </div></td>
            <td  width="14%" align="center" class="textotabla1"><div align="center">
                <?=number_format($total_ventas_dia,0,'.','.' )?>
              </div></td>
            <td width="14%" align="center" class="textotabla1"><div align="center"><span class="textotabla01">
                <?=number_format(($total_ventas_dia /$meta_dia * 100),0,'.','.' ); ?>
                </span></div></td>
            <td width="14%" align="center" class="textotabla1"><div align="center">
                <?=number_format($acum_ventas_mes,0,'.','.') ?>
              </div></td>
            <td width="14%" align="center" class="textotabla1"><div align="center"><span class="textotabla01">
                <?=number_format(($acum_ventas_mes /$meta_mes * 100),0,'.','.'); ?>
                </span></div></td>
          </tr>
          <tr bgcolor="<?=$color?>">
            <td colspan="7" class="textotabla1">&nbsp;</td>
          </tr>
          <? 
			  if($i==1) { $i=2; }
			  else {$i=1;	 } 

      ?>
      </tbody>
      <?   
} //del while rutas
?>
</table></td>
  </tr>
</table>
</br>
<input type="hidden" name="mes" id="mes" value="<?=$mes?>">
<input type="hidden" name="ano" iod="ano" value="<?=$ano?>">
</br>
<table width="200" border="0" align="center">
  <tr>
    <td><div align="center" class="tituloproductos" id="imp">
        <input name="button3" type="button" class="botones"  onclick="imprimir()" value="Imprimir" />
      </div></td>
  </tr>
</table>
<?
} //del if

?>
</body>
</html>