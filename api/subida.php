<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Directorio donde se guardará la imagen subida
    $uploadDir = '../subidas/';
    $uploadFile = $uploadDir . basename($_FILES['image']['name']);

    // Mueve la imagen subida al directorio de destino
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        // Verifica el tipo de imagen subida
        $imageType = exif_imagetype($uploadFile);
        if ($imageType == IMAGETYPE_JPEG || $imageType == IMAGETYPE_PNG) {
            // Crea una imagen desde el archivo subido
            if ($imageType == IMAGETYPE_JPEG) {
                $image = imagecreatefromjpeg($uploadFile);
            } elseif ($imageType == IMAGETYPE_PNG) {
                $image = imagecreatefrompng($uploadFile);
            }

            // Carga la imagen PNG que se superpondrá
            $overlay = imagecreatefrompng('../assets/marco.png');

            // Obtén el tamaño de la imagen principal y la imagen de superposición
            $imageWidth = imagesx($image);
            $imageHeight = imagesy($image);
            $overlayWidth = imagesx($overlay);
            $overlayHeight = imagesy($overlay);

            // Superpone la imagen PNG sobre la imagen subida
            imagecopy($image, $overlay, 0, 0, 0, 0, $overlayWidth, $overlayHeight);

            // Guarda la nueva imagen
            $outputFile = $uploadDir . 'superpuesta_' . basename($_FILES['image']['name']);
            if ($imageType == IMAGETYPE_JPEG) {
                imagejpeg($image, $outputFile);
            } elseif ($imageType == IMAGETYPE_PNG) {
                imagepng($image, $outputFile);
            }

            // Libera la memoria
            imagedestroy($image);
            imagedestroy($overlay);

            echo "Imagen subida y superpuesta exitosamente: <a href='$outputFile'>$outputFile</a>";
        } else {
            echo "El archivo subido no es una imagen válida.";
        }
    } else {
        echo "Error al subir la imagen.";
    }
} else {
    echo "Método de solicitud no válido.";
}
?>