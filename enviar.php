<?
include "lib/sesion.php";
include("lib/database.php");
include("js/funciones.php");	

	$db = new Database();
	$sql = "SELECT * FROM bodega1
	WHERE mail_bod != ''";
	$db->query($sql);
	while($db->next_row()){
		enviar_alerta2($asunto,$mensaje);
	}
?>