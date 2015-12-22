<?php
namespace Hagane\Controller;

class Error extends AbstractController{
	function index() {
		$this->message->append('routerError','No existe la ruta (404)');
	}
}

?>