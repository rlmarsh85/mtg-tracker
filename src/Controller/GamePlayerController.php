<?php

namespace App\Controller;

use App\Entity\GamePlayer;
use App\Form\GamePlayerType;
use App\Repository\GamePlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/games/player")
 */
class GamePlayerController extends AbstractController
{
    /**
     * @Route("/", name="game_player_index", methods={"GET"})
     */
    public function index(GamePlayerRepository $gamePlayerRepository): Response
    {
        return $this->render('game_player/index.html.twig', [
            'game_players' => $gamePlayerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="game_player_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $gamePlayer = new GamePlayer();
        $form = $this->createForm(GamePlayerType::class, $gamePlayer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gamePlayer);
            $entityManager->flush();

            return $this->redirectToRoute('game_player_index');
        }

        return $this->render('game_player/new.html.twig', [
            'game_player' => $gamePlayer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="game_player_show", methods={"GET"})
     */
    public function show(GamePlayer $gamePlayer): Response
    {
        return $this->render('game_player/show.html.twig', [
            'game_player' => $gamePlayer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="game_player_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GamePlayer $gamePlayer): Response
    {
        $form = $this->createForm(GamePlayerType::class, $gamePlayer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('game_player_index');
        }

        return $this->render('game_player/edit.html.twig', [
            'game_player' => $gamePlayer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="game_player_delete", methods={"DELETE"})
     */
    public function delete(Request $request, GamePlayer $gamePlayer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gamePlayer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gamePlayer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('game_player_index');
    }
}
