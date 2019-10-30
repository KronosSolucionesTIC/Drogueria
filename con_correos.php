<?
include "lib/sesion.php";
include("lib/database.php");	
?>


<title>FACTURA DE VENTA</title>
<style type="text/css">
<!--
.Estilo14 {
	font-size: 14px;
	font-family: "Arial Black";
}
-->
</style>
<body>
<form name="form1" method="post" action="enviar.php">
<TABLE border="0" cellpadding="2" cellspacing="0"  width="306" <?=$anulacion?> >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">


			<TR>
			  <TD align="center"><table width="300" border="0" >
			    <tr>
			      <td colspan="3"><div align="center">**************************************</div></td>
		        </tr>
			    <tr>
			      <td width="28%"><span class="Estilo14">ASUNTO:</span></td>
			      <td width="72%" colspan="2"><div align="right"><INPUT type="TEXT" name="asunto" id="asunto"></div></td>
		        </tr>
			    <tr>
			      <td><span class="Estilo14">MENSAJE:</span></td>
			      <td colspan="2"><div align="right">
			        <textarea name="mensaje" cols="45" rows="3" class="textfield02" id="mensaje"></textarea>
			      </div></td>
		        </tr>
                    <?
	$db = new Database();
	$sql = "SELECT * FROM bodega1
	WHERE mail_bod != ''";
	$db->query($sql);
	while($db->next_row()){
	?>
			    <tr>
			      <td><span class="Estilo14">CORREO:</span></td>
			      <td colspan="2"><?=$db->mail_bod?></td>
		        </tr>
	 <? 
	 } 
	 ?>
			    <tr>
			      <td></td>
			      <td colspan="2">
			        <input type="submit" name="button" id="button" value="Enviar">
</td>
		        </tr>
			    </table></TD>
		  </TR>
            </table>
			</TD>
		  </TR>
		  
		  
				<TR>
				  <TD colspan="2" align="center" class="Estilo4">&nbsp;</TD>
		  </TR>
</TABLE>
</form>
</body>