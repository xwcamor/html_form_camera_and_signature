<?php

require_once('conection.php');

$validator = array('success' => false, 'messages' => array());

$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$fecha = $_POST["fecha"];
$email = $_POST["email"];
$dni = $_POST["dni"];
$sexo = $_POST["sexo"];
$facultad = $_POST["facultad"];
$carrera = $_POST["carrera"];
$detalle = $_POST["detalle"];
$firma = $_POST["firma"]; 
$foto = base64_decode($_POST["foto"]);

// Guardar la foto en un archivo
$route_photo = "../foto/f_" . $dni . ".jpg";
$name_photo = "f_" . $dni . ".jpg";
$file = fopen($route_photo, "w");

if ($file) {
    fwrite($file, $foto);
    fclose($file);
} else {
    $validator['messages'] = "ERROR AL GUARDAR LA FOTO";
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($validator);
    exit();
}

// Guardar en la base de datos
$sql = 'INSERT INTO original (dni, nombre, apellido, fnac, email, sexo, facultad, carrera, detalle, foto, signature) VALUES (:dni, :nombre, :apellido, :fnac, :email, :sexo, :facultad, :carrera, :detalle, :foto, :signature)';
$stmt = $db->prepare($sql);
$stmt->bindValue(':dni', $dni, PDO::PARAM_STR);
$stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
$stmt->bindValue(':apellido', $apellido, PDO::PARAM_STR);
$stmt->bindValue(':fnac', $fecha, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':sexo', $sexo, PDO::PARAM_STR);
$stmt->bindValue(':facultad', $facultad, PDO::PARAM_STR);
$stmt->bindValue(':carrera', $carrera, PDO::PARAM_STR);
$stmt->bindValue(':detalle', $detalle, PDO::PARAM_STR);
$stmt->bindValue(':foto', $name_photo, PDO::PARAM_STR);
$stmt->bindValue(':signature', $firma, PDO::PARAM_STR); // Guardar la firma base64 en la base de datos

if ($stmt->execute()) {
    $validator['success'] = true;
    $validator['messages'] = "DATOS GUARDADOS";
} else {
    $validator['messages'] = "ERROR AL GUARDAR DATOS";
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($validator);
exit();
?>