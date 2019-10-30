<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<?
if($guardar == 1 and $codigo==0){
	$campos="(num_mov,fec_emi,fec_venci,tipo_mov,cod_mov_pago,obs_mov)";
	$valores="('".$num_fac."','".$fecha_emision."','".$fecha_venci."','".$tipo_movimientos."','".$factura."','".$observaciones."')" ;
	$ins_id=insertar_maestro("m_movimientos",$campos,$valores); 
	
	$campos="(cod_mov,cuenta_dmov,concepto_dmov,debito_dmov,credito_dmov,cod_ter)";
	for ($ii=1 ;  $ii <= $val_inicial; $ii++) 
		{	
			if($_POST["debito_".$ii] != 0 or $_POST["credito_".$ii] != 0){	
				$valores="('".$ins_id."','".$_POST["cod_auxiliar_".$ii]."','".$_POST["cod_concepto_".$ii]."','".$_POST["debito_".$ii]."','".$_POST["credito_".$ii]."','".$_POST["cod_pro_".$ii]."')";	
				$error=insertar("d_movimientos",$campos,$valores);
			}
			
			//NIVEL 4
			//TOMA LOS 6 PRIMEROS CARACTERES DEL CODIGO CONTABLE DE LA CUENTA
			$cod_cuenta = $_POST["cod_auxiliar_".$ii];
			$dbc = new Database();
			$sqlc = "SELECT substring(cod_contable,1,6) as cadena FROM cuenta
			WHERE cod_cuenta = $cod_cuenta";
			$dbc->query($sqlc);
			$dbc->next_row();
			$cod = $dbc->cadena;
			
			//BUSCA EL CODIGO DE LA CUENTA MAYOR
			$dbcm = new Database();
			$sqlcm = "SELECT cod_cuenta FROM cuenta
			WHERE cod_contable = $cod";
			$dbcm->query($sqlcm);
			$dbcm->next_row();
			$cuenta = $dbcm->cod_cuenta;
			
			//INGRESA REGISTROS DEL NIVEL 4
			$valores="('".$ins_id."','".$cuenta."','".$_POST["cod_concepto_".$ii]."','".$_POST["debito_".$ii]."','".$_POST["credito_".$ii]."','".$_POST["cod_pro_".$ii]."')";					
			$error=insertar("d_movimientos",$campos,$valores);
			
			
			//NIVEL 3
			//TOMA LOS 4 PRIMEROS CARACTERES DEL CODIGO CONTABLE DE LA CUENTA
			$cod_cuenta = $_POST["cod_auxiliar_".$ii];
			$dbc = new Database();
			$sqlc = "SELECT substring(cod_contable,1,4) as cadena FROM cuenta
			WHERE cod_cuenta = $cod_cuenta";
			$dbc->query($sqlc);
			$dbc->next_row();
			$cod = $dbc->cadena;
			
			//BUSCA EL CODIGO DE LA CUENTA MAYOR
			$dbcm = new Database();
			$sqlcm = "SELECT cod_cuenta FROM cuenta
			WHERE cod_contable = $cod";
			$dbcm->query($sqlcm);
			$dbcm->next_row();
			$cuenta = $dbcm->cod_cuenta;
			
			//INGRESA REGISTROS DEL NIVEL 3
			$valores="('".$ins_id."','".$cuenta."','".$_POST["cod_concepto_".$ii]."','".$_POST["debito_".$ii]."','".$_POST["credito_".$ii]."','".$_POST["cod_pro_".$ii]."')";					
			$error=insertar("d_movimientos",$campos,$valores);
			
			//NIVEL 2
			//TOMA LOS 2 PRIMEROS CARACTERES DEL CODIGO CONTABLE DE LA CUENTA
			$cod_cuenta = $_POST["cod_auxiliar_".$ii];
			$dbc = new Database();
			$sqlc = "SELECT substring(cod_contable,1,2) as cadena FROM cuenta
			WHERE cod_cuenta = $cod_cuenta";
			$dbc->query($sqlc);
			$dbc->next_row();
			$cod = $dbc->cadena;
			
			//BUSCA EL CODIGO DE LA CUENTA MAYOR
			$dbcm = new Database();
			$sqlcm = "SELECT cod_cuenta FROM cuenta
			WHERE cod_contable = $cod";
			$dbcm->query($sqlcm);
			$dbcm->next_row();
			$cuenta = $dbcm->cod_cuenta;
			
			//INGRESA REGISTROS DEL NIVEL 2
			$valores="('".$ins_id."','".$cuenta."','".$_POST["cod_concepto_".$ii]."','".$_POST["debito_".$ii]."','".$_POST["credito_".$ii]."','".$_POST["cod_pro_".$ii]."')";					
			$error=insertar("d_movimientos",$campos,$valores);
			
			//NIVEL 1
			//TOMA EL 1 PRIMER CARACTER DEL CODIGO CONTABLE DE LA CUENTA
			$cod_cuenta = $_POST["cod_auxiliar_".$ii];
			$dbc = new Database();
			$sqlc = "SELECT substring(cod_contable,1,1) as cadena FROM cuenta
			WHERE cod_cuenta = $cod_cuenta";
			$dbc->query($sqlc);
			$dbc->next_row();
			$cod = $dbc->cadena;
			
			//BUSCA EL CODIGO DE LA CUENTA MAYOR
			$dbcm = new Database();
			$sqlcm = "SELECT cod_cuenta FROM cuenta
			WHERE cod_contable = $cod";
			$dbcm->query($sqlcm);
			$dbcm->next_row();
			$cuenta = $dbcm->cod_cuenta;
			
			//INGRESA REGISTROS DEL NIVEL 1
			$valores="('".$ins_id."','".$cuenta."','".$_POST["cod_concepto_".$ii]."','".$_POST["debito_".$ii]."','".$_POST["credito_".$ii]."','".$_POST["cod_pro_".$ii]."')";					
			$error=insertar("d_movimientos",$campos,$valores);
			
		}
	
	if ($error==1) {
		header("Location: con_nomina.php?confirmacion=1&editar=$editar&insertar=$insertar&eliminar=$eliminar"); 
	}
	else
		echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>" ;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
    <? inicio() ?>
    <script language="javascript">
function datos_completos(){ 
		return true;	
}
</script>
		<link rel="stylesheet" type="text/css" href="css/excel.css">
    <script type="text/javascript" src="js/funciones.js"></script>
	<script type="text/javascript" src="js/js.js"></script>
	</head>
	<body <?=$sis?>>
        <form  name="forma" id="forma" action="man_nomina.php"  method="post">
		  <h1>SUBIR NOMINAS POR EXCEL
		    <input type="hidden" name="editar"   id="editar"   value="<?=$editar?>">
            <input type="hidden" name="insertar" id="insertar" value="<?=$insertar?>">
            <input type="hidden" name="eliminar" id="eliminar" value="<?=$eliminar?>">
            <input type="hidden" name="codigo" id="codigo" value="<?=$codigo?>" />
		  </h1>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
					  <td>Tipo movimiento:</td>
					  <td colspan="2"><? combo_evento("tipo_movimientos","tipo_movimientos","cod_tmov","nom_tmov",$dbm->tipo_mov,"","nom_tmov");  ?>					    <span class="textorojo">*</span></td>
					  <td>No factura:</td>
					  <td colspan="2"><input name="num_fac" id="num_fac" type="text" class="textfield2" value="<?=$dbm->num_mov?>" /></td>
                      <td>Observaciones</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
				  </tr>
					<tr>
					  <td>Fecha de emision:</td>
					  <td colspan="2"><input name="fecha_emision" id="fecha_emision" type="text" class="textfield2" value="<?=$dbm->fec_emi?>" />
					    <span class="textorojo"><img src="imagenes/date.png" alt="Calendario1" name="calendario1" width="18" height="18" id="calendario1" style="cursor:pointer"/></span><span class="textorojo">*</span></td>
					  <td>Fecha de vencimiento</td>
					  <td colspan="2"><input name="fecha_venci" id="fecha_venci" type="text" class="textfield2" value="<?=$dbm->fec_venci?>" />
					    <span class="textorojo"><img src="imagenes/date.png" alt="Calendario2" name="calendario2" width="18" height="18" id="calendario2" style="cursor:pointer"/></span><span class="textorojo">*</span></td>
                      <td><div align="left" >
                        <textarea name="observaciones" cols="45" rows="3" class="textfield02"  onchange='buscar_rutas()' ><?=$dbm->obs_mov?>
                      </textarea>
                      </div></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
				  </tr>
					<tr>
					  <th>&nbsp;</th>
					  <th colspan="6">DEVENGADO</th>
					  <th colspan="4">DEDUCCIONES</th>
					  <th>&nbsp;</th>
					  <th>&nbsp;</th>
                      <th>&nbsp;</th>
				  </tr>
					<tr>
					  <th>EMPLEADO</th>
					  <th>SUELDO</th>
					  <th>DOMINGOS</th>
                      <th>HORAS EXTRAS</th>
					  <th>TRANSPORTE</th>
					  <th>COMISION</th>
					  <th>BONO</th>
					  <th>LIQUIDACION</th>
					  <th>TOTAL</th>
					  <th>SALUD</th>
					  <th>PENSION</th>
					  <th>PRESTAMO</th>
					  <th>TOTAL A PAGAR</th>
					  <th>CORRECTO</th>
					  </tr>
				</thead>
				<tbody>
				<?php
					//incluimos la clase
					require_once 'php/ext/PHPExcel-1.7.7/Classes/PHPExcel/IOFactory.php';
					
					//cargamos el archivo que deseamos leer
					$objPHPExcel = PHPExcel_IOFactory::load('xls/prueba.xls');
					//obtenemos los datos de la hoja activa (la primera)
					$objHoja=$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					
					//recorremos las filas obtenidas
					$jj=1;
					foreach ($objHoja as $iIndice=>$objCelda) {
						//imprimimos el contenido de la celda utilizando la letra de cada columna
						echo "<tr>";
						$dbe = new Database();
						$sqle = "SELECT cod_pro FROM proveedor
						WHERE nom_pro = '$objCelda[A]'";
						$dbe->query($sqle);
						$dbe->next_row();
						echo "<td><span class='textfield01'> $objCelda[A] </span></td>";
						
						//CODIGO DE LA CUENTA 51050605
						echo "<td><INPUT type='hidden'  name='cod_auxiliar_$jj' id='cod_auxiliar_$jj' value='265'><INPUT type='hidden' name='cod_concepto_$jj' id='cod_concepto_$jj' value='38'><INPUT type='hidden' name='debito_$jj' id='debito_$jj' value='$objCelda[B]'><INPUT type='hidden' name='credito_$jj' id='credito_$jj' value='0'><INPUT type='hidden'  name='cod_pro_$jj' id='cod_pro_$jj' value='$dbe->cod_pro'> $objCelda[B] </span> </td>";
						$jj++;
						
						//CODIGO DE LA CUENTA 51051505
						echo "<td><INPUT type='hidden'  name='cod_auxiliar_$jj' id='cod_auxiliar_$jj' value='267'><INPUT type='hidden' name='cod_concepto_$jj' id='cod_concepto_$jj' value='38'><INPUT type='hidden' name='debito_$jj' id='debito_$jj' value='$objCelda[C]'><INPUT type='hidden' name='credito_$jj' id='credito_$jj' value='0'><INPUT type='hidden'  name='cod_pro_$jj' id='cod_pro_$jj' value='$dbe->cod_pro'> $objCelda[C] </span> </td>";
						$jj++;
						
						//CODIGO DE LA CUENTA 51051505
						echo "<td><INPUT type='hidden'  name='cod_auxiliar_$jj' id='cod_auxiliar_$jj' value='267'><INPUT type='hidden' name='cod_concepto_$jj' id='cod_concepto_$jj' value='38'><INPUT type='hidden' name='debito_$jj' id='debito_$jj' value='$objCelda[D]'><INPUT type='hidden' name='credito_$jj' id='credito_$jj' value='0'><INPUT type='hidden'  name='cod_pro_$jj' id='cod_pro_$jj' value='$dbe->cod_pro'> $objCelda[D] </span> </td>";
						$jj++;
						
						//CODIGO DE LA CUENTA 51052705
						echo "<td><INPUT type='hidden'  name='cod_auxiliar_$jj' id='cod_auxiliar_$jj' value='273'><INPUT type='hidden' name='cod_concepto_$jj' id='cod_concepto_$jj' value='38'><INPUT type='hidden' name='debito_$jj' id='debito_$jj' value='$objCelda[E]'><INPUT type='hidden' name='credito_$jj' id='credito_$jj' value='0'><INPUT type='hidden'  name='cod_pro_$jj' id='cod_pro_$jj' value='$dbe->cod_pro'> $objCelda[E] </span> </td>";
						$jj++;
						
						//CODIGO DE LA CUENTA 51051805
						echo "<td><INPUT type='hidden'  name='cod_auxiliar_$jj' id='cod_auxiliar_$jj' value='269'><INPUT type='hidden' name='cod_concepto_$jj' id='cod_concepto_$jj' value='38'><INPUT type='hidden' name='debito_$jj' id='debito_$jj' value='$objCelda[F]'><INPUT type='hidden' name='credito_$jj' id='credito_$jj' value='0'><INPUT type='hidden'  name='cod_pro_$jj' id='cod_pro_$jj' value='$dbe->cod_pro'> $objCelda[F] </span> </td>";
						$jj++;
						
						//CODIGO DE LA CUENTA 51054805
						echo "<td><INPUT type='hidden'  name='cod_auxiliar_$jj' id='cod_auxiliar_$jj' value='283'><INPUT type='hidden' name='cod_concepto_$jj' id='cod_concepto_$jj' value='38'><INPUT type='hidden' name='debito_$jj' id='debito_$jj' value='$objCelda[G]'><INPUT type='hidden' name='credito_$jj' id='credito_$jj' value='0'><INPUT type='hidden'  name='cod_pro_$jj' id='cod_pro_$jj' value='$dbe->cod_pro'> $objCelda[G]</td>";
						$jj++;
						
						//CODIGO DE LA CUENTA 25050110
						echo "<td><INPUT type='hidden'  name='cod_auxiliar_$jj' id='cod_auxiliar_$jj' value='464'><INPUT type='hidden' name='cod_concepto_$jj' id='cod_concepto_$jj' value='38'><INPUT type='hidden' name='debito_$jj' id='debito_$jj' value='$objCelda[H]'><INPUT type='hidden' name='credito_$jj' id='credito_$jj' value='0'><INPUT type='hidden'  name='cod_pro_$jj' id='cod_pro_$jj' value='$dbe->cod_pro'> $objCelda[H]</td>";
						$jj++;
						
						echo "<td><span class='textfield01'> $objCelda[I] </span> </td>";
						
						//CODIGO DE LA CUENTA 23700505
						echo "<td><INPUT type='hidden'  name='cod_auxiliar_$jj' id='cod_auxiliar_$jj' value='164'><INPUT type='hidden' name='cod_concepto_$jj' id='cod_concepto_$jj' value='38'><INPUT type='hidden' name='debito_$jj' id='debito_$jj' value='0'><INPUT type='hidden' name='credito_$jj' id='credito_$jj' value='$objCelda[J]'><INPUT type='hidden'  name='cod_pro_$jj' id='cod_pro_$jj' value='$dbe->cod_pro'> $objCelda[J] </span> </td>";
						$jj++;
						
						//CODIGO DE LA CUENTA 23803005
						echo "<td><INPUT type='hidden'  name='cod_auxiliar_$jj' id='cod_auxiliar_$jj' value='175'><INPUT type='hidden' name='cod_concepto_$jj' id='cod_concepto_$jj' value='38'><INPUT type='hidden' name='debito_$jj' id='debito_$jj' value='0'><INPUT type='hidden' name='credito_$jj' id='credito_$jj' value='$objCelda[K]'><INPUT type='hidden'  name='cod_pro_$jj' id='cod_pro_$jj' value='$dbe->cod_pro'> $objCelda[K] </span> </td>";
						$jj++;
						
						//CODIGO DE LA CUENTA 13301505
						echo "<td><INPUT type='hidden'  name='cod_auxiliar_$jj' id='cod_auxiliar_$jj' value='37'><INPUT type='hidden' name='cod_concepto_$jj' id='cod_concepto_$jj' value='38'><INPUT type='hidden' name='debito_$jj' id='debito_$jj' value='0'><INPUT type='hidden' name='credito_$jj' id='credito_$jj' value='$objCelda[L]'><INPUT type='hidden'  name='cod_pro_$jj' id='cod_pro_$jj' value='$dbe->cod_pro'> $objCelda[L] </span> </td>";
						$jj++;
						
						//CODIGO DE LA CUENTA 25050105
						echo "<td><INPUT type='hidden'  name='cod_auxiliar_$jj' id='cod_auxiliar_$jj' value='194'><INPUT type='hidden' name='cod_concepto_$jj' id='cod_concepto_$jj' value='38'><INPUT type='hidden' name='debito_$jj' id='debito_$jj' value='0'><INPUT type='hidden' name='credito_$jj' id='credito_$jj' value='$objCelda[M]'><INPUT type='hidden'  name='cod_pro_$jj' id='cod_pro_$jj' value='$dbe->cod_pro'> $objCelda[M] </span> </td>";
						$jj++;
						if($dbe->cod_pro != ""){
							echo "<td align='center'><img src='imagenes/ok.png' width='16' height='16'  style=\"cursor:pointer\" /></td>";	
						}
						else{
							echo "<td align='center'><img src='imagenes/alerta.png' width='16' height='16' alt='No existe el tercero o esta diferente el nombre' style=\"cursor:pointer\" /></td>";
						}
						echo "</tr>";
						$ii++;
					}
				?>
                	<tr>
                	  <th>&nbsp;</th>
                	  <th>&nbsp;</th>
                	  <th>&nbsp;</th>
                	  <th>&nbsp;</th>
                	  <th>&nbsp;</th>
                	  <th>&nbsp;</th>
                	  <th>&nbsp;</th>
                	  <th>&nbsp;</th>
                	  <th>&nbsp;</th>
                	  <th>&nbsp;</th>
                	  <th>&nbsp;</th>
                	  <th>&nbsp;</th>
                	  <th>&nbsp;</th>
              	  </tr>
                	<tr>
					  <th>&nbsp;</th>
					  <th>&nbsp;</th>
					  <th>&nbsp;</th>
					  <th>&nbsp;</th>
					  <th>&nbsp;</th>
					  <th><input type='button'  class='botones' value='PROCESAR' onclick='cambio_guardar()' /></th>
					  <th><input type="hidden" name="guardar" id="guardar" value='0' />					    <input type="hidden" name="val_inicial" id="val_inicial" value="<? echo $jj-1;?>" /></th>
					  <th>&nbsp;</th>
					  <th>&nbsp;</th>
					  <th>&nbsp;</th>
					  <th>&nbsp;</th>
					  <th>&nbsp;</th>
					  <th>&nbsp;</th>
				  </tr>
				</tbody>
       
				<tfoot>
					<td colspan="4"></td>
				</tfoot>
			</table>
    </form>
	</body>
    <script type="text/javascript">	

Calendar.setup(
				{
					inputField  : "fecha_emision",      
					ifFormat    : "%Y-%m-%d",    
					button      : "calendario1" ,  
					align       :"T3",
					singleClick :true
				}
			);		
			

Calendar.setup(
				{
					inputField  : "fecha_venci",      
					ifFormat    : "%Y-%m-%d",    
					button      : "calendario2" ,  
					align       :"T3",
					singleClick :true
				}
			);			
</script>
</html>