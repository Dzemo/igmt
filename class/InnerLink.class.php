<?php
/**
 * @package igmt.model
 * @author Raphaël BIDEAU
 * @version 1.0
 */
/**
 * Abstract inner class of Element. Link between the Element and the target.
 * Extends by Need and Allow.
 */
abstract class InnerLink{
	///////////////
	//ATTRIBUTS //
	///////////////
	/**
	 * Id of the Link referenced by this InnerLink
	 * Greater than 0
	 * @var int
	 */
	private $link_id;
	/**
	 * Target Element of this link
	 * Not null
	 * @var Element
	 */
	private $target;
	/**
	 * Conditions of this link. May be emtpy
	 * @var string
	 */
	private $conditions;
	/////////////////
	//CONSTRUCTOR //
	/////////////////
	/**
	 * Initilize a innerLink with the link_id and the target Element
	 * @param int     $link_id Integer greater than 0
	 * @param Element $target  Not null
	 * @throws InvalidArgumentException
	 */
	public function __construct($link_id, Element $target){
		if($link_id == null || !is_int($link_id) || $link_id <= 0)
			throw new InvalidArgumentException ("link_id must be an integer greater thant 0, input was: ".$link_id);
		else if($target == null)
			throw new InvalidArgumentException ("target canno't be null");
		
		$this->link_id = $link_id;
		$this->target = $target;
		$this->conditions = '';
	}
	/////////////////////
	//GETTER & SETTER //
	/////////////////////
	/**
	 * @return int
	 */
	public function getLinkId(){
		return $this->link_id ;
	}
	/**
	 * @param int $link_id Must be a integer greater than 0
	 * @throws InvalidArgumentException
	 */
	public function setLinkId($link_id){
		if($link_id == null || !is_int($link_id) || $link_id <= 0)
			throw new InvalidArgumentException ("link_id must be an integer greater thant 0, input was: ".$link_id);
		
		$this->link_id = $link_id ;
	}
	/**
	 * @return Element
	 */
	public function getTarget(){
		return $this->target ;
	}
	/**
	 * @param Element $target
	 * @throws InvalidArgumentException
	 */
	public function setTarget(Element $target){
		if($target == null)
			throw new InvalidArgumentException ("target canno't be null");
		
		$this->target = $target ;
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
	 * Return the type of the extended InnerLink
	 * @return string
	 */
	public function getType(){
		return get_class($this);
	}
	/////////////
	//METHODS //
	/////////////
	
	/**
	 * Determine wether this link has a conditions
	 * @return boolean
	 */
	public function hasConditions(){
		return strlen($this->conditions) > 0;
	}
	public function __toString(){
		$string = self::getType().": linkId: ".$this->link_id." Target: ".$this->target->getName();
		if(strlen($this->conditions) > 0 )
			$string .= " Conditions: ".$this->conditions;
		return $string;
	}
}