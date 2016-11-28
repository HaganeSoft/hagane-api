<?php
namespace Hagane;

use \Hagane\Resource\AbstractResource;
use \Hagane\Database;
use \Hagane\Message;
use \Hagane\Router;
use \Hagane\Load\Loader;

class App {
	private static $instance;
	public $config;

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

	function start($config) {
		$this->config = $config;
		//timezone
		if(array_key_exists('timezone', $this->config)) {
			date_default_timezone_set($this->config['timezone']);
		} else {
			date_default_timezone_set('America/Monterrey');
		}

		$this->enableCORS();
		$this->call();
	}

	function call($method = null, $uri = null) {
		if (!empty($method) && !empty($uri)) {
			$innerCall = array(
				'method' => $method,
				'uri' => $uri
			);
		} else {
			$innerCall = null;
		}

		$router = new \Hagane\Router($this->config);
		$uri = $router->parse($innerCall); // gets an array being 0:resource and so on.
		$resourceName = $router->load($uri); // loads the resource name if it exist else its a false

		$RerosurceClass = '\\Hagane\\Resource\\'.$resourceName;
		if ($resourceName) {
			//var_dump($resourceName);
			$resource = new $RerosurceClass($this->config);
			$resource->load();
			$resource->execute($uri);
		} else {
			$this->message = \Hagane\Message::getInstance();
			$this->message->appendError('error:app:init','Resource not found(404): '.$uri['resource']);
			echo $this->message->send();
			die();
		}
	}

	function enableCORS() {
		// Allow from any origin
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Max-Age: 86400');    // cache for 1 day
		}

		// Access-Control headers are received during OPTIONS requests
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
		}
	}
}

?>
