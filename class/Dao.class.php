<?php
/**
 * @package igmt.dao
 * @author Raphaël BIDEAU
 * @version 1.0
 */

/**
 * Class implementing the singleton pattern to retreive a PDO connection to the database
 */
class Dao{
	/**
	 * Singleton representing a PDO connection
	 * @var PDO
	 */
	private static $_connexion;

	/**
	 * Allow to perfom a query and return an array
	 * @param  string $query
	 * @param  array $param Optionnel
	 * @return array 
	 */
	public static function execute($query, array $param=null){
		$stmt = self::getConnexion()->prepare($query);
		if($stmt->execute($param) && $stmt->columnCount() > 0){
			try{
			return $stmt->fetchAll();
			}catch(PDOException $e){echo $e->getMessage();}
		}
		else{
			return null;
		}
	}
	/**		 
	 * @return PDO return a connection to the database
	 */
	public static function getConnexion(){
		if(self::$_connexion){
			return self::$_connexion;
		}
		else{
			try{
				require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."database_info.php");

				self::$_connexion = new PDO("mysql:charset=utf8;host=$dbhost;dbname=$dbname", $dbuser, $dbpass, $dbparams);
  				self::$_connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	 				self::$_connexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			}
			catch (PDOException $e)
			{
			    echo 'Exception -> ';
			    var_dump($e->getMessage());
			}
			return self::$_connexion;
		}
	}
}
?>