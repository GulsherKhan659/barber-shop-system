<?php 

session_start();

require_once __DIR__ . '/../database/configue.php';
require_once __DIR__ . '/../database/connection.php';

$config = new Configue();
$db = new Database($config->servername, $config->database, $config->username, $config->password);

define('GOOGLE_CLIENT_ID', ''); 
define('GOOGLE_CLIENT_SECRET', ''); 
define('GOOGLE_REDIRECT_URI', ''); 

?>