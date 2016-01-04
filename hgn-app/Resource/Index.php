<?php
namespace Hagane\Resource;

class Index extends AbstractResource{
	function load() {

		$this->get('clientes', function() {
			$this->message->append('clientessssss', 'rrr');
			echo $this->message->send();
		});

		$this->get('cliente', function() {
			$this->message->append('rrrrrrr', 'sssss');
			echo $this->message->send();
		});
	}
}

?>