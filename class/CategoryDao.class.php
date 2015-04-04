<?php
/**
 * File about ElementDao class
 * @author Flavio Deroo and Raphaël BIDEAU
 * @package Dao
 */
/**
 * Class interacting with all the Category of the game
 */
class CategoryDao extends Dao{

	////////////
	//PUBLIC //
	////////////

	/**
	 * Return an array containing all category indexed by their id
	 * @return array
	 */
	public static function getAll(){
		return self::getByQuery("SELECT * FROM igmt_category");
	}

	/**
	 * Return the category of the specified name or null if this category doesn't exist
	 * @param  string $name 
	 * @return Cateogry       
	 */
	public static function getByName($name){
		$arrayResult = self::getByQuery("SELECT * FROM igmt_category WHERE name = ?", [$name]);
		if(!empty($arrayResult)){
			return reset($arrayResult);
		}
		else {
			return null;
		}
	}

	/**
	 * Return the category of the specified id or null if this category doesn't exist
	 * @param  int $id
	 * @return Cateogry       
	 */
	public static function getById($id){
		$arrayResult = self::getByQuery("SELECT * FROM igmt_category WHERE id = ?", [$id]);
		if(array_key_exists($id, $arrayResult)){
			return $arrayResult[$id];
		}
		else {
			return null;
		}
	}

	/**
	 * Insert a new Category in the database
	 * Return the category or null if there was an error
	 * @param  Category $category 
	 * @return Category           
	 */
	public static function insert(Category $category){
		$stmt = parent::getConnexion()->prepare("INSERT INTO igmt_category (name, description, color) VALUES (?,?,?)");
		$result = $stmt->execute(array(
						$category->getName(),
						$category->getDescription(),
						$category->getColor()
							));

		if($result){
			$category->setId(parent::getConnexion()->lastInsertId());
			return $category;
		}
		else
			return null;
	}

	/**
	 * Update an Category
	 * @param  Category $category 
	 * @return Category           
	 */
	public static function update(Category $category){
		$stmt = parent::getConnexion()->prepare("UPDATE igmt_category SET name = ?, description = ?, color = ? WHERE id = ?");
		$result = $stmt->execute(array(
						$category->getName(),
						$category->getDescription(),
						$category->getColor(),
						$category->getId()
							));

		if($category){
			return $category;
		}
		else
			return null;
	}

	/**
	 * Delete a category and all her element
	 * @param  int $categoryId
	 */
	public static function delete($categoryId){
		$stmtSelect = parent::getConnexion()->prepare("SELECT id FROM igmt_element WHERE category_id = ?");
		if($stmtSelect->execute(array($categoryId)) && $stmtSelect->rowCount() > 0){
			while($row = $stmtSelect->fetch(PDO::FETCH_ASSOC)){
				ElementDao::delete($row['id']);
			}
		}

		$stmtDelete = parent::getConnexion()->prepare("DELETE FROM igmt_category WHERE id = ?");
		$stmtDelete->execute(array($categoryId));
	}

	/////////////
	//PRIVATE //
	/////////////

	/**
	 * Execute a query with optionnal parameter and return an array of category indexed by their id
	 * @param  string $query 
	 * @param  array $param 
	 * @return array        
	 */
	private static function getByQuery($query, $param = null){
			$stmt = parent::getConnexion()->prepare($query);
			if($stmt->execute($param) && $stmt->rowCount() > 0){

				$arrayResultat = array();
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

					$category = new Category($row['id'], $row['name'], $row['color']);
					$category->setDescription($row['description']);

					$arrayResultat[$category->getId()] = $category;
				}
				return $arrayResultat;
			}
			else{
				return array();
			}
		}
}
?>