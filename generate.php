<?php
require_once('vendor/autoload.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

use Julio\Certificado\Certificate;

use Shuchkin\SimpleXLSX;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // obtener datos del formulario
        $key = $_POST['key'];
        $fileExcel = $_FILES['fileExcel'];
        $fileImage = $_FILES['fileImage'];
        $to = $_POST['to'];
        $from = $_POST['from'];



        // mover el archivo de imagen cargado a la ruta assets/plantilla/
        $urlImage = './assets/template/' . $fileImage['name'];
        move_uploaded_file($fileImage['tmp_name'], $urlImage);

        // mover el archivo de Excel cargado a la ruta assets/excel/
        $urlExcel = './assets/excel/' . $fileExcel['name'];
        move_uploaded_file($fileExcel['tmp_name'], $urlExcel);


        // Carga el archivo de Excel
        $xlsx = SimpleXLSX::parse($urlExcel);

        // Verifica que la carga se haya realizado correctamente
        if (!$xlsx) {
            echo json_encode(["error" => "Error al leer archivo excel"]);

            return;
        }

        $rows = $xlsx->rows();
        $counter = 0;
        foreach ($rows as $rowNum => $cols) {
            // Omitir la primera fila
            if ($rowNum == 0) continue;

            foreach ($cols as $colNum => $cell) {
                // Si deseas obtener solo la primera columna
                if ($colNum == 0) {
                    if ($to > $from) break;
                    Certificate::generate($cell, $cell, $key, $to, $urlImage);
                    $counter++;
                    $to++;
                }
            }
        }
        http_response_code(200);
        echo json_encode(["message" => "Hemos generado " . $counter  . " Certificados"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al generar certificado"]);
}
