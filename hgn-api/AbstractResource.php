<?php
namespace Hagane\Resource;

//el abastracto del resource va a dar de alta todas las variables y servicios necesarios para
//esconder esta funcionalidad del uso cotidiano

abstract class AbstractResource {
	protected $config;
	protected $view;
	protected $db;
	protected $auth;
	protected $user;

	protected $_init;
	protected $_action;
	protected $message;

	public function __construct($config = null){
		$this->config = $config;
		$this->message = \Hagane\Message::getInstance();

		$this->db = \Hagane\Database::getInstance();
		$this->db->setDatabase($this->config);
		if ($this->db->isActive()) {
			$this->auth = new \Hagane\Authentication($this->config, $this->db);
			$this->user = new \Hagane\Model\User($this->auth, $this->db);
		}

		$this->view = '';
		$this->_init = '';
		$this->_action = '';
		$this->sendHtml = false;

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

	public function linkInitAction($action){
		$this->view .= $this->_init;
		$this->view .= $this->_action;
		return $this->view;
	}

	public function redirect($routeName) {
		if (substr($routeName, 0, 1) == '/') {
			$routeName = substr($routeName, 1);
		}
		header("Location: ".$this->config['document_root'].$routeName);
	}
}

?>