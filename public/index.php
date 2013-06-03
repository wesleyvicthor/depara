<?php

require_once __DIR__ . '/../bootstrap.php';

use Respect\Rest\Router;
use Symfony\Component\Yaml\Parser;

define('TABLES_CONFIG', __DIR__ . '/../config/%s.yml');
function getConfigTables($configName) {
    return file_get_contents(sprintf(TABLES_CONFIG, $configName));
}

$router = new Router();
$yaml = new Parser();

$router->any('/*', function ($configName = null) use ($twig, $yaml) {
    if (!file_exists(sprintf(TABLES_CONFIG, $configName))) {
        return $twig->render('error.html.twig', array('message' => "Nao existe tabela configurada para {$configName}"));
    }

    if (is_null($configName)) {
        return $twig->render('error.html.twig', array('message' => "Voce deve especificar o nome de uma tabela configurada! exemplo: /partner"));
    }

    $csvLines = array();

    if (isset($_FILES['mock-file']) && $_FILES['mock-file']['size'] > 0) {
        $file = new \SplFileObject($_FILES['mock-file']['tmp_name']);
        $file->setCSVControl(';');
        $file->setFlags(\SplFileObject::READ_CSV);

        list($csvControl) = $file->getCSVControl();
        $csvLines = explode($csvControl, trim($file->getCurrentLine()));
        $csvLines = array_filter($csvLines);
    }

    $tables = $yaml->parse(getConfigTables($configName));
    return $twig->render(
        'index.html.twig',
        array('tables' => $tables, 'csvLines' => $csvLines, 'configName' => $configName)
    );
});

$router->post('/sql', function () {
    var_dump($_POST);
});