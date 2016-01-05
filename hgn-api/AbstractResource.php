<?php
namespace Hagane\Resource;

//el abastracto del resource va a dar de alta todas las variables y servicios necesarios para
//esconder esta funcionalidad del uso cotidiano

abstract class AbstractResource {
	protected $config;
	protected $db;
	protected $message;

	protected $getNode;

	public function __construct($config = null){
		$this->getNode = array();
		$this->config = $config;
		$this->message = \Hagane\Message::getInstance();

		$this->db = \Hagane\Database::getInstance();
		$this->db->setDatabase($this->config);
	}

	public function execute($uri) {
		if ($uri['method'] == 'GET') {
			if (isset($uri['uri']) && $uri['uri'] != '') {
				$this->match($uri);
				// if (array_key_exists($uri['uri'], $this->getNode)) {
				// 	$call = $this->getNode[$uri['uri']];
				// 	$call();
				// } else {
				// 	$this->message->appendError('resource:execute','uri not found(404): ' . $uri['uri']);
				// 	echo $this->message->send();
				// }
			} else {
				$this->message->appendError('resource:execute','uri not found(404): NULL');
				echo $this->message->send();
			}
		}
	}

	public function match($uri) {
		$request = explode('/', $uri['uri']);
		$result = null;
		//contar que sea el mismo numero
		//ver si es igual y si tiene los dos puntos igunorar
		foreach ($this->getNode as $path => $f) {
			$req = true;
			$objectPath = explode('/', $path);
			$objNum = count($objectPath);
			if ($objNum == count($request)) {
				for ($n=0; $n < $objNum; $n++) {
					if (substr($objectPath[$n], 0, 1) != ':') {
						if ($objectPath[$n] != $request[$n]) {
							$req = false;
							//echo 'lol';
						}
					}
				}
				if ($req) {
					$result = $path;
				}
			}
		}
		print_r($result);
	}

	protected function get($path, $function) {
		$this->getNode = array_merge($this->getNode, array($path => $function));
	}
}

?>