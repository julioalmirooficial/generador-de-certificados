<?php

namespace Julio\Certificado;

use Exception;
use TCPDF;

class Certificate
{
    /**
     * @param string $nameDocument | es el nombre del documento
     * @param string $userName | es el nombre del usuario o alumno
     * @param string $serie | es la serie u identificador de la empresa
     * @param string $to | es el numero correlativo que generar el sistema
     * @param string $template | es el nombre de la plantilla del certificado
     */
    public static function generate(string $nameDocument, string $userName, string $serie, int $to, string $template)
    {

        try {

            // Crear una instancia de la clase TCPDF
            $pdf = new TCPDF('L', 'mm', array(297, 210), true, 'UTF-8', false);


            $pdf->AddPage();
            $pdf->SetAutoPageBreak(false);

            // Agregar la imagen al centro del documento
            $pdf->Image($template, 0, 0, 297, 210, '', '', '', false, 300, '', false, false, 0);




            //! Calcular la posición central del documento
            $docWidth = $pdf->getPageWidth() - 297;
            $docHeight = $pdf->getPageHeight() - 17;
            $x = $docWidth / 2;
            $y = $docHeight / 2;

            // Establecer la posición del cursor en el centro del documento
            $pdf->SetXY($x, $y);
            // Escribir el texto centrado
            //! $pdf->SetFont(TIPO DE FUENTE, GROSOR FUENTE, TAMAÑO DE FUENTE);
            $pdf->SetFont('helvetica', 'XB', 26);
            $pdf->Cell(0, 10, strtoupper($userName), 0, 1, 'C');

            // Establecer la posición del cursor en el centro del documento
            $pdf->SetXY(245, 180);
            // Escribir el texto centrado
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(30, 10, strtoupper($serie . $to), 0, 1, 'R');



            $filename = dirname(__DIR__) . '/assets/certificate/' . str_replace(' ', '-', $nameDocument) . '.pdf';

            // Guardar el PDF en la raíz del proyecto
            $pdf->Output($filename, 'F');

            // Mostrar el archivo PDF en el navegador
            header('Content-Type: application/pdf');
            header('Content-Length: ' . filesize($filename));
        } catch (Exception $e) {
            echo json_encode(["error" => "Error "]);
        }
    }
}
