<?php
//TODO
//funcion match para obtener las rutas ya mapeadas
//parametro para tener cargadas todas las rutas mapeadas

namespace Hagane;

class Router {
	private $config = array();
	private $routes = array();
	private $message;

	function __construct(&$config){
		$this->config = $config->getConf();
		$this->routes = $config->getRoutes();
		$this->message = \Hagane\Message::getInstance();
	}

	function parse() {
		//parseo de URI
		$request = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

		if ($this->config['document_root'] != '/') {
			$request = str_replace($this->config['document_root'], '', $request);
		} else {
			$request = substr($request, 1);
		}
		if ($tmp = $this->match((string)$request)) {
			$request = $tmp;
		}
		$requestArray = explode("/", $request);

		//check method
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
			if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
				$method = 'DELETE';
			} else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
				$method = 'PUT';
			} else {
				throw new Exception("Unexpected Header");
			}
		}

		$requestArray['resource'] = ucfirst($requestArray[0]);
		$requestArray['method'] = $method;
		$requestArray['request'] = $request;
		$requestArray['uri'] = str_replace($requestArray[0], '', $request);

		return $requestArray;
	}

	function load($uri) {
		//chequeo de existencia de uri
		if (isset($uri['resource']) && $uri['resource'] != '') {
			if (file_exists($this->config['appPath'].'Resource/'.$uri['resource'].'.php')) {
				include_once($this->config['appPath'].'Resource/'.$uri['resource'].'.php');
				return $uri['resource'];
			} else {
				//si no existe el resource
				$this->message->appendError('error:router:load', 'Resource path not found');
				return false;
			}
		} else {
			$this->message->appendError('error:router:load', 'Resource request not found');
			return false;
		}
	}

	function match($request){
		return array_key_exists ($request, $this->routes) ? $this->routes[$request] : null;
	}
}

?>
