<?php

/**
 * @package igmt.model
 * @author Raphaël BIDEAU
 * @version 1.0
 */

/**
 * Class that represents an Element of the game. 
 */
class Element implements JsonSerializable {

    ////////////////
    // ATTRIBUTS //
    ////////////////
    /**
     * Id of the element
     * Null for new element or greater than 0
     * @var int
     */
    private $id;

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
     * @var Extend
     */
    private $extend;

    /**
     * List of Elements that extend this element
     * @var array Array of ExtendedBy
     */
    private $extendedBy;

    /**
     * List of Elements that this element can evolve into
     * @var array Array of Evolve
     */
    private $evolve;

    /**
     * The element that element have evolve from
     * @var Regress
     */
    private $regress;
    
    /**
     * Array of cost for this element
     * @var array 
     */
    private $costs;

    //////////////////
    // CONSTRUCTOR //
    //////////////////
    /**
     * Initialise this Element description as emtpy string, tag, allow, need and extendedBy as empty array and extend as null
     *
     * @param int 	$id
     * @param string $name None empty string, max length 40
     * @param Category    $category  Object category expected
     * @throws InvalidArgumentException
     */
    public function __construct($id, $name, Category $category) {

        if ($id != null && $id <= 0)
            throw new InvalidArgumentException("id can't be less than 1");
        else if ($name == null || !is_string($name) || strlen($name) == 0)
            throw new InvalidArgumentException("name must be a none empty string");
        else if (strlen($name) > 40)
            throw new InvalidArgumentException("name length must be less thant 40");
        else if ($category == null)
            throw new InvalidArgumentException("category can't be empty");

        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
        $this->description = '';
        $this->tags = array();
        $this->need = array();
        $this->allow = array();
        $this->extend = null;
        $this->extendedBy = array();
        $this->evolve = array();
        $this->regres = null;
        $this->cost = array();
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
            throw new InvalidArgumentException("name length must be less thant 40");
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @param string $category None empty string, max length 20
     * @throws InvalidArgumentException
     */
    public function setCategory(Category $category) {
        if ($category == null)
            throw new InvalidArgumentException("category must be a none empty string");
        else if (strlen($category) > 20)
            throw new InvalidArgumentException("category length must be less thant 20");
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) {
        if ($description != null && is_string($description))
            $this->description = $description;
    }

    /**
     * @return array
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags($tags) {
        if (is_array($tags))
            $this->tags = $tags;
    }

    /**
     * @return array
     */
    public function getNeed() {
        return $this->need;
    }

    /**
     * @param array $need
     */
    public function setNeed($need) {
        if (is_array($need))
            $this->need = $need;
    }

    /**
     * @return array
     */
    public function getAllow() {
        return $this->allow;
    }

    /**
     * @param array $allow
     */
    public function setAllow($allow) {
        if (is_array($allow))
            $this->allow = $allow;
    }

    /**
     * @return Extend
     */
    public function getExtend() {
        return $this->extend;
    }

    /**
     * @param Extend $extend
     * @throws InvalidArgumentException
     */
    public function setExtend(Extend $extend) {
        if ($extend == null || $extend->getTarget()->getId() == $this->id)
            throw new InvalidArgumentException("The extend element can't be null or this element");

        $this->extend = $extend;
    }

    /**
     * @return array
     */
    public function getExtendedBy() {
        return $this->extendedBy;
    }

    /**
     * @param array $extendedBy
     */
    public function setExtendedBy($extendedBy) {
        if (is_array($extendedBy))
            $this->extendedBy = $extendedBy;
    }

    /**
     * @return Regress
     */
    public function getRegress() {
        return $this->regress;
    }

    /**
     * @param Regress $regress
     * @throws InvalidArgumentException
     */
    public function setRegress(Regress $regress) {
        if ($regress == null || $regress->getTarget()->getId() == $this->id)
            throw new InvalidArgumentException("The regress element can't be null or this element");

        $this->regress = $regress;
    }

    /**
     * @return array
     */
    public function getEvolve() {
        return $this->evolve;
    }

    /**
     * @param array $evolve
     */
    public function setEvolve($evolve) {
        if (is_array($evolve))
            $this->evolve = $evolve;
    }
    
    /**
     * @return array
     */
    public function getCosts() {
        return $this->costs;
    }

    /**
     * @param array $costs
     */
    public function setCosts($costs) {
        if (is_array($costs))
            $this->costs = $costs;
    }

    ////////////////////
    // OTHER METHODS //
    ////////////////////

    /**
     * Return all Link object corresponding with the InnerLink of this Element
     * @return array Array of Link indexed by link id
     */
    public function getLinksArray() {

        $links = array(); //Array of links
        //Need
        if ($this->hasNeed()) {
            foreach ($this->need as $linkNeed) {
                $link = new Link(
                        $linkNeed->getLinkId(), $linkNeed->getTarget()->getId(), $this->id, Link::typeRequire
                );
                if ($linkNeed->hasConditions())
                    $link->setConditions($linkNeed->getConditions());

                $links[] = $link;
            }
        }

        //Allow
        if ($this->hasAllowing()) {
            foreach ($this->allow as $linkAllow) {
                $link = new Link(
                        $linkAllow->getLinkId(), $this->id, $linkAllow->getTarget()->getId(), Link::typeRequire
                );
                if ($linkAllow->hasConditions())
                    $link->setConditions($linkAllow->getConditions());

                $links[] = $link;
            }
        }

        //Extend
        if ($this->isExtending()) {
            $link = new Link(
                    $this->extend->getLinkId(), $this->id, $this->extend->getTarget()->getId(), Link::typeExtend
            );
            if ($this->extend->hasConditions())
                $link->setConditions($this->extend->getConditions());

            $links[] = $link;
        }

        //ExtendedBy
        if ($this->hasExtension()) {
            foreach ($this->extendedBy as $linkExtendedBy) {
                $link = new Link(
                        $linkExtendedBy->getLinkId(), $linkExtendedBy->getTarget()->getId(), $this->id, Link::typeExtend
                );
                if ($linkExtendedBy->hasConditions())
                    $link->setConditions($linkExtendedBy->getConditions());

                $links[] = $link;
            }
        }

        //Regress
        if ($this->isEvolveing()) {
            $link = new Link(
                    $this->regress->getLinkId(), $this->id, $this->regress->getTarget()->getId(), Link::typeEvolve
            );
            if ($this->regress->hasConditions())
                $link->setConditions($this->regress->getConditions());

            $links[] = $link;
        }

        //Evolve
        if ($this->hasEvolution()) {
            foreach ($this->evolve as $linkEvolve) {
                $link = new Link(
                        $linkEvolve->getLinkId(), $linkEvolve->getTarget()->getId(), $this->id, Link::typeEvolve
                );
                if ($linkEvolve->hasConditions())
                    $link->setConditions($linkEvolve->getConditions());

                $links[] = $link;
            }
        }

        return $links;
    }

    /**
     * Determine wether this element has a description
     * @return boolean
     */
    public function hasDescription() {
        return strlen($this->description) > 0;
    }

    /**
     * Add a tag to the tags. Avoid null and empty string
     * @param string $tag 
     */
    public function addTag($tag) {
        if ($tag !== null && strlen($tag) > 0)
            $this->tags[] = $tag;
    }

    /**
     * Determine wether this element has some tags
     * @return boolean
     */
    public function hasTags() {
        return count($this->tags) > 0;
    }

    /**
     * Return the tags separated by a ;
     * @return string 
     */
    public function getTagsString() {
        $stringTag = "";
        foreach ($this->tags as $tag) {
            if (strlen($stringTag) > 0)
                $stringTag .= ";";
            $stringTag .= $tag;
        }

        return $stringTag;
    }

    /**
     * Determine wether this Element extend another Element
     * @return boolean true if this element extend another Element, false otherwise
     */
    public function isExtending() {
        return $this->extend != null;
    }

    /**
     * Determine wether this Element evolve from another element
     * @return boolean true if this element evolve from another Element, false otherwise
     */
    public function isEvolveing() {
        return $this->regress != null;
    }

    /**
     * Add an extension element to this element
     * The extension must be a different element and not null
     * @param ExtendedBy $extenstion
     * @throws InvalidArgumentException
     */
    public function addExtension(ExtendedBy $extension) {
        if ($extension == null || $extension->getTarget()->getId() == $this->id)
            throw new InvalidArgumentException("The extension element can't be null or this element");
        $this->extendedBy[] = $extension;
    }

    /**
     * Determine wether this element has some extension
     * @return boolean
     */
    public function hasExtension() {
        return count($this->extendedBy) > 0;
    }

    /**
     * Add an extensioevolutionn element to this element
     * The evolution must be a different element and not null
     * @param Evolve $extenstion
     * @throws InvalidArgumentException
     */
    public function addEvolution(Evolve $evolution) {
        if ($evolution == null || $evolution->getTarget()->getId() == $this->id)
            throw new InvalidArgumentException("The evolve element can't be null or this element");
        $this->evolve[] = $evolution;
    }

    /**
     * Determine wether this element has some evolution
     * @return boolean
     */
    public function hasEvolution() {
        return count($this->evolve) > 0;
    }

    /**
     * Add a allow link to this element
     * @param Allow $allow 
     * @throws InvalidArgumentException
     */
    public function addAllow(Allow $allow) {
        if ($allow == null)
            throw new InvalidArgumentException("allow can't be null");
        else if ($allow->getTarget()->getId() == $this->id)
            throw new InvalidArgumentException("the allow target can't be this element");
        $this->allow[] = $allow;
    }

    /**
     * Determine wether this element allow some other Elements
     * @return boolean
     */
    public function hasAllowing() {
        return count($this->allow) > 0;
    }

    /**
     * Add a need link to this element
     * @param Need $need 
     * @throws InvalidArgumentException
     */
    public function addNeed(Need $need) {
        if ($need == null)
            throw new InvalidArgumentException("need can't be null");
        else if ($need->getTarget()->getId() == $this->id)
            throw new InvalidArgumentException("the need target can't be this element");
        $this->need[] = $need;
    }

    /**
     * Determine wether this element need some other Elements
     * @return boolean
     */
    public function hasNeed() {
        return count($this->need) > 0;
    }
    
    /**
     * Determine wether this element costs something
     * @return boolean
     */
    public function hasCost() {
        return count($this->costs) > 0;
    }

    
    
    /**
     * String representing this element
     * Example: 
     * @return string 
     */
    public function __toString() {
        $string = "Element: <br>" .
                "&emsp;Id: " . $this->id . "<br>" .
                "&emsp;Name: " . $this->name . "<br>" .
                "&emsp;Category: " . $this->category . "<br>";
        if ($this->hasDescription())
            $string .= " &emsp;Description: " . $this->description . "<br>";
        if ($this->hasTags()) {
            $stringTag = "";
            foreach ($this->tags as $tag) {
                if (strlen($stringTag) > 0)
                    $stringTag .= ", ";
                $stringTag .= $tag;
            }
            $string .= " &emsp;Tags: [" . $stringTag . "]<br>";
        }
        if ($this->hasNeed()) {
            $stringNeed = "";
            foreach ($this->getNeed() as $need) {
                if (strlen($stringNeed) > 0)
                    $stringNeed .= ", ";
                $stringNeed .= $need->getTarget()->getName();
            }
            $string .= " &emsp;Need: [" . $stringNeed . "]<br>";
        }
        if ($this->hasAllowing()) {
            $stringAllow = "";
            foreach ($this->getAllow() as $allowing) {
                if (strlen($stringAllow) > 0)
                    $stringAllow .= ", ";
                $stringAllow .= $allowing->getTarget()->getName();
            }
            $string .= " &emsp;Allow: [" . $stringAllow . "]<br>";
        }
        if ($this->isExtending())
            $string .= " &emsp;Extending: " . $this->extend->getTarget()->getName() . "<br>";
        if ($this->hasExtension()) {
            $stringExtension = "";
            foreach ($this->getExtendedBy() as $extension) {
                if (strlen($stringExtension) > 0)
                    $stringExtension .= ", ";
                $stringExtension .= $extension->getTarget()->getName();
            }
            $string .= "&emsp;Extension: [" . $stringExtension . "]<br>";
        }
        if ($this->isEvolveing())
            $string .= " &emsp;Evolving from: " . $this->regress->getTarget()->getName() . "<br>";
        if ($this->hasEvolution()) {
            $stringEvolution = "";
            foreach ($this->getEvolve() as $evolution) {
                if (strlen($stringEvolution) > 0)
                    $stringEvolution .= ", ";
                $stringEvolution .= $evolution->getTarget()->getName();
            }
            $string .= "&emsp;Evolution: [" . $stringEvolution . "]<br>";
        }
        if ($this->hasCost()) {
            $stringCost = "";
            foreach ($this->getCosts() as $cost) {
                if (strlen($stringCost) > 0)
                    $stringCost .= ", ";
                $stringCost .= $cost->getElement() != null ? $cost->getElement()->getName() : "Time" . " (".$cost->getBaseQuantity().")";
            }
            $string .= "&emsp;Cost: [" . $stringCost . "]<br>";
        }
        return $string;
    }

    /**
     * Serialize cette class en un array acceptable par json_encode
     * @return array 
     */
    public function jsonSerialize() {
        return [
            'name' => $this->name,
            'category_name' => $this->category->getName(),
            'description' => $this->description,
            'need' => $this->need,
            'allow' => $this->allow,
            'extendedBy' => $this->extendedBy,
            'extends' => $this->extend,
            'evolve' => $this->evolve,
            'regress' => $this->regress,
            'cost' => $this->costs
                /* 'cost' => [{"name":"element","scaling_id":"expo"},{"name":"element","scaling_id":"expo"}],
                  'scaling' => 'none|stacking|level', */
            
        ];
    }

}

?>