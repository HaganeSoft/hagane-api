<?php
//just load conekta and serves its api key from app configuration.
//se considera que la libreria esta justo afuera que la de hagane.
$this->message = \Hagane\Message::getInstance();
$conektaPath = !empty($this->config->getConf()['conektaPath']) ? $this->config->getConf()['conektaPath'] : null;
$conektaApiKey = !empty($this->config->getConf()['conecta_api_key']) ? $this->config->getConf()['conecta_api_key'] : null;
$hgConekta = false;

if (!empty($conektaPath)) {
	if (file_exists('../'.$conektaPath)) {
		require_once('../'.$conektaPath);
		$hgConekta = true;
	} else {
		$this->message->appendError('module:conekta', 'conekta file not exists');
	}
} else {
	if (file_exists($this->config->appDepth.'conekta/Conekta.php')) {
		require_once($this->config->appDepth."conekta/Conekta.php");
		$hgConekta = true;
	} else {
		$this->message->appendError('module:conekta', 'conekta file not exists');
	}
}

if ($hgConekta && !empty($conektaApiKey)) {
	Conekta::setApiKey($conektaApiKey);
} else {
	$this->message->appendError('module:conekta', 'Conekta api key is NULL');
}
?>
