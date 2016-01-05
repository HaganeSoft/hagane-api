<?php
namespace Hagane\Resource;

//el abastracto del resource va a dar de alta todas las variables y servicios necesarios para
//esconder esta funcionalidad del uso cotidiano

abstract class AbstractResource {
	protected $config;
	protected $db;
	protected $message;

	protected $getNode;
	protected $routeParam;

	public function __construct($config = null){
		$this->getNode = array();
		$this->routeParams = array();
		$this->config = $config;
		$this->message = \Hagane\Message::getInstance();

		$this->db = \Hagane\Database::getInstance();
		$this->db->setDatabase($this->config);
	}

	public function execute($uri) {
		if ($uri['method'] == 'GET') {
			if (isset($uri['uri']) && $uri['uri'] != '') { //if uri exists
				$path = $this->match($uri); //get uri or null
				if (isset($path)) {
					$call = $this->getNode[$path]; //call to function using path
					$call();
				} else {
					$this->message->appendError('resource:execute','uri not found(404): ' . $uri['uri']);
					echo $this->message->send();
				}
			} else {
				$this->message->appendError('resource:execute','uri not found(404): NULL');
				echo $this->message->send();
			}
		}
	}

	public function match($uri) {
		$request = explode('/', $uri['uri']);
		$result = null;
		$routeParams = array();
		//contar que sea el mismo numero
		//ver si es igual y si tiene los dos puntos igunorar
		foreach ($this->getNode as $path => $f) {
			$req = true; //flag for path attributes
			$objectPath = explode('/', $path);
			$objNum = count($objectPath);
			if ($objNum == count($request)) { //if the number of attributes are not the same, dont waste CPU!
				for ($n=0; $n < $objNum; $n++) {
					if (substr($objectPath[$n], 0, 1) != ':') {  //ignore route parameters
						if ($objectPath[$n] != $request[$n]) {
							$req = false; //does not match
						}
					} else {
						//add to route parameters
						$this->routeParam[substr($objectPath[$n], 1)] = $request[$n];
					}
				}
				if ($req) { //everything went good, so this is the match
					$result = $path;
				}
			}
		}

		//Processing route parameters
		// if (isset($result)) {

		// }

		return $result;
	}

	protected function get($path, $function) {
		$this->getNode = array_merge($this->getNode, array($path => $function));
	}
}

?>