<?php
namespace Hagane;

include_once('AbstractResource.php');
include_once('Database.php');
include_once('Authentication.php');
include_once('MessageDriver.php');
include_once('Router.php');

class App {
	function start($HaganeInit = array()) {
		// Allow from any origin
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Max-Age: 86400');    // cache for 1 day
		}

		// Access-Control headers are received during OPTIONS requests
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
		}
		include_once($HaganeInit['appFolderDepth'].$HaganeInit['appFolderName'].'/config/config.php'); //llama a la configuracion de la carpeta de la app
		$config = new \Hagane\Config($HaganeInit);

		//inicializacion de modulos
		foreach ($config->getModules() as $module) {
			include_once('Modules/'.$module.'.php');
		}

		// include_once($HaganeInit['appFolderDepth'].$HaganeInit['appFolderName'].'/Model/UserModel.php');

		$router = new \Hagane\Router($config);
		$uri = $router->parse(); // gets an array being 0:resource and so on.
		$resourceName = $router->load($uri); // loads the resource name if it exist else its a false

		$RerosurceClass = '\\Hagane\\Resource\\'.$uri['resource'];
		if ($resourceName) {
			$resource = new $RerosurceClass($config);
			$resource->load();
			$resource->execute($uri);
		} else {
			$this->message = \Hagane\Message::getInstance();
			$this->message->appendError('error:app:init','Resource not found(404): '.$uri['resource']);
			echo $this->message->send();
			die();
		}

		// $ResourceDriver = new \Hagane\ResourceDriver($config->getConf());
		// $ResourceDriver->execute($params);  //params >>> controllerName, action and get params
	}

}

?>