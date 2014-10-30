<?php
/**
 * @package igmt.model
 * @author Flavio Deroo
 * @version 1.0
 */
/**
 * Class that represents a Category of an Element. 
 */
class Category{
	
	////////////////
	// ATTRIBUTS //
	////////////////
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
	public function __construct($name, $color){
		if($name == null || !is_string($name) || strlen($name) == 0)
			throw new InvalidArgumentException ("name must be a none empty string");
		else if(strlen($name) > 40)
			throw new InvalidArgumentException ("name length must be less than 40");
		else if($color == null)
			throw new InvalidArgumentException ("color can't be empty");
		else if(strlen($color) > 7)
			throw new InvalidArgumentException ("color lenght must be less than 7");
		$this->name = $name;
		$this->color = $color;
	}
	////////////////////////
	// GETTER and SETTER //
	////////////////////////
	/**
	 * @return string
	 */
	public function getName(){
		return $this->name ;
	}
	/**
	 * @param string $name None empty string, max length 40
	 * @throws InvalidArgumentException
	 */
	public function setName($name){
		if($name == null || !is_string($name) || strlen($name) == 0)
			throw new InvalidArgumentException ("name must be a none empty string");
		else if(strlen($name) > 40)
			throw new InvalidArgumentException ("name length must be less thant 40");
		$this->name = $name ;
	}
	/**
	 * @return string
	 */
	public function getColor(){
		return $this->color ;
	}
	/**
	 * @param string $color None empty string, max length 7
	 * @throws InvalidArgumentException
	 */
	public function setColor($color){
		if($color == null || !is_string($color) || strlen($color) == 0)
			throw new InvalidArgumentException ("color must be a none empty string");
		else if(strlen($color) > 7)
			throw new InvalidArgumentException ("color length must be less thant 40");
		$this->color = $color ;
	}
	////////////////////
	// OTHER METHODS //
	////////////////////
	
	/**
	 * Return the name of this category without any space (to use in html identifier)
	 * @return string
	 */
	public function trimedName(){
		return preg_replace('/( *)/', '', $this->name);
	}

	/**
	 * Echo the css style for this category
	 */
	public function cssStyle(){
		echo " style='color:".$this->color."' ";
	}
	
	/**
	 * String representing this category
	 * Example: 
	 * @return string 
	 */
	public function __toString(){
		$string = "Category: Name: ".$this->name." Color: ".$this->color;
		return $string;
	}
}
?>