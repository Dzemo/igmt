<?php
/**
 * @package igmt.model
 * @author Raphaël BIDEAU
 * @version 1.0
 */
/**
 * Class that represents a link between allow Element
 */
class Link{
	///////////////
	//CONSTANTS //
	///////////////
	/**
	 * Constant for a link that represents a need relation :
	 * need is allowing allow allow
	 */
	const typeRequire = "REQUIRE";
	/**
	 * Constant for a link that represents a extends relation :
	 * need is extended by allow
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
	 * Element name on the left side of this link
	 * Not null
	 * @var string
	 */
	private $need;
	/**
	 * Element name on the right side of this link
	 * Not null
	 * @var string
	 */
	private $allow;
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
	 * Initialise a link with id, need and allow and a type. Conditions is set allow ""
	 * @param int  $id   	Must be a integer greater than 0 or null for new link
	 * @param string $need 
	 * @param string $allow   
	 * @param string $type Must be a not empty string in {typeRequire, typeExtends}
	 * @throws InvalidArgumentException
	 */
	public function __construct($id, $need, $allow, $type){
		if($id != null && (!is_int($id) || $id <= 0))
			throw new InvalidArgumentException ("id must be an integer greater than 0 or null, input was: ".$id);
		else if($need == null)
			throw new InvalidArgumentException ("need cannot be null");
		else if($allow == null)
			throw new InvalidArgumentException ("allow cannot be null");
		else if($type == null || ($type != Link::typeExtends && $type != Link::typeRequire))
			throw new InvalidArgumentException ("type must be ".Link::typeExtends." or ".Link::typeRequire);
		$this->id = $id;
		$this->need = $need;
		$this->allow = $allow;
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
		if($id != null && (!is_int($id) || $id <= 0))
			throw new InvalidArgumentException ("id must be an integer greater than 0, input was: ".$id);
		
		$this->id = $id ;
	}
	/**
	 * @return string
	 */
	public function getNeed(){
		return $this->need ;
	}
	/**
	 * @param string $need
	 * @throws InvalidArgumentException
	 */
	public function setNeed($need){
		if($need == null)
			throw new InvalidArgumentException ("need can't be null");
		
		$this->need = $need ;
	}
	/**
	 * @return string
	 */
	public function getAllow(){
		return $this->allow ;
	}
	/**
	 * @param string $allow
	 * @throws InvalidArgumentException
	 */
	public function setAllow($allow){
		if($allow == null)
			throw new InvalidArgumentException ("allow can't be null");
		
		$this->allow = $allow ;
	}
	/**
	 * @return string
	 */
	public function getConditions(){
		return $this->conditions ;
	}
	/**
	 * @param string $conditions
	 */
	public function setConditions($conditions){
		if($conditions != null && is_string($conditions))
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
	 * @param  array $allElements all Elements
	 * @return array
	 */
	public function toInnerLink($allElements){
		$result = array();
		if($this->type == Link::typeRequire){
			$allow = new Allow($this->id, $allElements[$this->allow]);
			if($this->hasConditions())
				$allow->setConditions($this->conditions);
			$result['allow'] = $allow;
			$need = new Need($this->id, $allElements[$this->need]);
			if($this->hasConditions())
				$need->setConditions($this->conditions);
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
		$string = "Link: id=".$this->id." Type: ".$this->type." need: ".$this->need->getName()." allow: ".$this->allow->getName();
		if(strlen($this->conditions) > 0 )
			$string .= " Conditions: ".$this->conditions;
		return $string;
	}
}
?>