<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function findPlayerRanks(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT player.id, player.name, COUNT(game_id) `num_games`, SUM(winning_player) `num_wins`,
            ROUND(( SUM(winning_player) / COUNT(game_id) * 100  ),2) `win_ratio`
            FROM player
            LEFT JOIN game_player
            ON game_player.player_id = player.id
            GROUP BY player.id, player.name
            HAVING `num_wins` > 0
            ORDER BY win_ratio DESC            
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();
    }


    public function findPlayerOverallRank(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT 
            player.id, player.name, SUM(winning_player) `num_wins`,
            ROUND(( SUM(winning_player) / total_games.c * 100  ),2) `win_ratio`
            FROM player
            LEFT JOIN game_player
            ON game_player.player_id = player.id
            LEFT JOIN 
                (SELECT COUNT(id) c FROM game_player WHERE winning_player = 1) total_games ON 1 = 1
            GROUP BY player.id, player.name
            HAVING `num_wins` > 0
            ORDER BY win_ratio DESC            
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();
    }    
    
    public function findDeckRanks(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT deck.id, deck.name, COUNT(game_id) `num_games`, SUM(winning_player) `num_wins`, 
            ROUND(( SUM(winning_player) / COUNT(game_id) * 100  ),2) `win_ratio`
            FROM deck
            LEFT JOIN game_player
            ON game_player.deck_id = deck.id
            GROUP BY deck.id, deck.name
            ORDER BY win_ratio DESC            
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();
    }
    
    public function findColorRanks(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT 
            color.id, color.name, COUNT(game_id) `num_games`, SUM(winning_player) `num_wins`, 
            ROUND(( SUM(winning_player) / COUNT(game_id) * 100  ),2) `win_ratio`
            FROM deck
            LEFT JOIN game_player
            ON game_player.deck_id = deck.id
            LEFT JOIN decks_colors
            ON decks_colors.deck_id = deck.id
            LEFT JOIN color
            ON color.id = decks_colors.color_id
            GROUP BY color.id, color.name
            ORDER BY win_ratio DESC
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();
    }
    
    public function findTotalNumberGames(): int
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT COUNT(id) `count`
            FROM game
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $count = intval(($stmt->fetch(\PDO::FETCH_COLUMN)));
        // returns an array of arrays (i.e. a raw data set)
        return $count;
    }    

}
