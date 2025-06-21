<?php
	$hostname = "localhost";
	$dbname = "	db_hitachi_assistance";
	$username = "root";
	$pw = "";


	try {
		$db = new PDO('mysql:host='.$hostname.';dbname='.$dbname, $username, $pw);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Opcional: para manejar errores
	} catch (PDOException $ex) {
		echo "Error al conectar a la base de datos: " . $ex->getMessage() . "\n";
		exit;
	}

?>
