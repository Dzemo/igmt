<?php
class CategoryDao extends Dao{

	public static function getAll(){
		return self::getByQuery("SELECT * FROM igmt_category");
	}

	public static function getByName($name){
		$arrayResultGetByName = self::getByQuery("SELECT * FROM igmt_category WHERE name = ?", [$name]);
		if(!empty($arrayResultGetByName)){
			return reset($arrayResultGetByName);
		}
		else {
			return null;
		}
	}

	private static function getByQuery($query, $param = null){
			$stmt = parent::getConnexion()->prepare($query);
			if($stmt->execute($param) && $stmt->rowCount() > 0){
				$arrayResultat = array();
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$category = new Category($row['name'], $row['color']);
					$arrayResultat[$category->getName()] = $category;
				}
				return $arrayResultat;
			}
			else{
				return null;
			}
		}
}
?>