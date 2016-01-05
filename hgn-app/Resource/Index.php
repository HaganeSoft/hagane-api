<?php
namespace Hagane\Resource;

class Index extends AbstractResource{
	function load() {

		$this->get('/clientes/:id/reg', function() {
			$this->message->append('clientessssss', 'rrr');
			echo $this->message->send();
		});

		$this->get('/clientes/:id', function() {
			$this->message->append('rrrrrrr', 'sssss');
			echo $this->message->send();
		});
	}
}

?>