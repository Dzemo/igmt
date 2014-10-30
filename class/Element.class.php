<?php
/**
 * @package igmt.model
 * @author Raphaël BIDEAU
 * @version 1.0
 */


/**
 * Class that represents an Element of the game. 
 */
class Element{
	
	////////////////
	// ATTRIBUTS //
	////////////////

	/**
	 * Name of this element.
	 * Max length 40. Not null.
	 * @var string
	 */
	private $name;

	/**
	 * Name of the category of this element. For example: building, resource,...
	 * Must be use for naming the css classe for this element.
	 * Max length 20. 
	 * @var string
	 */
	private $category;

	/**
	 * Description of this element. Texte.
	 * Could be null.
	 * @var Category
	 */
	private $description;

	/**
	 * Liste of Tag for this element.
	 * @var array Array of string
	 */
	private $tags;

	/**
	 * Liste of needd elements by this element.
	 * @var array Array of Need
	 */
	private $need;

	/**
	 * Liste of allowed elements by this element.
	 * @var array Array of Allow
	 */
	private $allow;

	/**
	 * Element extended by this element, may be null
	 * @var Element
	 */
	private $extends;

	/**
	 * List of Elements that extends this element
	 * @var array
	 */
	private $extendedBy;

	//////////////////
	// CONSTRUCTOR //
	//////////////////

	/**
	 * Initialise this Element description as emtpy string, tag, allow, need and extendedBy as empty array and extends as null
	 *
	 * @param string 	$name 		None empty string, max length 40
	 * @param Category    $category  Object category expected
	 * @throws InvalidArgumentException
	 */
	public function __construct($name, Category $category){
		if($name == null || !is_string($name) || strlen($name) == 0)
			throw new InvalidArgumentException ("name must be a none empty string");
		else if(strlen($name) > 40)
			throw new InvalidArgumentException ("name length must be less thant 40");
		else if($category == null)
			throw new InvalidArgumentException ("category can't be empty");

		$this->name = $name;
		$this->category = $category;
		$this->description = '';
		$this->tags = array();
		$this->need = array();
		$this->allow = array();
		$this->extends = null;
		$this->extendedBy = array();
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
	public function getCategory(){
		return $this->category ;
	}
	/**
	 * @param string $category None empty string, max length 20
	 * @throws InvalidArgumentException
	 */
	public function setCategory(Category $category){
		if($category == null)
			throw new InvalidArgumentException ("category must be a none empty string");
		else if(strlen($category) > 20)
			throw new InvalidArgumentException ("category length must be less thant 20");

		$this->category = $category ;
	}

	/**
	 * @return string
	 */
	public function getDescription(){
		return $this->description ;
	}
	/**
	 * @param string $description
	 */
	public function setDescription($description){
		if($description != null && is_string($description))
			$this->description = $description ;
	}

	/**
	 * @return array
	 */
	public function getTags(){
		return $this->tags ;
	}
	/**
	 * @param array $tags
	 */
	public function setTags($tags){
		if(is_array($tags))
			$this->tags = $tags ;
	}
	
	/**
	 * @return array
	 */
	public function getNeed(){
		return $this->need ;
	}
	/**
	 * @param array $need
	 */
	public function setNeed($need){
		if(is_array($need))
			$this->need = $need ;
	}
	
	/**
	 * @return array
	 */
	public function getAllow(){
		return $this->allow ;
	}
	/**
	 * @param array $allow
	 */
	public function setAllow($allow){
		if(is_array($allow))
			$this->allow = $allow ;
	}

	/**
	 * @return Element
	 */
	public function getExtends(){
		return $this->extends ;
	}
	/**
	 * @param Element $extends
	 */
	public function setExtends(Element $extends){
		$this->extends = $extends ;
	}

	/**
	 * @return array
	 */
	public function getExtendedBy(){
		return $this->extendedBy ;
	}
	/**
	 * @param array $extendedBy
	 */
	public function setExtendedBy($extendedBy){
		if(is_array($extendedBy))
			$this->extendedBy = $extendedBy ;
	}

	////////////////////
	// OTHER METHODS //
	////////////////////
	
	/**
	 * Return the name of this element without any space (to use in html identifier)
	 * @return string
	 */
	public function trimedName(){
		return preg_replace('/( *)/', '', $this->name);
	}

	/**
	 * Determine wether this element has a description
	 * @return boolean
	 */
	public function hasDescription(){
		return strlen($this->description) > 0;
	}

	/**
	 * Add a tag to the tags. Avoid null and empty string
	 * @param string $tag 
	 */
	public function addTag($tag){
		if($tag !== null && strlen($tag) > 0)
			$this->tags[] = $tag;
	}

	/**
	 * Determine wether this element has some tags
	 * @return boolean
	 */
	public function hasTags(){
		return count($this->tags) > 0;
	}

	/**
	 * Determine wether this Element extends another Element
	 * @return boolean true if this element extends another Element, false otherwise
	 */
	public function isExtending(){
		return $this->extends != null;
	}

	/**
	 * Add an extension element to this element
	 * The extension must be a different element and not null
	 * @param Element $extenstion [description]
	 * @throws InvalidArgumentException
	 */
	public function addExtension(Element $extenstion){
		if($extension == null || $extension->getName() == $this->name)
			throw new InvalidArgumentException ("The extention element canno't be null or this element");

		$this->extendedBy[] = $extension;
	}

	/**
	 * Determine wether this element has some extension
	 * @return boolean
	 */
	public function hasExtension(){
		return count($this->extendedBy) > 0;
	}

	/**
	 * Add a allow link to this element
	 * @param Allow $allow 
	 * @throws InvalidArgumentException
	 */
	public function addAllow(Allow $allow){
		if($allow == null)
			throw new InvalidArgumentException ("allow canno't be null");
		else if($allow->getTarget()->getName() == $this->name)
			throw new InvalidArgumentException ("the allow target canno't be this element");

		$this->allow[] = $allow;
	}

	/**
	 * Determine wether this element allow some other Elements
	 * @return boolean
	 */
	public function hasAllowing(){
		return count($this->allow) > 0;
	}

	/**
	 * Add a need link to this element
	 * @param Need $need 
	 * @throws InvalidArgumentException
	 */
	public function addNeed(Need $need){
		if($need == null)
			throw new InvalidArgumentException ("need canno't be null");
		else if($need->getTarget()->getName() == $this->name)
			throw new InvalidArgumentException ("the need target canno't be this element");

		$this->need[] = $need;
	}

	/**
	 * Determine wether this element need some other Elements
	 * @return boolean
	 */
	public function hasNeed(){
		return count($this->need) > 0;
	}

	/**
	 * String representing this element
	 * Example: 
	 * @return string 
	 */
	public function __toString(){
		$string = "Element: Name: ".$this->name." Category: ".$this->category;

		if($this->hasDescription())
			$string .= " Description: ".$this->description;

		if($this->hasTags()){
			$stringTag = "";
			foreach ($this->tags as $tag) {
				if(strlen($stringTag) > 0)
					$stringTag .= ", ";
				$stringTag .= $tag;
			}
			$string .= " Tags: [".$stringTag."]";
		}

		if($this->hasNeed()){
			$stringNeed = "";
			foreach ($this->need as $need) {
				if(strlen($stringNeed) > 0)
					$stringNeed .= ", ";
				$stringNeed .= $need->getTarget()->getName();
			}
			$string .= " Need: [".$stringNeed."]";
		}

		if($this->hasAllowing()){
			$stringAllow = "";
			foreach ($this->allow as $allowing) {
				if(strlen($stringAllow) > 0)
					$stringAllow .= ", ";
				$stringAllow .= $allowing->getTarget()->getName();
			}
			$string .= " Allow: [".$stringAllow."]";
		}

		if($this->isExtending())
			$string .= " Extending: ".$this->extends->getName();

		if($this->hasExtension()){
			$stringExtension = "";
			foreach ($this->extendedBy as $extension) {
				if(strlen($stringExtension) > 0)
					$stringExtension .= ", ";
				$stringExtension .= $extension->getName();
			}
			$string .= "Extension: [".$stringExtension."]";
		}

		return $string;
	}
}
?>