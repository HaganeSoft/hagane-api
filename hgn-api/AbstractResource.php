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

	public function executeURI($uri) {
		if ($uri['method'] == 'GET') {
			if (isset($uri['uri']) && $uri['uri'] != '') {
				if (array_key_exists($uri['uri'], $this->getNode)) {
					$call = $this->getNode[$uri['uri']];
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

	protected function get($path, $function) {
		$this->getNode = array_merge($this->getNode, array($path => $function));
	}
}

?>