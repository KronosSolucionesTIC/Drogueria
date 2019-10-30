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
 <title><?=$nombre_aplicacion?> -- ENTRADAS DE INVENTARIO --</title>
 <style type="text/css">
<!--
.Estilo4 {font-size: 18px}
-->
 </style>
 <TABLE width="100%" border="0" cellspacing="0" cellpadding="0"   >
 <?=$credito?>
	
	<TR>
		<TD align="center">
		<TABLE width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

			<TR>
			  <TD width="100%" class='txtablas'>
			  <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  	<tr>
			  		<td width="47%" height="26"><div align="center"><span class="Estilo4">INFORME HORARIOS DE 
	  		      <?=$fec_ini?> HASTA <?=$fec_fin?></span></div></td>
			    </tr>
				</table>					</TD>
		  </TR>
			
			<TR>
			  <TD align="center">             </TD>
		  </TR>
</TABLE>
		<table width="98%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
        <tr >
          <td width="34%"  class="botones1"><div align="center">USUARIO</div></td>
          <td width="33%"  class="botones1"><div align="center">FECHA</div></td>
          <td width="33%"  class="botones1"><div align="center">HORA</div></td>
        </tr>
        <?
				if($cod_usu == "0"){
					$where = "";
				}
				else{
					$where = "AND usuario.cod_usu = $cod_usu";
				}
				$db = new Database ();
				$sql = "SELECT * FROM horarios
				INNER JOIN usuario ON usuario.cod_usu = horarios.cod_usu
				WHERE (fec_aud >='$fec_ini') AND (fec_aud <='$fec_fin') $where
				ORDER BY  fec_aud,hora_aud ASC";
				$db->query($sql);
				$estilo="formsleo";
				while($db->next_row()){						
				?>
        <tr id="fila_0"  >
          <td  class="textotabla01"><?=$db->log_usu?></td>
          <td  class="textotabla01"><?=$db->fec_aud?></td>
          <td  class="textotabla01"><?=$db->hora_aud?></td>
        </tr>
        <? } ?>
        <tr >
          <td colspan="6" >&nbsp;</td>
        </tr>
        <?
				$db = new Database ();
				$sql = "SELECT nivel,cod_contable,desc_cuenta,debito_dmov,credito_dmov,SUM(credito_dmov) AS suma_credito FROM cuenta
				INNER JOIN d_movimientos ON d_movimientos.cuenta_dmov = cuenta.cod_cuenta
				INNER JOIN m_movimientos ON m_movimientos.cod_mov = d_movimientos.cod_mov
				WHERE ((cod_contable LIKE '2%')or(cod_contable LIKE '3%') ) AND ((fec_emi >='$fec_ini') AND (fec_emi <='$fec_fin'))
				GROUP BY cod_cuenta
				ORDER BY  `cuenta`.`cod_contable` ASC";
				$db->query($sql);
				$estilo="formsleo";
				$total_saldo = 0;
				$total_debito = 0;
				$total_credito = 0;
				$total_nuevo_saldo = 0;
				while($db->next_row()){						
				?>
        <?
				  } 
				 ?>
        <tr >
          <td colspan="6" ><table  width="100%"  >
            <tr></tr>
          </table></td>
        </tr>
        </table>
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
	