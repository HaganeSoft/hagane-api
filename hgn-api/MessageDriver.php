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

	function append($key, $data) {
		if (empty($this->data)) {
			$this->data = array('message' => array($key => $data));
		} elseif (empty($this->data['message'])) {
			$this->data['message'] = array($key => $data);
		} else {
			$this->data['message'] = array_merge($this->data['message'], array($key => $data));
		}
		$this->data['success'] = true;
	}

	function appendError($key, $data) {
		if (empty($this->data)) {
			$this->data = array('error' => array($key => $data));
		} elseif (empty($this->data['error'])) {
			$this->data['error'] = array($key => $data);
		} else {
			$this->data['error'] = array_merge($this->data['error'], array($key => $data));
		}
	}

	function deleteMessage() {
		if (!empty($this->data)) {
			if (!empty($this->data['message'])) {
				unset($this->data['message']);
			}
		}
	}

	function send() {
		header("Content-type: application/json; charset=utf-8");
		if (!empty($this->data['error'])) {
			unset($this->data['success']);
		}
		return json_encode($this->data);
	}
}

?>
