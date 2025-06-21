<?php

require_once('conection.php');

$validator = array('success' => false, 'messages' => array());

// Verifica si se ha enviado una imagen
if (!empty($_FILES["archivo"]["name"])) {
    $fileName = basename($_FILES["archivo"]["name"]);
    $targetFilePath = '../foto/' . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    $allowTypes = array('jpg', 'png', 'jpeg');

    if (in_array($fileType, $allowTypes)) {
        if (copy($_FILES["archivo"]["tmp_name"], $targetFilePath)) {
            $uploadedFile = $fileName;

            // Recibir los datos del formulario
            $nombre = $_POST["nombre"];
            $apellido = $_POST["apellido"];
            $fnac = $_POST["fnac"];
            $email = $_POST["email"];
            $dni = $_POST["dni"];
            $sexo = $_POST["sexo"];
            $facultad = $_POST["facultad"];
            $carrera = $_POST["carrera"];
            $detalle = $_POST["detalle"];
            $firma = $_POST["firma"]; // <--- Aquí estás recibiendo la firma

            // Guardar la foto en un archivo
            $route_photo = "../foto/f_" . $dni . ".jpg";
            $name_photo = "f_" . $dni . ".jpg";
            $file = fopen($route_photo, "w");

            if ($file) {
                fwrite($file, file_get_contents($targetFilePath));
                fclose($file);
            } else {
                $validator['messages'] = "ERROR AL GUARDAR LA FOTO";
                header('Content-type: application/json; charset=utf-8');
                echo json_encode($validator);
                exit();
            }

            // Guardar en la base de datos
            $sql = 'INSERT INTO original
                    (num_doc,   name,  lastname, date_born,   email,  sex,  photo,  signature) 
                    VALUES
                    (:num_doc, :name, :lastname, :date_born, :email, :sex, :photo, :signature)';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':num_doc', $dni, PDO::PARAM_STR);
            $stmt->bindValue(':name', $nombre, PDO::PARAM_STR);
            $stmt->bindValue(':lastname', $apellido, PDO::PARAM_STR);
            $stmt->bindValue(':date_born', $fnac, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':sex', $sexo, PDO::PARAM_STR);
            $stmt->bindValue(':photo', $fileName, PDO::PARAM_STR);
            $stmt->bindValue(':signature', $firma, PDO::PARAM_STR); // Ahora se guarda la firma

            if ($stmt->execute()) {
                $validator['success'] = true;
                $validator['messages'] = "DATOS GUARDADOS";
            } else {
                $validator['messages'] = "ERROR AL GUARDAR DATOS";
            }
        } else {
            $validator['messages'] = 'NO SE COPIO LA IMAGEN';
        }
    } else {
        $validator['messages'] = 'SOLO SE PERMITEN FORMATOS JPG, PNG Y JPEG.';
    }
} else {
    $validator['messages'] = "NO HAY DATOS";
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($validator);
exit();
?>
