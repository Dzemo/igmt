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
	 * search for an Element by his ID and return the single row found as an Element Object
	 * @param  int $id
	 * @return Element
	 */
	public static function getById($id){
		$result = self::getByQuery("SELECT * FROM igmt_element WHERE id = ?", [intval($id)]);

		if($result != null && count($result) == 1)
			return $result[0];
		else
			return null;
	}

	private static function getByQuery($query, $param = null){
			$stmt = parent::getConnexion()->prepare($query);
			if($stmt->execute($param) && $stmt->rowCount() > 0){
				$arrayResultat = array();
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$element = new Element($row['name'], $row['category']);
					$element = setDescription();
					$element = setTag(explode($row['tag'],';'));

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