<?php
/**
 * File about ElementDao class
 * @author Flavio Deroo
 * @package Dao
 */
/**
 * Class interacting with all the Elements of the game
 */
class ElementDao extends Dao{

	////////////
	//Public //
	////////////
	
	/**
	 * return every Elements in the database
	 * @return array
	 */
	public static function getAll(){
		return self::getAllElements();
	}

	/**
	 * search for an Element by id and return an Element
	 * @param  int $id
	 * @return Element
	 */
	public static function getById($id){
		$result = self::getAllElements();
		if($result != null)
			return $result[$id];
		else
			return null;
	}

	/**
	 * Return all element from a given category
	 * @param  string $categoryName 
	 * @return array
	 */
	public static function getByCategory($categoryName){
		$allElement = self::getAllElements();
		$resultat = array();
		foreach ($allElement as $element) {
			if($element->getCategory()->getName() == $categoryName)
				$resultat[$element->getId()] = $element;
		}

		return $resultat;
	}

	/**
	 * Insert a new Element in the database and all his links
	 * Return the element or null if there was an error
	 * @param  Element $element 
	 * @return Element           
	 */
	public static function insert(Element $element){
		$stmt = parent::getConnexion()->prepare("INSERT INTO igmt_element (name, category, description, tag) VALUES (?,?,?,?)");
		$result = $stmt->execute(array(
						$element->getName(),
						$element->getCategory()->getName(),
						$element->getDescription(),
						$element->getTagsString()
							));

		if($result){
			LinkDao::insertFromElement($element);
			return $element;
		}
		else
			return null;
	}

	/**
	 * Update an Element and his links
	 * @param  Element $element 
	 * @return Element           
	 */
	public static function update(Element $element){
		$stmt = parent::getConnexion()->prepare("UPDATE igmt_element SET name = ?, category = ?, description = ?, tag = ? WHERE id = ?");
		$result = $stmt->execute(array(
						$element->getName(),
						$element->getCategory()->getName(),
						$element->getDescription(),
						$element->getTagsString(),
						$element->getId()
							));

		if($result){
			LinkDao::updateFromElement($element);
			return $element;
		}
		else
			return null;
	}

	/**
	 * Delete an Element and his links
	 * @param  Element $element 
	 */
	public static function delete(Element $element){
		LinkDao::deleteFromElement($element);

		$stmt = parent::getConnexion()->prepare("DELETE FROM igmt_element WHERE id = ?");
		$stmt->execute(array($element->getId()));
	}

	/////////////
	//PRIVATE //
	/////////////

	/**
	 * Execute a query and return the result array with all Element as Element with link
	 * @return array        
	 */
	private static function getAllElements(){
			//If the result doesn't contain all Element there will be error when building links
			$stmt = parent::getConnexion()->prepare("SELECT * FROM igmt_element");
			if($stmt->execute() && $stmt->rowCount() > 0){
				$arrayResultat = array();
				$allCategories = CategoryDao::getAll();


				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					//Info for each element
					$element = new Element($row['id'], $row['name'], $allCategories[$row['category']]);	
					$element->setDescription($row['description']);
					if(!empty($row['tag']))
						$element->setTags(explode(';',$row['tag']));

					$arrayResultat[$element->getId()] = $element;
				}

				//Get all links for db
				if(count($arrayResultat) > 1){
					$links = LinkDao::getAll();
				}elseif(count($arrayResultat) == 1){
					$links = LinkDao::getForElement($element);
				}else{
					$links = array();
				}

				//Set links for each elements
				foreach ($links as $link) {
					if($link->getType() == Link::typeRequire){
						$innerLinks = $link->toInnerLink($arrayResultat);
						$arrayResultat[$link->getTo()]->addNeed($innerLinks['need']);
						$arrayResultat[$link->getFrom()]->addAllow($innerLinks['allow']);
					}
					else if($link->getType() == Link::typeExtend){
						$innerLinks = $link->toInnerLink($arrayResultat);
						$arrayResultat[$link->getTo()]->addExtension($innerLinks['extendedby']);
						$arrayResultat[$link->getFrom()]->setExtend($innerLinks['extend']);
					}
					else if($link->getType() == Link::typeEvolve){
						$innerLinks = $link->toInnerLink($arrayResultat);
						$arrayResultat[$link->getTo()]->addEvolution($innerLinks['evolve']);
						$arrayResultat[$link->getFrom()]->setRegress($innerLinks['regress']);
					}
				}
				return $arrayResultat;
			}
			else{
				return array();
			}
		}
}
?>