<?php
namespace Hagane\Resource;

class Index extends AbstractResource{
	function load() {
		$this->get('id', function() {
			$this->message->append('inside id', 'inside id');
			echo $this->message->send();
		});
	}
}

?>