<?php
require_once('library/tcpdf.php');
require_once('conexion_db.php');

// Comprobar la sesión
if (isset($_SESSION['userID']) && isset($_SESSION['username'])) {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['username'];
} else {
    header("Location: login.php");
    exit();
}

$html = '<link rel="icon" type="image/x-icon" href="images/favicon.ico">';


// Crear instancia de TCPDF
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Establecer información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Listado_Pacientes');
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Agregar una página
$pdf->AddPage();

// Obtener los datos de la tabla pacientes
$sql = "SELECT idPaciente, nombre, edad, sexo, correo, contacto FROM pacientes";
$result = mysqli_query($conn, $sql);

// Verificar si hay resultados
if (mysqli_num_rows($result) > 0) {
    // Crear tabla para mostrar los datos
    $html = '<h1 style="text-align: center; color: #0d6efd; padding: 10px;">Listado de Pacientes</h1>';
    $html .= '<table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #0d6efd; color: #fff;">
                        <th style="padding: 8px; border: 1px solid #ddd; width: 60px;">ID</th>
                        <th style="padding: 8px; border: 1px solid #ddd; width: 250px;">Nombre</th>
                        <th style="padding: 8px; border: 1px solid #ddd; width: 60px;">Edad</th>
                        <th style="padding: 8px; border: 1px solid #ddd; width: 70px;">Sexo</th>
                        <th style="padding: 8px; border: 1px solid #ddd; width: 200px;">Correo</th>
                        <th style="padding: 8px; border: 1px solid #ddd; width: 90px;">Contacto</th>
                    </tr>
                </thead>
                <tbody>';

    // Recorrer los resultados y agregar filas a la tabla
    while ($row = mysqli_fetch_assoc($result)) {
        $html .= '<tr>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 60px;">'.$row['idPaciente'].'</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 250px;">'.$row['nombre'].'</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 60px;">'.$row['edad'].'</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 70px;">'.$row['sexo'].'</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 200px;">'.$row['correo'].'</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 90px;">'.$row['contacto'].'</td>
                </tr>';
    }

    $html .= '</tbody></table>';

    // Agregar espacio vertical entre los campos de la tabla
    $html = preg_replace('/(<td[^>]+>)([^<]*)(<\/td>)/', '$1<div style="margin-top: 15px; margin-bottom: 15px;">$2</div>$3', $html);

    // Agregar la tabla al PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Generar el archivo PDF
    $pdf->Output('listado_pacientes.pdf', 'I');
} else {
    echo 'No hay pacientes registrados.';
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>