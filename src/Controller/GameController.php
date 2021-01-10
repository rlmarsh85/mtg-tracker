<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\GamePlayer;
use App\Entity\Player;
use App\Repository\PlayerRepository;
use App\Entity\Deck;
use App\Repository\DeckRepository;

use Psr\Log\LoggerInterface;

/**
 * @Route("/game")
 */
class GameController extends AbstractController
{

    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * @Route("/", name="game_index", methods={"GET"})
     */
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('game/index.html.twig', [
            'games' => $gameRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="game_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $this->logger->error("Calling new function");
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);
        $isAjax = $request->request->get("isAjax");

        if ($form->isSubmitted() && $form->isValid() && !$isAjax) {

            $this->logger->error("Submitting form");

            $entityManager = $this->getDoctrine()->getManager();
            $playerRepo = $entityManager->getRepository(Player::class);
            $deckRepo = $entityManager->getRepository(Deck::class);


            $entityManager->persist($game);
            $entityManager->flush();
/*
            for($i=1; $i <= $request->request->get("game")["NumberPlayers"]; $i++){
              $game_player = new GamePlayer();
              $player_data = $request->request->get("game")['Player' . $i . 'Section'];
              $game_player->setPlayer($playerRepo->find($player_data['Player']));
              $game_player->setDeck($deckRepo->find($player_data['Deck']));
              $game_player->setGame($game);

              $winner = (array_key_exists('WinningPlayer',$player_data) && $player_data['WinningPlayer'] == 1) ? 1 : 0;
              $game_player->setWinningPlayer($winner);

              $entityManager->persist($game_player);
              $entityManager->flush();
            }
*/


            return $this->redirectToRoute('game_index');
        }

        return $this->render('game/new.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="game_show", methods={"GET"})
     */
    public function show(Game $game): Response
    {
        return $this->render('game/show.html.twig', [
            'game' => $game,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="game_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Game $game): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('game_index');
        }

        return $this->render('game/edit.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="game_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Game $game): Response
    {
        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($game);
            $entityManager->flush();
        }

        return $this->redirectToRoute('game_index');
    }
}
