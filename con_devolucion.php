<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<? 

if (!isset($global[2])) {
  echo "<script language='javascript'>alert('La sesion finalizó, por favor reinicie el sistema')</script>";
}
if($eliminacion==1) {//confirmacion de insercion  
	$error=eliminar("m_devolucion",$eli_codigo,"cod_dev");

	$sql ="DELETE FROM d_devolucion WHERE cod_mdev=$eli_codigo";//=		
	$dbdatos_1= new  Database();
	$dbdatos_1->query($sql);
					
	// actualiza movimientos
	resumen_movimiento($eli_codigo,"Devolucion",$fecha,$vendedor,$total_nuevo_saldo,"resta","eliminando");
	// actualiza movimientos
	
	if ($error >=1)
	echo "<script language='javascript'> alert('Se Elimino el registro Correctamente..') </script>" ;
	
}


if($confirmacion==1) //confirmacion de insercion 
	echo "<script language='javascript'> alert('Se Inserto el registro Correctamente..') </script>" ;

if($confirmacion==2) //confirmacion de insercion 
	echo "<script language='javascript'> alert('Se Edito el registro Correctamente..') </script>" ;


$where_cli="";

$sql="select 

distinct punto_venta.cod_bod as valor , nom_bod as nombre from punto_venta  
inner join  bodega  on punto_venta.cod_bod=bodega.cod_bod where cod_ven=$global[2]";
		$dbdatos= new  Database();
		$dbdatos->query($sql);
		
		$where_cli="";
		while($dbdatos->next_row())
		{
			$where_cli .= "bodega.cod_bod= ".$dbdatos->valor  ;
			$where_cli .= " or ";
		}
		
		$where_cli .= " bodega.cod_bod < 0 "; 



if($det==0)
	$where.=" where cod_dev>0   and  ( $where_cli )  ";



if(!empty($busquedas)) { #codigo para buscar 
	$busquedas=reemplazar_1($busquedas);
	$where.=" and $busquedas   ";
}#codigo para buscar 
  $sql=" SELECT 
   cod_dev, m_devolucion.fec_dev,
  bodega.nom_bod as bodega,
  m_factura.num_fac,
  m_devolucion.val_del,
  usuario.nom_usu,
  m_factura.cod_cli,
  CONCAT(bodega1.nom_bod,' ',apel_bod) as nombre 
FROM
  m_devolucion
  INNER JOIN usuario ON (m_devolucion.cod_ven_dev = usuario.cod_usu)
  INNER JOIN bodega ON (m_devolucion.cod_bod_dev = bodega.cod_bod)
  INNER JOIN m_factura ON (m_devolucion.num_fac_dev = m_factura.cod_fac)
  INNER JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)   $where 
ORDER BY
 cod_dev DESC  ";

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
<html >
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
</head>
<body  <?=$sis?> onLoad="cambio_1(<?=$cant_pag?>,<?=$act_pag?>);">

<table align="center">
<tr>
<td valign="top" >
<form id="forma_total" name="forma_total" method="post" action="man_devolucion.php">
                  <table width="624" border="0" cellspacing="0" cellpadding="0" align="center" >
                    <tr>
                      <td bgcolor="#D1D8DE"><table width="624" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="16" height="33"> </td>
                          <td width="19"> 
						  <? if ($insertar==1) {?>
					  	  <img src="imagenes/agregar.png" width="16" height="16"  alt="Nuevo Registro" style="cursor:pointer" onClick="location.href='man_d_devolucion.php?codigo=0&editar=<?=$editar?>&insertar=<?=$insertar?>&eliminar=<?=$eliminar?>'"/>
					  	  <? } ?></td>
                          <td width="160"><span class="ctablaform">
                            <? if ($insertar==1) {?>
								Agregar
							<? } ?>
                          </span></td>
                          <td width="20" class="ctablaform">&nbsp;</td>
                          <td width="53" class="ctablaform">Buscar: </td>
                          <td width="103"><label>
                            <input name="text" type="text" class="textfield" size="12" id="texto" />
                          </label></td>
                          <td width="19" class="ctablaform"> en</td>
                          <td width="160" valign="middle"><select name="campos" class="textfieldlista" id="campos" >
                            <option value="0">Seleccion</option>
							<option value="fec_dev">Fecha</option>
							<option value="nom_usu">Usuario</option>
							<option value="bodega.nom_bod">Bodega</option>
							<option value="num_fac_dev">Factura</option>
                            <option value="bodega1.nom_bod">Nombre cliente</option>
							<option value="bodega1.apel_bod">Apellido cliente</option>
                           	<option value="-1">Lista Completa</option>
                          </select></td>
                          <td width="74" valign="middle"><img src="imagenes/lupa.png" width="16" height="16" style="cursor:pointer"  onClick="buscar()"/></td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><table width="624" border="0"  cellpadding="0">
                        <tr>
                          <td width="91"  class="ctablasup" >No DEVOLUCION</td>
						  <td width="81"  class="ctablasup" >FECHA </td>
						  <td width="69"  class="ctablasup">USUARIO </td>
						  <td width="71"  class="ctablasup">BODEGA</td>
						  <td width="61"  class="ctablasup">FACTURA</td>
						  <td width="61"  class="ctablasup">CLIENTE</td>
						  <td width="60"  class="ctablasup">TOTAL </td>
                          <td  class="ctablasup"  width="112">OPCIONES</td>
                        </tr>
						<? 
						
						echo "<tr style='display:none'><td ><form name='forma_0' action='man_devolucion.php'>";
						echo "  </form> </td></tr>  ";						  
						$estilo="ctablablanc";
						$estilo="ctablagris";
						
						//echo $sql;
						$db->query($sql);  #consulta paginada
						while($db->next_row()) {
							if ($aux==0) { $estilo="ctablablanc"; $aux=1; $cambio_celda=$celda_blanca; }else { $estilo="ctablagris";  $cambio_celda=$celda_gris; $aux=0;}
							echo "<tr class='$estilo' $cambio_celda> <form name='forma_$db->cod_dev' action='man_devolucion.php'>  ";
							echo "<td >$db->cod_dev</td>";
                          	echo "<td >$db->fec_dev</td>";
							echo "<td >$db->nom_usu </td>";
							echo "<td >$db->bodega </td>";
							echo "<td align='right'>".$db->num_fac."</td>";
							echo "<td >$db->nombre</td>";
							echo "<td align='right'>".number_format($db->val_del,0,",",".")."</td>";
                            echo "<td aling='center' >"; 
							echo 	"<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
                            echo 	" <tr>  <td align='center'> <input type='hidden' name='codigo' value='$db->cod_dev'>";
							if ($editar==1)
							 	echo "<img src='imagenes/icoeditar.png' alt='Editar Registro' width='16' height='16' style='cursor:pointer'  onclick='document.forma_$db->cod_dev.submit()'/></td>";
							else 
								echo "<img src='imagenes/e_icoeditar.png' width='16' height='16'  /></td>";
                            echo 	"<td align='center'>";
							if ($eliminar==1)
								echo"<img src='imagenes/icoeliminar.png' width='16' alt='Eliminar Registro' height='16' style='cursor:pointer' onclick='confirmar($db->cod_dev)' /></td> ";
							else
								echo"<img src='imagenes/e_icoeliminar.png' width='16' height='16'  /></td> ";
							//impresion	
							echo "<td align='center'><img src='imagenes/mirar.png' width='16' height='16'  style=\"cursor:pointer\" onClick=\"imprimir_inf('inf_devolucion.php','$db->cod_dev','mediano')\" /></td>";	
							
                            echo "  </tr> </table>  </td>  ";
							echo "<input type='hidden' name='editar' value=".$editar."> <input type='hidden' name='insertar' value=".$insertar."> <input type='hidden' name='eliminar' value=".$eliminar.">";
							echo "  </form></tr>  ";
						} ?>
                      </table ></td>
                    </tr>
                    
                    <tr>
                      <td><img src="imagenes/lineasup3.gif" width="624" height="4" /></td>
                    </tr>
                    <tr>
                      <td height="30" align="center" valign="bottom"><table>
                        <tr>
                          <td> <span class="ctablaform" > <?  if ($cant_pag>0) echo "Pagina ".$act_pag." de ".$cant_pag ; else echo "No hay Resultados"  ?> </span>
                            <img src="imagenes/primero.png" alt="Inicio" width="16" height="16" id="primero" style="cursor:pointer; display:inline"  onClick="cambio(1)"/> <img src="imagenes/regresar.png" alt="Anterior" width="16" height="16" id="regresar" style="cursor:pointer; display:inline" onClick="cambio(2)"/> <img src="imagenes/siguiente.png" alt="Siguiente" width="16" height="16"  id="siguiente" style="cursor:pointer; display:inline" onClick="cambio(3)"/> <img src="imagenes/ultimo.png" alt="Ultimo" width="16" height="16" id="ultimo" style="cursor:pointer; display:inline" onClick="cambio(4)"/> </td>
                        </tr>
                      </table></td>
                    </tr>
                  </table>
      </form>
</td>
</tr>
</table>						
<form name="forma" method="post" action="con_devolucion.php">
  <input type="hidden" name="editar" id="editar" value="<?=$editar?>">
  <input type="hidden" name="insertar" id="insertar" value="<?=$insertar?>">
  <input type="hidden" name="eliminar" id="eliminar" value="<?=$eliminar?>">
  <input type="hidden" name="cant_pag"  id="cant_pag" value="<?=$cant_pag?>">
  <input type="hidden" name="act_pag"  id="act_pag" value="<? if(!empty($act_pag)) echo $act_pag; else echo $pagina;?>">
  <input type="hidden" name="busquedas" id="busquedas" value="<?=$busquedas?>">
   <input type="hidden" name="eliminacion" id="eliminacion" >
    <input type="hidden" name="eli_codigo" id="eli_codigo" >
</form>
</body>
</html>
