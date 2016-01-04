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
			$call = $this->getNode[$uri[1]];
			$call();
		}
	}

	protected function get($path, $function) {
		$this->getNode = array_merge($this->getNode, array($path => $function));
	}

	public function executeAction($action){
		if (method_exists($this, '_init')) {
			ob_start();
			$this->_init();
			$this->init = ob_get_clean();
		}

		//ejecucion de accion
		ob_start();
		$this->$action();
		$this->_action = ob_get_clean();

		if ($this->sendHtml) {
			header('Content-type: text/html; charset=utf-8');
			return $this->linkInitAction($action);
		} else {
			header("Content-type: application/json; charset=utf-8");
			return $this->message->send();
		}
	}
}

?>