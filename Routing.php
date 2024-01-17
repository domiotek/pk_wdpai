<?php

require_once "src/controllers/DefaultController.php";
require_once "src/controllers/SecurityController.php";
require_once "src/controllers/FormController.php";
require_once "src/controllers/APIController.php";

class Router {
    private static $defaultRoute = "";
    public static $routes;


    public static function get($url, $controller) {
        self::$routes[$url] = $controller;
    }

    public static function post($url, $controller) {
        self::$routes[$url] = $controller;
    }

    public static function setDefaultRoute($url) {
        if(!array_key_exists($url, self::$routes)) {
            die("Couldn't set default route - " . $url . " is not defined.");
        }

        self::$defaultRoute = $url;
    }

    public static function run($url) {
        $action = explode("/", $url)[0];

        if(!array_key_exists($action, self::$routes)) {
            if(self::$defaultRoute !== "") {
                header("location: " . self::$defaultRoute);
                die();
            }else {
                http_response_code(404);
                die();
            }
        }
        
        $controller = self::$routes[$action];
        $object = new $controller;

        $object->$action();
    }
}


