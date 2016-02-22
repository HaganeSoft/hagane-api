<?php
namespace Hagane\Resource;

class Index extends AbstractResource{
	function load() {
		$this->get('/', function() {
			$this->message->append('haganeapi', 'ver 0.0.2');
			echo $this->message->send();
		});

		include_once($this->config->getConf()['appPath'].'/class/hello.php');
		$cl = new \Hagane\Resource\hello();
		$this->get('/hola', &$cl->world());
	}
}

?>
