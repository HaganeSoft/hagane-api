<?php
namespace Hagane\Resource;

class Index extends AbstractResource{
	function load() {
		$this->get('/clientes/:id/reg/:param1/:param2', function() {
			$this->message->append('idparam', $this->routeParam['id']);
			$this->message->append('param1', $this->routeParam['param1']);
			$this->message->append('param2', $this->routeParam['param2']);
			echo $this->message->send();
		});

		$this->get('/prueba/:id', function() {
			$this->message->append('idparam', $this->routeParam['id']);
			echo $this->message->send();
		});
	}
}

?>