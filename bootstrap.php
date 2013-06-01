<?php

require_once __DIR__  . '/vendor/autoload.php';

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(__DIR__.'/public/views/');
$twig = new Twig_Environment($loader);