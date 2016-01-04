<?php
namespace Hagane;

include_once('AbstractResource.php');
include_once('Database.php');
include_once('Authentication.php');
include_once('ResourceDriver.php');
include_once('MessageDriver.php');
include_once('Router.php');

class App {
	function start($HaganeInit = array()) {
		include_once($HaganeInit['appFolderDepth'].$HaganeInit['appFolderName'].'/config/config.php'); //llama a la configuracion de la carpeta de la app
		$config = new \Hagane\Config($HaganeInit);

		//inicializacion de modulos
		foreach ($config->getModules() as $module) {
			include_once('Modules/'.$module.'.php');
		}

		// include_once($HaganeInit['appFolderDepth'].$HaganeInit['appFolderName'].'/Model/UserModel.php');

		$router = new \Hagane\Router($config);
		$uri = $router->parse(); // gets an array being 0:resource and so on.
		$router->load($uri); // loads the resourse paths

		$RerosurceClass = '\\Hagane\\Resource\\'.$uri[0];
		$resource = new $RerosurceClass($config);



		// $ResourceDriver = new \Hagane\ResourceDriver($config->getConf());
		// $ResourceDriver->execute($params);  //params >>> controllerName, action and get params
	}

}

?>