<?php

namespace App\Controller;

use App\Entity\Etape;
use App\Form\EtapeType;
use App\Repository\EtapeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/etape')]
class EtapeController extends AbstractController
{
    #[Route('/', name: 'app_etape_index', methods: ['GET'])]
    public function index(EtapeRepository $etapeRepository): Response
    {
        return $this->render('etape/index.html.twig', [
            'etapes' => $etapeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_etape_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EtapeRepository $etapeRepository): Response
    {
        $etape = new Etape();
        $form = $this->createForm(EtapeType::class, $etape);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etapeRepository->save($etape, true);

            return $this->redirectToRoute('app_etape_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etape/new.html.twig', [
            'etape' => $etape,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etape_show', methods: ['GET'])]
    public function show(Etape $etape): Response
    {
        return $this->render('etape/show.html.twig', [
            'etape' => $etape,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_etape_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etape $etape, EtapeRepository $etapeRepository): Response
    {
        $form = $this->createForm(EtapeType::class, $etape);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etapeRepository->save($etape, true);

            return $this->redirectToRoute('app_etape_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etape/edit.html.twig', [
            'etape' => $etape,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etape_delete', methods: ['POST'])]
    public function delete(Request $request, Etape $etape, EtapeRepository $etapeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etape->getId(), $request->request->get('_token'))) {
            $etapeRepository->remove($etape, true);
        }

        return $this->redirectToRoute('app_etape_index', [], Response::HTTP_SEE_OTHER);
    }
}
