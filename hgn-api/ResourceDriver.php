<?php
namespace Hagane;

class ResourceDriver {
	private $config = array();

	public function  __construct($config){
		$this->config = $config;
	}

	public function execute($params) {
		//include_once($this->config['appPath'].'Resource/'.$params['resource'].'.php');
		//$class = '\\Hagane\\Resource\\'.$params['resource'];
		//$resource = new $class($this->config);

		echo $params['resource']->executeAction($params['action']);
	}
}

?>