<?php
namespace Hagane\Resource;

//el abastracto del resource va a dar de alta todas las variables y servicios necesarios para
//esconder esta funcionalidad del uso cotidiano

abstract class AbstractResource {
	protected $config;
	protected $db;
	protected $message;

	protected $getNode;
	protected $postNode;
	protected $deleteNode;
	protected $putNode;
	protected $params;

	public function __construct($config = null){
		$this->getNode = array();
		$this->postNode = array();
		$this->deleteNode = array();
		$this->putNode = array();

		$this->params = array();
		$this->config = $config;
		$this->message = \Hagane\Message::getInstance();

		$this->db = \Hagane\Database::getInstance();
		$this->db->setDatabase($this->config);
	}

	public function execute($uri) {
		if (isset($uri['uri']) && $uri['uri'] != '') { //if uri exists
			$methodNode = $this->matchMethod($uri);
			$path = $this->matchPath($uri, $methodNode); //get uri or null
			if (isset($path)) {
				$call = $this->{$methodNode}[$path]; //call to function using path
				$call();
			} else {
				$this->message->appendError('resource:execute','uri not found(404): ' . $uri['method'] . ' ' .$uri['request']);
				echo $this->message->send();
				die();
			}
		} else {
			$methodNode = $this->matchMethod($uri);
			if (array_key_exists('/', $this->{$methodNode})) {
				$call = $this->{$methodNode}['/']; //call to function using path
				$call();
			} else {
				$this->message->appendError('resource:execute','uri not found(404): NULL');
				echo $this->message->send();
				die();
			}
		}
	}

	public function matchPath($uri, $methodNode) {
		$request = explode('/', $uri['uri']);
		$result = null;

		if (!empty($this->{$methodNode})) {
			foreach ($this->{$methodNode} as $path => $f) {
				$req = true; //flag for path attributes
				$objectPath = explode('/', $path);
				$objNum = count($objectPath);
				if ($objNum == count($request)) { //if the number of attributes are not the same, dont waste CPU!
					for ($n=0; $n < $objNum; $n++) {
						if (substr($objectPath[$n], 0, 1) != ':') {  //ignore route parameters
							if ($objectPath[$n] != $request[$n]) {
								$req = false; //does not match
							}
						} else {
							//add to route parameters
							$this->params[substr($objectPath[$n], 1)] = $request[$n];
						}
					}
					if ($req) { //everything went good, so this is the match
						$result = $path;
					}
				}
			}//end foreach
		}//end if is empty
		return $result;
	}

	public function matchMethod($uri) {
		$request = explode('/', $uri['uri']);
		$methodNode = null;

		switch ($uri['method']) {
			case 'DELETE':
				$methodNode = 'deleteNode';
				break;
			case 'POST':
				$methodNode = 'postNode';
				break;
			case 'GET':
				$methodNode = 'getNode';
				break;
			case 'PUT':
				$methodNode = 'putNode';
				$this->message->appendError('status','PUT method is deprecated');
				break;
			default:
				$this->message->appendError('resource:match','invalid method(405)');
				echo $this->message->send();
				die();
				break;
		}

		return $methodNode;
	}

	public function call($method, $uri) {
		$app = \Hagane\App::getInstance();
		ob_start();
		$app->call($method, $uri);
		return ob_get_clean();
	}

	public function role($accessToken, $roles = array(), $verbose = true) {
		if (!empty($accessToken)) {
			$request = $this->call('GET', '/User/authorize/'.$accessToken);
			$request = json_decode($request);
			if (!empty($request->success)) {
				$this->message->deleteMessage();
				if (!empty($roles) && in_array($request->message->user->role, $roles)) {
					return $request->message->user;
				} else if(empty($roles)){
					return $request->message->user;
				} else if($verbose) {
					$this->message->appendError('acceso denegado', false);
					return false;
				} else {
					return false;
				}
			} else if($verbose){
				$this->message->deleteMessage();
				$this->message->appendError('acceso denegado', false);
				return false;
			} else {
				return false;
			}
		} else if($verbose){
			$this->message->deleteMessage();
			$this->message->appendError('acceso denegado', false);
			return false;
		} else {
			return false;
		}
	}

	// Convert camelCase type array's keys to under_score+lowercase type array's keys
	// @param   array   $array          array to convert
	// @param   array   $arrayHolder    parent array holder for recursive array
	// @return  array   under_score array
	public function underscoreKeys($array, $arrayHolder = array()) {
		$underscoreArray = !empty($arrayHolder) ? $arrayHolder : array();
		foreach ($array as $key => $val) {
			$newKey = preg_replace('/[A-Z]/', '_$0', $key);
			$newKey = strtolower($newKey);
			$newKey = ltrim($newKey, '_');
			if (!is_array($val)) {
				$underscoreArray[$newKey] = $val;
			} else {
				$underscoreArray[$newKey] = $this->underscoreKeys($val, $underscoreArray[$newKey]);
			}
		}
		return $underscoreArray;
	}

	protected function get($path, $function) {
		$this->getNode = array_merge($this->getNode, array($path => $function));
	}

	protected function post($path, $function) {
		$this->postNode = array_merge($this->postNode, array($path => $function));
	}

	protected function delete($path, $function) {
		$this->deleteNode = array_merge($this->deleteNode, array($path => $function));
	}

	protected function put($path, $function) {
		$this->putNode = array_merge($this->putNode, array($path => $function));
	}
}

?>
