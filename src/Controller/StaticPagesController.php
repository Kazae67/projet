<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticPagesController extends AbstractController
{
    #[Route('/privacy-policy', name: 'privacy_policy')]
    public function privacyPolicy(): Response
    {
        return $this->render('static_pages/privacy_policy.html.twig');
    }

    #[Route('/legal-mentions', name: 'legal_mentions')]
    public function legalMentions(): Response
    {
        return $this->render('static_pages/legal_mentions.html.twig');
    }

    #[Route('/terms-of-service', name: 'terms_of_service')]
    public function termsOfService(): Response
    {
        return $this->render('static_pages/terms_of_service.html.twig');
    }
    #[Route('/terms-of-use', name: 'terms_of_use')]
    public function termsOfUse(): Response
    {
        return $this->render('static_pages/terms_of_use.html.twig');
    }
}
