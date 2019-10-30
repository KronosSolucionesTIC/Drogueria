<? include("js/funciones.php")?>
<? include("lib/database.php")?>
<?	
//echo $leotabla;
	$db = new Database();
	$campos = "cod_bod_Abo, val_abo,cod_usu_abo,fec_abo,saldo,observacion,anotacion,cod_rso_abo,tipo_pago";
	$values = "'$bodega','$valor','".$global[2]."','$fecha','$saldo', '$observacion_abo', '$leotabla', '$rsocial_fac','$tipo_pago'"; 

	$sql = "INSERT INTO abono ($campos) VALUES($values)";
//echo "<br>";
	$db->query($sql);	
	$id=$db->insert_id();		
	
	enviar_alerta("Alerta FACTURA ABONADA ", "Se ha Abonado una  Factura, Consultar Factura <a href='http://www.globater.com/sistema/informes/ver_abono.php?codigo=$id'>Consultar Factura</a>");
	//actualiza la cartera

		for($i=1;$i<=$cantidad;$i++){
			if($_POST["accion_".$i]=='CANCELADA'){
				$num_abonos=$_POST["num_abonos_".$i]."|".$id;
				$sql="UPDATE cartera_factura SET num_abono= '".$num_abonos."',estado_car='CANCELADA', valor_abono='".$total_abono."'  WHERE  cod_car_fac=".$_POST["codigo_cartera_".$i]."  ";
				//echo "<br>";
			 	$db->query($sql);	
		 	}
			elseif($_POST["accion_".$i]=='ABONADO'){
				$num_abonos=$_POST["num_abonos_".$i]."|".$id;
				$sql="UPDATE cartera_factura SET num_abono= '".$num_abonos."',estado_car='ABONADO', valor_abono='".$total_abono."'  WHERE  cod_car_fac=".$_POST["codigo_cartera_".$i]."  ";
				//echo "<br>";
			 	$db->query($sql);	
		 	}
		}
	
	
?>

<FORM method="POST" action="con_abono.php" name="myForm">
<input type="hidden" name="editar"   id="editar"   value="<?=$editar?>">
<input type="hidden" name="insertar" id="insertar" value="<?=$insertar?>">
<input type="hidden" name="eliminar" id="eliminar" value="<?=$eliminar?>">
<input type="hidden" name="codigo" id="codigo" value="<?=$codigo?>" />
</FORM>
<SCRIPT>
	alert('SE HAN GUARDADO SATISFACTORIAMENTE SUS DATOS');
	window.open("informes/ver_abono.php?codigo="+<?=$id?>,"ventana","menubar=0,resizable=1,width=700,height=400,toolbar=0,scrollbars=yes")
	document.myForm.submit();
</SCRIPT>