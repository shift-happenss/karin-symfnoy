<?php
namespace App\Service;

use App\Entity\Certificat;
use App\Entity\Formation;
use Doctrine\ORM\EntityManagerInterface;

class CertificatService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function attribuerCertificat(int $idFormation, string $nomCertificat): Certificat
    {
        // Recuperer la formation depuis bd
        $formation = $this->entityManager->getRepository(Formation::class)->find($idFormation);

        if (!$formation) {
            throw new \Exception("Formation non trouvée !");
        }

        // Creation du certificat
        $certificat = new Certificat();
        $certificat->setFormation($formation);
        $certificat->setTitle($formation->getTitre());
        $certificat->setUserName('userName');
        // Sauvegarde en bd
        $this->entityManager->persist($certificat);
        $this->entityManager->flush();

        return $certificat;
    }
}