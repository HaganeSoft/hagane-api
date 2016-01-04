<?php
namespace Hagane\Resource;

class Index extends AbstractResource{
	function load() {

		$this->get('clientes', function() {
			$this->message->appendError('error', 'error');
			$this->message->append('index:id', 'inside id');
			echo $this->message->send();
		});
	}
}

?>