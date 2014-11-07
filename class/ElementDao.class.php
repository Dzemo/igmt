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
		return self::getByQuery("SELECT * FROM igmt_element");
	}

	/**
	 * search for an Element by id and return an Element
	 * @param  int $id
	 * @return Element
	 */
	public static function getById($id){
		$result = self::getByQuery("SELECT * FROM igmt_element WHERE id = ?", [$id]);
		if($result != null && count($result) == 1)
			return $result[$id];
		else
			return null;
	}

	/**
	 * Return all element from a given category
	 * @param  string $category 
	 * @return array
	 */
	public static function getByCategory($category){
		return self::getByQuery("SELECT * FROM igmt_element WHERE category = ?",[$category]);
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

	/////////////
	//PRIVATE //
	/////////////

	/**
	 * Execute a query and return the result array as Element with link
	 * @param  string $query 
	 * @param  array $param 
	 * @return array        
	 */
	private static function getByQuery($query, $param = null){
			$stmt = parent::getConnexion()->prepare($query);
			if($stmt->execute($param) && $stmt->rowCount() > 0){
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
				return null;
			}
		}
}
?>