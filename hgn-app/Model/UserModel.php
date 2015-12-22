<?php
namespace Hagane\Model;

class User {
	private $username;

	public function __construct(&$auth, &$db) {
		$id = $auth->isAuth();
		if (!empty($id)) {
			$data = array('id' => $id);
			$userArray = $db->getRow('SELECT * FROM User WHERE id = :id', $data);

		}
	}

	public function getUsername() {
		return $this->username;
	}
}

?>