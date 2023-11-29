<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;

class PdfGenerator
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function generatePdfFromHtml($html)
    {
        // Configuration de DOMPDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Chargement du HTML dans DOMPDF
        $dompdf->loadHtml($html);

        // Définition du format et de l'orientation du papier
        $dompdf->setPaper('A4', 'portrait');

        // Rendu du PDF
        $dompdf->render();

        // Retourner le contenu du PDF
        return $dompdf->output();
    }

    public function generatePdfFromTemplate($templatePath, $data = [])
    {
        // Générer le HTML à partir du template Twig
        $html = $this->twig->render($templatePath, $data);

        return $this->generatePdfFromHtml($html);
    }
}
