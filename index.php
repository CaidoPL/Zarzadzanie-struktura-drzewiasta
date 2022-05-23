<?php

declare(strict_types=1);

spl_autoload_register(
    function (string $name) {
        $name = str_replace(['\\', 'App/'], ['/', ''], $name);
        $path = "src/$name.php";
        require_once($path);
    }
);
require_once("src/Utils/debug.php");

use App\Controllers\Controller;
use App\Exception\AppException;
use App\Model\Model;
use App\Request;

$config = require_once("config/config.php");
$request = new Request($_GET, $_POST, $_SERVER);
try {
    (new Controller($request, $config))->run();
} catch (AppException $e) {
    echo '<h1>Error occurred in application</h1>';
    echo "<h3>" . $e->getMessage() . "</h3>";
    dump($e);
} catch (Throwable $e) {
    echo '<h1>Error occurred in application</h1>';
    echo "<h3>" . $e->getMessage() . "</h3>";
    dump($e);
}
