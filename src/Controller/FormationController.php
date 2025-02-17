<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Certificat;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\FormationRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Formation;
use App\Form\FormationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\CertificatService;
final class FormationController extends AbstractController
{
    private CertificatService $certificatService;
    private EntityManagerInterface $entityManager;

    public function __construct(CertificatService $certificatService, EntityManagerInterface $entityManager)
    {
        $this->certificatService = $certificatService;
        $this->entityManager = $entityManager;
    }

   

   // Afficher toutes les formations
   #[Route('/courses', name: 'app_courses_index', methods: ['GET'])]
   public function index(EntityManagerInterface $entityManager): Response
   {
       $formations = $entityManager->getRepository(Formation::class)->findAll();

       return $this->render('chabeb/nermine/courses.html.twig', [
           'formations' => $formations,
       ]);
   }

   #[Route('/formation/formations', name: 'formation_index', methods: ['GET'])]
   public function index1(EntityManagerInterface $entityManager): Response
   {
       $formations = $entityManager->getRepository(Formation::class)->findAll();

       return $this->render('chabeb/nermine/formation/index.html.twig', [
           'formations' => $formations,
       ]);
   }
  
    #[Route('/formation/new', name: 'formation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Déplace l'image vers le dossier public/uploads
                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception("Impossible d'uploader l'image !");
                }

                $formation->setUrlImage($newFilename);
            }


                // 🔹 Gestion de l'upload du fichier
                $file = $form->get('file')->getData();
                if ($file) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                    try {
                        $file->move(
                            $this->getParameter('files_directory'), // Défini dans services.yaml
                            $newFilename
                        );
                        $formation->setUrlFichier($newFilename);
                    } catch (FileException $e) {
                        $this->addFlash('error', 'Erreur lors de l’upload du fichier.');
                    }
                }



            $entityManager->persist($formation);
            $entityManager->flush();

            $this->addFlash('success', 'Formation ajoutée avec succès !');
                return $this->redirectToRoute('formation_index');
        }

        return $this->render('chabeb/nermine/formation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    } 
   // Afficher les détails d'une formation
   #[Route('/formation/{id}', name: 'app_courses_details', methods: ['GET'])]
   
   public function show(int $id, EntityManagerInterface $entityManager): Response
   {
       $formation = $entityManager->getRepository(Formation::class)->find($id);

       if (!$formation) {
           throw $this->createNotFoundException('Formation non trouvée.');
       }

       $entityManager->persist($formation);
       $entityManager->flush();

       return $this->render('chabeb/nermine/courses_details.html.twig', [
           'formation' => $formation,
       ]);
   }

   #[Route('/{id}', name: 'formation_show', requirements: ['id' => '\d+'], methods: ['GET'])]

    public function show1(int $id, EntityManagerInterface $entityManager): Response
    {
        $formation = $entityManager->getRepository(Formation::class)->find($id);
 
        if (!$formation) {
            throw $this->createNotFoundException('Formation non trouvée.');
        }
 
        
        $entityManager->persist($formation);
        $entityManager->flush();
 
        return $this->render('chabeb/nermine/formation/show.html.twig', [
            'formation' => $formation,
        ]);
    }

   // Modifier une formation
   #[Route('/formation/{id}/edit', name: 'formation_edit', methods: ['GET', 'POST'])]
   public function edit(Request $request, Formation $formation, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload de l'image
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'erreur si nécessaire
                }

                // Supprimer l'ancienne image si elle existe
                if ($formation->getUrlImage()) {
                    $oldImagePath = $this->getParameter('uploads_directory') . '/' . $formation->getUrlImage();
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Mettre à jour l'entité avec le nouveau fichier
                $formation->setUrlImage($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('formation_index');
        }

        return $this->render('chabeb/nermine/formation/edit.html.twig', [
            'form' => $form->createView(),
            'formation' => $formation,
        ]);
    }

   // Supprimer une formation
   #[Route('/formation/{id}/delete', name: 'formation_delete', methods: ['POST'])]
   public function delete(Request $request, Formation $formation, EntityManagerInterface $entityManager): Response
   {
       if ($this->isCsrfTokenValid('delete' . $formation->getId(), $request->request->get('_token'))) {
           $entityManager->remove($formation);
           $entityManager->flush();
       }

       return $this->redirectToRoute('formation_index');
   }

   

    #[Route('/formation/enroll/{id}', name: 'formation_enroll', methods: ['POST','GET'])]
    public function enroll(int $id, EntityManagerInterface $entityManager): Response
    {
        $formation = $entityManager->getRepository(Formation::class)->find($id);

        if (!$formation) {
            throw $this->createNotFoundException('Formation non trouvée.');
        }

        // Changer l'état de la formation
        $formation->avancerEtat();
        $entityManager->persist($formation);
        $entityManager->flush();

        // Rediriger vers les détails de la formation ou la liste
        return $this->redirectToRoute('formation_show', ['id' => $id]);
    }


    #[Route('/formation/{id}/certificat', name: 'obtenir_certificat', methods: ['POST', 'GET'])]
    public function attribuerCertificat(int $id): Response
    {
        try {
            $certificat = $this->certificatService->attribuerCertificat($id, "Certificat de réussite");

            return $this->redirectToRoute('afficher_certificat', ['id' => $certificat->getId()]);
        } catch (\Exception $e) {
            $this->addFlash('error', "Erreur lors de l'attribution du certificat : " . $e->getMessage());
            return $this->redirectToRoute('formation_show', ['id' => $id]);
        }
    }

    #[Route('/certificat/{id}', name: 'afficher_certificat', methods: ['GET'])]
    public function afficherCertificat(int $id, EntityManagerInterface $entityManager): Response
    {
        $certificat = $entityManager->getRepository(Certificat::class)->find($id);

        if (!$certificat) {
            throw $this->createNotFoundException('Certificat non trouvé.');
        }

        return $this->render('chabeb/nermine/certificat/show.html.twig', [
            'certificat' => $certificat,
        ]);
    }


   // Afficher la liste des cours pour les étudiants
   #[Route('/user/formations', name: 'formation_show_user', methods: ['GET'])]
   public function showuser(FormationRepository $formationRepository): Response
   {
       return $this->render('chabeb/nermine/formation/show_user.html.twig', [
           'formations' => $formationRepository->findAll(),
       ]);
   }
    


}