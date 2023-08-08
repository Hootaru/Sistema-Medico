<?php
// Incluir la biblioteca TCPDF
require_once('library/tcpdf.php');

// Convertir la fuente TTF al formato TCPDF y almacenarla en la carpeta "fonts"
$fontname = TCPDF_FONTS::addTTFfont('fonts/Montserrat-BoldItalic.ttf', 'TrueTypeUnicode', '', 96);

// Crear una instancia de TCPDF
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// Obtener el idCita enviado por POST
if (isset($_POST['idCita'])) {
    $idCita = $_POST['idCita'];

    // Insertar los datos en la tabla "recetas"
    require_once('conexion_db.php'); // Asegúrate de tener el archivo de conexión incluido

    // Obtener los datos de la cita, receta y el nombre del paciente para el idCita especificado
    $sql = "SELECT c.idCita, r.idReceta, p.idPaciente, p.nombre, p.edad, p.estatura, p.peso, p.sexo, p.correo, p.contacto,
                DATE_FORMAT(c.fechaCita, '%d-%m-%Y') AS fecha,
                SUBSTRING(c.horaCita, 1, 5) AS hora,
                r.diagnostico, r.prescripcion, r.observaciones
            FROM citas AS c
            JOIN pacientes AS p ON c.idPaciente = p.idPaciente
            JOIN recetas AS r ON c.idCita = r.idCita
            WHERE c.idCita = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCita);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nombre = $row['nombre'];
        $edad = $row['edad'];
        $estatura = $row['estatura'];
        $peso = $row['peso'];
        $diagnostico = $row['diagnostico'];
        $prescripcion = $row['prescripcion'];
        $observaciones = $row['observaciones'];

        // Eliminar etiquetas HTML del texto de prescripción
        $prescripcionSinEtiquetas = strip_tags($prescripcion);

        // Resto del código para generar el PDF
        // ...

        // Establecer información del documento
        $pdf->SetCreator(PDF_CREATOR);

        $pdf->SetMargins(0, 0, 0, true);

        // No imprimir el encabezado
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        // Agregar una nueva página
        $pdf->AddPage();

        // Obtener la ruta de la imagen
        $imagenPath = 'images/receta2.png';

        // Obtener las dimensiones de la imagen
        list($imagenWidth, $imagenHeight) = getimagesize($imagenPath);

        // Calcular el tamaño máximo de la imagen para ajustarla proporcionalmente
        $maxWidth = $pdf->getPageWidth() - $pdf->getMargins()['left'] - $pdf->getMargins()['right'];
        $maxHeight = $pdf->getPageHeight() - $pdf->getMargins()['top'] - $pdf->getMargins()['bottom'];

        // Calcular el factor de escala para ajustar la imagen proporcionalmente
        $scaleFactor = min($maxWidth / $imagenWidth, $maxHeight / $imagenHeight);

        // Calcular el nuevo ancho y alto de la imagen
        $newWidth = $imagenWidth * $scaleFactor;
        $newHeight = $imagenHeight * $scaleFactor;

        // Calcular la posición para centrar la imagen en la página
        $x = $pdf->getMargins()['left'] + ($maxWidth - $newWidth) / 2;
        $y = $pdf->getMargins()['top'] + ($maxHeight - $newHeight) / 2;

        // Agregar la imagen al PDF
        $pdf->Image($imagenPath, $x, $y, $newWidth, $newHeight, '', '', '', false, 300, '', false, false, 0);

        // Obtener la fecha actual
        $fechaActual = date('d/m/Y');

        // Establecer la fuente TCPDF
        $pdf->SetFont($fontname, '', 12);

        // Mostrar los datos en el PDF
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetXY(60, 57);
        $pdf->Cell(0, 10, $nombre, 0, 1, 'L');

        $pdf->SetXY(173, 57);
        $pdf->Cell(0, 10, $edad, 0, 1, 'L');

        $pdf->SetXY(217, 57);
        $pdf->Cell(0, 10, $estatura, 0, 1, 'L');

        $pdf->SetXY(256, 57);
        $pdf->Cell(0, 10, $peso, 0, 1, 'L');

        $pdf->SetXY(193, 73);
        $pdf->Cell(0, 10, $observaciones, 0, 1, 'L');



      // Calcular el ancho máximo para el diagnóstico
        $diagnosticoWidth = $newWidth - 100;

        $pdf->SetXY(68, 76);
        $pdf->MultiCell($diagnosticoWidth, 10, $diagnostico, 0, 'L');


        // Establecer las coordenadas para el texto de prescripción
        $prescripcionX = 27;
        $prescripcionY = 105;
        $prescripcionWidth = $newWidth - 20; // Restar 20 para reducir el ancho de la prescripción
        $prescripcionHeight = $newHeight - ($prescripcionY - $y) - 10;

        $pdf->SetXY($prescripcionX, $prescripcionY);
        $pdf->MultiCell($prescripcionWidth, 10, $prescripcionSinEtiquetas, 0, 'J'); // Utilizar 'J' para justificar el contenido

        $pdf->SetXY(197, 31.8);
        $pdf->Cell(0, 10, $fechaActual, 0, 1, 'L');

        // Personalizar el encabezado para agregar el favicon
        $iconPath = 'images/favicon.ico';
        $iconData = file_get_contents($iconPath);
        $base64Icon = base64_encode($iconData);
        $encabezado = '<link rel="icon" type="image/x-icon" href="data:image/x-icon;base64,' . $base64Icon . '" />';
        $pdf->SetHeaderData('', '', $encabezado);

        // Generar el nombre del archivo PDF
        $nombreArchivo = 'Receta de ' . $nombre . '.pdf';

        // Mostrar el archivo PDF en el navegador
        $pdf->Output($nombreArchivo, 'I');

    } else {
        echo "No se encontró la cita";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID de cita no proporcionado";
}
?>