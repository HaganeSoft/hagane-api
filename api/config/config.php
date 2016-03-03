<?php
namespace Hagane;

class Config {
	public $appDir;
	public $appDepth;

	public function __construct($HaganeInit = array()){
		$this->appDir = $HaganeInit['appFolderName'];
		$this->appDepth = $HaganeInit['appFolderDepth'];
	}

	function getConf() {
		return
			array(
				'appPath' => $this->appDepth.$this->appDir.'/',
				'db_engine' => 'mysql',
				'db_server' => 'localhost',
				'db_database' => 'basket',
				'db_user' => 'dev',
				'db_password' => 'Bicarbonato1!',
				'session_time' => 3600,
				'conekta_api_key' => 'key_eYvWV7gSDkNYXsmr',
				'conekta_path' => '../externalLibs/conekta-php/lib/Conekta.php',
				'document_root' => '/'
			);
	}

	function getModules() {
		return
			array('hgConekta');
	}

	function getRoutes() {
		// Add custom routes here so you can call them with a simple route name
		// Use the key of the element in the array as
		return
			array();
	}
}
?>
