<?php

namespace App\Controller;

use App\Entity\Aventure;
use App\Entity\Partie;
use App\Entity\Etape;
use App\Entity\Personnage;
use App\Repository\PersonnageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PersonnageType;
use App\Repository\AlternativeRepository;
use App\Repository\AvatarRepository;
use App\Repository\AventureRepository;
use App\Repository\EtapeRepository;
use App\Repository\PartieRepository;
use Symfony\Component\HttpFoundation\Request;


class JouerController extends AbstractController
{
    #[Route('/jouer', name: 'app_jouer')]
    public function index(PersonnageRepository $personnageRepository): Response
    {   
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $personnages = $personnageRepository->findBy(["user"=> $this->getUser()]);
        return $this->render('jouer/index.html.twig', [
            'personnages' => $personnages ,
        ]);
    }

    #[Route('/jouer/new', name: 'app_jouer_new', methods: ['GET', 'POST'])]
    public function new(PersonnageRepository $personnageRepository, Request $request): Response 
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $personnage = new Personnage();
        $form = $this->createForm(PersonnageType::class, $personnage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            // sauvegarde du personnage
            $personnage->setUser($this->getUser());

        $personnageRepository->save($personnage, true);
        return $this->redirectToRoute('app_jouer', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('jouer/new_personnage.html.twig', ['form'=>$form,'personnage'=>$personnage]);
    }

    #[Route('/jouer/aventures/{idPersonnage}', name: 'app_choix_aventure', methods: ['GET'])]
    public function afficherAventures($idPersonnage, PersonnageRepository $personnageRepository, AventureRepository $aventureRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $personnage = $personnageRepository->find($idPersonnage);
        $aventures = $aventureRepository->findAll();

        return $this->render('jouer/aventures.html.twig', ['personnage'=> $personnage, 'aventures' =>$aventures]);
    }

    #[Route('/jouer/aventures/{idPersonnage}/{idAventure}', name: 'app_start_aventure', methods: ['GET'])]
    public function demarrerAventure($idPersonnage,$idAventure, PersonnageRepository $personnageRepository, AventureRepository $AventureRepository, PartieRepository $partieRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $aventure = $AventureRepository->find($idAventure);
        $personnage = $personnageRepository->find($idPersonnage);
        $partie = $partieRepository->findOneBy(array('aventurier'=>$personnage,'aventure'=>$aventure));
        $isNewPartie = !isset($partie);

        if ($isNewPartie)
        {
            $isNewPartie = true;
            $partie = new Partie();
            $partie->setAventurier($personnage);
            $partie->setAventure($aventure);
            $partie->setEtape($aventure->getPremiereEtape());
            $partie->setDatePartie(new \DateTime('now'));
            $partieRepository->save($partie,true);
        }
   

    return $this->render('jouer/aventure-start.html.twig', ['aventure'=> $aventure, 'personnage'=> $personnage, 'newPartie'=> $isNewPartie,'partie'=>$partie]);
}

    #[Route('/jouer/etape/{idPartie}/{idEtape}/{idPersonnage}', name:'app_play_aventure', methods:['GET'])]
    public function jouerEtape ($idPartie, $idEtape,$idPersonnage, PartieRepository $partieRepository, EtapeRepository $etapeRepository, PersonnageRepository $personnageRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $etape = $etapeRepository->find($idEtape);
        $partie = $partieRepository->find($idPartie);
        $personnage = $personnageRepository->find($idPersonnage);
        $partie->setEtape($etape);
if ($etape->getFinAventure()!=null)
    {
    return $this->redirectToRoute('app_finir_aventure',['idPersonnage'=> $personnage->getId(), 'idAventure'=> $etape->getAventure()->getId(), 'idEtape'=> $etape->getId()]);
    }
    else
        return $this->render('jouer/aventure-play.html.twig', ['etape'=> $etape, 'partie'=>$partie, 'personnage'=> $personnage]);   
}

    #[Route('/jouer/aventure/finir/{idPersonnage}/{idAventure}/{idEtape}', name: 'app_finir_aventure', methods:['GET'])] 
    public function finirPartie ($idPersonnage,$idAventure, $idEtape, PersonnageRepository $personnageRepository, EtapeRepository $etapeRepository, AventureRepository $aventureRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $personnage = $personnageRepository->find($idPersonnage);
        $etape = $etapeRepository->find($idEtape);
        return $this->render("jouer/aventure-end.html.twig", ['etape'=>$etape,'personnage'=>$personnage]);
    }
    


}







