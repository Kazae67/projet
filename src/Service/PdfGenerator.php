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
        // Configuration initiale de DOMPDF
        $options = new Options();
        $options->set('defaultFont', 'Arial'); // Définition de la police par défaut
        $dompdf = new Dompdf($options); // Initialisation de DOMPDF avec les options configurées
    
        // Chargement du HTML dans DOMPDF
        $dompdf->loadHtml($html); // Intégration du contenu HTML à convertir
    
        // Définition de l'orientation et du format du papier
        $dompdf->setPaper('A4', 'portrait'); // Format A4 en orientation portrait
    
        // Génération du PDF
        $dompdf->render(); // Traitement et rendu du PDF
    
        // Extraction et retour du contenu PDF généré
        return $dompdf->output(); // Renvoi du contenu PDF pour utilisation ultérieure
    }

    public function generatePdfFromTemplate($templatePath, $data = [])
    {
        // Générer le HTML à partir du template Twig
        $html = $this->twig->render($templatePath, $data);

        return $this->generatePdfFromHtml($html);
    }
}
