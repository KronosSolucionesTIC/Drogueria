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
<style type="text/css">
<!--
.Estilo38 {font-size: 9px; }
.Estilo32 {font-size: 10px}
-->
</style>
</head>
<body  <?=$sis?>>
 <!--SELECCION PROVEEDOR Y FECHA-->
  <table width="100%" align="center" class="subfongris">
    <tr>
      <td width="330" height="21">&nbsp;</td>
      <td width="287">INFORME DE VENTAS POR EMPRESA </td>
      <td width="347">&nbsp;</td>
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
  
               
				
  ?>

 
 
  </span>
  <table width="100%"border="1" align="center" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
   <tr>
     <td width="18%" class="boton"><div align="center">CLIENTE</div></td>
     <td width="10%" class="boton"><div align="center">NIT</div></td>
      <td width="9%" class="boton"><div align="center">N. FACTURA </div></td>
     <td width="10%" class="boton"><div align="center">F. FACTURA </div></td>
      <td width="10%" class="boton"><div align="center">DIRECCION</div>
     </span></td>
     <td width="9%" class="boton"><div align="center">TELEFONO</div></td>
     <td width="13%" class="boton"><div align="center">CIUDAD</div></td>
     <td width="7%" class="boton"><div align="center">BASE</div></td>
     <td width="6%" class="boton"><div align="center">IVA</div></td>
     <td width="8%" class="boton"><div align="center">TOTAL FACTURA</div></td>
     
    </tr>
  
			    <tr bgcolor="<?=$color?>">
  <?  for($g=0; $g<=$val_inicial; $g++)
   {  
     $ttt = $aaa[$g]; 
     if($ttt!="")
	 {  
     $sqlg="SELECT nom_bod,cod_bod,ciu_bod
	        FROM bodega1
	        WHERE cod_bod='$ttt'";
		    $dbg= new  Database();
			$dbg->query($sqlg);
            while ($dbg->next_row())
			{
               $ciudad=$dbg->ciu_bod;
			    
			    
		  $sqlt="SELECT 
		  bodega1.cod_bod as cliente,
		  bodega1.nom_bod,
		  bodega1.ciu_bod,
		  bodega1.cod_bod_cli,
		  bodega1.iden_bod,
		  bodega1.dir_bod,
		  bodega1.tel_bod,
		  m_factura.cod_fac,
		  m_factura.fecha,
		  m_factura.cod_cli,
		  m_factura.cod_bod as bodega,
		  punto_venta.nom_pun,
		  punto_venta.cod_bod,
		  rsocial.nom_rso,
		  rsocial.nit_rso,
		  rsocial.cod_rso,
		  rsocial.reg_rso,
		  punto_venta.cod_rso
		  
		  FROM bodega1
		  INNER JOIN m_factura ON (bodega1.cod_bod = m_factura.cod_cli)
		  INNER JOIN punto_venta ON (bodega1.cod_bod_cli = punto_venta.cod_bod)
		  INNER JOIN rsocial ON (punto_venta.cod_rso = rsocial.cod_rso)
		  WHERE bodega1.ciu_bod='$ciudad' $fecha  group by m_factura.cod_cli"; 
		  
		  $dbt= new  Database();
		  $dbt->query($sqlt);
		  $total_c=0;
		  while ($dbt->next_row())
		  {
		  $nombre=$dbt->nom_bod;
		  $code=$dbt->cliente;  
			  
		  $sqlr="SELECT *,sum(m_factura.tot_fac) AS tot_facs
  
		  FROM m_factura
		  INNER JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)
		  INNER JOIN bodega ON (m_factura.cod_bod = bodega.cod_bod)
		  INNER JOIN rsocial ON (m_factura.cod_razon_fac = rsocial.cod_rso)
		  INNER JOIN d_factura ON (m_factura.cod_fac = d_factura.cod_mfac)  
		  WHERE m_factura.cod_cli='$code'  AND estado IS NULL AND rsocial.cod_rso=$combo_empresa  $fecha  GROUP BY num_fac,cod_cli ORDER BY fecha ASC"; 
		 
		
		  $dbr= new  Database();
		  $dbr->query($sqlr);
		  $i==1;
		  $a=1;
		
		  while ($dbr->next_row())
		  {
		  $regimen = $dbr->reg_rso;
		  $total+=$dbr->tot_fac;
		  
		  
		  if($i==1) {$color='#F2F4F7'; $color_est='#FFFFFF';} else {$color='#FFFFFF'; $color_est='#F2F4F7';}
			   
			    ?>  
			  <tr bgcolor="<?=$color?>">  
                <td class="textotabla1">
                        <div align="left">
                          <?=$nombre?>
                  </div></td>
                <td class="textotabla1">
                        <div align="left">
                          <?=$dbr->iden_bod?>
                  </div></td><td class="textotabla1"><div align="center">
          <?=$dbr->num_fac?>
      </div></td>
      <td class="textotabla1"><div align="center">
          <?=$dbr->fecha?>
      </div></td>
      <td align="center" class="textotabla1">
        <div align="left">
          <?=$dbr->dir_bod?>
        </div></td><td align="center" class="textotabla1"><div align="center">
          <?=$dbr->tel_bod?>
      </div></td>
      <td align="center" class="textotabla1">
        <div align="left">
          <?=$ciudad?>
        </div></td><? if($regimen=="Comun")
		   { 
               $base = $dbr->tot_fac / 1.16;
               $iva_reg=$dbr->tot_fac-  $base ;
			   $base_total+= $base;
			   $iva_total+= $iva_reg;
               $hhh=number_format($base,0,".",".");
               $ww=number_format($iva_reg,0,".",".");
  
            }
			   else
			   {
			   $hhh=0;
			   $ww=0;
			   }
  
  
  
  ?>
      <td align="center" class="textotabla1"><div align="center"><span class="Estilo32">
          <?=$hhh?>
      </span></div></td>
      
      <td align="center" class="textotabla1"><div align="center"><span class="Estilo32">
          <?=$ww?>
      </span></div></td>
      <td align="center" class="textotabla1"><div align="center">
        <?=$dbr->tot_fac?>
		
      </div></td>
        <? } }}?>
    </tr>
    <? if($i==1) { $i=2; }
		else {$i=1;	 } 
		
		 
	$a++;  } } ?>
  </table>
  </br>
<input type="hidden" name="clientes_abono" id="clientes_abono" value="<?=$clientes_abono?>">
   <input type="hidden" name="fechas" id="fechas" value="<?=$fechas?>">
     <input type="hidden" name="fechas2" id="fechas2" value="<?=$fechas2?>">
</form>
  <!--FIN SELECCION PROVEEDOR Y FECHA-->  
<!--SELECCION FECHA-->



  
  
 
   <input type="hidden" name="fechas" id="fechas" value="<?=$fechas?>">
     <input type="hidden" name="fechas2" id="fechas2" value="<?=$fechas2?>">
</form>
 <!--FIN SELECCION FECHA-->
 <table width="100%"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
   <tr>
     <td class="textotabla01" width="842"><div align="right">TOTAL BASE </div></td>
     <td><div align="left" class="textotabla1">$
       <?=number_format($base_total,0,".",".")?>
     </div></td>
   </tr>
   <tr>
     <td class="textotabla01" width="842"><div align="right">TOTAL IVA </div></td>
     <td><div align="left" class="textotabla1">$
       <?=number_format($iva_total,0,".",".")?>
     </div></td>
   </tr>
   
   <tr>
     <td width="842" class="textotabla01"><div align="right">TOTAL VENTAS </div></td>
     <td width="128"><div align="left" class="textotabla1">$
       <?=number_format($total,0,".",".")?>
     </div></td>
   </tr>
</table>  
 </br>
 <table width="200" border="0" align="center">
   <tr>
     <td><div align="center" class="tituloproductos">
         <input type="hidden" name="mapa" value="<?=$mapa?>" />
         <input name="button3" type="button" class="botones"  onclick="window.print()" value="Imprimir" />
         
     </div></td>
   </tr>
 </table>
</body>
</html>