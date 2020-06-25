<?php
include $_SERVER['DOCUMENT_ROOT'].'\..\config\params.php';
include ROOT_DIR . 'components\Autoloader.php';

spl_autoload_register([new components\Autoloader(), 'loadClass']);

$router = new Router();
$router->run();