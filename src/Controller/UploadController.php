<?php

namespace App\Controller;

use DateTime;
use App\Entity\Legume;
use App\Form\CreateLegumeType;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Service\UploadService;


class UploadController extends AbstractController
{
    #[Route('/upload', name: 'upload')]
    public function index(Request $request, EntityManagerInterface $em, UploadService $uploadService): Response
    {
        # on instancie l'entité légume
        $legume = new Legume();

        # on pré-rempli des infos par défaut
        $legume->setUpdatedAt(new \DateTime);

        # on crée le formulaire createForm( type de formulaire, un objet entité )
        $legumeForm = $this->createForm(CreateLegumeType::class, $legume);

        # Récupérer les données du formulaire : 
        $legumeForm->handleRequest($request);

        # Quand le formulaire est valide et soumis ... 
        if($legumeForm->isSubmitted() && $legumeForm->isValid())
        {

            # on définit un chemin où envoyer le fichier 
            # $this->getParameter() permet d'aller chercher un paramètre définit dans service.yaml

            $directory = $this->getParameter('images_directory');

            # on appelle un service personnalisé, qui envoie le fichier dans le chemin
            # défini dans $directory, qui nous renverra le nom du fichier

            $fileName = $uploadService->upload($legumeForm,$directory);

            # on rentre le nom dans la bdd, ici dans le champ filename: 
            $legume->setFileName($fileName);

            # persist et flush
            $em->persist($legume);
            $em->flush();

            # crée un message de confirmation, récupérable dans la view
            $this->addFlash('success','Votre légume a bien été ajouté');
        }

        return $this->render('upload/index.html.twig', [
            'controller_name' => 'UploadController',
            'form' => $legumeForm->createView()
        ]);
    }
}
