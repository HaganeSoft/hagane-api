<?php
namespace Hagane\Resource;

class Usuarios extends AbstractResource{
	function load() {
		$this->get('/', function() {
			$accessToken = !empty($_GET['accessToken']) ? $_GET['accessToken'] : null;
			$roles = array('Administrador');

			if ($this->roles($accessToken, $roles)) {
				$data = array();
				$result = $this->db->query('SELECT * FROM User', $data);
				$this->message->append('usuarios', $result);
			}

			echo $this->message->send();
		});
	}
}

?>