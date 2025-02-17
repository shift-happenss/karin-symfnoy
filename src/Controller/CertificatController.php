<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Certificat;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CertificatController extends AbstractController
{
    #[Route('/certificat', name: 'app_certificat')]
    public function index(): Response
    {
        return $this->render('chabeb/nermine/certificat/show.html.twig', [
            'controller_name' => 'CertificatController',
        ]);
    }

    #[Route('/certificat/{id}/download', name: 'certificat_download')]
    public function downloadCertificat(int $id, EntityManagerInterface $entityManager, Pdf $pdf): Response
    {
        $certificat = $entityManager->getRepository(Certificat::class)->find($id);

        if (!$certificat) {
            throw $this->createNotFoundException('Certificat non trouvé.');
        }

        $html = $this->renderView('chabeb/nermine/certificat/pdf.html.twig', [
            'certificat' => $certificat,
        ]);

        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            'certificat.pdf'
        );
    }
    #[Route('/certificats', name: 'liste_certificats', methods: ['GET'])]
    public function index1(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les certificats depuis la base de données
        $certificats = $entityManager->getRepository(Certificat::class)->findAll();

        // Rendre la vue Twig avec la liste des certificats
        return $this->render('chabeb/nermine/certificat/index.html.twig', [
            'certificats' => $certificats
        ]);
    }

#[Route('/certificat/{id}', name: 'certificat_show', methods: ['GET'])]
    public function show(Certificat $certificat): Response
    {
        return $this->render('chabeb/nermine/certificat/show.html.twig', [
            'certificat' => $certificat,
        ]);
    }

}