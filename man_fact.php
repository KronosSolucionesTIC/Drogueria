<?php include("lib/database.php");?>
<?php include("js/funciones.php");?>
<?php 
// RUTINA PARA  INSERTAR REGISTROS NUEVOS	
	$db6 = new Database();
	$sql = "select num_fac_rso + 1  as  num_factura from rsocial WHERE cod_rso=1";
	$db6->query($sql);
	
	if($db6->next_row())
	$num_factura = $db6->num_factura;
	
		$db7 = new Database();
	$sql = "select *  from m_factura WHERE num_fac = $num_factura and cod_razon_fac=1";
	$db7->query($sql);
	
	if ($db7->num_rows() >0) {
		$num_factura +=1;
	}
		
	//ACTUALIZA LA ULTIMA FACTURA
	$db2 = new Database();
	$sql = "UPDATE rsocial SET num_fac_rso = $num_factura  WHERE  cod_rso=1";
	$db2->query($sql);
		
	$tipo_pago="Contado";
	
	$compos="(cod_usu, cod_cli,fecha,num_fac,cod_razon_fac,tipo_pago,tot_fac,cod_bod)";
	$valores="('".$global[2]."','".$_POST["cliente_".$conta]."','".date("y/m/d")."','".$num_factura."','1','".$tipo_pago."','".$_POST["tot_".$conta]."','1')" ;

	$ins_id=insertar_maestro("m_factura",$compos,$valores); 
	
	if($tipo_pago != 'Contado' ) 
	{
		$sql = "INSERT INTO cartera_factura ( fec_car_fac, cod_fac) VALUES( '".date("y/m/d")."', '$ins_id');";
		$db2->query($sql);	
	}
	
	if ($ins_id > 0) 
	{	
		
		//INSERCION EN D FACTURA
		$compos="(cod_mfac,cod_tpro,cod_cat,cod_peso, cod_pro, cant_pro, val_uni, total_pro) ";
		for ($ii=1 ;  $ii <= $_POST["val_inicial_".$conta] ; $ii++) 
		{
				if($_POST["cod_pro_".$conta.$ii]!=NULL){
				$valores="('".$ins_id."','".$_POST["cod_tpro_".$conta.$ii]."','".$_POST["cod_mar_".$conta.$ii]."','".$_POST["cod_talla_".$conta.$ii]."','".$_POST["cod_pro_".$conta.$ii]."','".$_POST["cant_pro_".$conta.$ii]."','".$_POST["val_pro_".$conta.$ii]."','".$_POST["val_tot_pro_".$conta.$ii]."')";
			
				$error=insertar("d_factura",$compos,$valores);
				
				kardex("resta",$_POST["cod_pro_".$conta.$ii],1,$_POST["cant_pro_".$conta.$ii],$_POST["val_pro_".$conta.$ii]);
				}
			
		}

			header("Location: informes/ver_factura_imp.php?codigo=$ins_id"); 
	}
	
	else
	{
		echo "<script language='javascript'> alert('Hay un error en los Datos, Intente Nuevamente ') </script>"; 	

    }
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<body>
</body>
</html>