<?php

require_once __DIR__ . "/../models/User.php";

class AppController {

    private $request;

    public function _construct() {
        $this->request = $_SERVER["REQUEST_METHOD"];
    }

    protected function isGET(): bool {
        return $this->request==="GET";
    }

    protected function isPost(): bool {
        return $_SERVER["REQUEST_METHOD"]==="POST";
    }

    protected function isAuthenticated(): bool {
        if(!isset($_COOKIE["session"])) {
            return false;
        }

        $token = $_COOKIE["session"];

        $sessions = new SessionRepository();
        $session = $sessions->getSession($token);

        if ($session) {
            if($session->isValid()) {
                $sessions->touchSession($token);

                if(!isset($_COOKIE["short-lived-session"])) {
                    setcookie("session", $token, strtotime("+7 days"));
                }

                return true;
            }

            setcookie("session","");
        }

        return false; 
    }

    protected function getSignedInUserID(): ?User {
        if(!isset($_COOKIE["session"])) {
            return null;
        }

        $token = $_COOKIE["session"];

        $sessions = new SessionRepository();
        $session = $sessions->getSession($token);

        if($session) {
            if($session->isValid()) {
                return $session->getUser();
            }
        }

        return null; 
    }

    protected function redirect(string $url): void {
        header("Location: ". $url);
        die();
    }

    protected function render(string $template = null, array $variables = []) {
        $templatePath = 'public/views/' . $template . '.php';
        $output = "File not found!";
        
        if(file_exists($templatePath)) {
            extract($variables);

            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }

        print $output;
    }
}