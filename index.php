<?php
include_once './bootstrap/Autoloader.php';

$battleResult = new Controller\ActionsController() ;

print_r("Winer is : " .$battleResult->battleBegins());

