<?php
namespace Hagane\Resource;

class Index extends AbstractResource{
	function load() {
		$this->get('/', function() {
			$this->message->append('haganeapi', 'ver 0.0.2');
			echo $this->message->send();
		});

		$this->get('/caller', function() {
			$this->message->append('i am the call-e', 'calleee');
			echo $this->message->send();
		});

		$this->get('/innercall', function() {
			$resp = $this->call('GET', '/Index/caller');
			$this->message->deleteMessage();

			$this->message->append('I am the one who knocks', 'walter white');
			echo $this->message->send();
		});
	}
}

?>