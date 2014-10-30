<?php
/**
 * @package igmt.model
 * @author Raphaël BIDEAU
 * @version 1.0
 */


/**
 * Class that represents a link between to Element
 */
class Link{

	///////////////
	//CONSTANTS //
	///////////////

	/**
	 * Constant for a link that represents a need relation :
	 * from is allowing to to
	 */
	const typeRequire = "REQUIRE";

	/**
	 * Constant for a link that represents a extends relation :
	 * from is extended by to
	 */
	const typeExtends = "EXTENDS";
	
	///////////////
	//ATTRIBUTS //
	///////////////
	
	/**
	 * Id of this link
	 * Start at 1
	 * @var int
	 */
	private $id;

	/**
	 * Element on the left side of this link
	 * Not null
	 * @var Element
	 */
	private $from;

	/**
	 * Element on the right side of this link
	 * Not null
	 * @var Element
	 */
	private $to;

	/**
	 * Optionnal conditions needed for this link
	 * The string may be empty
	 * @var string
	 */
	private $conditions;

	/**
	 * The type of this link
	 * Must be a not empty string in {typeRequire, typeExtends}
	 * @var string
	 */
	private $type;

	//////////////////
	//CONSTRUCTEUR //
	//////////////////

	/**
	 * Initialise a link with id, from and to and a type. Conditions is set to ""
	 * @param int  $id   	Must be a integer greater than 0
	 * @param Element $from 
	 * @param Element $to   
	 * @param string $type Must be a not empty string in {typeRequire, typeExtends}
	 * @throws InvalidArgumentException
	 */
	public function __construct($id, Element $from, Element $to, $type){
		if($id == null || !is_int($id) || $id <= 0)
			throw new InvalidArgumentException ("id must be an integer greater thant 0, input was: ".$id);
		else if($from == null)
			throw new InvalidArgumentException ("from canno't be null");
		else if($to == null)
			throw new InvalidArgumentException ("to canno't be null");
		else if($type == null || ($type != Link::typeExtends && $type != Link::typeRequire))
			throw new InvalidArgumentException ("type must be ".Link::typeExtends." or ".Link::typeRequire);

		$this->id = $id;
		$this->from = $from;
		$this->to = $to;
		$this->conditions = "";
		$this->type = $type;
	}

	//////////////////////
	// GETTER & SETTER //
	//////////////////////

	/**
	 * @return int
	 */
	public function getId(){
		return $this->id ;
	}
	/**
	 * @param int $id Must be a integer greater than 0
	 * @throws InvalidArgumentException
	 */
	public function setId($id){
		if($id == null || !is_int($id) || $id <= 0)
			throw new InvalidArgumentException ("id must be an integer greater thant 0, input was: ".$id);
		
		$this->id = $id ;
	}

	/**
	 * @return Element
	 */
	public function getFrom(){
		return $this->from ;
	}
	/**
	 * @param Element $from
	 * @throws InvalidArgumentException
	 */
	public function setFrom(Element $from){
		if($from == null)
			throw new InvalidArgumentException ("from canno't be null");
		
		$this->from = $from ;
	}

	/**
	 * @return Element
	 */
	public function getTo(){
		return $this->to ;
	}
	/**
	 * @param Element $to
	 * @throws InvalidArgumentException
	 */
	public function setTo(Element $to){
		if($to == null)
			throw new InvalidArgumentException ("to canno't be null");
		
		$this->to = $to ;
	}

	/**
	 * @return string
	 */
	public function getCondition(){
		return $this->conditions ;
	}
	/**
	 * @param string $conditions
	 */
	public function setCondition($conditions){
		if($condition != null && is_string($condition))
			$this->conditions = $conditions ;
	}

	/**
	 * @return string
	 */
	public function getType(){
		return $this->type ;
	}
	/**
	 * @param string $type Must be a not empty string in {typeRequire, typeExtends}
	 * @throws InvalidArgumentException
	 */
	public function setType($type){
		if($type == null || ($type != Link::typeExtends && $type != Link::typeRequire))
			throw new InvalidArgumentException ("type must be ".Link::typeExtends." or".Link::typeRequire);
		
		$this->type = $type ;
	}

	//////////////
	// METHODS //
	//////////////
	
	/**
	 * Transforme a REQUIRE link into an array containing the two 'allow' and 'need' InnerLink
	 * @return array
	 */
	public function toInnerLink(){
		$result = array();

		if($this->type == Link::typeRequire){
			$allow = new Allow($this->id, $this->to);
			if($this->hasConditions())
				$allow->setCondition($this->conditions);
			$result['allow'] = $allow;

			$need = new Need($this->id, $this->from);
			if($this->hasConditions())
				$need->setCondition($this->conditions);
			$result['need'] = $need ;
		}

		return $result;
	}

	/**
	 * Determine wether this link has a condition
	 * @return boolean
	 */
	public function hasConditions(){
		return strlen($this->conditions) > 0;
	}

	/**
	 * Return a string representing this Link
	 * Example : 
	 * @return string 
	 */
	public function __toString(){
		$string = "Link: id=".$this->id." Type: ".$this->type." From: ".$this->from->getName()." To: ".$this->to->getName();
		if(strlen($this->conditions) > 0 )
			$string .= " Conditions: ".$this->conditions;

		return $string;
	}

}
?>