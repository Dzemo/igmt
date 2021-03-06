<?php
/**
 * @package igmt.model
 * @author Raphaël BIDEAU
 * @version 1.0
 */
/**
 * Inner class of Elemet.
 * Need link between an Element and the target Element.
 */
class Need extends InnerLink{
	/////////////////
	//CONSTRUCTOR //
	/////////////////
	
	/**
	 * Initilize a innerLink with the link_id and the target Element
	 * @param int     $link_id Integer greater than 0 or null for new link
	 * @param Element $target  Not null
	 * @throws InvalidArgumentException
	 */
	public function __construct($link_id, Element $target){
		parent::__construct($link_id, $target);
	}
}
?>