<?php
namespace Hagane;

class Database {
	private static $instance;
	private $pdo;
	private $active;
	private $config;
	public $database_log = array();
	private $_message;

	public static function getInstance()
	{
		if (null === static::$instance) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	protected function  __construct(){
	}
	private function __clone(){
	}
	private function __wakeup(){
	}

	public function setDatabase($config){
		$this->active = false;
		$this->config = $config->getConf();
		$this->_message = \Hagane\Message::getInstance();

		if (isset($this->config['db_engine'])) {
			if (strcasecmp($this->config['db_engine'], 'mysql') == 0) {
				try {
					$this->pdo = new \PDO("mysql:host=".$this->config['db_server'].";dbname=".$this->config['db_database'].";charset=UTF8", $this->config['db_user'], $this->config['db_password']);
					$this->active = true;
				} catch (\PDOException $e) {
					$this->_message->appendError('database:setdatabase','exception: ' . $e->getMessage());
				}
			}
		} else {
			//destruye
			$this->_message->appendError('database:setdatabase','undeclared db_engine');
		}
	}

	function getPDOobject(){
		return $this->pdo;
	}

	function isActive(){
		return $this->active;
	}

	function insert($queryString, $data = null){
		$statement = $this->pdo->prepare($queryString);

		if ($statement->execute($data)) {
			$lastId = $this->pdo->lastInsertId();
		} else {
			$lastId = null;
			$this->_message->appendError('database:query', $statement->errorInfo());
		}

		return $lastId;
	}

	function query($queryString, $data = null){
		$statement = $this->pdo->prepare($queryString);
		$querySuccess = $statement->execute($data);

		if (!$querySuccess) {
			$this->_message->appendError('database:query', $statement->errorInfo());
		}

		$assocArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
		$result = $this->camelCaseKeys($assocArray); 
		return $result;
	}

	function getRow($queryString, $data = null){
		$statement = $this->pdo->prepare($queryString. ' LIMIT 1 ');
		$statement->execute($data);

		$assocArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
		$result = $this->camelCaseKeys($assocArray);
		return $result[0];
	}

	function rowCount($queryString, $data = null){
		$statement = $this->pdo->prepare($queryString);
		$statement->execute($data);

		return $statement->rowCount();
	}

	public function camelCaseKeys($array, $arrayHolder = array()) {
		$camelCaseArray = !empty($arrayHolder) ? $arrayHolder : array();
		foreach ($array as $key => $val) {
			$newKey = @explode('_', $key);
			array_walk($newKey, create_function('&$v', '$v = ucwords($v);'));
			$newKey = @implode('', $newKey);
			$newKey{0} = strtolower($newKey{0});
			if (!is_array($val)) {
				$camelCaseArray[$newKey] = $val;
			} else {
				$camelCaseArray[$newKey] = @$this->camelCaseKeys($val, $camelCaseArray[$newKey]);
			}
		}
		return $camelCaseArray;
	}
}

?>
