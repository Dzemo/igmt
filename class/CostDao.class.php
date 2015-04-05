<?php

/**
 * File about CostDao class
 * @author Flavio Deroo and Raphaël BIDEAU
 * @package Dao
 */

/**
 * Class interacting with all the Cost of the game
 */
class CostDao extends Dao {
    ////////////
    //PUBLIC //
    ////////////

    /**
     * Return an array containing all cost indexed by their id
     * @param  array $allElement
     * @return array
     */
    public static function getAll($allElement) {
        return self::getByQuery("SELECT * FROM igmt_cost", $allElement);
    }

    /**
     * Return the cost of the specified id or null if this cost doesn't exist
     * @param  int $id
     * @param  array $allElement
     * @return Cost       
     */
    public static function getById($id, $allElement) {
        $arrayResult = self::getByQuery("SELECT * FROM igmt_cost WHERE id = ?", $allElement, [$id]);
        if (array_key_exists($id, $arrayResult)) {
            return $arrayResult[$id];
        } else {
            return null;
        }
    }
    
    /**
     * Return all the cost associateed for this element
     * @param  Element $element
     * @param  array $allElement
     * @return Cost       
     */
    public static function getForElement($element, $allElement) {
        return self::getByQuery("SELECT * FROM igmt_cost WHERE element_from_id = ?", $allElement, [$element->getId()]);
    }

    /**
     * Insert a new Cost in the database
     * Return the cost or null if there was an error
     * @param  Cost $cost 
     * @return Cost           
     */
    public static function insert(Cost $cost) {
        $stmt = parent::getConnexion()->prepare("INSERT INTO igmt_cost (element_from_id, element_to_pay_id, scaling_id, base_quantity) VALUES (?,?,?,?)");
        $result = $stmt->execute(array(
            $cost->getElementFrom() != null ? $cost->getElementFrom()->getId() : null,
            $cost->getElementToPay() != null ? $cost->getElementToPay()->getId() : null,
            $cost->getScaling() != null ? $cost->getScaling()->getId() : null,
            $cost->getBaseQuantity()
        ));

        if ($result) {
            $cost->setId(parent::getConnexion()->lastInsertId());
            return $cost;
        } else
            return null;
    }
    
    /**
     * Insert all cost of an element
     * @param Element $element
     */
    public static function insertFromElement(Element $element){
        
        foreach($element->getCosts() as $cost){
            $cost = self::insert($cost);
        }
    }


    /**
     * Delete a cost
     * @param  int $elementId
     */
    public static function deleteFromElementId($elementId) {
        $stmtDelete = parent::getConnexion()->prepare("DELETE FROM igmt_cost WHERE element_from_id = ?");
        $stmtDelete->execute(array($elementId));
    }

    /////////////
    //PRIVATE //
    /////////////

    /**
     * Execute a query with optionnal parameter and return an array of cost indexed by their id
     * @param  string $query 
     * @param  array $allElement
     * @param  array $param 
     * @return array        
     */
    private static function getByQuery($query, $allElement, $param = null) {
        $stmt = parent::getConnexion()->prepare($query);
        if ($stmt->execute($param) && $stmt->rowCount() > 0) {

            $arrayResultat = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $cost = new Cost($row['id'], null, null, CostScalingDao::getById($row['scaling_id']), $row['base_quantity']);

                $arrayResultat[$cost->getId()] = $cost;
                
                if($allElement != null && array_key_exists($row['element_from_id'], $allElement)){
                    $cost->setElementFrom($allElement[$row['element_from_id']]);
                }
                if($allElement != null && array_key_exists($row['element_to_pay_id'], $allElement)){
                    $cost->setElementToPay($allElement[$row['element_to_pay_id']]);
                }
            }
            return $arrayResultat;
        } else {
            return array();
        }
    }

}

?>