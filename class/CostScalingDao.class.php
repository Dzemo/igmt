<?php

/**
 * File about CostDao class
 * @author Flavio Deroo and Raphaël BIDEAU
 * @package Dao
 */

/**
 * Class interacting with all the Cost of the game
 */
class CostScalingDao extends Dao {
    ////////////
    //PUBLIC //
    ////////////

    /**
     * Return an array containing all cost scalling indexed by their id
     * @return array
     */
    public static function getAll() {
        return self::getByQuery("SELECT * FROM igmt_cost_scaling");
    }

    /**
     * Return the cost scaling of the specified id or null if this cost scaling doesn't exist
     * @param  int $id
     * @return Cost       
     */
    public static function getById($id) {
        if($id == null)
            return null;
        
        $arrayResult = self::getByQuery("SELECT * FROM igmt_cost_scaling WHERE id = ?", [$id]);
        if (array_key_exists($id, $arrayResult)) {
            return $arrayResult[$id];
        } else {
            return null;
        }
    }

    /////////////
    //PRIVATE //
    /////////////

    /**
     * Execute a query with optionnal parameter and return an array of cost scaling indexed by their id
     * @param  string $query 
     * @param  array $param 
     * @return array        
     */
    private static function getByQuery($query, $param = null) {
        $stmt = parent::getConnexion()->prepare($query);
        if ($stmt->execute($param) && $stmt->rowCount() > 0) {

            $arrayResultat = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $costScaling = new CostScaling($row['id'], $row['name'], $row['formula']);

                $arrayResultat[$costScaling->getId()] = $costScaling;             
            }
            return $arrayResultat;
        } else {
            return array();
        }
    }

}

?>