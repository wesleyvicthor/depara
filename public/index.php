<?php

require_once __DIR__ . '/../bootstrap.php';

use Respect\Rest\Router;
use Symfony\Component\Yaml\Parser;

define('TABLES_CONFIG', __DIR__ . '/../config.yml');

$router = new Router();
$yaml = new Parser();

$router->get('/', function () use ($twig, $yaml) {

	$tables = $yaml->parse(file_get_contents(TABLES_CONFIG));
	return $twig->render('index.html.twig', array('tables' => $tables));
});