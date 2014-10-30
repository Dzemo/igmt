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





	private static function getByQuery($query, $param = null){
			$stmt = parent::getConnexion()->prepare($query);
			if($stmt->execute($param) && $stmt->rowCount() > 0){
				$arrayResultat = array();
				$allCategories = CategoryDao::getAll();
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$element = new Element($row['name'],$allCategories[$row['category']]);
					$element->setDescription('');
					if(!empty($row['tag']))
						$element->setTags(explode(';',$row['tag']));
					$arrayResultat[$element->getName()] = $element;
				}
				return $arrayResultat;
			}
			else{
				return null;
			}
		}
}
?>