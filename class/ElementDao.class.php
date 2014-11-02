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

	/* Public */
	/**
	 * return every Elements in the database
	 * @return array
	 */
	public static function getAll(){
		return self::getByQuery("SELECT * FROM igmt_element");
	}

	/**
	 * search for an Element by name and return an Element
	 * @param  string $name
	 * @return Element
	 */
	public static function getByName($name){
		$result = self::getByQuery("SELECT * FROM igmt_element WHERE name = ?", [$name]);
		if($result != null && count($result) == 1)
			return $result[$name];
		else
			return null;
	}

	public static function getByCategory($category){
		return self::getByQuery("SELECT * FROM igmt_element WHERE category = ?",[$category]);
	}



	private static function getByQuery($query, $param = null){
			$stmt = parent::getConnexion()->prepare($query);
			if($stmt->execute($param) && $stmt->rowCount() > 0){
				$arrayResultat = array();
				$allCategories = CategoryDao::getAll();
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$element = new Element($row['name'],$allCategories[$row['category']]);
					$element->setDescription($row['description']);
					if(!empty($row['tag']))
						$element->setTags(explode(';',$row['tag']));

					$arrayResultat[$element->getName()] = $element;
				}
				if(count($arrayResultat) > 1){
					$links = LinkDao::getAll();
				}elseif(count($arrayResultat) == 1){
					$links = LinkDao::getForElement($element);
				}else{
					$links = array();
				}

				foreach ($links as $link) {
					if($link->getType() == Link::typeRequire){
						$innerLinks = $link->toInnerLink($arrayResultat);
						$arrayResultat[$link->getAllow()]->addNeed($innerLinks['need']);
						$arrayResultat[$link->getNeed()]->addAllow($innerLinks['allow']);
					}
					if($link->getType() == Link::typeExtends){

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