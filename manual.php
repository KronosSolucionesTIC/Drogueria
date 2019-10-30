<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<? 
if($eliminacion==1) {//confirmacion de insercion  
	$error=eliminar("abono",$eli_codigo,"cod_abo");
	if ($error >=1)
	echo "<script language='javascript'> alert('Se Elimino el registro Correctamente..') </script>" ;
}

if($confirmacion==1) //confirmacion de insercion 
	echo "<script language='javascript'> alert('Se Inserto el registro Correctamente..') </script>" ;

if($confirmacion==2) //confirmacion de insercion 
	echo "<script language='javascript'> alert('Se Edito el registro Correctamente..') </script>" ;

$where_cli="";
$sql="select distinct punto_venta.cod_bod as valor , nom_bod as nombre from punto_venta  inner join  bodega  on punto_venta.cod_bod=bodega.cod_bod where cod_ven=$global[2]";
		$dbdatos= new  Database();
		$dbdatos->query($sql);	
		$where_cli="";
		while($dbdatos->next_row())
		{
			$where_cli .= "bodega1.cod_bod_cli= ".$dbdatos->valor  ;
			$where_cli .= " or ";
		}		
		$where_cli .= " bodega1.cod_bod < 0 "; 


//echo $det."=";

if($det==0)
	$where.=" where cod_abo>0   and ($where_cli)  ";

if(!empty($busquedas)) { #codigo para buscar 
	$busquedas=reemplazar_1($busquedas);
	$where.=" and $busquedas   ";
}#codigo para buscar 


//$sql="SELECT   abono.cod_abo,  abono.cod_bod_Abo,  abono.val_abo,  abono.cod_usu_abo,  abono.fec_abo, abono.cod_rso_abo,  bodega1.nom_bod,   usuario.nom_usu,  rsocial.nom_rso FROM   abono   left JOIN bodega1 ON (abono.cod_bod_Abo = bodega1.cod_bod)  left JOIN usuario ON (abono.cod_usu_abo = usuario.cod_usu)   left JOIN rsocial ON (abono.cod_rso_abo = rsocial.cod_rso) $where ORDER BY cod_abo  DESC ";
 

 $sql="SELECT * , bodega1.nom_bod as cliente 
 FROM   abono 
 
 left JOIN bodega1 ON (abono.cod_bod_Abo = bodega1.cod_bod)
 left JOIN usuario ON (abono.cod_usu_abo = usuario.cod_usu) 
 left JOIN bodega ON (bodega.cod_bod = bodega1.cod_bod_cli) 
 
  $where ORDER BY cod_abo  DESC ";
 



$cantidad_paginas=paginar($sql);
$cant_pag=ceil($cantidad_paginas/$cant_reg_pag);

if(!empty($act_pag)) 
	$inicio=($act_pag -1)*$cant_reg_pag  ;
else { 
	$inicio =0;
	$act_pag=1;
	}
$paginar=" limit  $inicio, $cant_reg_pag";
$sql.=$paginar;
$busquedas=reemplazar($busquedas);
?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=$nombre_aplicacion?></title>
<script type="text/javascript">
var tWorkPath="menus/data.files/";
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
</script>
<script type="text/javascript" src="js/funciones.js"></script>
<script type="text/javascript" src="informes/inf.js"></script>
 <link href="css/styles.css" rel="stylesheet" type="text/css">
 <style type="text/css">
<!--
.Estilo1 {color: #666666}
-->
 </style>
</head>

<body  <?=$sis?> onLoad="cambio_1(<?=$cant_pag?>,<?=$act_pag?>);">
<p>&nbsp;</p>
<table width="871" height="276" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="46679a" style="border-collapse:collapse">
  <tr>
    <td width="167" height="274" bgcolor="#46679a">&nbsp;</td>
    <td width="698"><table border="0" align="center" width="698">
      <tr>
        <th height="15" colspan="2" scope="col"></th>
      </tr>
      <tr>
        <th height="15" colspan="2" scope="col"></th>
      </tr>
      <tr>
        <th height="15" colspan="2" scope="col"></th>
      </tr>
      <tr>
        <th height="15" colspan="2" scope="col">&nbsp;</th>
      </tr>
      <tr>
        <th height="31" colspan="2" scope="col"><div align="right" class="arialazul28">Sistema de Informaci&oacute;n MEGALIGA</div></th>
      </tr>
      <tr>
        <th height="4" colspan="2" scope="col"><hr width="100%"  size="2" class="lineablue" align="right" /></th>
      </tr>
      <tr>
        <td height="21"  colspan="2"><div align="right"><span class="bradleyazul16">Manual de usuario</span></div></td>
      </tr>
      <tr>
        <td height="104"  colspan="2"><table width="579" border="0" align="center"  class="linea_azul"">
          <tr>
            <td colspan="2" bgcolor="#46679a"><div align="center" class="arial12boldwhite">Descargue el manual de usuario del Sistema de Informaci&oacute;n MEGALIGA </div></td>
            </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td width="283"><span class="bradleyazul16">Manual de usuario</span></td>
            <td width="284"><a href='manual/manual_megaliga' target='_blank' class="Estilo1">Descargar</a>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      
      
      <tr>
        <td height="22" colspan="2"><div align="center" class="pestana"><STRONG>Copyright <FONT class="pestana" size="1" ><STRONG style="text-align:right">
          <?=date("Y");?>
        </STRONG></FONT> ,   Dise&ntilde;o y Desarrollo Kaome Estudio</STRONG> </div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
