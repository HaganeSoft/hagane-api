<?php
//just load conekta and serves its api key from app configuration.
//se considera que la libreria esta justo afuera que la de hagane.
$this->message = \Hagane\Message::getInstance();
$conektaPath = !empty($this->config->getConf()['conekta_path']) ? $this->config->getConf()['conekta_path'] : null;
$conektaApiKey = !empty($this->config->getConf()['conekta_api_key']) ? $this->config->getConf()['conekta_api_key'] : null;
$hgConekta = false;

if (!empty($conektaPath)) {
	if (file_exists($this->config->appDepth.$conektaPath)) {
		require_once($this->config->appDepth.$conektaPath);
		$hgConekta = true;
	} else {
		$this->message->appendError('module:conekta:file', 'conekta file not exists');
	}
} else {
	if (file_exists($this->config->appDepth.'conekta/Conekta.php')) {
		require_once($this->config->appDepth."conekta/Conekta.php");
		$hgConekta = true;
	} else {
		$this->message->appendError('module:conekta:file', 'conekta file not exists');
	}
}

if ($hgConekta && !empty($conektaApiKey)) {
	\Conekta::setApiKey($conektaApiKey);
} else if(empty($conektaApiKey)) {
	$this->message->appendError('module:conekta:apikey', 'Conekta API key is NULL');
} else {
	$this->message->appendError('module:conekta', 'Conekta not initialized.');
}
?>
