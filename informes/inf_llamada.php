<?
include "../lib/sesion.php";
include("../lib/database.php");
			
$db = new Database();
$db_ver = new Database();
$sql = "SELECT  *  FROM m_llamadas
INNER JOIN bodega1 ON bodega1.cod_bod = m_llamadas.cli_llamada
INNER JOIN ruta ON ruta.cod_ruta = bodega1.cod_ruta
INNER JOIN vendedor ON vendedor.cod_ven = ruta.cod_vendedor
WHERE m_llamadas.cod_llamada = $codigo";
$db_ver->query($sql);	
$db_ver->next_row();

?>
<script language="javascript">
function imprimir(){
	document.getElementById('imp').style.display="none";
	document.getElementById('cer').style.display="none";
	window.print();
}


</script>
 <link href="styles.css" rel="stylesheet" type="text/css" />
 <link href="../styles.css" rel="stylesheet" type="text/css" />
 <link href="../css/styles.css" rel="stylesheet" type="text/css" />
 <link href="../css/stylesforms.css" rel="stylesheet" type="text/css" />
 <title><?=$nombre_aplicacion?> -- REGISTRO DE LLAMADAS --</title>
 <style type="text/css">
<!--
.Estilo4 {font-size: 18px}
-->
 </style>
 <TABLE width="100%" border="0" cellspacing="0" cellpadding="0"   >
	
	<TR>
		<TD align="center">
		<TABLE width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

			<TR>
			  <TD colspan="2" class='txtablas'>
			  <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  	<tr>
			  		<td width="47%" rowspan="2"><span class="Estilo4">REGISTRO DE LLAMADA</span> </td>
				    <td width="22%" height="22" class="ctablaform"> <span class="textoproductos1"> &nbsp;&nbsp;Fecha:<span class="textotabla01">
                    <?=$db_ver->fecha_llamada?>
				    </span></span></td>
			  	   
			  	    <td width="31%" class="ctablaform"><span class="textoproductos1">Numero: &nbsp;<?=$db_ver->cod_llamada?></span></td>
			  	</tr>
			  	<tr>
			  	  <td  class="ctablaform"><span class="textoproductos1">&nbsp;Responsable:<span class="textotabla01">
                  <?=$db_ver->nom_ven?>
                  </span></span></td>
			  	  <td  class="ctablaform"><span class="textoproductos1">&nbsp;&nbsp;Duracion:<span class="textotabla01">
                  <?=$db_ver->duracion_llamada?>
                  </span></span><span class="textoproductos1">&nbsp;&nbsp;</span></td>
		  	    </tr>
			  	</table>					</TD>
		  </TR>
			
			
			<TR>
			  <TD colspan="2" align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                
				  <tr >
            <td width="17%"  class="botones1">CLIENTE</td>
            <td width="17%"  class="botones1">NIT</td>
			<td width="17%"  class="botones1">DIRECCION</td>
			<td width="17%"  class="botones1">DEPARTAMENTO</td>
			<td width="17%"  class="botones1">CIUDAD</td>
            <td width="15%"  class="botones1">RUTA</td>
			 </tr>
				<?
				 	$sql = "SELECT *,CONCAT(iden_bod,' ',digito_bod) AS nit,CONCAT(nom_bod,' ',apel_bod,' ',rsocial_bod) AS nombre FROM bodega1 
				 	INNER JOIN m_llamadas ON m_llamadas.cli_llamada = bodega1.cod_bod
					INNER JOIN departamento ON departamento.cod_departamento = bodega1.dpto_cli
					INNER JOIN ciudad ON ciudad.cod_ciudad = ciu_bod
					INNER JOIN ruta ON ruta.cod_ruta = bodega1.cod_ruta
					INNER JOIN vendedor ON vendedor.cod_ven = ruta.cod_vendedor
				 	WHERE cod_llamada =$codigo";
					$db->query($sql);
					$estilo="formsleo";
					while($db->next_row()){ 	
						
				?>
                <tr id="fila_0"  >
                  <td  class="textotabla01"><?=$db->nombre?></td>
				  <td  class="textotabla01"><?=$db->nit?></td>
                  <td  class="textotabla01"><?=$db->dir_bod?></td>
                  <td  class="textotabla01"><?=$db->desc_departamento?></td>
                  <td  class="textotabla01"><?=$db->desc_ciudad?></td>
                  <td  class="textotabla01"><?=$db->desc_ruta?></td>
                </tr>				 
				  <tr >
				    <td class="botones1">TELEFONO FIJO</td>
				    <td class="botones1">CELULAR</td>
				    <td class="botones1">CORREO</td>
				    <td class="botones1">CUPO</td>
				    <td class="botones1">DISPONIBLE</td>
				    <td class="botones1">RUT</td>
			    </tr>
				  <tr >
			  
                  <td class="textotabla01"><?=$db->tel_bod?></td>
                  <td class="textotabla01"><?=$db->cel_bod?></td>
                  <td class="textotabla01"><?=$db->mail_bod?></td>
                  <td class="textotabla01"><?=$db->cupo_au_covinoc?></td>
                  <? 
				  	$cliente = $db->cli_llamada;
					$dbc = new Database();
					$sqlc ="SELECT SUM(((SELECT SUM(total_pro) FROM d_factura WHERE cod_mfac=m_factura.cod_fac)- valor_abono )) -(SUM(tot_dev_mfac)) AS cartera FROM m_factura
					INNER JOIN cartera_factura ON cartera_factura.cod_fac= m_factura.cod_fac
					WHERE  tipo_pago='Credito'   AND estado_car<>'CANCELADA' AND cod_cli = $cliente";
					$dbc->query($sqlc);
					while($dbc->next_row()){
						$cartera_ocupada=$dbc->cartera;
					}
		
					$cupo_covinoc=$db->cupo_au_covinoc - $cartera_ocupada;
              ?>
                  <td class="textotabla01"><?=$cupo_covinoc?></td>
                  <? 
					if($db->rut == 1){
						$rut = 'SI';
					}
					else{
						$rut = 'NO';
					}
				  ?>
    			  <td class="textotabla01"><?=$rut?></td>
                  </tr>
                 				  
				 <?
				  } 
				 ?>
              </table></TD>
		  </TR>
			<TR>
			  <TD colspan="2" align="center">             </TD>
		  </TR>
			
            <? 
			$dbd = new Database();
			$sqld = "SELECT  *  FROM m_llamadas
			INNER JOIN d_llamadas ON d_llamadas.cod_llamada = m_llamadas.cod_llamada
			INNER JOIN r_llamadas ON r_llamadas.cod_rllamada = d_llamadas.cod_resultado
			WHERE m_llamadas.cod_llamada = $codigo";
			$dbd->query($sqld);	
			while($dbd->next_row()){  
			?> 
            <TR>
			  <TD colspan="2" class="textotabla01"><?=$dbd->desc_rllamada?></TD>
		  	</TR>
            <? } ?>
			<TR>
			  <TD width="13%" height="40" align="center" valign="top"><div align="center" class="textoproductos1">
			    <div align="left" class="subtitulosproductos">Observaciones:			    </div>
			  </div></TD>
		      <TD width="87%" valign="top" ><span class="textotabla01">
		        <?=$db_ver->obs_llamada?>
		      </span></TD>
			</TR>
</TABLE>

 
<TABLE width="70%" border="0" cellspacing="0" cellpadding="0">
	
	<TR><TD colspan="3" align="center"><input name="button" type="button"  class="botones1" id="imp" onClick="imprimir()" value="Imprimr">
        <input name="button" type="button"  class="botones1"  id="cer" onClick="window.close()" value="Cerrar"></TD>
	</TR>

	<TR>
		<TD width="1%" background="images/bordefondo.jpg" style="background-repeat:repeat-y" rowspan="2"></TD>
		<TD bgcolor="#F4F4F4" class="pag_actual">&nbsp;</TD>
		<TD width="1%" background="images/bordefondo.jpg" style="background-repeat:repeat-y" rowspan="2"></TD>
	</TR>
	<TR>
	  <TD align="center">
	