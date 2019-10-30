<?
include "../lib/sesion.php";
include("../lib/database.php");
	
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
 <title><?=$nombre_aplicacion?> -- INFORME DE LLAMADAS --</title>
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
			  <TD width="100%" colspan="2" class='txtablas'>
			  <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  	<tr>
			  		<td width="47%">INFORME DE LLAMADAS</td>
				    <td width="22%" height="22" class="ctablaform"> <span class="textoproductos1"> &nbsp;&nbsp;Fecha inicial:<span class="textotabla01">
                    <?=$fec_ini?>
				    </span></span></td>
			  	   
			  	    <td width="31%" class="ctablaform"><span class="textoproductos1">Fecha final: &nbsp;<span class="textotabla01"><?=$fec_fin?></span></span></td>
			  	</tr>
			  	</table>					</TD>
		  </TR>
			
			
			<TR>
			  <TD colspan="2" align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                
				  <tr >
            <td width="10%"  class="botones1">Nro llamada</td>
            <td width="14%"  class="botones1">Fecha</td>
            <td width="14%"  class="botones1">Duracion</td>
			<td width="14%"  class="botones1">Responsable</td>
			<td width="13%"  class="botones1">Resultado</td>
            <td width="35%"  class="botones1">Observaciones</td>
			 </tr>
				<?
				if($ven == 0){
 					$vendedor = '';
				}
				else{
					$vendedor = ' AND cod_respon ='.$ven ;
				}
				$cad = '('.$cad.')';
				$db_ver = new Database();
				$sql = "SELECT * FROM m_llamadas
				INNER JOIN d_llamadas ON d_llamadas.cod_llamada = m_llamadas.cod_llamada
				INNER JOIN bodega1 ON bodega1.cod_bod = m_llamadas.cli_llamada
				INNER JOIN ruta ON ruta.cod_ruta = bodega1.cod_ruta
				INNER JOIN responsable ON responsable.cod_respon = d_llamadas.cod_respllamada
				WHERE fecha_llamada >= '$fec_ini' AND fecha_llamada <= '$fec_fin' $vendedor AND $cad
				GROUP BY m_llamadas.cod_llamada";
				$db_ver->query($sql);
				$total = 0;
					while($db_ver->next_row()){ 	
						
				?>
                <tr>
                  <td  class="textotabla01"><?=$db_ver->cod_llamada?></td>
                  <td  class="textotabla01"><?=$db_ver->fecha_llamada?></td>
				  <td  class="textotabla01"><?=$db_ver->duracion_llamada?></td>
                  <td colspan="1" class="textotabla01"><?=$db_ver->desc_respon?></td>
                <? 
				$dbdl = new Database();
				$sqldl = "SELECT * FROM d_llamadas
				INNER JOIN r_llamadas ON r_llamadas.cod_rllamada = d_llamadas.cod_resultado
				WHERE cod_llamada = $db_ver->cod_llamada";
				$dbdl->query($sqldl);
				$cadena = '';
				while($dbdl->next_row()){
					if($cadena == ''){
						$cadena = $cadena . $dbdl->desc_rllamada;
					}
					else{
						$cadena = $cadena. ',' . $dbdl->desc_rllamada;
					}
				}
				?>
                  <td  class="textotabla01"><?=$cadena?></td>
                  <td  class="textotabla01"><div align="right"><?=$db_ver->obs_llamada?></div></td>
                </tr>
				  
				<?
					$total++;
				  } 
				 
				 ?>
				 
				  <tr >
			  
                  <td colspan="6" >
				  <table  width="100%"  > 
				  <tr>
				    <td class="subfongris"><div align="right">Total llamadas</div></td>
				    <td><div align="right"><span class="tituloproductos">
				      <?=number_format($total,0,".",".")?>
			        </span></div></td>
				    </tr>
				  <tr>
				  <td class="subfongris"><div align="right">Total duracion llamadas</div></td>
				    <td><div align="right"><span class="tituloproductos">
                    <? 
				$dbd = new Database();
				$sqld = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(duracion_llamada))) as total_llamadas FROM m_llamadas
				INNER JOIN d_llamadas ON d_llamadas.cod_llamada = m_llamadas.cod_llamada
				INNER JOIN bodega1 ON bodega1.cod_bod = m_llamadas.cli_llamada
				INNER JOIN ruta ON ruta.cod_ruta = bodega1.cod_ruta
				INNER JOIN responsable ON responsable.cod_respon = d_llamadas.cod_respllamada
				WHERE fecha_llamada >= '$fec_ini' AND fecha_llamada <= '$fec_fin' $vendedor AND $cad";
				$dbd->query($sqld);
				$dbd->next_row();
					?>
				      <?=$dbd->total_llamadas ?> 
			        </span></div></td>	
				  </tr>
				  </table>				  </td>
				  </tr>
              </table></TD>
		  </TR>
			<TR>
			  <TD colspan="2" align="center">             </TD>
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
	