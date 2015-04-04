<?php

/**
 * @package igmt.model
 * @author Flavio Deroo
 * @version 1.0
 */

/**
 * Class that represents a Category of an Element. 
 */
class Cost implements JsonSerializable {

    ////////////////
    // ATTRIBUTS //
    ////////////////
    /**
     * Id of this Cost
     * null for new Cost or greater than 0
     * @var int
     */
    private $id;

    /**
     * Element paid with this cost
     * @var Element
     */
    private $elementFrom;

    /**
     * Element use to pay this cost. If null then it's time that must be paid.
     * @var Element
     */
    private $elementToPay;

    /**
     * Scaling of the cost
     * @var CostScaling
     */
    private $scaling;

    /**
     * @var integer
     */
    private $baseQuantity;

    //////////////////
    // CONSTRUCTOR //
    //////////////////
    /**
     * 
     * @param int $id
     * @param Element $elementFrom
     * @param Element $elementToPay
     * @param CostScaling $scaling
     * @param int $baseQuantity
     * @throws InvalidArgumentException
     */
    public function __construct($id, $elementFrom, $elementToPay, $scaling, $baseQuantity) {
        if ($id != null && $id <= 0)
            throw new InvalidArgumentException("id can't be less than 1");
        $this->id = $id;
        $this->elementFrom = $elementFrom;
        $this->elementToPay = $elementToPay;
        $this->scaling = $scaling;
        $this->baseQuantity = $baseQuantity;
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
     * @return Element
     */
    public function getElementToPay() {
        return $this->elementToPay;
    }
   
    /**
     * 
     * @param Element $elementToPay
     */
    public function setElementToPay($elementToPay) {
        $this->elementToPay = $elementToPay;
    }
   
    /**
     * 
     * @param Element $elementFrom
     */
    public function setElementFrom($elementFrom) {
        $this->elementFrom = $elementFrom;
    }

    /**
     * @return Element
     */
    public function getElementFrom() {
        return $this->elementFrom;
    }

    /**
     * @return Scaling
     */
    public function getScaling() {
        return $this->scaling;
    }
    
    /**
     * 
     * @param CostScaling $scaling
     */
    public function setScaling($scaling) {
        $this->scaling = $scaling;
    }

    /**
     * @return int
     */
    public function getDescription() {
        return $this->baseQuantity;
    }
    
    /**
     * @param int $baseQuantity 
     */
    public function setDescription($baseQuantity) {
        $this->baseQuantity = $baseQuantity;
    }

    /**
     * String representing this cost
     * Example: 
     * @return string 
     */
    public function __toString() {
        $string = "Cost: <br>" .
                "&emsp;Id: " . $this->id . "<br>" .
                "&emsp;ElementFrom: " . $this->elementFrom != null ? $this->elementFrom->getName() : "Time". "<br>" .
                "&emsp;ElementToPay: " . $this->elementToPay != null ? $this->elementToPay->getName() : "Time". "<br>" .
                "&emsp;Scaling: " . $this->scaling . "<br>" .
                "&emsp;Base quantity: " . $this->baseQuantity . "<br>";
        return $string;
    }
    
    /**
     * 
     * @return string
     */
    public function jsonSerialize() {
        return [
            'element_name' => $this->elementToPay != null ? $this->elementToPay>getName() : "Time",
            'base_quantity' => $this->baseQuantity,
            'scaling_name' => $this->scaling != null ? $this->scaling->getName() : null,
        ];
    }
}

?>