<?php

/**
 * Class interacting with all the Elements of the game
 */
class LinkDao extends Dao{

	/* Public */
	/**
	 * return every Links in the database
	 * @return array
	 */
	public static function getAll(){
		return self::getByQuery("SELECT * FROM igmt_link");
	}

	/**
	 * search for an links for a called Element
	 * @param  string $name
	 * @return array
	 */
	public static function getForElement(Element $element){
		$result = self::getByQuery("SELECT * FROM igmt_link WHERE need = ? OR allow = ?", [$element->getName(),$element->getName()]);
		if($result != null)
			return $result;
		else
			return null;
	}

	private static function getByQuery($query, $param = null){
			$stmt = parent::getConnexion()->prepare($query);
			if($stmt->execute($param) && $stmt->rowCount() > 0){
				$arrayResultat = array();
		
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$link = new Link(intval($row['id']),$row['need'],$row['allow'],$row['type']);
					$link->setConditions($row['conditions']);
					$arrayResultat[] = $link;
				}
			
				
				return $arrayResultat;
			}
			else{
				return null;
			}
		}
}