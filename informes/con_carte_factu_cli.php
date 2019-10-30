<?
include("../lib/database.php");
$db_ver = new Database();
 $sql = "SELECT * FROM bodega1  WHERE cod_bod=$codigo";
$db_ver->query($sql);	
if($db_ver->next_row()){ 
	$nombre=$db_ver->nom_bod;
	$identificacion=$db_ver->iden_bod;
}	
?>

<link href="styles.css" rel="stylesheet" type="text/css" />
<link href="../css/styles.css" rel="stylesheet" type="text/css">
<title>Cartera por Cliente</title><table width="98%" border="1" cellpadding="2" cellspacing="1"  class="textoproductos1">
  <tr>
    <td colspan="11" class="ctablasup" align="center"> CARTERA  POR CLIENTE </td>
  </tr>
  <tr> </tr>
  <tr>
    <td colspan="5" class="ctablasup"> Cliente :
      <?=$nombre?></td>
    <td colspan="6" class="ctablasup">IDENTIFICACION:
      <?=$identificacion?></td>
  </tr>
  <TD width="22%" height="16" class="subfongris">NOMBRE </TD>
		<TD width="10%" class="subfongris">FECHA</TD>
        <TD width="6%" class="subfongris">N. FAC.</TD>
        <TD width="8%" class="subfongris">T. FAC.</TD>
		<TD width="8%" class="subfongris">S. FAC.</TD>
		<TD width="11%" class="subfongris" >F. VEN. </TD>
        <TD width="10%" class="subfongris"> CARTERA CORRIENTE </TD>
		<TD width="10%" class="subfongris">30</TD>
		<TD width="10%" class="subfongris">60</TD>
		<TD width="10%" class="subfongris">90</TD>
    <!--<TD width="11%" class="botones1" >OPCIONES</TD>-->
  </tr>
  <?
		$db = new Database();
		$db1 = new Database();
		
		//echo $sql="SELECT cod_car_fac as codigo_cartera ,'CLIENTE' AS tipo_credito, cod_car_fac,fec_car_fac, cartera_factura.cod_fac, 	num_fac, cod_cli, (SELECT SUM(total_pro) FROM d_factura WHERE cod_mfac=cartera_factura.cod_fac) - sum(tot_dev_mfac) AS total, datediff( curdate(),fecha) AS dias, 	(SELECT 		nom_bod FROM bodega1 WHERE cod_bod=cod_cli) AS nombre , DATE_ADD(cartera_factura.fec_car_fac,INTERVAL (SELECT dias_credito 	FROM bodega1 WHERE cod_bod=cod_cli) DAY) AS vecimiento, datediff( curdate(),fecha) AS pasado , valor_Abono , (SELECT SUM(total_pro) FROM d_factura WHERE cod_mfac=cartera_factura.cod_fac) - IFNULL(valor_Abono,0) - sum(tot_dev_mfac) AS saldo, bodega1.nom_bod , num_abono 	FROM cartera_factura  	INNER JOIN m_factura ON m_factura.cod_fac=cartera_factura.cod_fac  	INNER JOIN bodega1 ON bodega1.cod_bod=cod_cli 	WHERE cod_cli> -1  AND estado_car <> 'CANCELADA' and cod_cli=$codigo  	ORDER BY num_fac ";
		
	//echo	 $sql="SELECT cod_car_fac as codigo_cartera ,'CLIENTE' AS tipo_credito, cod_car_fac,fec_car_fac, cartera_factura.cod_fac, 	num_fac, cod_cli, (SELECT SUM(total_pro) FROM d_factura WHERE cod_mfac=cartera_factura.cod_fac)  AS total, datediff( curdate(),fecha) AS dias, 	(SELECT nom_bod FROM bodega1 WHERE cod_bod=cod_cli) AS nombre , DATE_ADD(cartera_factura.fec_car_fac,INTERVAL (SELECT dias_credito 	FROM bodega1 WHERE cod_bod=cod_cli) DAY) AS vecimiento, datediff( curdate(),fecha) AS pasado , valor_Abono , (SELECT SUM(total_pro) FROM d_factura WHERE cod_mfac=cartera_factura.cod_fac) - IFNULL(valor_Abono,0)  AS saldo, bodega1.nom_bod , num_abono 	FROM cartera_factura  	INNER JOIN m_factura ON m_factura.cod_fac=cartera_factura.cod_fac  	INNER JOIN bodega1 ON bodega1.cod_bod=cod_cli 	WHERE cod_cli> -1  AND estado_car <> 'CANCELADA' and cod_cli=$codigo  	ORDER BY num_fac ";
		
$sql=" 	SELECT 'CLIENTE' AS tipo_credito, cod_car_fac,fec_car_fac, cartera_factura.cod_fac,  	num_fac, cod_cli,  	(SELECT SUM(total_pro) FROM d_factura WHERE cod_mfac=cartera_factura.cod_fac) -  ifnull(tot_dev_mfac,0) AS total_neto,  	(SELECT SUM(total_pro) FROM d_factura WHERE cod_mfac=cartera_factura.cod_fac)  - ifnull(valor_abono,0) -  ifnull(tot_dev_mfac,0) AS total,  	datediff( curdate(),fecha) AS dias, (SELECT nom_bod FROM bodega1 WHERE cod_bod=cod_cli) AS nombre ,  	DATE_ADD(cartera_factura.fec_car_fac,INTERVAL (SELECT dias_credito FROM bodega1 WHERE cod_bod=cod_cli) DAY) AS vecimiento,  	datediff( curdate(), DATE_ADD(cartera_factura.fec_car_fac,INTERVAL (SELECT dias_credito FROM bodega1 WHERE cod_bod=cod_cli) DAY)) AS pasado  	FROM cartera_factura  	INNER JOIN m_factura ON m_factura.cod_fac=cartera_factura.cod_fac  	WHERE cod_cli> -1 AND  ( estado_car <>'CANCELADA' and  estado_car <>'ANULADO' )   and  cod_cli=$codigo   	ORDER BY  num_fac, fec_car_fac  asc ";
		

		$db->query($sql);
		$totalfacturacion=0;
		$totalcorriente=0;
		$total30=0;
		$total60=0;
		$total90=0;
		$validador=0;
		while($db->next_row()){
			$validador=0;
			$totalfacturacion=$totalfacturacion+$db->saldo;
			echo "<FORM action='liq_cartera.php' method='POST'> <INPUT type='hidden' name='mapa' value='$mapa'> <TR>";
			//echo "<TD class='ctablablanc' >$db->tipo_credito</TD>";
			echo "<TD class='ctablablanc' >$db->nombre</TD>";
			echo "<TD class='ctablablanc' >$db->fec_car_fac</TD>";
			echo "<TD class='ctablablanc' >$db->num_fac</TD>";
			echo "<TD class='ctablablanc' align='right'><div align='right'>".number_format($db->total_neto,0,".",".")."</div></TD>";
			echo "<TD class='ctablablanc' align='right'><div align='right'>".number_format($db->total,0,".",".")."</div></TD>";
			
			if(!empty($db->vecimiento))
				echo "<TD class='ctablablanc' align='right'>$db->vecimiento</TD>";
			else 
				echo "<TD class='ctablablanc' align='right'>&nbsp;</TD>";
			
			if ($db->pasado < 1 )
				echo "<TD class='ctablablanc'  align='right'><div align='right'>".number_format($db->total,0,".",".")."</div></TD>";
			else 
				echo "<TD class='ctablablanc'  align='right'>&nbsp;</TD>";
			
			
			if($db->pasado > 0 && $db->pasado < 31) {
				echo "<TD class='ctablablanc' align='right'><div align='right'>".number_format($db->total,0,".",".")."</div></TD>";
				$total30=$total30+$db->total;
			}
			else {	
				echo "<TD class='ctablablanc' align='right'>&nbsp;</TD>";
				$validador=1;
			}
			if($db->pasado > 30 && $db->pasado < 61){
				echo "<TD class='ctablablanc' align='right'><div align='right'>".number_format($db->total,0,".",".")."</div></TD>";
				$total60=$total60+$db->total;
			}
			else {	
				echo "<TD class='ctablablanc' >&nbsp;</TD>";
				$validador=1;
			}
				
			if($db->pasado > 60 ){
				echo "<TD class='ctablablanc' align='right'><div align='right'>".number_format($db->total,0,".",".")."</div></TD>";
				$total90=$total90+$db->total;
			}
			else 	{
				echo "<TD class='ctablablanc' >&nbsp;</TD>";
				$validador=1;
			}
			
			if($validador==1)
				$totalcorriente = $totalcorriente + $db->total;
			echo "</TR>	</FORM>";
		}
	?>
  <tr>
    <td height="16" colspan="11" class="subtitulosproductos">TOTAL FACTURA:
        <?=number_format($totalfacturacion,0,".",".")?>    </td>
    <!--<TD width="11%" class="botones1" >OPCIONES</TD>-->
  </tr>
  <tr>
    <td height="16" colspan="11" class="subtitulosproductos">TOTAL CORRIENTE:
      <?=number_format($totalcorriente-$total30-$total60-$total90,0,".",".")?></td>
    <!--<TD width="11%" class="botones1" >OPCIONES</TD>-->
  </tr>
  <tr>
    <td height="16" colspan="11" class="subtitulosproductos">TOTAL 30 DIAS:
      <?=number_format($total30,0,".",".")?></td>
    <!--<TD width="11%" class="botones1" >OPCIONES</TD>-->
  </tr>
  <tr>
    <td height="16" colspan="11" class="subtitulosproductos">TOTAL 60 DIAS:
      <?=number_format($total60,0,".",".")?></td>
    <!--<TD width="11%" class="botones1" >OPCIONES</TD>-->
  </tr>
  <tr>
    <td height="16" colspan="11" class="subtitulosproductos">TOTAL 90 DIAS:
      <?=number_format($total90,0,".",".")?></td>
    <!--<TD width="11%" class="botones1" >OPCIONES</TD>-->
  </tr>
  <tr>
    <td height="16" colspan="11" class="tituloproductos">
	  <div align="center">
	    <input name="button" type="button" class="botones"  onclick="window.print()" value="Imprimir" />
      </div></td>
    <!--<TD width="11%" class="botones1" >OPCIONES</TD>-->
  </tr>
</table>	