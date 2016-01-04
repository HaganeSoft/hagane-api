<?php
namespace Hagane;

class Message {
	private static $instance;
	private $data;

	public static function getInstance()
	{
		if (null === static::$instance) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	protected function __construct(){
	}
	private function __clone(){
	}
	private function __wakeup(){
	}

	function appendArray($incommingData = array()) {
		if (empty($this->data)) {
			$this->data = $incommingData;
		} else {
			$this->data = array_merge($this->data, $incommingData);
		}
	}

	function append($key, $data) {
		if (empty($this->data)) {
			$this->data = array('data' => array($key => $data));
		} elseif(empty($this->data['data'])) {
			$this->data['data'] = array($key => $data);
		} else {
			$this->data['data'] = array_merge($this->data['data'], array($key => $data));
		}
	}

	function appendError($key, $data) {
		if (empty($this->data)) {
			$this->data = array('error' => array($key => $data));
		} elseif(empty($this->data['error'])) {
			$this->data['error'] = array($key => $data);
		} else {
			$this->data['error'] = array_merge($this->data['error'], array($key => $data));
		}
	}

	function send() {
		return json_encode($this->data);
	}
}

?>
