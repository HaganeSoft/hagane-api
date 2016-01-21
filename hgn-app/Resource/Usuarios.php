<?php
namespace Hagane\Resource;

class Usuarios extends AbstractResource{
	function load() {
		$this->get('/', function() {
			$accessToken = null;
			if (isset($_GET['accessToken']) && $_GET['accessToken'] != '') {
				$accessToken = $_GET['accessToken'];
			}

			$request = $this->call('GET', '/User/authorize/'.$accessToken);
			$request = json_decode($request);

			if (!empty($request->success) && $request->message->user->role == 'Administrador') {
				$data = array();
				$result = $this->db->query('SELECT * FROM User', $data);
				$this->message->append('usuarios', $result);
			} else {
				$this->message->deleteMessage();
				$this->message->appendError('acceso denegado', false);
			}

			echo $this->message->send();
		});
	}
}

?>