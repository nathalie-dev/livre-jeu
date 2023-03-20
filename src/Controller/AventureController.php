<?php

namespace App\Controller;

use App\Entity\Aventure;
use App\Form\AventureType;
use App\Repository\AventureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/aventure')]
class AventureController extends AbstractController
{
    #[Route('/', name: 'app_aventure_index', methods: ['GET'])]
    public function index(AventureRepository $aventureRepository): Response
    {
        return $this->render('aventure/index.html.twig', [
            'aventures' => $aventureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_aventure_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AventureRepository $aventureRepository): Response
    {
        $aventure = new Aventure();
        $form = $this->createForm(AventureType::class, $aventure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $aventureRepository->save($aventure, true);

            return $this->redirectToRoute('app_aventure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('aventure/new.html.twig', [
            'aventure' => $aventure,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_aventure_show', methods: ['GET'])]
    public function show(Aventure $aventure): Response
    {
        return $this->render('aventure/show.html.twig', [
            'aventure' => $aventure,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_aventure_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Aventure $aventure, AventureRepository $aventureRepository): Response
    {
        $form = $this->createForm(AventureType::class, $aventure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $aventureRepository->save($aventure, true);

            return $this->redirectToRoute('app_aventure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('aventure/edit.html.twig', [
            'aventure' => $aventure,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_aventure_delete', methods: ['POST'])]
    public function delete(Request $request, Aventure $aventure, AventureRepository $aventureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aventure->getId(), $request->request->get('_token'))) {
            $aventureRepository->remove($aventure, true);
        }

        return $this->redirectToRoute('app_aventure_index', [], Response::HTTP_SEE_OTHER);
    }
}
