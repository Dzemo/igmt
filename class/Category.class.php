<?php

/**
 * @package igmt.model
 * @author Flavio Deroo
 * @version 1.0
 */

/**
 * Class that represents a Category of an Element. 
 */
class Category implements JsonSerializable {

    /**
     * Brainstorming Tag :
     * -hasCost : cet éléments à un coût X d'un ou plusieurs autres élements pour être produit/construit/developper
     * -isProducer: cet éléments créer/produit une quantité X d'un ou plusieurs autres éléments à chaque tick
     * -hasSustainCost: cet éléments consome/detruit/utilise une quantité X d'un ou plusieurs autres éléments à chaque tick
     * 
     * -hisModifier: cette élément modifier de Y la quantité d'un tag hasCost, isProducer, hasSustainCost X
     */
    ////////////////
    // ATTRIBUTS //
    ////////////////
    /**
     * Id of this Category
     * null for new category or greater than 0
     * @var int
     */
    private $id;

    /**
     * Name of this Category.
     * Max length 40. Not null.
     * @var string
     */
    private $name;

    /**
     * Color of the category. I must be an hexadecimal code.
     * It will define the css property background-color of the element.
     * Max length 7. 
     * @var string
     */
    private $color;

    /**
     * @var string
     */
    private $description;

    //////////////////
    // CONSTRUCTOR //
    //////////////////
    /**
     * Initialise this Category 
     * 
     * @param string 	$name 	no empty string. max lenght 40
     * @param string    $color  hexadecimal code. max lenght 7
     * @throws InvalidArgumentException
     */
    public function __construct($id, $name, $color) {
        if ($id != null && $id <= 0)
            throw new InvalidArgumentException("id can't be less than 1");
        else if ($name == null || !is_string($name) || strlen($name) == 0)
            throw new InvalidArgumentException("name must be a none empty string");
        else if (strlen($name) > 40)
            throw new InvalidArgumentException("name length must be less than 40");
        else if ($color == null)
            throw new InvalidArgumentException("color can't be empty");
        else if (strlen($color) > 7)
            throw new InvalidArgumentException("color lenght must be less than 7");
        $this->id = $id;
        $this->name = $name;
        $this->color = $color;
        $this->description = "";
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
    public function getColor() {
        return $this->color;
    }

    /**
     * @param string $color None empty string, max length 7
     * @throws InvalidArgumentException
     */
    public function setColor($color) {
        if ($color == null || !is_string($color) || strlen($color) == 0)
            throw new InvalidArgumentException("color must be a none empty string");
        else if (strlen($color) > 7)
            throw new InvalidArgumentException("color length must be less than 7");
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description None empty string
     * @throws InvalidArgumentException
     */
    public function setDescription($description) {
        if ($description == null || !is_string($description))
            throw new InvalidArgumentException("description must be a none empty string");
        $this->description = $description;
    }

    ////////////////////
    // OTHER METHODS //
    ////////////////////

    /**
     * return the css class name of this category
     * @return string
     */
    public function cssClassName() {
        return $this->trimedName() . "-" . $this->id;
    }

    /**
     * Return the css class requiremenet for an html file with single quote
     * @return string
     */
    public function cssHTML() {
        return " class='" . $this->cssClassName() . "' ";
    }

    /**
     * Return the css definition for the class of this category
     * @return string 
     */
    public function cssClass() {
        $cssClass = "." . $this->cssClassName() . "{\n";
        $cssClass.= "   color:" . $this->color . "\n";
        $cssClass.= "}";

        return $cssClass;
    }

    /**
     * String representing this category
     * Example: 
     * @return string 
     */
    public function __toString() {
        $string = "Category: <br>" .
                "&emsp;Id: " . $this->id . "<br>" .
                "&emsp;Name: " . $this->name . "<br>" .
                "&emsp;Description: " . $this->description . "<br>" .
                "&emsp;Color: <span style='color:" . $this->color . "'>" . $this->color . "</span><br>";
        return $string;
    }

    /**
     * Return the name of this category without any space (to use in html identifier)
     * @return string
     */
    private function trimedName() {
        return preg_replace('/( *)/', '', $this->name);
    }

    /**
     * Serialize cette class en un array acceptable par json_encode
     * @return array 
     */
    public function jsonSerialize() {
        return [
            'name' => $this->name,
            'color' => $this->color,
            'description' => $this->description,
        ];
    }

}

?>