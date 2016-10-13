<?php
namespace Hagane\Load;

class Loader {
	public static $config;

	static function setConfig($config) {
		self::$config = $config;
	}

	static function call($rawAction, $parameters = null) {
		$action = explode('@', $rawAction);

		$class = $action[0];
		$classPath = str_replace('\\', '/', $class);
		$fullClass = '\\Hagane\\Classes\\'.$action[0];
		$method = $action[1];

		if (file_exists(self::$config['appPath'].'Classes/'.$classPath.'.php')) {
			include_once(self::$config['appPath'].'Classes/'.$classPath.'.php');

			//Se crea instancia para llamarlo fuera de un ambiente estatico
			$instance = new $fullClass(self::$config);

			//resolve parameters
			if (method_exists($instance, $method)) {
				$parameters = self::resolveMethodDependencies($parameters, new \ReflectionMethod($instance, $method));
			}

			call_user_func_array([$instance, $method], $parameters);
		} else {
			throw new \Exception('No existe la clase');
		}
	}

	public static function resolveMethodDependencies(array $rawParameters, \ReflectionFunctionAbstract $reflector) {
		$parameters = [];

		foreach ($reflector->getParameters() as $param) {
			$name = $param->getName();
			$isArgumentGiven = array_key_exists($name, $rawParameters);
			if (!$isArgumentGiven && !$param->isDefaultValueAvailable()) {
				die ("Parameter $name is mandatory but was not provided");
			}

			$parameters[$param->getPosition()] = $isArgumentGiven ? $rawParameters[$name] : $param->getDefaultValue();
		}
		return $parameters;
	}
}
