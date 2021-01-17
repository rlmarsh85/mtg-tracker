<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\GameRepository;
use App\Entity\Game;

/**
 * @Route("/stats")
 */
class StatsController extends AbstractController
{
    /**
     * @Route("/", name="stats_index", methods={"GET"})
     */
    public function index(): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $gameRepo = $entityManager->getRepository(Game::class);     
        $player_ranks = $gameRepo->findPlayerRanks();
        $player_overall_ranks = $gameRepo->findPlayerOverallRank();
        $deck_ranks = $gameRepo->findDeckRanks();
        $color_ranks = $gameRepo->findColorRanks();
        $total_games = $gameRepo->findTotalNumberGames();

        return $this->render('stats/index.html.twig', [
            'player_ranks' => $player_ranks,
            'player_overall_ranks' => $player_overall_ranks,
            'deck_ranks' => $deck_ranks,
            'color_ranks' => $color_ranks,
            'total_games' => $total_games
        ]);
    }

}
