<?php

namespace App\Controller;

use App\Entity\Deck;
use App\Form\DeckType;
use App\Repository\DeckRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/deck")
 */
class DeckController extends AbstractController
{
    /**
     * @Route("/", name="deck_index", methods={"GET"})
     */
    public function index(DeckRepository $deckRepository): Response
    {
        return $this->render('deck/index.html.twig', [
            'decks' => $deckRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="deck_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $deck = new Deck();
        $form = $this->createForm(DeckType::class, $deck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($deck);
            $entityManager->flush();

            return $this->redirectToRoute('deck_index');
        }

        return $this->render('deck/new.html.twig', [
            'deck' => $deck,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="deck_show", methods={"GET"})
     */
    public function show(Deck $deck): Response
    {
        return $this->render('deck/show.html.twig', [
            'deck' => $deck,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="deck_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Deck $deck): Response
    {
        $form = $this->createForm(DeckType::class, $deck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('deck_index');
        }

        return $this->render('deck/edit.html.twig', [
            'deck' => $deck,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="deck_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Deck $deck): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deck->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($deck);
            $entityManager->flush();
        }

        return $this->redirectToRoute('deck_index');
    }
}
