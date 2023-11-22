<?php


require "Routing.php";

$path = trim($_SERVER["REQUEST_URI"], "/");
$path = parse_url($path, PHP_URL_PATH);

Router::get("index", "DefaultController");
Router::get("freshStart", "DefaultController");

Router::run($path);
