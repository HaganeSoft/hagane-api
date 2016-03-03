<?php
namespace Hagane\Resource;

class Error extends AbstractResource{
	function index() {
		$this->message->append('routerError','No existe la ruta (404)');
	}
}

?>