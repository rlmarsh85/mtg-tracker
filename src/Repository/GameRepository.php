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

    /**
     * Gets each players win rate, i.e. percent of games won of all games which that player played in
     */
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


    /**
     * Gets player's overall win rate, i.e. percent of games won out of all games played
     */
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
            ROUND(( SUM(winning_player) / total_games.c * 100  ),2) `win_ratio`
            FROM deck
            LEFT JOIN game_player
            ON game_player.deck_id = deck.id
            LEFT JOIN decks_colors
            ON decks_colors.deck_id = deck.id
            LEFT JOIN color
            ON color.id = decks_colors.color_id
            LEFT JOIN 
                (SELECT COUNT(id) c FROM game_player WHERE winning_player = 1) total_games ON 1 = 1            
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
    
    public function findAverageGameLength(): float
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT AVG(number_turns) `number_turns`
            FROM game
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $count = floatval(($stmt->fetch(\PDO::FETCH_COLUMN)));
        // returns an array of arrays (i.e. a raw data set)
        return $count;        
    }

    public function findPercentWonWithSolRing(): float
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT SUM(IF((winning_player + first_or_second_turn_sol_ring) = 2,1,0)) / COUNT(DISTINCT game.id)
            FROM game
            LEFT JOIN game_player
            ON game_player.game_id = game.id
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $count = floatval(($stmt->fetch(\PDO::FETCH_COLUMN)));
        // returns an array of arrays (i.e. a raw data set)
        return $count;            
    }

    public function findPlayersGameStats(): int
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT SUM(first_or_second_turn_sol_ring) `games_with_sol_ring`, 
            COUNT(game_id) `games`, 
            SUM(first_or_second_turn_sol_ring) / COUNT(game_id) `percent_games_sol_ring`,
            player.id, player.name
            
            FROM game_player
            LEFT JOIN player
            ON player.id = game_player.player_id
            GROUP BY player_id, player.name
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $count = intval(($stmt->fetch(\PDO::FETCH_COLUMN)));
        return $count;            
    }

}
