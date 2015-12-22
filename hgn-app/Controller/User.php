<?php
namespace Hagane\Controller;

class User extends AbstractController{
	function _init() {
	}

	function index() {
	}

	function auth() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$this->auth->authenticate($_POST['user'], $_POST['password']);
		}
	}

	function logout() {
		$this->auth->logout();
	}
}

?>
