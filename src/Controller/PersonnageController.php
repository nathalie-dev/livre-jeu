<?php

namespace App\Controller;

use App\Entity\Personnage;
use App\Form\Personnage1Type;
use App\Repository\PersonnageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/personnage')]
class PersonnageController extends AbstractController
{
    #[Route('/', name: 'app_personnage_index', methods: ['GET'])]
    public function index(PersonnageRepository $personnageRepository): Response
    {
        return $this->render('personnage/index.html.twig', [
            'personnages' => $personnageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_personnage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PersonnageRepository $personnageRepository): Response
    {
        $personnage = new Personnage();
        $form = $this->createForm(Personnage1Type::class, $personnage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personnageRepository->save($personnage, true);

            return $this->redirectToRoute('app_personnage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personnage/new.html.twig', [
            'personnage' => $personnage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_personnage_show', methods: ['GET'])]
    public function show(Personnage $personnage): Response
    {
        return $this->render('personnage/show.html.twig', [
            'personnage' => $personnage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_personnage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Personnage $personnage, PersonnageRepository $personnageRepository): Response
    {
        $form = $this->createForm(Personnage1Type::class, $personnage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personnageRepository->save($personnage, true);

            return $this->redirectToRoute('app_personnage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personnage/edit.html.twig', [
            'personnage' => $personnage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_personnage_delete', methods: ['POST'])]
    public function delete(Request $request, Personnage $personnage, PersonnageRepository $personnageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$personnage->getId(), $request->request->get('_token'))) {
            $personnageRepository->remove($personnage, true);
        }

        return $this->redirectToRoute('app_personnage_index', [], Response::HTTP_SEE_OTHER);
    }
}
