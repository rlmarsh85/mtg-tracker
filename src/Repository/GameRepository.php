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



    private function resolveFormat($formats) : array
    {
        $conn = $this->getEntityManager()->getConnection();
        $ids = [];
        $sql = '
            SELECT game_format.id FROM game_format
        ';
        $where_clause = "";
        if($formats != null && !empty($formats)){

            $where_clause = " WHERE game_format.name IN(";
            $c = count($formats);
            $i = 0;
            while($i < $c){
                $where_clause .= "\"" . $formats[$i] . "\""  
                . (($i == $c - 1) ? "" : ",");
                $i++;
            }

            $where_clause .= ")";



        }

        $stmt = $conn->prepare($sql . $where_clause);
        $stmt->execute();    
        $results = $stmt->fetchAllAssociative();

        foreach($results as $result){
            $ids[] = $result['id'];
        }        

        return $ids;
        
    }

    private function constructFormatIds($ids) : string
    {
        $str = "";
        $c = count($ids);
        $i = 0;
        while($i < $c){
            $str .= $ids[$i] 
            . (($i == $c - 1) ? "" : ",");
            $i++;
        }        

        return $str;
    }

    /**
     * Gets each players win rate, i.e. percent of games won of all games which that player played in
     */
    public function findPlayerRanks($limit=-1, $formats=null): array
    {

        $conn = $this->getEntityManager()->getConnection();

        $format_ids = $this->resolveFormat($formats);

        $sql = '
            SELECT player.id, player.name, COUNT(game_id) `num_games`, SUM(winning_player) `num_wins`,
            ROUND(( SUM(winning_player) / COUNT(game_id) * 100  ),2) `win_ratio`
            FROM player
            LEFT JOIN game_player
            ON game_player.player_id = player.id
            LEFT JOIN game
            ON game.id = game_player.game_id
            WHERE game.format_id IN (' . $this->constructFormatIds($format_ids) . ')
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
    public function findPlayerOverallRanks($limit=-1, $formats=null): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $format_ids = $this->resolveFormat($formats);

        $sql = '
            SELECT 
            player.id, player.name, SUM(winning_player) `num_wins`,
            ROUND(( SUM(winning_player) / total_games.c * 100  ),2) `win_ratio`
            FROM player
            LEFT JOIN game_player
            ON game_player.player_id = player.id
            LEFT JOIN game
            ON game.id = game_player.game_id
            LEFT JOIN 
                (SELECT COUNT(game_player.id) c 
                FROM game_player 
                LEFT JOIN game 
                ON game.id = game_player.game_id 
                WHERE winning_player = 1 
                AND game.format_id IN (' . $this->constructFormatIds($format_ids) . ')
            ) total_games ON 1 = 1

            WHERE game.format_id IN (' . $this->constructFormatIds($format_ids) . ')
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
    public function findDeckRanks($limit=-1, $formats=null): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $format_ids = $this->resolveFormat($formats);

        $sql = '
            SELECT deck.id, deck.name, COUNT(game_id) `num_games`, SUM(winning_player) `num_wins`, 
            ROUND(( SUM(winning_player) / COUNT(game_id) * 100  ),2) `win_ratio`
            FROM deck
            LEFT JOIN game_player
            ON game_player.deck_id = deck.id
            LEFT JOIN game
            ON game.id = game_player.game_id
            WHERE game.format_id IN (' . $this->constructFormatIds($format_ids) . ')
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
    public function findDeckOverallRanks($limit=-1, $formats=null): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $format_ids = $this->resolveFormat($formats);

        $sql = '
            SELECT deck.id, deck.name, COUNT(game_id) `num_games`, SUM(winning_player) `num_wins`, 
            ROUND(( SUM(winning_player) / total_games.c * 100  ),2) `win_ratio`
            FROM deck
            LEFT JOIN game_player
            ON game_player.deck_id = deck.id
            LEFT JOIN game
            ON game.id = game_player.game_id

            LEFT JOIN 
                (SELECT COUNT(game_player.id) c 
                FROM game_player 
                LEFT JOIN game 
                ON game.id = game_player.game_id 
                WHERE winning_player = 1 
                AND game.format_id IN (' . $this->constructFormatIds($format_ids) . ')
            ) total_games ON 1 = 1

            WHERE game.format_id IN (' . $this->constructFormatIds($format_ids) . ')          
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
    public function findColorOverallRanks($limit = -1, $formats=null): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $format_ids = $this->resolveFormat($formats);

        $sql = '
            SELECT deck.name, COUNT(DISTINCT game_player.game_id) `num_games`, SUM(winning_player) `num_wins`, 
            ROUND(( SUM(winning_player) / total_games.c * 100  ),2) `win_ratio`            
            
            FROM 
            (
                SELECT deck.id, 
                IF( GROUP_CONCAT(color.name SEPARATOR  \'/\') = "Blue/Black/Green/Red/White", "5 color", GROUP_CONCAT(color.name SEPARATOR  \'/\') )  `name`
                FROM deck 
                LEFT JOIN decks_colors ON deck.id = decks_colors.deck_id 
                LEFT JOIN color ON color.id = decks_colors.color_id 
                GROUP BY deck.id
            ) `deck`
            LEFT JOIN game_player
            ON game_player.deck_id = deck.id

            LEFT JOIN game
            ON game.id = game_player.game_id

            LEFT JOIN 
                (SELECT COUNT(game_player.id) c 
                FROM game_player 
                LEFT JOIN game 
                ON game.id = game_player.game_id 
                WHERE winning_player = 1 
                AND game.format_id IN (' . $this->constructFormatIds($format_ids) . ')
            ) total_games ON 1 = 1 

            WHERE game.format_id IN (' . $this->constructFormatIds($format_ids) . ')      
            GROUP BY `deck`.name
            HAVING num_wins IS NOT NULL AND num_wins > 0            
            ORDER BY win_ratio DESC
            ';
        $sql .= ($limit == -1) ? "" : (" LIMIT " . $limit);            

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
    public function findColorRanks($formats=null): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $format_ids = $this->resolveFormat($formats);

        $sql = '
            SELECT color.id, color.name, COUNT(game_player.id) `num_games`, SUM(winning_player) `num_wins`, 
            ROUND(( SUM(winning_player) / COUNT(game_player.id) * 100  ),2) `win_ratio`
            FROM deck
            LEFT JOIN game_player
            ON game_player.deck_id = deck.id
            LEFT JOIN decks_colors
            ON decks_colors.deck_id = deck.id
            LEFT JOIN color
            ON color.id = decks_colors.color_id  
            LEFT JOIN game
            ON game.id = game_player.game_id
            WHERE game.format_id IN (' . $this->constructFormatIds($format_ids) . ')    
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
    
    public function findTotalNumberGames($formats=null): int
    {
        $conn = $this->getEntityManager()->getConnection();
        $format_ids = $this->resolveFormat($formats);

        $sql = '
            SELECT COUNT(id) `count`
            FROM game
            WHERE game.format_id IN (' . $this->constructFormatIds($format_ids) . ') 
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $count = intval(($stmt->fetch(\PDO::FETCH_COLUMN)));
        // returns an array of arrays (i.e. a raw data set)
        return $count;
    } 
    
    public function findAverageGameLength($formats=null): float
    {
        $conn = $this->getEntityManager()->getConnection();
        $format_ids = $this->resolveFormat($formats);

        $sql = '
            SELECT AVG(number_turns) `number_turns`
            FROM game
            WHERE game.format_id IN (' . $this->constructFormatIds($format_ids) . ')
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $count = floatval(($stmt->fetch(\PDO::FETCH_COLUMN)));
        // returns an array of arrays (i.e. a raw data set)
        return $count;        
    }

    public function findPercentWonWithSolRing($formats=null): float
    {
        $conn = $this->getEntityManager()->getConnection();
        $format_ids = $this->resolveFormat($formats);

        $sql = '
            SELECT SUM(IF((winning_player + first_or_second_turn_sol_ring) = 2,1,0)) / COUNT(DISTINCT game.id)
            FROM game
            LEFT JOIN game_player
            ON game_player.game_id = game.id
            WHERE game.format_id IN (' . $this->constructFormatIds($format_ids) . ')
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

    public function findMostPopularDecks($limit=-1, $formats=null): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $format_ids = $this->resolveFormat($formats);

        $sql = '
            SELECT deck.id, deck.name, COUNT(game_player.id) `num_plays` FROM 
            game
            LEFT JOIN game_player
            ON game_player.game_id = game.id
            LEFT JOIN deck
            ON deck.id = game_player.deck_id
            WHERE game.format_id IN (' . $this->constructFormatIds($format_ids) . ')
            GROUP BY deck.id
            ORDER BY num_plays DESC        
        ';

        $sql .= ($limit == -1) ? "" : (" LIMIT " . $limit);
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();

    }

    public function findMostPopularCommanders($limit=-1) : array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT commander.id, commander.name, COUNT(game_player.id) `num_plays` 
                FROM game
                LEFT JOIN game_player
                ON game_player.game_id = game.id
                LEFT JOIN deck
                ON deck.id = game_player.deck_id
                LEFT JOIN commanders_decks
                ON commanders_decks.deck_id = deck.id
                LEFT JOIN commander
                ON commander.id = commanders_decks.commander_id
                WHERE game.format_id IN (63,64)
                GROUP BY commander.id
                ORDER BY num_plays DESC       
        ';

        $sql .= ($limit == -1) ? "" : (" LIMIT " . $limit);
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();        
    }


    public function findMostPopularColors($formats=null): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $format_ids = $this->resolveFormat($formats);

        $sql = '
            SELECT color.id, color.name, COUNT(game_player.id) `num_plays` 
                FROM game
                LEFT JOIN game_player
                ON game_player.game_id = game.id
                LEFT JOIN deck
                ON deck.id = game_player.deck_id
                LEFT JOIN decks_colors
                ON decks_colors.deck_id = deck.id
                LEFT JOIN color
                ON color.id = decks_colors.color_id
                WHERE game.format_id IN (' . $this->constructFormatIds($format_ids) . ')
                GROUP BY color.id
                ORDER BY num_plays DESC      
        ';
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();

    }   
    
    public function findMostRampedPlayers($formats=null): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $format_ids = $this->resolveFormat($formats);

        $sql = '
            SELECT player.id, player.name, SUM(game_player.first_or_second_turn_sol_ring) `num_ramps`
            FROM game
            LEFT JOIN game_player
            ON game.id = game_player.game_id
            LEFT JOIN player
            ON player.id = game_player.player_id
            WHERE game.format_id IN (' . $this->constructFormatIds($format_ids) . ')
            GROUP BY player.id
            ORDER BY num_ramps DESC   
        ';
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();

    }      

}
