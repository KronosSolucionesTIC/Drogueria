<?
include "lib/sesion.php";
include("lib/database.php");
include("js/funciones.php");	

$usuario = $global[2];
$fecha = date("Y-m-d");
$hora = new DateTime();
$hora->setTimezone(new DateTimeZone('America/Bogota'));
$reloj = $hora->format("H:i:s");
$cadena = "Usuario: ".$global[3]." Fecha: ".$fecha." Hora: ".$reloj."";

	$campos="(cod_usu,fec_aud,hora_aud)";
	$valores="('".$usuario."','".$fecha."','".$reloj."')" ;
	
	$error=insertar("horarios",$campos,$valores);
	if($error==1)
	{
		enviar_alerta("Horario", $cadena);
	}
	else 
	{
		echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>" ; 
	}
?>

<script language="javascript">
function imprimir(){
	window.print();
	window.close();
}
</script>


<title>FACTURA DE VENTA</title>
<style type="text/css">
<!--
.Estilo14 {
	font-size: 14px;
	font-family: "Arial Black";
}
-->
</style>
<body onLoad="imprimir()">
<TABLE border="0" cellpadding="2" cellspacing="0"  width="306" <?=$anulacion?> >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">


			<TR>
			  <TD align="center"><table width="300" border="0" >
			    <tr>
			      <td colspan="3"><div align="center">**************************************</div></td>
		        </tr>
			    <tr>
			      <td width="28%"><span class="Estilo14">NOMBRE:</span></td>
			      <td width="72%" colspan="2"><div align="right"><?=$global[3]?></div></td>
		        </tr>
			    <tr>
			      <td><span class="Estilo14">FECHA:</span></td>
			      <td colspan="2"><div align="right"><?=date("Y-m-d")?></div></td>
		        </tr>
			    <tr>
			      <td><span class="Estilo14">HORA:</span></td>
			      <td colspan="2"><div align="right"><?=$reloj?></div></td>
		        </tr>
			    </table></TD>
		  </TR>
			<TR>
			  <TD align="center">&nbsp;</TD>
		  </TR>
			
			<TR>
            		  <TD><table width="2" align="center" border="0"  id="select_tablas">                
              </table></TD>
		  </TR>
          			<TR>
			  <TD align="center"><div align="center">**************************************</div></TD>
		  </TR>
			<TR><TD colspan="2" align="left">
			
			<div align="center" class="Estilo14">
			  <p>FIRMA</p>
			  <p>&nbsp;</p>
			  <p>&nbsp;</p>
			</div>
            <div align="center">**************************************</div></td>
              </tr>
            </table>
			</TD>
		  </TR>
		  
		  
				<TR>
				  <TD colspan="2" align="center" class="Estilo4">&nbsp;</TD>
		  </TR>
</TABLE>
<body>