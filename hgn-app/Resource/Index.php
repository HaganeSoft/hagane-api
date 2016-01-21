<?php
namespace Hagane\Resource;

class Index extends AbstractResource{
	function load() {
		$this->get('/', function() {
			$this->message->append('haganeapi', 'ver 0.0.1');
			echo $this->message->send();
		});

		$this->get('/prueba/:id', function() {
			$this->message->append('idparam', $this->routeParam['id']);
			echo $this->message->send();
		});
	}
}

?>