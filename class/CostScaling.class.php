<?php

/**
 * @package igmt.model
 * @author Flavio Deroo
 * @version 1.0
 */

/**
 * Class that represents a Category of an Element. 
 */
class CostScaling implements JsonSerializable {

    ////////////////
    // ATTRIBUTS //
    ////////////////
    /**
     * Id of this CostScaling
     * null for new Cost or greater than 0
     * @var int
     */
    private $id;

    /**
     * Name of this scaling
     * @var String
     */
    private $name;

    /**
     * @var String
     */
    private $formula;

    //////////////////
    // CONSTRUCTOR //
    //////////////////
    /**
     * 
     * @param int $id
     * @param String $name
     * @param String $formula
     * @throws InvalidArgumentException
     */
    public function __construct($id, $name, $formula) {
        if ($id != null && $id <= 0)
            throw new InvalidArgumentException("id can't be less than 1");
        else if ($name == null || !is_string($name) || strlen($name) == 0)
            throw new InvalidArgumentException("name must be a none empty string");
        else if (strlen($name) > 40)
            throw new InvalidArgumentException("name length must be less than 40");
         else if ($formula == null || !is_string($formula) || strlen($formula) == 0)
            throw new InvalidArgumentException("formula must be a none empty string");
        else if (strlen($formula) > 40)
            throw new InvalidArgumentException("formula length must be less than 40");
        $this->id = $id;
        $this->name = $name;
        $this->formula = $formula;
    }

    ////////////////////////
    // GETTER and SETTER //
    ////////////////////////
    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     * @throws InvalidArgumentException
     */
    public function setId($id) {
        if ($id <= 0)
            throw new InvalidArgumentException("id can't be less than 1");
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name None empty string, max length 40
     * @throws InvalidArgumentException
     */
    public function setName($name) {
        if ($name == null || !is_string($name) || strlen($name) == 0)
            throw new InvalidArgumentException("name must be a none empty string");
        else if (strlen($name) > 40)
            throw new InvalidArgumentException("name length must be less than 40");
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getFormula() {
        return $this->formula;
    }

    /**
     * @param string $formula None empty string, max length 40
     * @throws InvalidArgumentException
     */
    public function setFormula($formula) {
        if ($formula == null || !is_string($formula) || strlen($formula) == 0)
            throw new InvalidArgumentException("formula must be a none empty string");
        else if (strlen($formula) > 40)
            throw new InvalidArgumentException("formula length must be less than 40");
        $this->formula = $formula;
    }

    /**
     * String representing this cost
     * Example: 
     * @return string 
     */
    public function __toString() {
        $string = "CostScaling: <br>" .
                "&emsp;Id: " . $this->id . "<br>" .
                "&emsp;Name: " . $this->name . "<br>" .
                "&emsp;Formula: " . $this->formula . "<br>";
        
        return $string;
    }
    
    /**
     * Serialize cette class en un array acceptable par json_encode
     * @return array 
     */
    public function jsonSerialize() {
        return [
            'name' => $this->name,
            'formula' => $this->formula
        ];
    }
}

?>