<?php


require "Routing.php";

$path = trim($_SERVER["REQUEST_URI"], "/");
$path = parse_url($path, PHP_URL_PATH);

Router::get("home", "DefaultController");
Router::get("new", "DefaultController");
Router::get("d", "DefaultController");
Router::get("group", "DefaultController");

Router::post("login", "SecurityController");
Router::post("logout","SecurityController");
Router::get("passwordTest","SecurityController");

Router::get("createGroup","FormController");
Router::get("joinGroup", "FormController");
Router::get("switchToGroup","FormController");

Router::run($path);
