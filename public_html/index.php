<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
//arreglo designado para hacer convivir a mas apps de Hagane <3
// $HaganeInit = array(
// 	'appFolderName' => 'app-uni',
// 	'appFolderDepth' => '../../'
// );

$HaganeInit = array(
	'appFolderName' => 'hgn-app',
	'appFolderDepth' => '../'
);

$depth = explode('..', $HaganeInit['appFolderDepth']);
//print_r($depth);
if ($depth[0] =='') {
	$steps = (count($depth) - 1);
}
$indexPath = dirname(__FILE__);

//echo str_replace(dirname(__DIR__), '', dirname(__FILE__));
//echo dirname(__FILE__);

include_once($HaganeInit['appFolderDepth'].'hgn-api/init.php');

$app = new \Hagane\App();
$app->start($HaganeInit);
// phpinfo();
?>
