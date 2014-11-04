<?php
/**
 * @package igmt.model
 * @author Raphaël BIDEAU
 * @version 1.0
 */
/**
 * Inner class of Element.
 * ExtendedBy link between an Element and the target Element.
 */
class ExtendedBy extends InnerLink{
	/////////////////
	//CONSTRUCTOR //
	/////////////////
	
	/**
	 * Initilize a innerLink with the link_id and the target Element
	 * @param int     $link_id Integer greater than 0 or null
	 * @param Element $target  Not null
	 * @throws InvalidArgumentException
	 */
	public function __construct($link_id, Element $target){
		parent::__construct($link_id, $target);
	}
}
?>