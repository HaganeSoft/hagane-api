<?php
namespace Hagane\Resource;

class User extends AbstractResource{
	private $sessionidLength = 60;

	function load() {
		$this->post('/login', function() {
			$request = json_decode(file_get_contents("php://input"));

			//checar par de pass y user
			$data = array('username' => $request->username, 'password' => $request->password);
			$result = $this->db->getRow('SELECT * FROM User WHERE username=:username AND password=:password', $data);
			if (!empty ( $result )) {
				$this->message->append('user', array(
						'accessToken' => $this->generateAccessToken($result['id']),
						'id' => $result['id'],
						'role' => $result['role']
					)
				);
			} else {
				$this->message->appendError('login','usuario y/o contraseña inválidos.');
			}

			echo $this->message->send();
		});

		$this->post('/authorize', function() {
			$request = json_decode(file_get_contents("php://input"));

			//checar par de pass y user
			$data = array('accessToken' => $request->accessToken);
			$result = $this->db->getRow('SELECT * FROM User WHERE accessToken=:accessToken', $data);
			if (!empty ( $result )) {
				$this->message->append('user', array(
						'id' => $result['id'],
						'role' => $result['role']
					)
				);
			} else {
				$this->message->appendError('authorize','accessToken inválido.');
			}

			echo $this->message->send();
		});
	}

	private function generateAccessToken($userId){
		$token = $this->getToken($this->sessionidLength);
		$data = array('accessToken' => $token);

		while ($this->db->rowCount('SELECT accessToken FROM User WHERE accessToken = :accessToken', $data) > 0) {
			$data = array('accessToken' => $this->getToken($this->accessTokenLength));
		}

		$data['id'] = $userId;
		$this->db->query('UPDATE User SET accessToken = :accessToken WHERE id = :id', $data);
		return $token;
	}

	private function crypto_rand_secure($min, $max) {
		$range = $max - $min;
		if ($range < 0) return $min; // not so random...
		$log = log($range, 2);
		$bytes = (int) ($log / 8) + 1; // length in bytes
		$bits = (int) $log + 1; // length in bits
		$filter = (int) (1 << $bits) - 1; // set all lower bits to 1
		do {
			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
			$rnd = $rnd & $filter; // discard irrelevant bits
		} while ($rnd >= $range);
		return $min + $rnd;
	}

	private function getToken($length){
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
		for($i=0;$i<$length;$i++){
			$token .= $codeAlphabet[$this->crypto_rand_secure(0,strlen($codeAlphabet))];
		}
		return $token;
	}
}

?>