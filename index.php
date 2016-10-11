<?php
ini_set("display_errors", 1);
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "root" );
define( "DB_HOST", '127.0.0.1');
define( "DB_PORT", 5432);
define( "DB_NAME", 'db_sso');
define( "SCHEMA", 'adm_sso' );
define( 'ENCODING', "SET NAMES utf8");
define( "DB_DSN", "pgsql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";");
define( "TOKEN_ISSUER", "http://www.evandromohr.com");
define( "TOKEN_AUDIENCE", "http://evandro.mohr.com");

$composer_autoload = 'vendor/autoload.php';
if (false === file_exists($composer_autoload)) {
	throw new RuntimeException('Por favor instalar as dependências do composer.');
}

include $composer_autoload;

use GuzzleHttp\Client;

$app = new \Slim\Slim(array(
    'mode' => 'development',
    'debug' => true));

$app->get('/', function () {
	die( "Auth API  v0.1");
});



include_once 'src/Routes/auth.php';

$app->run();