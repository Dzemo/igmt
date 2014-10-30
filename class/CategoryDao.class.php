<?php
class CategoryDao{

	public static function getAll(){
		return self::getByQuery("SELECT * FROM igmt_category");
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