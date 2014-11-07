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
	 * Constant for a link that represents a from relation :
	 * from is toing to
	 */
	const typeRequire = "REQUIRE";
	/**
	 * Constant for a link that represents a extend relation :
	 * from is extended by to
	 */
	const typeExtend = "EXTEND";

	/**
	 * Constant for a link that represents a evovle relation :
	 * from is evolving into to
	 */
	const typeEvolve = "EVOLVE";
	
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
	 * @var int
	 */
	private $from;
	/**
	 * Element name on the right side of this link
	 * Not null
	 * @var int
	 */
	private $to;
	/**
	 * Optionnal conditions fromed for this link
	 * The string may be empty
	 * @var string
	 */
	private $conditions;
	/**
	 * The type of this link
	 * Must be a not empty string in {typeRequire, typeExtend}
	 * @var string
	 */
	private $type;
	//////////////////
	//CONSTRUCTEUR //
	//////////////////
	/**
	 * Initialise a link with id, from and to and a type. Conditions is set to ""
	 * @param int  $id   	Must be a integer greater than 0 or null for new link
	 * @param int $from 
	 * @param int $to   
	 * @param string $type Must be a not empty string in {typeRequire, typeExtend}
	 * @throws InvalidArgumentException
	 */
	public function __construct($id, $from, $to, $type){
		if($id != null && (!is_int($id) || $id <= 0))
			throw new InvalidArgumentException ("id must be an integer greater than 0 or null, input was: ".$id);
		else if($from == null)
			throw new InvalidArgumentException ("from cannot be null");
		else if($to == null)
			throw new InvalidArgumentException ("to cannot be null");
		else if($type == null || ($type != Link::typeExtend && $type != Link::typeRequire && $type != Link::typeEvolve))
			throw new InvalidArgumentException ("type must be ".Link::typeExtend." or ".Link::typeRequire);
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
		if($id != null && (!is_int($id) || $id <= 0))
			throw new InvalidArgumentException ("id must be an integer greater than 0, input was: ".$id);
		
		$this->id = $id ;
	}
	/**
	 * @return int
	 */
	public function getFrom(){
		return $this->from ;
	}
	/**
	 * @param int $from
	 * @throws InvalidArgumentException
	 */
	public function setFrom($from){
		if($from == null)
			throw new InvalidArgumentException ("from can't be null");
		
		$this->from = $from ;
	}
	/**
	 * @return int
	 */
	public function getTo(){
		return $this->to ;
	}
	/**
	 * @param int $to
	 * @throws InvalidArgumentException
	 */
	public function setTo($to){
		if($to == null)
			throw new InvalidArgumentException ("to can't be null");
		
		$this->to = $to ;
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
	 * @param string $type Must be a not empty string in {typeRequire, typeExtend}
	 * @throws InvalidArgumentException
	 */
	public function setType($type){
		if($type == null || ($type != Link::typeExtend && $type != Link::typeRequire))
			throw new InvalidArgumentException ("type must be ".Link::typeExtend." or".Link::typeRequire);
		
		$this->type = $type ;
	}
	//////////////
	// METHODS //
	//////////////
	
	/**
	 * Transforme a link into an array containing :
	 *  the two 'to' and 'from' InnerLink for REQUIRE link or
	 *  the two 'extend' and 'extendedby' for EXTEND link or
	 *  the two 'evolve' and 'regress' for EVOLVE link
	 *  
	 * @param  array $allElements all Elements
	 * @return array
	 */
	public function toInnerLink($allElements){
		$result = array();
		if($this->type == Link::typeRequire){
			$to = new Allow($this->id, $allElements[$this->to]);
			if($this->hasConditions())
				$to->setConditions($this->conditions);
			$result['allow'] = $to;

			$from = new Need($this->id, $allElements[$this->from]);
			if($this->hasConditions())
				$from->setConditions($this->conditions);
			$result['need'] = $from ;
		}

		else if($this->type == Link::typeExtend){
			$extend = new Extend($this->id, $allElements[$this->to]);
			if($this->hasConditions())
				$extend->setConditions($this->conditions);
			$result['extend'] = $extend;

			$extendedBy = new ExtendedBy($this->id, $allElements[$this->from]);
			if($this->hasConditions())
				$extendedBy->setConditions($this->conditions);
			$result['extendedby'] = $extendedBy ;
		}

		else if($this->type == Link::typeEvolve){
			$regress = new Regress($this->id, $allElements[$this->to]);
			if($this->hasConditions())
				$regress->setConditions($this->conditions);
			$result['regress'] = $regress ;

			$evolve = new Evolve($this->id, $allElements[$this->from]);
			if($this->hasConditions())
				$evolve->setConditions($this->conditions);
			$result['evolve'] = $evolve;
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
		$string = "Link: <br>";
		$string.= "&emsp;Id=".$this->id."<br>";
		$string.= "&emsp;From: ".$this->from."<br>";
		$string.= "&emsp;To: ".$this->to."<br>";
		$string.= "&emsp;Type: ".$this->type."<br>";
		if(strlen($this->conditions) > 0 )
			$string .= "&emsp;Conditions: ".$this->conditions;
		return $string;
	}
}
?>