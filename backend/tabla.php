<?php

$data['data'] = false;

require_once('conection.php');

$sql = 'SELECT id, dni, nombre, apellido, fnac, email, facultad, carrera, signature, foto FROM original;';
$sentencia = $db->query($sql);
$alumnos = $sentencia->fetchAll(PDO::FETCH_OBJ);

foreach ($alumnos as $alumno) {
    $id = $alumno->id;
    $fecha = $alumno->fnac;
    $dni = $alumno->dni;
    $nombre = $alumno->nombre;
    $apellido = $alumno->apellido;
    $email = $alumno->email;
    $facultad = $alumno->facultad;
    $carrera = $alumno->carrera;
    $signature = $alumno->signature;
    $foto = $alumno->foto;
    $boton = '<a type="button" class="btn btn-sm btn-info" onclick="verfoto(\'' . $foto . '\')" id="btnFoto_' . $dni . '"> Foto</a>';

    // Convierte la firma en una URL de imagen
    $signatureImg = '<img src="data:image/png;base64,' . $signature . '" alt="Firma" style="max-width: 100px; max-height: 50px;">';

    $data['data'][] = array($id, $fecha, $dni, $nombre, $apellido, $email, $facultad, $carrera, $signatureImg, $boton);
}

echo json_encode($data);
exit;
