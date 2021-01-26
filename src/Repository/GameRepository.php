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
    public function findPlayerRanks($limit=-1): array
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
        $sql .= ($limit == -1) ? "" : (" LIMIT " . $limit);
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();
    }


    /**
     * Gets player's overall win rate, i.e. percent of games won out of ALL games played
     */
    public function findPlayerOverallRanks($limit=-1): array
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
        $sql .= ($limit == -1) ? "" : (" LIMIT " . $limit);

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();
    }    
    
    /**
     * Gets the win rate of each deck, i.e. percent of games won of all games which that deck was played
     */
    public function findDeckRanks($limit=-1): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT deck.id, deck.name, COUNT(game_id) `num_games`, SUM(winning_player) `num_wins`, 
            ROUND(( SUM(winning_player) / COUNT(game_id) * 100  ),2) `win_ratio`
            FROM deck
            LEFT JOIN game_player
            ON game_player.deck_id = deck.id
            GROUP BY deck.id, deck.name
            HAVING num_wins > 0
            ORDER BY win_ratio DESC            
            ';
        $sql .= ($limit == -1) ? "" : (" LIMIT " . $limit);            

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();
    }

    /**
     * Gets decks overall win rate, i.e. percent of games won of ALL games played.
     */
    public function findDeckOverallRanks($limit=-1): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT deck.id, deck.name, COUNT(game_id) `num_games`, SUM(winning_player) `num_wins`, 
            ROUND(( SUM(winning_player) / total_games.c * 100  ),2) `win_ratio`
            FROM deck
            LEFT JOIN game_player
            ON game_player.deck_id = deck.id
            LEFT JOIN 
                (SELECT COUNT(id) c FROM game_player WHERE winning_player = 1) total_games ON 1 = 1            
            GROUP BY deck.id, deck.name
            HAVING num_wins > 0
            ORDER BY win_ratio DESC 
            ';
        $sql .= ($limit == -1) ? "" : (" LIMIT " . $limit);

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();
    }    
    
    /**
     * Get the overall win rate for colors, i.e. how often this color wins among games in which it's played.
     * This is confusing because the percent total summed is > 100 because, remember, this is how
     * well each color performs among the games where it's played.
     * This is also confusing because if two players are playing decks with the same color,
     * then the match is still counted as a win for the color.
     */
    public function findColorOverallRanks(): array
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

    /**
     * Gets the win rate for each color, i.e. how often the color wins among ALL games.
     * This one is confusing because the total % is still > 100. This time it's because a winning
     * deck can and usually does represent more than one color. May not actually be appropriate to
     * display as a pie chart ...
     */
    public function findColorRanks(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT color.id, color.name, COUNT(game_id) `num_games`, SUM(winning_player) `num_wins`, 
            ROUND(( SUM(winning_player) / COUNT(DISTINCT game_id) * 100  ),2) `win_ratio`
            FROM deck
            LEFT JOIN game_player
            ON game_player.deck_id = deck.id
            LEFT JOIN decks_colors
            ON decks_colors.deck_id = deck.id
            LEFT JOIN color
            ON color.id = decks_colors.color_id  
            GROUP BY color.id, color.name
            HAVING num_wins > 0
            ORDER BY win_ratio DESC    
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();
    }
    
    /**
     * Get the win ratio for each commander, i.e. the win percent for each game in which the commander is played.
     */
    public function findCommanderRanks($limit = -1): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT commander.id, commander.name, COUNT(game_id) `num_games`, SUM(winning_player) `num_wins`, 
            ROUND(( SUM(winning_player) / COUNT(DISTINCT game_id) * 100  ),2) `win_ratio`, COUNT(DISTINCT game_id) c
            FROM deck
            LEFT JOIN game_player
            ON game_player.deck_id = deck.id
            
            LEFT JOIN commanders_decks
            ON commanders_decks.deck_id = deck.id
            LEFT JOIN commander
            ON commander.id = commanders_decks.commander_id

            WHERE commander.id IS NOT NULL
            GROUP BY commander.id, commander.name
            HAVING num_wins > 0
            ORDER BY win_ratio DESC        
            ';
        $sql .= ($limit == -1) ? "" : (" LIMIT " . $limit);

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();        
    }

    /**
     * Finds the overall win ratio for each commadner, i.e. the percent win among ALL games played.
     */
    public function findCommanderOverallRanks($limit = -1): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT 
                commander.id, commander.name, COUNT(game_id) `num_games`, SUM(winning_player) `num_wins`, 
                ROUND(( SUM(winning_player) / total_games.c * 100  ),2) `win_ratio`, total_games.c
                FROM deck
                LEFT JOIN game_player
                ON game_player.deck_id = deck.id
                LEFT JOIN commanders_decks
                ON commanders_decks.deck_id = deck.id
                LEFT JOIN commander
                ON commander.id = commanders_decks.commander_id
                LEFT JOIN 
                    (SELECT COUNT(game.id) c 
                        FROM game_player 
                        LEFT JOIN game ON game.id = game_player.game_id 
                        LEFT JOIN game_format ON game_format.id = game.format_id
                        WHERE winning_player = 1
                        AND game_format.name IN ("CEDH", "EDH")
                    ) `total_games` ON 1 = 1            
                    
                WHERE commander.id IS NOT NULL                    
                GROUP BY commander.id, commander.name
                HAVING win_ratio > 0
                ORDER BY win_ratio DESC
            ';
        $sql .= ($limit == -1) ? "" : (" LIMIT " . $limit);
        
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
