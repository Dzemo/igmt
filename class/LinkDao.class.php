<?php

/**
 * Class interacting with all the Elements of the game
 */
class LinkDao extends Dao{

	////////////
	//PUBLIC //
	////////////

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
		$result = self::getByQuery("SELECT * FROM igmt_link WHERE from_id = ? OR to_id = ?", [$element->getId(),$element->getId()]);
		if($result != null)
			return $result;
		else
			return array();
	}

	/**
	 * Insert all links of an Element
	 * @param  Element $element
	 * @return array        The array of inserted Link
	 */
	public static function insertFromElement(Element $element){

		$links = $element->getLinksArray();
		if(count($links) > 0){
		
			$stmt = parent::getConnexion()->prepare("INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (?,?,?,?)");

			foreach ($links as $link) {
				$stmt->execute(array(
										$link->getFrom(),
										$link->getTo(),
										$link->getType(),
										$link->getConditions()
									));
			}
		}
		return $links;
	}

	/**
	 * Update link in the database according to an element
	 * @param  Element $element 
	 * @return array           Array of link updated
	 */
	public static function updateFromElement(Element $element){

		//Delete old link that ar not in the element
		$oldLinks = self::getForElement($element);
		$newLinks = $element->getLinksArray();
		$stmtDelete = parent::getConnexion()->prepare("DELETE FROM igmt_link WHERE id = ?");

		foreach($oldLinks as $oldLink){
			$found = false;
			foreach ($newLinks as $newLink) {
				if($newLink->getId() != null && $newLink->getId() == $oldLink->getId()){
					$found = true;
					break;
				}
			}

			if(!$found){
				$stmtDelete->execute(array($oldLink->getId()));
			}
		}

		//Updating new links
		$stmtUpdate = parent::getConnexion()->prepare("UPDATE igmt_link SET from_id = ?, to_id = ?, type = ?, conditions = ? WHERE id = ?");
		$stmtInsert = parent::getConnexion()->prepare("INSERT INTO igmt_link (from_id, to_id, type, conditions) VALUES (?,?,?,?)");

		foreach ($newLinks as $newLink) {
			if($newLink->getId() == null){
				$stmtInsert->execute(array(
									$newLink->getFrom(),
									$newLink->getTo(),
									$newLink->getType(),
									$newLink->getConditions()
								));
			}
			else{
				$stmtUpdate->execute(array(
									$newLink->getFrom(),
									$newLink->getTo(),
									$newLink->getType(),
									$newLink->getConditions(),
									$newLink->getId()
								));
			}
		}

		return $newLinks;
	}

	/////////////
	//PRIVATE //
	/////////////

	/**
	 * Execute a query and return an array of link link
	 * @param  string $query 
	 * @param  array $param Optionnal parameters for the query
	 * @return array array of link indexed by their id
	 */
	private static function getByQuery($query, $param = null){
			$stmt = parent::getConnexion()->prepare($query);
			if($stmt->execute($param) && $stmt->rowCount() > 0){
				$arrayResultat = array();
		
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$link = new Link(intval($row['id']),$row['from_id'],$row['to_id'],$row['type']);
					$link->setConditions($row['conditions']);
					$arrayResultat[$link->getId()] = $link;
				}		
				
				return $arrayResultat;
			}
			else{
				return null;
			}
		}
}